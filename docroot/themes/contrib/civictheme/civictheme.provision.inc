<?php

/**
 * @file
 * Provision content using theme configuration form.
 *
 * Navigate to /admin/appearance/settings/civictheme or
 * /admin/appearance/settings/yourtheme, select 'Provision types' and press
 * 'Provision content' button. This will create all required content to make
 * your site look like default CivicTheme.
 *
 * This file can be safely removed after initial provisioning is performed in
 * production.
 */

use Drupal\block\Entity\Block;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\media\Entity\Media;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\user\Entity\Role;
use Drush\Drush;
use Drush\Log\LogLevel;

/**
 * @defgroup settings_form Settings form
 * @{
 * Settings form handling.
 */

/**
 * Theme settings form alter to provision content.
 */
function _civictheme_form_system_theme_settings_alter_provision(&$form, FormStateInterface $form_state) {
  $form['provision_content'] = [
    '#type' => 'details',
    '#title' => t('CivicTheme content provisioning'),
    '#description' => t('Create all required content to make your site look like default CivicTheme.<br/><br/>Triggering this multiple times will result in removal and re-creation of content items.'),
    '#weight' => 51,
    '#open' => TRUE,
  ];

  $options = [];
  foreach (civictheme_provision_get_types() as $type) {
    $options[$type] = ucfirst(str_replace('_', ' ', $type));
  }
  ksort($options);
  $form['provision_content']['provision_types'] = [
    '#type' => 'checkboxes',
    '#title' => t('Provision types'),
    '#description' => t('Select one or more content provisioning types.'),
    '#options' => $options,
    '#default_value' => $form_state->getValue('provision_types', array_keys($options)),
  ];

  $form['provision_content']['provision_clear_cache'] = [
    '#type' => 'checkbox',
    '#title' => t('Clear cache after content is provisioned'),
    '#description' => t('Use when plugin-based entities, like blocks, are provisioned.'),
    '#default_value' => TRUE,
  ];

  $form['provision_content']['provision_actions']['#type'] = 'actions';
  $form['provision_content']['provision_actions']['provision'] = [
    '#type' => 'submit',
    '#name' => 'provision',
    '#value' => t('Provision content'),
  ];

  $form['#validate'][] = '_civictheme_form_system_theme_settings_validate_provision';
  $form['#submit'][] = '_civictheme_form_system_theme_settings_submit_provision';
}

/**
 * Validate callback for theme settings form alter to provision content.
 */
function _civictheme_form_system_theme_settings_validate_provision(array &$form, FormStateInterface $form_state) {
  $triggering_element = $form_state->getTriggeringElement();
  $button_name = $triggering_element['#name'] ?? '';

  if ($button_name === 'provision') {
    $types = array_filter($form_state->getValue('provision_types'));
    if (empty($types)) {
      $form_state->setErrorByName('provision_types', t('At least one provision type must be selected.'));
    }
  }
}

/**
 * Submit callback for theme settings form alter to provision content.
 */
function _civictheme_form_system_theme_settings_submit_provision(array &$form, FormStateInterface $form_state) {
  $triggering_element = $form_state->getTriggeringElement();
  $button_name = $triggering_element['#name'] ?? '';
  if ($button_name === 'provision') {
    $types = array_filter($form_state->getValue('provision_types'));
    $clear_cache = $form_state->getValue('provision_clear_cache');
    if (!empty($types)) {
      $results = civictheme_provision($types, $clear_cache);
      foreach ($results as $type => $result) {
        if ($result === TRUE) {
          \Drupal::messenger()->addStatus(t('Successfully provisioned @type.', [
            '@type' => $type,
          ]));
        }
        else {
          \Drupal::messenger()->addError(t('Unable to provision @type: @message.', [
            '@type' => $type,
            '@message' => $result ?: t('Unknown reason'),
          ]));
        }
      }

      if ($clear_cache) {
        \Drupal::messenger()->addStatus(t('All caches cleared.'));
      }
    }
  }

  $form_state->unsetValue('provision_types');
}

/**
 * @}
 */

