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
    '#description' => t('Create all required content to make your site look like default CivicTheme.<br/>Triggering this multiple times will result in removal and re-creation of content items.'),
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
    Drush::getContainer()->get('logger')->log(LogLevel::OK, strip_tags(html_entity_decode($message)));
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
 *
 * @return \Drupal\Core\Entity\EntityInterface
 *   An instance of block_content.
 */
function civictheme_provision_create_block_content($block_type, $info) {
  $uuid = \Drupal::service('uuid')->generate();
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
  $theme = \Drupal::configFactory()->get('system.theme')->get('default');

  $settings += [
    'plugin' => $block_type . ':' . ($uuid ?? \Drupal::service('uuid')->generate()),
    'region' => $region,
    'id' => $theme . '_' . preg_replace('@[^a-zA-Z0-9_]+@', '_', strtolower(str_replace(' ', '', $label))),
    'theme' => $theme,
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

  $block = Block::load($values['id']);
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
  _civictheme_provision__blocks__banner();
  _civictheme_provision__blocks__search();
  _civictheme_provision__blocks__mobile_navigation();
  _civictheme_provision__blocks__signup();
  _civictheme_provision__blocks__social_links();
  _civictheme_provision__blocks__acknowledgment_of_country();
  _civictheme_provision__blocks__copyright();
}

/**
 * Provision menu blocks.
 */
function _civictheme_provision__blocks__menu_blocks() {
  civictheme_provision_place_block('Primary Navigation', 'header_middle_3', NULL, [
    'plugin' => "menu_block:civictheme-primary-navigation",
    'id' => "civictheme_primarynavigation_menu",
    'depth' => 3,
  ]);
  civictheme_provision_place_block('Secondary Navigation', 'header_top_3', NULL, [
    'plugin' => "menu_block:civictheme-secondary-navigation",
    'id' => "civictheme_secondarynavigation_menu",
  ]);
  civictheme_provision_place_block('Footer menu 1', 'footer_middle_1', NULL, [
    'plugin' => "menu_block:civictheme-footer",
    'id' => "civictheme_footer_menu_1",
    'depth' => 1,
  ]);
  civictheme_provision_place_block('Footer menu 2', 'footer_middle_2', NULL, [
    'plugin' => "menu_block:civictheme-footer",
    'id' => "civictheme_footer_menu_2",
    'depth' => 1,
  ]);
  civictheme_provision_place_block('Footer menu 3', 'footer_middle_3', NULL, [
    'plugin' => "menu_block:civictheme-footer",
    'id' => "civictheme_footer_menu_3",
    'depth' => 1,
  ]);
  civictheme_provision_place_block('Footer menu 4', 'footer_middle_4', NULL, [
    'plugin' => "menu_block:civictheme-footer",
    'id' => "civictheme_footer_menu_4",
    'depth' => 1,
  ]);
}

/**
 * Provision banner block.
 */
function _civictheme_provision__blocks__banner() {
  $block_content = civictheme_provision_create_block_content('civictheme_banner', t('Banner'));
  $block_content->field_c_b_background_image = civictheme_provision_load_media_by_name('civictheme_background_dark_1.jpg');
  $block_content->field_c_b_theme = 'dark';
  $block_content->save();
  civictheme_provision_place_block('Banner', 'banner', $block_content->uuid());
}

/**
 * Provision search block.
 */
function _civictheme_provision__blocks__search() {
  // Redirect to 'Search' page if the path exists.
  $search_path = '/search';
  $fallback_path = '/';

  /** @var \Drupal\path_alias\AliasRepositoryInterface $alias_repository */
  $alias_repository = \Drupal::service('path_alias.repository');
  $alias = $alias_repository->lookupByAlias($search_path, \Drupal::languageManager()->getCurrentLanguage()->getId());
  $uri = 'internal:' . ($alias ? $alias['path'] : $fallback_path);

  $block_content = civictheme_provision_create_block_content('civictheme_search', t('Search'));
  $block_content->field_c_b_link = [
    'title' => 'Search',
    'uri' => $uri,
  ];
  $block_content->save();
  civictheme_provision_place_block('Search', 'header_middle_3', $block_content->uuid());
}

/**
 * Provision mobile navigation block.
 */
function _civictheme_provision__blocks__mobile_navigation() {
  $theme_name = \Drupal::config('system.theme')->get('default');

  $block_content = civictheme_provision_create_block_content('civictheme_mobile_navigation', t('Mobile Navigation'));
  $block_content->field_c_b_top_menu = "{$theme_name}_primarynavigation_menu";
  $block_content->field_c_b_bottom_menu = "{$theme_name}_secondarynavigation_menu";
  $block_content->field_c_b_trigger_text = 'Menu';
  $block_content->field_c_b_trigger_theme = 'light';
  $block_content->save();
  civictheme_provision_place_block('Mobile Navigation', 'header_middle_3', $block_content->uuid());
}

/**
 * Provision signup block.
 */
function _civictheme_provision__blocks__signup() {
  $block_content = civictheme_provision_create_block_content('civictheme_component_block', t('Signup'));
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
  civictheme_provision_place_block('Signup', 'content_bottom', $block_content->uuid());
}

/**
 * Provision social links block.
 */
function _civictheme_provision__blocks__social_links() {
  $block_content = civictheme_provision_create_block_content('civictheme_social_links', t('Social links'));

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
  civictheme_provision_place_block('Social_links', 'footer_top_2', $block_content->uuid());
}

/**
 * Provision acknowledgment of country block.
 */
function _civictheme_provision__blocks__acknowledgment_of_country() {
  $block_content = civictheme_provision_create_block_content('civictheme_component_block', t('Footer - Acknowledgment of country'));
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
  civictheme_provision_place_block('Acknowledgment of country', 'footer_bottom_1', $block_content->uuid());
}

/**
 * Provision copyright block.
 */
function _civictheme_provision__blocks__copyright() {
  $block_content = civictheme_provision_create_block_content('civictheme_component_block', t('Footer - Copyright'));
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
  civictheme_provision_place_block('Copyright', 'footer_bottom_2', $block_content->uuid());
}

/**
 * @}
 */
