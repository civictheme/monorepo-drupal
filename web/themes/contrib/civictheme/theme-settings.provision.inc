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

declare(strict_types=1);

use Drupal\block\Entity\Block;
use Drupal\block_content\BlockContentInterface;
use Drupal\civictheme\CivicthemeConfigImporter;
use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\media\Entity\Media;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\paragraphs\Entity\Paragraph;
use Drush\Drush;

/**
 * @defgroup settings_form Settings form
 * @{
 * Settings form handling.
 */

/**
 * Theme settings form alter to provision content.
 */
function _civictheme_form_system_theme_settings_alter_provision(array &$form, FormStateInterface $form_state): void {
  $form['provision_content'] = [
    '#type' => 'details',
    '#title' => t('Content provisioning'),
    // @todo Improve content creation by allowing to skip overwriting of the
    // existing items.
    '#description' => t('Create all required content to make your site look like default CivicTheme.<br/><br/>Triggering this multiple times will result in removal and re-creation of content items.'),
    '#weight' => 50,
    '#open' => FALSE,
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
    '#validate' => ['_civictheme_form_system_theme_settings_provision_validate'],
    '#submit' => ['_civictheme_form_system_theme_settings_provision_submit'],
  ];
  $form['provision_content']['#process'][] = '_civictheme_form_system_theme_settings_alter_provision_process';
}

/**
 * Process provision form elements.
 *
 * @param array $element
 *   Form element.
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 *   Form state.
 * @param array $complete_form
 *   Complete form.
 *
 * @return array<mixed>
 *   Processed form element.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function _civictheme_form_system_theme_settings_alter_provision_process(array &$element, FormStateInterface $form_state, array &$complete_form): array {
  $form_state->addCleanValueKey('provision_types');
  $form_state->addCleanValueKey('provision_clear_cache');

  return $element;
}

/**
 * Validate callback for theme settings form alter to provision content.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function _civictheme_form_system_theme_settings_provision_validate(array &$form, FormStateInterface $form_state): void {
  $triggering_element = $form_state->getTriggeringElement();
  $button_name = $triggering_element['#name'] ?? '';

  // Validate provision types.
  if ($button_name === 'provision') {
    $types = array_filter($form_state->getValue('provision_types'));
    if (empty($types)) {
      $form_state->setErrorByName('provision_types', (string) t('At least one provision type must be selected.'));
    }
  }
}

/**
 * Submit callback for theme settings form alter to provision content.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function _civictheme_form_system_theme_settings_provision_submit(array &$form, FormStateInterface $form_state): void {
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
          continue;
        }

        \Drupal::messenger()->addError(t('Unable to provision @type: @message', [
          '@type' => $type,
          '@message' => $result ?: t('Unknown reason'),
        ]));
      }

      if ($clear_cache) {
        \Drupal::messenger()->addStatus(t('All caches cleared.'));
      }
    }
  }
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
 * @return array<string, mixed>
 *   Array of provision results, keyed by type with the following possible
 *   values:
 *   -  TRUE: (boolean) Provisioning finished successfully for this type.
 *   -  FALSE: (boolean) Provisioning finished unsuccessfully for this type and
 *      no error message was provided.
 *   -  message: (string) Provisioning finished unsuccessfully for this type and
 *      the value contains an error message.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function civictheme_provision(array $types = [], $clear_cache = TRUE, bool $verbose = FALSE): array {
  $results = [];

  $callbacks = _civictheme_provision_discover_callbacks();

  if (empty($types)) {
    $types = array_keys($callbacks);
  }

  foreach (array_keys($callbacks) as $callback_type) {
    if (in_array($callback_type, $types)) {
      if ($verbose) {
        civictheme_provision_cli_log((string) t('Started provisioning @type.', [
          '@type' => $callback_type,
        ]));
      }

      try {
        $results[$callback_type] = FALSE;
        // @phpstan-ignore-next-line
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
        civictheme_provision_cli_log((string) t('Finished provisioning @type: @message', [
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
function civictheme_provision_cli(array $types = []): void {
  $results = civictheme_provision($types, TRUE, TRUE);
  $errors = array_filter($results, static function ($value): bool {
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
function civictheme_provision_cli_log($message): void {
  if (class_exists(Drush::class)) {
    Drush::getContainer()->get('logger')->notice(strip_tags(html_entity_decode($message)));
  }
  elseif (PHP_SAPI === 'cli') {
    print strip_tags(html_entity_decode($message)) . PHP_EOL;

    return;
  }

  \Drupal::messenger()->addMessage($message);
}

/**
 * Get provision types.
 *
 * @return array<string>
 *   Array of provision types discovered from the callbacks.
 */