/**
 * @defgroup provisioning Provisioning core and helpers.
 * @{
 * Handles content provisioning callbacks discovery, execution and provides
 * additional helper to manipulate data.
 */

/**
 * Provision content.
 *
 * @param array $types
 *   Optional array of types discovered from defined callbacks.
 *   If not provided - provisioning for all types will run.
 * @param bool $clear_cache
 *   Optional flag to clear caches. Defaults to TRUE.
 *
 * @return array
 *   Array of provision results, keyed by type with the following possible
 *   values:
 *   -  TRUE: (boolean) Provisioning finished successfully for this type.
 *   -  FALSE: (boolean) Provisioning finished unsuccessfully for this type and
 *      no error message was provided.
 *   -  message: (string) Provisioning finished unsuccessfully for this type and
 *      the value contains an error message.
 */
function civictheme_provision(array $types = [], $clear_cache = TRUE, $verbose = FALSE) {
  $results = [];

  $callbacks = _civictheme_provision_discover_callbacks();

  if (empty($types)) {
    $types = array_keys($callbacks);
  }

  foreach ($callbacks as $callback_type => $callback) {
    if (in_array($callback_type, $types)) {
      if ($verbose) {
        civictheme_provision_cli_log(t('Started provisioning @type.', [
          '@type' => $callback_type,
        ]));
      }

      try {
        $results[$callback_type] = FALSE;
        $return = call_user_func($callbacks[$callback_type]);
        // Allow callbacks to not return a value on success.
        // Failure can be returned as FALSE or exception.
        if ($return === TRUE || is_null($return)) {
          $results[$callback_type] = TRUE;
        }
      }
      catch (\Exception $exception) {
        $results[$callback_type] = $exception->getMessage();
      }

      if ($verbose) {
        civictheme_provision_cli_log(t('Finished provisioning @type: @message', [
          '@type' => $callback_type,
          '@message' => $results[$callback_type] === TRUE ? t('Success') : t('ERROR: @error', [
            '@error' => is_string($results[$callback_type]) ? $results[$callback_type] : t('Unknown.'),
          ]),
        ]));
      }
    }
  }

  if ($clear_cache) {
    drupal_flush_all_caches();
  }

  return $results;
}

/**
 * Provision content from the CLI script.
 *
 * This will exit with a non-zero code if there was at least one provisioning
 * failure.
 */
function civictheme_provision_cli() {
  $results = civictheme_provision([], TRUE, TRUE);
  $errors = array_filter($results, function ($value) {
    return $value !== TRUE;
  });

  if (count($errors) > 0) {
    printf('Errors encountered during provisioning: %s', implode(PHP_EOL, $errors));
    exit(1);
  }
}

/**
 * Print a log message to stdout.
 *
 * Using \Drupal::messenger() when running Drush commands have side effects
 * where messages are displayed only after the command has finished rather than
 * during the command run.
 *
 * @param string $message
 *   String containing message.
 */
function civictheme_provision_cli_log($message) {
  if (class_exists('\Drush\Drush')) {
    Drush::getContainer()->get('logger')->notice(strip_tags(html_entity_decode($message)));
  }
  elseif (PHP_SAPI === 'cli') {
    print strip_tags(html_entity_decode($message)) . PHP_EOL;
  }
  else {
    \Drupal::messenger()->addMessage($message);
  }
}

/**
 * Get provision types.
 *
 * @return array
 *   Array of provision types discovered from the callbacks.
 */
function civictheme_provision_get_types() {
  return array_keys(_civictheme_provision_discover_callbacks());
}

/**
 * Discover provisioning callbacks.
 *
 * Provisioning callbacks allow to separate content provisioning into dedicated
 * self-contained functions that can be called from UI.
 *
 * Callbacks can be named as PREFIX_civictheme_provision__TYPE(),
 * where PREFIX is a custom theme or module prefix, and TYPE is a custom type
 * that will appear in UI as options (sentence-cased, underscores replaced
 * with spaces). Last implementation per type will override previous
 * definitions.
 *
 * Callbacks can throw exception with messages (will be shown in UI) or return
 * FALSE to indicate that the provisioning has failed.
 *
 * CivicTheme implements own callbacks for several types to provide sensible
 * default content.
 *
 * @return array
 *   Array of callbacks keyed by type and sorted in the order they are expected
 *   to be called.
 */
function _civictheme_provision_discover_callbacks() {
  $callbacks = [];

  $functions = get_defined_functions();
  foreach ($functions['user'] as $function) {
    // Callbacks from CivicTHeme.
    if (is_string($function) && strpos($function, 'civictheme_provision__') === 0) {
      $type = substr($function, strlen('civictheme_provision__'));
      // Allow custom callbacks to take precedence.
      if (empty($callbacks[$type])) {
        $callbacks[$type] = $function;
      }
    }
    // Custom callbacks in form PREFIX_civictheme_provision__TYPE
    // will override existing callbacks by type.
    // This allows child themes to alter existing provisioning scripts.
    if (is_string($function) && strpos($function, '_civictheme_provision__') !== FALSE) {
      $type = substr($function, strpos($function, '_civictheme_provision__') + strlen('_civictheme_provision__'));
      // Additionally, filter-out more callback helpers in a format
      // PREFIX_civictheme_provision__TYPE__more_specific_helper.
      if (strpos($type, '__') === FALSE) {
        $callbacks[$type] = $function;
      }
    }
  }

  // Make sure that 'blocks' always runs last to allow other types of
  // provisioning to prepare all necessary content.
  if (!empty($callbacks['blocks'])) {
    $callback_blocks = $callbacks['blocks'];
    unset($callbacks['blocks']);
    $callbacks['blocks'] = $callback_blocks;
  }

  return $callbacks;
}

/**
 * Create block_content instance.
 *
 * Blocks with existing $info will be removed and re-created.
 *
 * @param string $block_type
 *   Block type.
 * @param string $info
 *   Block info.
 * @param string $uuid
 *   Block uuid.
 *
 * @return \Drupal\Core\Entity\EntityInterface
 *   An instance of block_content.
 */
function civictheme_provision_create_block_content($block_type, $info, $uuid) {
  $block = \Drupal::service('entity.repository')->loadEntityByUuid('block_content', $uuid);
  if ($block !== NULL) {
    $block->delete();
  }
  $uuid = $uuid ?? \Drupal::service('uuid')->generate();
  $info = (string) $info;

  /** @var \Drupal\block_content\Entity\BlockContent $block */
  $blocks = \Drupal::entityTypeManager()->getStorage('block_content')->loadByProperties([
    'info' => $info,
  ]);

  if (!empty($blocks)) {
    foreach ($blocks as $block) {
      $block->delete();
    }
  }

  return \Drupal::entityTypeManager()->getStorage('block_content')->create([
    'type' => $block_type,
    'info' => $info,
    'uuid' => $uuid,
  ]);
}

/**
 * Place block.
 *
 * @param string $label
 *   Admin label for the block.
 * @param string $region
 *   Region to place the block into.
 * @param string $uuid
 *   Optional UUID to use for plugin_id. Defaults to NULL. If not provided - a
 *   random UUID will be generated.
 * @param array $settings
 *   Optional array of settings to pass to the block. Default to empty array.
 *   If not provided - sensible defaults will be used.
 * @param string $block_type
 *   Optional block type. Defaults to 'block_content'.
 */
function civictheme_provision_place_block($label, $region, $uuid = NULL, array $settings = [], $block_type = 'block_content') {
  $theme_name = \Drupal::configFactory()->get('system.theme')->get('default');

  $label_machine_name = preg_replace('@[^a-zA-Z0-9_]+@', '_', strtolower(str_replace(' ', '_', $label)));
  $id = $theme_name . '_' . $label_machine_name;
  $base_theme_id = 'civictheme_' . $label_machine_name;
  $settings += [
    'plugin' => $block_type . ':' . ($uuid ?? \Drupal::service('uuid')->generate()),
    'region' => $region,
    'id' => $id,
    'theme' => $theme_name,
    'label' => $label,
    'label_display' => FALSE,
    'visibility' => [],
    'weight' => 0,
  ];

  $values = [];
  // Remove extra values that do not belong in the settings array.
  foreach (['region', 'id', 'theme', 'plugin', 'weight', 'visibility'] as $key) {
    $values[$key] = $settings[$key];
    unset($settings[$key]);
  }

  foreach ($values['visibility'] as $id => $visibility) {
    $values['visibility'][$id]['id'] = $id;
  }
  $values['settings'] = $settings;

  // Remove block if it already exists.
  $block = Block::load($values['id']);
  if ($block) {
    $block->delete();
  }

  // Special case: remove existing blocks created by the base theme to avoid
  // duplicated blocks.
  $block = Block::load($base_theme_id);
  if ($block) {
    $block->delete();
  }

  $block = Block::create($values);

  $block->save();
}