function civictheme_provision_get_types(): array {
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
 * @return array<string, string>
 *   Array of callbacks keyed by type and sorted in the order they are expected
 *   to be called.
 */
function _civictheme_provision_discover_callbacks(): array {
  $callbacks = [];

  $functions = get_defined_functions();
  foreach ($functions['user'] as $function) {
    // Callbacks from CivicTHeme.
    if (is_string($function) && str_starts_with($function, 'civictheme_provision__')) {
      $type = substr($function, strlen('civictheme_provision__'));
      // Allow custom callbacks to take precedence.
      if (empty($callbacks[$type])) {
        $callbacks[$type] = $function;
      }
    }

    // Custom callbacks in form PREFIX_civictheme_provision__TYPE
    // will override existing callbacks by type.
    // This allows child themes to alter existing provisioning scripts.
    if (is_string($function) && str_contains($function, '_civictheme_provision__')) {
      $type = substr($function, strpos($function, '_civictheme_provision__') + strlen('_civictheme_provision__'));
      // Additionally, filter-out more callback helpers in a format
      // PREFIX_civictheme_provision__TYPE__more_specific_helper.
      if (!str_contains($type, '__')) {
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
 * @return \Drupal\block_content\BlockContentInterface
 *   An instance of block_content.
 */
function civictheme_provision_create_block_content($block_type, $info, $uuid): BlockContentInterface {
  $block = \Drupal::service('entity.repository')->loadEntityByUuid('block_content', $uuid);
  if ($block !== NULL) {
    $block->delete();
  }

  $uuid = $uuid ?: \Drupal::service('uuid')->generate();
  $info = (string) $info;

  /** @var \Drupal\block_content\Entity\BlockContent[] $blocks */
  $blocks = \Drupal::entityTypeManager()->getStorage('block_content')->loadByProperties([
    'info' => $info,
  ]);

  if (!empty($blocks)) {
    foreach ($blocks as $block) {
      $block->delete();
    }
  }

  /** @var \Drupal\block_content\BlockContentInterface $block_content_entity */
  $block_content_entity = \Drupal::entityTypeManager()->getStorage('block_content')->create([
    'type' => $block_type,
    'info' => $info,
    'uuid' => $uuid,
    'moderation_state' => 'published',
  ]);

  return $block_content_entity;
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
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_provision_place_block(string $label, string $region, string $uuid = NULL, array $settings = [], string $block_type = 'block_content'): void {
  $theme_name = \Drupal::configFactory()->get('system.theme')->get('default');

  $label_machine_name = $settings['label_machine_name'] ?? preg_replace('@[^a-zA-Z0-9_]+@', '_', strtolower(str_replace(' ', '_', $label)));
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
  unset($settings['label_machine_name']);

  $values = [];
  // Remove extra values that do not belong in the settings array.
  foreach (['region', 'id', 'theme', 'plugin', 'weight', 'visibility'] as $key) {
    $values[$key] = $settings[$key];
    unset($settings[$key]);
  }

  foreach (array_keys($values['visibility']) as $id) {
    $values['visibility'][$id]['id'] = $id;
  }

  $values['settings'] = $settings;

  // Remove block if it already exists.
  $block = Block::load($values['id']);
  if ($block !== NULL) {
    $block->delete();
  }

  // Special case: remove existing blocks created by the base theme to avoid
  // duplicated blocks.
  $block = Block::load($base_theme_id);
  if ($block !== NULL) {
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
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_provision_load_media_by_name(string $name): ?Media {
  $media = \Drupal::entityTypeManager()
    ->getStorage('media')
    ->loadByProperties(['name' => $name]);

  return empty($media) ? NULL : reset($media);
}

/**
 * Load menu items.
 *
 * @param string $menu_name
 *   String machine menu name.
 *
 * @return array<\Drupal\menu_link_content\Entity\MenuLinkContent>
 *   Array of menu items.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_provision_load_menu_items(string $menu_name): array {
  /** @var \Drupal\menu_link_content\MenuLinkContentStorage $storage */
  $storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');

  return $storage->loadByProperties([
    'menu_name' => $menu_name,
  ]);
}

/**
 * Clear all items in the menu.
 *
 * @param string $menu_name
 *   String machine menu name.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_provision_clear_menu(string $menu_name): void {
  $menu_items = civictheme_provision_load_menu_items($menu_name);

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
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function civictheme_provision__files(): void {
  $theme_name = \Drupal::config('system.theme')->get('default');
  $theme_path = \Drupal::service('extension.list.theme')->getPath($theme_name);

  $filepaths = [
    $theme_path . '/assets/icons/facebook.svg',
    $theme_path . '/assets/icons/linkedin.svg',
    $theme_path . '/assets/icons/x.svg',
    $theme_path . sprintf('/assets/backgrounds/%s_background_1.png', $theme_name),
    $theme_path . sprintf('/assets/backgrounds/%s_background_2.png', $theme_name),
  ];

  /** @var \Drupal\file\FileRepositoryInterface $file_repository */
  $file_repository = \Drupal::service('file.repository');
  $default_file_scheme = \Drupal::config('system.file')->get('default_scheme') . '://';
  foreach ($filepaths as $filepath) {
    if (file_exists($filepath)) {
      $file_repository->writeData((string) file_get_contents($filepath), $default_file_scheme . basename($filepath), FileSystemInterface::EXISTS_REPLACE);
      continue;
    }

    throw new \Exception(sprintf('File %s does not exist.', $filepath));
  }
}

/**
 * Provision media.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function civictheme_provision__media(): void {
  $theme_name = \Drupal::config('system.theme')->get('default');

  $filenames_for_media = [
    'icon' => [
      'facebook.svg',
      'linkedin.svg',
      'x.svg',
    ],
    'image' => [
      $theme_name . '_background_1.png',
      $theme_name . '_background_2.png',
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
            'moderation_state' => 'published',
          ])->save();
          break;

        case 'image':
          Media::create([
            'bundle' => 'civictheme_image',
            'field_c_m_image' => $file,
            'moderation_state' => 'published',
          ])->save();
          break;
      }
    }
  }
}

/**
 * Provision menu links.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_provision__menu_links(): void {
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
    if (!empty(civictheme_provision_load_menu_items($menu_name))) {
      // Special case for menu items: only provision for menus without items.
      // @todo Refactor to support conditional items removal based on the flag.
      continue;
    }

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
function civictheme_provision__blocks(): void {
  _civictheme_provision__blocks__menu_blocks();
  $blocks = _civictheme_get_theme_blocks();
  foreach ($blocks as $block) {
    call_user_func($block['builder'], $block);
  }
}

/**
 * Provision menu blocks.
 */
function _civictheme_provision__blocks__menu_blocks(): void {
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
    'visibility' => [
      'request_path' => [
        'id' => 'request_path',
        'negate' => TRUE,
        'pages' => '/search',
      ],
    ],
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
function _civictheme_provision__blocks__banner(array $block): void {
  $theme_name = \Drupal::config('system.theme')->get('default');
  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $block_content->set('field_c_b_background_image', civictheme_provision_load_media_by_name($theme_name . '_background_1.png'));
  $block_content->set('field_c_b_theme', 'dark');
  $block_content->set('field_c_b_banner_blend_mode', 'soft-light');
  $block_content->set('field_c_b_banner_type', 'default');
  $block_content->set('field_c_b_featured_image', civictheme_provision_load_media_by_name('demo_image.jpg'));
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid());
}

/**
 * Provision search block.
 *
 * @param array $block
 *   Data structure of block.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_provision__blocks__search(array $block): void {
  // Redirect to 'Search' page if the path exists.
  $search_path = '/search';
  $fallback_path = '/';

  $url_object = \Drupal::service('path.validator')->getUrlIfValid(ltrim($search_path, '/'));

  if (!$url_object instanceof Url) {
    $search_path = $fallback_path;
  }

  if (!str_starts_with($search_path, 'internal:')) {
    $search_path = 'internal:' . $search_path;
  }

  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $block_content->set('field_c_b_link', [
    'title' => 'Search',
    'uri' => $search_path,
  ]);
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid());
}

/**
 * Provision mobile navigation block.
 *
 * @param array $block
 *   Data structure of block.
 */
function _civictheme_provision__blocks__mobile_navigation(array $block): void {
  $theme_name = \Drupal::config('system.theme')->get('default');

  $context_theme = \Drupal::service('class_resolver')->getInstanceFromDefinition(CivicthemeConfigManager::class)->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);

  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $block_content->set('field_c_b_top', $theme_name . '_primary_navigation');
  $block_content->set('field_c_b_bottom', $theme_name . '_secondary_navigation');
  $block_content->set('field_c_b_trigger_text', 'Menu');
  $block_content->set('field_c_b_trigger_theme', $context_theme);
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid());
}

/**
 * Provision signup block.
 *
 * @param array $block
 *   Data structure of block.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_provision__blocks__signup(array $block): void {
  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $paragraph = Paragraph::create([
    'type' => 'civictheme_promo',
  ]);
  $paragraph->set('field_c_p_title', 'Sign up');
  $paragraph->set('field_c_p_content', [
    'value' => 'Sign up for news and updates from our agency.',
    'format' => 'civictheme_rich_text',
  ]);
  $paragraph->set('field_c_p_link', [
    'uri' => 'internal:/subscribe',
    'title' => 'Sign up',
  ]);
  $paragraph->set('field_c_p_theme', CivicthemeConstants::THEME_DARK);
  $paragraph->set('field_c_p_vertical_spacing', CivicthemeConstants::VERTICAL_SPACING_TOP);
  $block_content->get('field_c_b_components')->appendItem($paragraph);
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid());
}

/**
 * Provision social links block.
 *
 * @param array $block
 *   Data structure of block.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_provision__blocks__social_links(array $block): void {
  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);

  $paragraph = Paragraph::create([
    'type' => 'civictheme_social_icon',
  ]);
  $paragraph->set('field_c_p_icon', civictheme_provision_load_media_by_name('facebook.svg'));
  $paragraph->set('field_c_p_link', [
    'uri' => 'https://www.facebook.com/',
    'title' => 'Facebook',
  ]);
  $block_content->get('field_c_b_social_icons')->appendItem($paragraph);

  $paragraph = Paragraph::create([
    'type' => 'civictheme_social_icon',
  ]);
  $paragraph->set('field_c_p_icon', civictheme_provision_load_media_by_name('linkedin.svg'));
  $paragraph->set('field_c_p_link', [
    'uri' => 'https://www.linkedin.com/',
    'title' => 'LinkedIn',
  ]);
  $block_content->get('field_c_b_social_icons')->appendItem($paragraph);

  $paragraph = Paragraph::create([
    'type' => 'civictheme_social_icon',
  ]);
  $paragraph->set('field_c_p_icon', civictheme_provision_load_media_by_name('x.svg'));
  $paragraph->set('field_c_p_link', [
    'uri' => 'https://www.twitter.com/',
    'title' => 'X',
  ]);
  $block_content->get('field_c_b_social_icons')->appendItem($paragraph);

  $block_content->set('field_c_b_theme', CivicthemeConstants::THEME_DARK);
  $block_content->set('field_c_b_with_border', TRUE);
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid(), [
    'label_machine_name' => 'footer_social_links',
  ]);
}

/**
 * Provision acknowledgment of country block.
 *
 * @param array $block
 *   Data structure of block.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_provision__blocks__acknowledgment_of_country(array $block): void {
  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $paragraph = Paragraph::create([
    'type' => 'civictheme_content',
  ]);
  $paragraph->set('field_c_p_content', [
    'value' => "<p class=\"ct-text-small\">We acknowledge the traditional owners of the country throughout Australia and their continuing connection to land, sea and community. We pay our respect to them and their cultures and to the elders past and present.</p>\r\n",
    'format' => 'civictheme_rich_text',
  ]);
  $paragraph->set('field_c_p_theme', CivicthemeConstants::THEME_DARK);
  $block_content->get('field_c_b_components')->appendItem($paragraph);
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid(), [
    'label_machine_name' => 'footer_acknowledgment_of_country',
  ]);
}

/**
 * Provision copyright block.
 *
 * @param array $block
 *   Data structure of block.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_provision__blocks__copyright(array $block): void {
  $block_content = civictheme_provision_create_block_content($block['type'], $block['name'], $block['uuid']);
  $paragraph = Paragraph::create([
    'type' => 'civictheme_content',
  ]);
  $paragraph->set('field_c_p_content', [
    'value' => "<p class=\"text-align-right ct-text-small\">©2024 Commonwealth of Australia</p>\r\n",
    'format' => 'civictheme_rich_text',
  ]);
  $paragraph->set('field_c_p_theme', CivicthemeConstants::THEME_DARK);
  $block_content->get('field_c_b_components')->appendItem($paragraph);
  $block_content->save();
  civictheme_provision_place_block($block['name'], $block['region'], $block_content->uuid(), [
    'label_machine_name' => 'footer_copyright',
  ]);
}

/**
 * Provision theme settings.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_provision__theme_settings(): void {
  $theme_name = \Drupal::config('system.theme')->get('default');
  $theme_path = \Drupal::service('extension.list.theme')->getPath($theme_name);

  /** @var \Drupal\civictheme\CivicthemeConfigImporter $config_importer */
  $config_importer = \Drupal::service('class_resolver')->getInstanceFromDefinition(CivicthemeConfigImporter::class);
  // Import config from the 'config/optional' directory of the submodule being
  // installed.
  $config_importer->importSingleConfig(
    'civictheme.settings',
    \Drupal::service('extension.list.theme')->getPath('civictheme') . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'install',
    $theme_name . '.settings',
    [
      'themes/contrib/civictheme' => $theme_path,
      'civictheme' => $theme_name,
    ]
  );
}

/**
 * Provision permissions.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_provision__permissions(): void {
  $map = civictheme_provision_permissions_map();

  /** @var \Drupal\user\PermissionHandler $permission_handler */
  $permission_handler = \Drupal::getContainer()->get('user.permissions');
  $all_permissions = array_keys($permission_handler->getPermissions());

  foreach ($map as $role_name => $permissions_map) {
    /** @var \Drupal\user\RoleInterface|null $role */
    $role = \Drupal::entityTypeManager()->getStorage('user_role')->load($role_name);
    if ($role === NULL) {
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
 * Provision site settings.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_provision__site_settings(): void {
  $config = \Drupal::service('config.factory')->getEditable('system.site');
  $config->set('slogan', 'A design system by Salsa Digital');
  $config->save();
}

/**
 * Map of roles and permissions to be granted.
 *
 * This allows to centrally manage additional permissions that may be
 * optionally provisioned for specific CivicTheme roles. Such permissions
 * would not be a part of CivicTheme roles' configurations, but would be
 * expected the roles to have in some Drupal profiles (like GovCMS).
 *
 * @return array<string, array<string, array<string>>>
 *   Array of with role names as keys and permissions arrays as values, each of
 *   which is keyed by a module.
 */
function civictheme_provision_permissions_map(): array {
  return [
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
}

/**
 * @}
 */

/**
 * Install modules required by CivicTheme.
 *
 * This should be run before the theme is installed.
 *
 * @code
 * drush php:eval "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/theme-settings.provision.inc'; civictheme_enable_modules();"
 * @endcode
 *
 * @see https://www.drupal.org/node/2652542
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 *
 * @param bool $include_optional
 *   Whether to include optional dependencies. Defaults to TRUE.
 *
 * @throws \Drupal\Core\Extension\ExtensionNameLengthException
 * @throws \Drupal\Core\Extension\MissingDependencyException
 */
function civictheme_enable_modules(bool $include_optional = TRUE): void {
  $dependencies = _civictheme_get_theme_dependencies('civictheme', $include_optional);
  \Drupal::getContainer()->get('module_installer')->install($dependencies);
}

/**
 * Get dependencies of a theme.
 *
 * @param string $theme_name
 *   The name of the theme.
 * @param bool $include_optional
 *   Whether to include optional dependencies. Defaults to TRUE.
 *
 * @return array<string>
 *   Array of dependency names.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function _civictheme_get_theme_dependencies(string $theme_name, bool $include_optional = TRUE): array {
  /** @var \Drupal\Core\Extension\ThemeHandler $theme_handler */
  $theme_handler = \Drupal::getContainer()->get('theme_handler');
  $theme_data = $theme_handler->rebuildThemeData();

  // Merge dependencies from the theme's info file and from the 'optional'
  // config. We are deliberately not including dependencies from the 'install'
  // config to make sure that any unlisted required dependencies in the
  // theme's .info file would trigger an error.
  // @phpstan-ignore-next-line
  $dependencies = array_keys($theme_data['civictheme']->module_dependencies);

  if ($include_optional) {
    // @phpstan-ignore-next-line
    $theme_path = $theme_data[$theme_name]->subpath;
    $config_storage = new FileStorage($theme_path . '/config/optional');

    foreach ($config_storage->listAll() as $name) {
      $config_dependencies = $config_storage->read($name)['dependencies'] ?? [];
      if (!empty($config_dependencies)) {
        $dependencies = array_merge($dependencies, $config_dependencies['module'] ?? []);
      }
    }
  }

  $dependencies = array_unique($dependencies);

  return array_diff($dependencies, [$theme_name]);
}

/**
 * Map of blocks to be created.
 *
 * @return array<string, array<string, mixed>>
 *   Array of blocks keyed by block ID.
 */
function _civictheme_get_theme_blocks(): array {
  return [
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
    'civictheme_footer_social_links' =>
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
}

/**
 * Provision Media View.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_provision__media_view(): void {
  // Disable the 'views.view.media' view.
  \Drupal::configFactory()
    ->getEditable('views.view.media')
    ->set('status', FALSE)
    ->save();

  // Enable the 'views.view.civictheme_media' view.
  \Drupal::configFactory()
    ->getEditable('views.view.civictheme_media')
    ->set('status', TRUE)
    ->save();
}