/**
 * Load media by name.
 *
 * @param string $name
 *   Media name.
 *
 * @return \Drupal\media\Entity\Media|null
 *   Found media instance or NULL if not found.
 */
function civictheme_provision_load_media_by_name($name) {
  $media = \Drupal::entityTypeManager()
    ->getStorage('media')
    ->loadByProperties(['name' => $name]);

  return !empty($media) ? reset($media) : NULL;
}

/**
 * Clear all items in the menu.
 *
 * @param string $menu_name
 *   String machine menu name.
 */
function civictheme_provision_clear_menu($menu_name) {
  /** @var \Drupal\menu_link_content\MenuLinkContentStorage $storage */
  $storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');

  $menu_items = $storage->loadByProperties([
    'menu_name' => $menu_name,
  ]);

  foreach ($menu_items as $menu_item) {
    $menu_item->delete();
  }
}

/**
 * @}
 */

/**
 * @defgroup provision_callbacks Provisioning callbacks.
 * @{
 * Provisioning callbacks implementing PREFIX_civictheme_provision__TYPE().
 */

/**
 * Provision files.
 */
function civictheme_provision__files() {
  $theme_name = \Drupal::config('system.theme')->get('default');
  $theme_path = \Drupal::service('extension.list.theme')->getPath($theme_name);

  $filepaths = [
    $theme_path . '/assets/images/facebook.svg',
    $theme_path . '/assets/images/linkedin.svg',
    $theme_path . '/assets/images/twitter.svg',
    $theme_path . "/assets/backgrounds/{$theme_name}_background_dark_1.jpg",
    $theme_path . "/assets/backgrounds/{$theme_name}_background_dark_2.jpg",
    $theme_path . "/assets/backgrounds/{$theme_name}_background_light_1.jpg",
    $theme_path . "/assets/backgrounds/{$theme_name}_background_light_2.jpg",
  ];

  /** @var \Drupal\file\FileRepositoryInterface $file_repository */
  $file_repository = \Drupal::service('file.repository');
  $default_file_scheme = \Drupal::config('system.file')->get('default_scheme') . '://';
  foreach ($filepaths as $filepath) {
    if (file_exists($filepath)) {
      $file_repository->writeData(file_get_contents($filepath), $default_file_scheme . basename($filepath), FileSystemInterface::EXISTS_REPLACE);
    }
    else {
      throw new \Exception(sprintf('File %s does not exist.', $filepath));
    }
  }
}

/**
 * Provision media.
 */
function civictheme_provision__media() {
  $theme_name = \Drupal::config('system.theme')->get('default');

  $filenames_for_media = [
    'icon' => [
      'facebook.svg',
      'linkedin.svg',
      'twitter.svg',
    ],
    'image' => [
      "{$theme_name}_background_dark_1.jpg",
      "{$theme_name}_background_dark_2.jpg",
      "{$theme_name}_background_light_1.jpg",
      "{$theme_name}_background_light_2.jpg",
    ],
  ];

  /** @var \Drupal\file\FileStorageInterface $file_storage */
  $file_storage = \Drupal::entityTypeManager()->getStorage('file');
  foreach ($filenames_for_media as $type => $filenames) {
    foreach ($filenames as $filename) {
      $files = $file_storage->loadByProperties(['filename' => $filename]);
      if (empty($files)) {
        throw new \Exception(sprintf('File entity %s does not exist.', $filename));
      }

      $file = array_shift($files);

      switch ($type) {
        case 'icon':
          Media::create([
            'bundle' => 'civictheme_icon',
            'field_c_m_icon' => $file,
          ])->save();
          break;

        case 'image':
          Media::create([
            'bundle' => 'civictheme_image',
            'field_c_m_image' => $file,
          ])->save();
          break;
      }
    }
  }
}

/**
 * Provision menu links.
 */
function civictheme_provision__menu_links() {
  $menus = [
    'civictheme-primary-navigation' => [
      'Home' => '/',
    ],
    'civictheme-secondary-navigation' => [
      'Contact' => '/enquiry',
    ],
    'civictheme-footer' => [
      'Home' => '/',
      'Contact' => '/enquiry',
    ],
  ];

  foreach ($menus as $menu_name => $links) {
    civictheme_provision_clear_menu($menu_name);
    foreach ($links as $title => $uri) {
      $menu_link = MenuLinkContent::create([
        'menu_name' => $menu_name,
        'title' => $title,
        'link' => [
          'uri' => Url::fromUserInput($uri)->toUriString(),
        ],
      ]);
      $menu_link->save();
    }
  }
}

/**
 * Provision blocks.
 */
function civictheme_provision__blocks() {
  _civictheme_provision__blocks__menu_blocks();
  $blocks = _civictheme_get_theme_block();
  foreach ($blocks as $block) {
    call_user_func($block['builder'], $block);
  }
}

/**
 * Provision menu blocks.
 */
function _civictheme_provision__blocks__menu_blocks() {
  civictheme_provision_place_block('Primary Navigation', 'header_middle_3', NULL, [
    'plugin' => 'menu_block:civictheme-primary-navigation',
    'depth' => 3,
  ]);
  civictheme_provision_place_block('Secondary Navigation', 'header_top_3', NULL, [
    'plugin' => 'menu_block:civictheme-secondary-navigation',
  ]);
  civictheme_provision_place_block('Side Navigation', 'sidebar', NULL, [
    'plugin' => 'menu_block:civictheme-primary-navigation',
    'depth' => 3,
  ]);
  civictheme_provision_place_block('Footer menu 1', 'footer_middle_1', NULL, [
    'plugin' => 'menu_block:civictheme-footer',
    'depth' => 1,
  ]);
  civictheme_provision_place_block('Footer menu 2', 'footer_middle_2', NULL, [
    'plugin' => 'menu_block:civictheme-footer',
    'depth' => 1,
  ]);
  civictheme_provision_place_block('Footer menu 3', 'footer_middle_3', NULL, [
    'plugin' => 'menu_block:civictheme-footer',
    'depth' => 1,
  ]);
  civictheme_provision_place_block('Footer menu 4', 'footer_middle_4', NULL, [
    'plugin' => 'menu_block:civictheme-footer',
    'depth' => 1,
  ]);
}

/**
 * Provision banner block.
 *
 * @param array $block
 *   Data structure of block.
 */
function _civictheme_provision__blocks__banner(array $block) {
  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $block_content->field_c_b_background_image = civictheme_provision_load_media_by_name('civictheme_background_dark_1.jpg');
  $block_content->field_c_b_theme = 'dark';
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid());
}

/**
 * Provision search block.
 *
 * @param array $block
 *   Data structure of block.
 */
function _civictheme_provision__blocks__search(array $block) {
  // Redirect to 'Search' page if the path exists.
  $search_path = '/search';
  $fallback_path = '/';

  /** @var \Drupal\path_alias\AliasRepositoryInterface $alias_repository */
  $alias_repository = \Drupal::service('path_alias.repository');
  $alias = $alias_repository->lookupByAlias($search_path, \Drupal::languageManager()->getCurrentLanguage()->getId());
  $uri = 'internal:' . ($alias ? $alias['path'] : $fallback_path);

  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $block_content->field_c_b_link = [
    'title' => 'Search',
    'uri' => $uri,
  ];
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid());
}

/**
 * Provision mobile navigation block.
 *
 * @param array $block
 *   Data structure of block.
 */
function _civictheme_provision__blocks__mobile_navigation(array $block) {
  $theme_name = \Drupal::config('system.theme')->get('default');

  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $block_content->field_c_b_top_menu = "{$theme_name}_primary_navigation";
  $block_content->field_c_b_bottom_menu = "{$theme_name}_secondary_navigation";
  $block_content->field_c_b_trigger_text = 'Menu';
  $block_content->field_c_b_trigger_theme = 'light';
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid());
}

/**
 * Provision signup block.
 *
 * @param array $block
 *   Data structure of block.
 */
function _civictheme_provision__blocks__signup(array $block) {
  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $paragraph = Paragraph::create([
    'type' => 'civictheme_promo',
  ]);
  $paragraph->field_c_p_title = 'Sign up';
  $paragraph->field_c_p_summary = 'Sign up for news and updates from our agency.';
  $paragraph->field_c_p_link = [
    'uri' => 'internal:/subscribe',
    'title' => 'Sign up',
  ];
  $paragraph->field_c_p_theme = 'dark';
  $paragraph->field_c_p_space = 'top';
  $block_content->field_c_b_components->appendItem($paragraph);
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid());
}

/**
 * Provision social links block.
 *
 * @param array $block
 *   Data structure of block.
 */
function _civictheme_provision__blocks__social_links(array $block) {
  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);

  $paragraph = Paragraph::create([
    'type' => 'civictheme_social_icon',
  ]);
  $paragraph->field_c_p_icon = civictheme_provision_load_media_by_name('facebook.svg');
  $paragraph->field_c_p_link = [
    'uri' => 'https://www.facebook.com/',
    'title' => 'Facebook',
  ];
  $block_content->field_c_b_social_icons->appendItem($paragraph);

  $paragraph = Paragraph::create([
    'type' => 'civictheme_social_icon',
  ]);
  $paragraph->field_c_p_icon = civictheme_provision_load_media_by_name('linkedin.svg');
  $paragraph->field_c_p_link = [
    'uri' => 'https://www.linkedin.com/',
    'title' => 'LinkedIn',
  ];
  $block_content->field_c_b_social_icons->appendItem($paragraph);

  $paragraph = Paragraph::create([
    'type' => 'civictheme_social_icon',
  ]);
  $paragraph->field_c_p_icon = civictheme_provision_load_media_by_name('twitter.svg');
  $paragraph->field_c_p_link = [
    'uri' => 'https://www.twitter.com/',
    'title' => 'Twitter',
  ];
  $block_content->field_c_b_social_icons->appendItem($paragraph);

  $block_content->field_c_b_theme = 'dark';
  $block_content->field_c_b_with_border = TRUE;
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid());
}

/**
 * Provision acknowledgment of country block.
 *
 * @param array $block
 *   Data structure of block.
 */
function _civictheme_provision__blocks__acknowledgment_of_country(array $block) {
  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $paragraph = Paragraph::create([
    'type' => 'civictheme_content',
  ]);
  $paragraph->field_c_p_content = [
    'value' => "<p>We acknowledge the traditional owners of the country throughout Australia and their continuing connection to land, sea and community. We pay our respect to them and their cultures and to the elders past and present.</p>\r\n",
    'format' => 'civictheme_rich_text',
  ];
  $paragraph->field_c_p_theme = 'dark';
  $block_content->field_c_b_components->appendItem($paragraph);
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid());
}

/**
 * Provision copyright block.
 *
 * @param array $block
 *   Data structure of block.
 */
function _civictheme_provision__blocks__copyright(array $block) {
  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $paragraph = Paragraph::create([
    'type' => 'civictheme_content',
  ]);
  $paragraph->field_c_p_content = [
    'value' => "<p>© Commonwealth of Australia</p>\r\n",
    'format' => 'civictheme_rich_text',
  ];
  $paragraph->field_c_p_theme = 'dark';
  $block_content->field_c_b_components->appendItem($paragraph);
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid());
}

/**
 * Provision theme settings.
 */
function civictheme_provision__theme_settings() {
  $theme_name = \Drupal::config('system.theme')->get('default');
  $theme_path = \Drupal::service('extension.list.theme')->getPath($theme_name);

  $logo_header_desktop = "$theme_path/dist/assets/logos/{$theme_name}_logo_desktop_light.png";
  $logo_header_mobile = "$theme_path/dist/assets/logos/{$theme_name}_logo_mobile_light.png";
  $logo_footer_desktop = "$theme_path/dist/assets/logos/{$theme_name}_logo_desktop_dark.png";
  $logo_footer_mobile = "$theme_path/dist/assets/logos/{$theme_name}_logo_mobile_dark.png";

  $favicon = "$theme_path/dist/assets/favicon.ico";

  $config = \Drupal::service('config.factory')->getEditable("$theme_name.settings");

  $config->set('logo', [
    'use_default' => FALSE,
    'path' => $logo_header_desktop,
    'url' => '/' . ltrim($logo_header_desktop, '/'),
  ]);

  $config->set('logo_path', $logo_header_desktop);
  $config->set('civictheme_header_logo_mobile', $logo_header_mobile);
  $config->set('civictheme_footer_logo_desktop', $logo_footer_desktop);
  $config->set('civictheme_footer_logo_mobile', $logo_footer_mobile);

  $config->set('civictheme_site_logo_alt', 'CivicTheme demo logo');

  $config->set('favicon', [
    'use_default' => FALSE,
    'path' => $favicon,
    'mimetype' => 'image/vnd.microsoft.icon',
  ]);

  $config->set('civictheme_header_theme', 'light');
  $config->set('civictheme_footer_theme', 'dark');

  $config->save();
}

/**
 * Provision permissions.
 */
function civictheme_provision__permissions() {
  $map = civictheme_provision_permissions_map();

  /** @var \Drupal\user\PermissionHandler $permission_handler */
  $permission_handler = \Drupal::getContainer()->get('user.permissions');
  $all_permissions = array_keys($permission_handler->getPermissions());

  foreach ($map as $role_name => $permissions_map) {
    $role = Role::load($role_name);
    if (!$role) {
      continue;
    }

    foreach ($permissions_map as $permissions) {
      foreach ($permissions as $permission) {
        // Only grant permissions that are currently available on the site.
        if (in_array($permission, $all_permissions)) {
          $role->grantPermission($permission);
        }
      }
    }
    $role->save();
  }
}

/**
 * Provision simple sitemap.
 */
function civictheme_provision__simple_sitemap() {
  if (!\Drupal::moduleHandler()->moduleExists('simple_sitemap')) {
    \Drupal::service('module_installer')->install(['simple_sitemap']);
  }

  /** @var \Drupal\simple_sitemap\Manager\Generator $generator */
  $generator = \Drupal::service('simple_sitemap.generator');

  $settings = [
    'index' => TRUE,
    'priority' => 0.5,
    'changefreq' => 'hourly',
    'include_images' => FALSE,
  ];

  $bundles = ['civictheme_page', 'civictheme_event'];
  foreach ($bundles as $bundle) {
    $generator->entityManager()->setBundleSettings('node', $bundle, $settings);
  }

  $generator->customLinkManager()->add('/', $settings);
}

/**
 * Map of roles and permissions to be granted.
 *
 * This allows to centrally manage additional permissions that may be
 * optionally provisioned for specific CivicTheme roles. Such permissions
 * would not be a part of CivicTheme roles' configurations, but would be
 * expected the roles to have in some Drupal profiles (like GovCMS).
 *
 * @return array
 *   Array of with role names as keys and permissions arrays as values, each of
 *   which is keyed by a module.
 */
function civictheme_provision_permissions_map() {
  $map = [
    'civictheme_content_approver' => [
      'honeypot' => [
        'bypass honeypot protection',
      ],
      'tfa' => [
        'setup own tfa',
      ],
      'toolbar' => [
        'access toolbar',
      ],
    ],
    'civictheme_content_author' => [
      'honeypot' => [
        'bypass honeypot protection',
      ],
      'tfa' => [
        'setup own tfa',
      ],
      'toolbar' => [
        'access toolbar',
      ],
    ],
    'civictheme_site_administrator' => [
      'honeypot' => [
        'bypass honeypot protection',
      ],
      'tfa' => [
        'setup own tfa',
      ],
      'toolbar' => [
        'access toolbar',
      ],
      'password_policy' => [
        'manage password reset',
      ],
      'pathauto' => [
        'notify of path changes',
      ],
      'webform' => [
        'access webform submission log',
        'create webform content',
        'delete any webform content',
        'delete own webform content',
        'delete webform revisions',
        'delete webform submissions any node',
        'delete webform submissions own node',
        'edit any webform content',
        'edit own webform content',
        'edit webform submissions any node',
        'edit webform submissions any node',
        'edit webform submissions any node',
        'edit webform submissions own node',
        'revert webform revisions',
        'view webform revisions',
        'view webform submissions any node',
        'view webform submissions own node',
      ],
    ],
  ];

  return $map;
}

/**
 * @}
 */

/**
 * Helper to install modules required by CivicTheme.
 *
 * @see https://www.drupal.org/node/2652542
 */
function civictheme_enable_modules() {
  /** @var \Drupal\Core\Extension\ThemeHandler $theme_handler */
  $theme_handler = \Drupal::getContainer()->get('theme_handler');
  $theme_data = $theme_handler->rebuildThemeData();

  if (!empty($theme_data['civictheme'])) {
    $modules = array_keys($theme_data['civictheme']->module_dependencies);
    /** @var \Drupal\Core\Extension\ModuleInstaller $module_installer */
    $module_installer = \Drupal::getContainer()->get('module_installer');
    $module_installer->install($modules);
  }

}

/**
 * Map of blocks to be created.
 */
function _civictheme_get_theme_block() {
  $blocks = [
    'civictheme_banner' =>
    [
      'name' => 'Banner',
      'region' => 'banner',
      'uuid' => '50c52459-c100-4e4d-92a4-9c4c77d8f8d0',
      'builder' => '_civictheme_provision__blocks__banner',
      'type' => 'civictheme_banner',
    ],
    'civictheme_search' =>
    [
      'name' => 'Search',
      'region' => 'header_middle_3',
      'uuid' => '286ff750-a9a9-423a-8a4e-515f79fc0a8f',
      'builder' => '_civictheme_provision__blocks__search',
      'type' => 'civictheme_search',
    ],
    'civictheme_mobile_navigation' =>
    [
      'name' => 'Mobile Navigation',
      'region' => 'header_middle_3',
      'uuid' => 'b7f36176-620f-4178-aadd-9b448c610986',
      'builder' => '_civictheme_provision__blocks__mobile_navigation',
      'type' => 'civictheme_mobile_navigation',
    ],
    'civictheme_signup' =>
    [
      'name' => 'Signup',
      'region' => 'content_bottom',
      'uuid' => '5508dc51-1cf8-4577-9038-b572fbd698ae',
      'builder' => '_civictheme_provision__blocks__signup',
      'type' => 'civictheme_component_block',
    ],
    'civictheme_social_links' =>
    [
      'name' => 'Social links',
      'region' => 'footer_top_2',
      'uuid' => '5c0ad15f-3e9c-4eeb-8525-7a064b06fc58',
      'builder' => '_civictheme_provision__blocks__social_links',
      'type' => 'civictheme_social_links',
    ],
    'civictheme_footer_acknowledgment' =>
    [
      'name' => 'Footer - Acknowledgment of country',
      'region' => 'footer_bottom_1',
      'uuid' => '7cf65f98-cbae-4e9d-b6ea-7d6d1c437c1c',
      'builder' => '_civictheme_provision__blocks__acknowledgment_of_country',
      'type' => 'civictheme_component_block',
    ],
    'civictheme_footer_copyright' =>
    [
      'name' => 'Footer - Copyright',
      'region' => 'footer_bottom_2',
      'uuid' => 'd7098a8c-3ba3-48f7-bc0c-5787ebaa0427',
      'builder' => '_civictheme_provision__blocks__copyright',
      'type' => 'civictheme_component_block',
    ],
  ];
  return $blocks;
}
