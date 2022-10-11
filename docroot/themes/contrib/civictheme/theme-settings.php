<?php

/**
 * @file
 * Theme settings form for CivicTheme theme.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\File\Exception\FileException;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\StreamWrapper\StreamWrapperManager;
use Drupal\Core\Url;

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function civictheme_form_system_theme_settings_alter(&$form, FormStateInterface &$form_state) {
  _civictheme_form_system_theme_settings_theme_version($form, $form_state);
  _civictheme_form_system_theme_settings_colors($form, $form_state);
  _civictheme_form_system_theme_settings_components($form, $form_state);
  _civictheme_form_system_theme_settings_content_provision($form, $form_state);
  _civictheme_form_system_theme_settings_storybook($form, $form_state);
}

/**
 * Provide theme version to theme settings.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function _civictheme_form_system_theme_settings_theme_version(&$form, FormStateInterface &$form_state) {
  $civictheme_version = civictheme_get_version();
  if ($civictheme_version) {
    $form['civictheme_version'] = [
      '#type' => 'inline_template',
      '#template' => '{{ content|raw }}',
      '#context' => [
        'content' => t('<div class="messages messages--info">CivicTheme version: @version</div>', [
          '@version' => Link::fromTextAndUrl($civictheme_version, Url::fromUri('https://github.com/salsadigitalauorg/civictheme/releases/tag/' . $civictheme_version))->toString(),
        ]),
      ],
      '#weight' => -100,
    ];
  }
}

/**
 * Provide components settings to theme settings form.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _civictheme_form_system_theme_settings_components(&$form, FormStateInterface &$form_state) {
  $form['components'] = [
    '#type' => 'vertical_tabs',
    '#title' => t('CivicTheme components'),
    '#weight' => 50,
    '#tree' => TRUE,
  ];

  $form['components']['logo'] = [
    '#type' => 'details',
    '#title' => t('Logo'),
    '#open' => TRUE,
    '#group' => 'components',
    '#tree' => TRUE,
  ];

  $breakpoints = ['desktop', 'mobile'];
  $logo_types = ['primary', 'secondary'];

  foreach (civictheme_theme_options() as $theme => $theme_label) {
    foreach ($logo_types as $logo_type) {
      $form['components']['logo'][$logo_type][$theme] = [
        '#type' => 'fieldset',
        '#title' => t('Logo @logo_type @theme', [
          '@theme' => $theme_label,
          '@logo_type' => $logo_type,
        ]),
        '#tree' => TRUE,
      ];
      foreach ($breakpoints as $breakpoint) {
        $form['components']['logo'][$logo_type][$theme]["image_{$logo_type}_{$theme}_{$breakpoint}_group"] = [
          '#type' => 'fieldset',
          '#title' => t('Logo @logo_type @theme @breakpoint', [
            '@theme' => $theme_label,
            '@breakpoint' => $breakpoint,
            '@logo_type' => $logo_type,
          ]),
          '#tree' => FALSE,
        ];

        $form['components']['logo'][$logo_type][$theme]["image_{$logo_type}_{$theme}_{$breakpoint}"] = [
          '#type' => 'textfield',
          '#title' => t('Logo image for @logo_type in @theme theme for @breakpoint', [
            '@theme' => $theme_label,
            '@breakpoint' => $breakpoint,
            '@logo_type' => $logo_type,
          ]),
          '#description' => _civictheme_path_field_description(theme_get_setting("components.logo.image_{$logo_type}_{$theme}_{$breakpoint}"), "logo-{$logo_type}-{$theme}-{$breakpoint}.svg"),
          '#default_value' => _civictheme_field_friendly_path(theme_get_setting("components.logo.image_{$logo_type}_{$theme}_{$breakpoint}")),
          '#group' => "image_{$logo_type}_{$theme}_{$breakpoint}_group",
        ];

        $form['components']['logo'][$logo_type][$theme]["image_{$logo_type}_{$theme}_{$breakpoint}_group"]["image_{$logo_type}_{$theme}_{$breakpoint}_upload"] = [
          '#type' => 'file',
          '#title' => t('Upload logo image for @logo_type in @theme theme for @breakpoint', [
            '@theme' => $theme_label,
            '@breakpoint' => $breakpoint,
            '@logo_type' => $logo_type,
          ]),
          '#maxlength' => 40,
          '#description' => t("If you don't have direct file access to the server, use this field to upload your logo."),
          '#upload_validators' => [
            'file_validate_is_image' => [],
          ],
          '#tree' => FALSE,
          '#weight' => 1,
        ];
      }
    }
  }

  $form['components']['logo']['image_alt'] = [
    '#type' => 'textfield',
    '#title' => t('Logo image "alt" text'),
    '#description' => t('Text for the <code>alt</code> attribute of the site logo image.'),
    '#default_value' => theme_get_setting('components.logo.image_alt'),
  ];

  $form['components']['header'] = [
    '#type' => 'details',
    '#title' => t('Header'),
    '#group' => 'components',
    '#tree' => TRUE,
  ];

  $form['components']['header']['theme'] = [
    '#title' => t('Theme'),
    '#description' => t('Set the Header color theme.'),
    '#type' => 'radios',
    '#required' => TRUE,
    '#options' => civictheme_theme_options(),
    '#default_value' => theme_get_setting('components.header.theme') ?? CIVICTHEME_HEADER_THEME_DEFAULT,
  ];

  $form['components']['header']['logo_type'] = [
    '#title' => t('Logo type'),
    '#description' => t('Logo type to appear in the Header.'),
    '#type' => 'radios',
    '#required' => TRUE,
    '#options' => civictheme_type_options(),
    '#default_value' => theme_get_setting('components.header.logo_type') ?? CIVICTHEME_LOGO_TYPE_DEFAULT,
  ];

  $form['components']['footer'] = [
    '#type' => 'details',
    '#title' => t('Footer'),
    '#group' => 'components',
    '#tree' => TRUE,
  ];

  $form['components']['footer']['theme'] = [
    '#title' => t('Theme'),
    '#description' => t('Set the Footer color theme.'),
    '#type' => 'radios',
    '#required' => TRUE,
    '#options' => civictheme_theme_options(),
    '#default_value' => theme_get_setting('components.footer.theme') ?? CIVICTHEME_FOOTER_THEME_DEFAULT,
  ];

  $form['components']['footer']['logo_type'] = [
    '#title' => t('Logo type'),
    '#description' => t('Logo type to appear in the Footer.'),
    '#type' => 'radios',
    '#required' => TRUE,
    '#options' => civictheme_type_options(),
    '#default_value' => theme_get_setting('components.footer.logo_type') ?? CIVICTHEME_LOGO_TYPE_DEFAULT,
  ];

  $form['components']['footer']['background_image'] = [
    '#type' => 'textfield',
    '#title' => t('Footer background image path'),
    '#description' => _civictheme_path_field_description(theme_get_setting('components.footer.background_image'), 'footer-background.png'),
    '#default_value' => _civictheme_field_friendly_path(theme_get_setting('components.footer.background_image')),
  ];

  $form['components']['navigation'] = [
    '#type' => 'details',
    '#title' => t('Navigation'),
    '#group' => 'components',
    '#tree' => TRUE,
  ];

  $navigation_map = [
    'primary_navigation' => [
      'title' => t('Primary navigation'),
      'dropdown' => CIVICTHEME_NAVIGATION_DROPDOWN_DRAWER,
      'dropdown_columns' => 4,
      'dropdown_columns_fill' => FALSE,
      'is_animated' => FALSE,
    ],
    'secondary_navigation' => [
      'title' => t('Secondary navigation'),
      'dropdown' => CIVICTHEME_NAVIGATION_DROPDOWN_NONE,
      'dropdown_columns' => 4,
      'dropdown_columns_fill' => FALSE,
      'is_animated' => FALSE,
    ],
  ];

  foreach ($navigation_map as $navigation_name => $navigation_defaults) {
    $form['components']['navigation'][$navigation_name] = [
      '#type' => 'details',
      '#title' => $navigation_defaults['title'],
      '#tree' => TRUE,
      '#open' => TRUE,
    ];

    $form['components']['navigation'][$navigation_name]['dropdown'] = [
      '#title' => t('Dropdown type'),
      '#description' => t('Select how the menu sub-tree items would appear in the menu.'),
      '#type' => 'select',
      '#required' => TRUE,
      '#options' => [
        'none' => t('None'),
        'dropdown' => t('Dropdown'),
        'drawer' => t('Drawer'),
      ],
      '#default_value' => theme_get_setting("components.navigation.$navigation_name.dropdown") ?? $navigation_defaults['dropdown'],
    ];

    $form['components']['navigation'][$navigation_name]['dropdown_columns'] = [
      '#title' => t('Number of columns in the drawer row'),
      '#description' => t('Number of menu columns per row. If there are more menus than items per row - they will flow on the next row.'),
      '#type' => 'number',
      '#min' => 1,
      '#max' => 4,
      '#default_value' => theme_get_setting("components.navigation.$navigation_name.dropdown_columns") ?? $navigation_defaults['dropdown_columns'],
      '#states' => [
        'visible' => [
          ':input[name="components[navigation][' . $navigation_name . '][dropdown]"]' => ['value' => CIVICTHEME_NAVIGATION_DROPDOWN_DRAWER],
        ],
      ],
    ];

    $form['components']['navigation'][$navigation_name]['dropdown_columns_fill'] = [
      '#title' => t('Fill width of the last drawer column'),
      '#description' => t('Fill the width of the last column in the drawer. Useful for large menus.'),
      '#type' => 'checkbox',
      '#default_value' => theme_get_setting("components.navigation.$navigation_name.dropdown_columns_fill") ?? $navigation_defaults['dropdown_columns_fill'],
      '#states' => [
        'visible' => [
          ':input[name="components[navigation][' . $navigation_name . '][dropdown]"]' => ['value' => CIVICTHEME_NAVIGATION_DROPDOWN_DRAWER],
        ],
      ],
    ];

    $form['components']['navigation'][$navigation_name]['is_animated'] = [
      '#title' => t('Animate'),
      '#description' => t('Animate transitions.'),
      '#type' => 'checkbox',
      '#default_value' => theme_get_setting("components.navigation.$navigation_name.is_animated") ?? $navigation_defaults['is_animated'],
      '#states' => [
        'visible' => [
          ':input[name="components[navigation][' . $navigation_name . '][dropdown]"]' => [
            ['value' => CIVICTHEME_NAVIGATION_DROPDOWN_DROPDOWN],
            ['value' => CIVICTHEME_NAVIGATION_DROPDOWN_DRAWER],
          ],
        ],
      ],
    ];
  }

  $form['components']['link'] = [
    '#type' => 'details',
    '#title' => t('Link'),
    '#group' => 'components',
    '#tree' => TRUE,
  ];

  $form['components']['link']['new_window'] = [
    '#type' => 'checkbox',
    '#title' => t('Open links in a new window'),
    '#description' => t('Open internal and external links in a new browser window.'),
    '#default_value' => theme_get_setting('components.link.new_window'),
  ];

  $form['components']['link']['external_new_window'] = [
    '#type' => 'checkbox',
    '#title' => t('Open external links in a new window'),
    '#description' => t('Open all external links in a new browser window.'),
    '#default_value' => theme_get_setting('components.link.external_new_window'),
    '#states' => [
      'visible' => [
        ':input[name="components[link][new_window]"]' => ['checked' => FALSE],
      ],
    ],
  ];

  $form['components']['link']['external_override_domains'] = [
    '#type' => 'textarea',
    '#title' => t('Override external link domains'),
    '#description' => t('A list of domains that should be considered as internal. External links matching these domains will not be displayed as external.<br/>One domain per line.<br/>Do not include trailing slash (/).<br/>Protocol is optional.'),
    '#default_value' => civictheme_array_to_multiline(theme_get_setting('components.link.external_override_domains')),
    '#rows' => 4,
  ];

  // Create validators for all components, if they exist.
  foreach (array_keys($form['components']) as $component_name) {
    $validator = "_civictheme_form_system_theme_settings_{$component_name}_validate";
    if (is_callable($validator)) {
      $form['#validate'][] = $validator;
    }

    $submit = "_civictheme_form_system_theme_settings_{$component_name}_submit";
    if (is_callable($submit)) {
      $form['#submit'][] = $submit;
    }
  }
}

/**
 * Validate callback for theme settings form of Logo component.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function _civictheme_form_system_theme_settings_logo_validate(array &$form, FormStateInterface $form_state) {
  $breakpoints = ['desktop', 'mobile'];
  $logo_types = ['primary', 'secondary'];
  foreach (array_keys(civictheme_theme_options()) as $theme) {
    foreach ($logo_types as $logo_type) {
      foreach ($breakpoints as $breakpoint) {
        $field_name_key = [
          'components',
          'logo',
          $logo_type,
          $theme,
          "image_{$logo_type}_{$theme}_{$breakpoint}",
        ];
        $path = $form_state->getValue($field_name_key);
        $field_name_processed_key = [
          'components',
          'logo',
          "image_{$logo_type}_{$theme}_{$breakpoint}",
        ];

        // Check for a new uploaded logo.
        if (isset($form['components']['logo']["image_{$logo_type}_{$theme}_{$breakpoint}_group"]["image_{$logo_type}_{$theme}_{$breakpoint}_upload"])) {
          $file = _file_save_upload_from_form($form['components']['logo']["image_{$logo_type}_{$theme}_{$breakpoint}_group"]["image_{$logo_type}_{$theme}_{$breakpoint}_upload"], $form_state, 0, FileSystemInterface::EXISTS_REPLACE);
          if ($file) {
            // Put the temp file in form_values so we can save it on submit.
            $form_state->setValue("image_{$logo_type}_{$theme}_{$breakpoint}_upload", $file);
          }
        }

        if (!empty($path)) {
          $path = _civictheme_form_system_theme_settings_validate_path($path);
          if ($path) {
            $path = \Drupal::service('file_url_generator')->generateString($path);
            $form_state->setValue($field_name_processed_key, ltrim($path, '/'));
            continue;
          }
          $form_state->setErrorByName(implode('][', $field_name_key), t('The image path is invalid.'));
        }
      }
    }
  }
}

/**
 * Submit callback for theme settings form of Logo component.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function _civictheme_form_system_theme_settings_logo_submit(array &$form, FormStateInterface $form_state) {
  $breakpoints = ['desktop', 'mobile'];
  $logo_types = ['primary', 'secondary'];
  $values = $form_state->getValues();
  foreach (array_keys(civictheme_theme_options()) as $theme) {
    foreach ($logo_types as $logo_type) {
      foreach ($breakpoints as $breakpoint) {
        $logo_field_name_key = [
          'components',
          'logo',
          "image_{$logo_type}_{$theme}_{$breakpoint}",
        ];
        $field_name_key = "image_{$logo_type}_{$theme}_{$breakpoint}_upload";

        // If the user uploaded a new logo, save it to a permanent location and
        // use it in place of the provided path.
        $default_scheme = \Drupal::config('system.file')->get('default_scheme');
        try {
          if (!empty($values[$field_name_key])) {
            $filename = \Drupal::service('file_system')->copy($values[$field_name_key]->getFileUri(), $default_scheme . '://');
            if (!empty($filename)) {
              $path = _civictheme_form_system_theme_settings_validate_path($filename);
              if ($path) {
                $path = \Drupal::service('file_url_generator')->generateString($path);
                $form_state->setValue($logo_field_name_key, ltrim($path, '/'));
              }
            }
          }
        }
        catch (FileException $e) {
          // Ignore.
        }
        $form_state->unsetValue($field_name_key);
      }
    }
  }
}

/**
 * Validate callback for theme settings form to check footer settings.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_form_system_theme_settings_footer_validate(array $form, FormStateInterface &$form_state) {
  $field_name_key = ['components', 'footer', 'background_image'];
  $path = $form_state->getValue($field_name_key);

  if (!empty($path)) {
    $path = _civictheme_form_system_theme_settings_validate_path($path);
    if ($path) {
      $path = \Drupal::service('file_url_generator')->generateString($path);
      $form_state->setValue($field_name_key, ltrim($path, '/'));
    }
    else {
      $form_state->setErrorByName(implode('][', $field_name_key), t('The image path is invalid.'));
    }
  }
}

/**
 * Validate callback for theme settings form of Link component.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_form_system_theme_settings_link_validate(array &$form, FormStateInterface $form_state) {
  $override_domain_field_name_keys = [
    'components',
    'link',
    'external_override_domains',
  ];
  $domains = $form_state->getValue($override_domain_field_name_keys, '');
  $domains = civictheme_multiline_to_array($domains);
  $invalid_domains = [];
  foreach ($domains as $domain) {
    // Allow to enter 'example.com' instead of 'http://example.com'.
    $domain_normalised = _civictheme_external_link_normalise_domain($domain);
    if (!UrlHelper::isValid($domain_normalised, TRUE)) {
      $invalid_domains[] = $domain;
      continue;
    }

    $domain_parts = parse_url($domain_normalised);
    if (!empty($domain_parts['path']) || !empty($domain_parts['query']) || !empty($domain_parts['fragment'])) {
      $invalid_domains[] = $domain;
    }
  }

  if (!empty($invalid_domains)) {
    $form_state->setErrorByName(implode('][', $override_domain_field_name_keys), t('Domain values are not valid domains: %domains', [
      '%domains' => implode(', ', $invalid_domains),
    ]));

    return;
  }

  // Set field to converted array of links.
  $form_state->setValue($override_domain_field_name_keys, $domains);
}

/**
 * Provide content provision to theme settings form.
 */
function _civictheme_form_system_theme_settings_content_provision(&$form, FormStateInterface &$form_state) {
  // Programmatically provision content.
  $civictheme_path = \Drupal::service('extension.list.theme')->getPath('civictheme');
  $provision_file = $civictheme_path . DIRECTORY_SEPARATOR . 'theme-settings.provision.inc';
  if (file_exists($provision_file)) {
    require_once $provision_file;
    _civictheme_form_system_theme_settings_alter_provision($form, $form_state);
  }
}

/**
 * Provide storybook to theme settings form.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function _civictheme_form_system_theme_settings_storybook(&$form, FormStateInterface &$form_state) {
  $theme_name = \Drupal::theme()->getActiveTheme()->getName();
  $theme_path = \Drupal::service('extension.list.theme')->getPath($theme_name);

  // Show compiled Storybook.
  // @note For development of components, please use `npm run storybook`.
  $form['storybook'] = [
    '#type' => 'details',
    '#title' => t('Storybook for %theme theme', [
      '%theme' => $theme_name,
    ]),
    '#open' => TRUE,
    '#weight' => 52,
  ];

  $storybook_file = $theme_path . '/storybook-static/index.html';
  if (file_exists($storybook_file)) {
    $url = \Drupal::service('file_url_generator')->generateAbsoluteString($storybook_file) . '?cachebust=' . time();
    $form['storybook']['link'] = [
      '#type' => 'inline_template',
      '#template' => '<p><a href="{{ url }}">{{ url }}</a></p>',
      '#context' => [
        'url' => $url,
      ],
    ];

    $form['storybook']['markup'] = [
      '#type' => 'inline_template',
      '#template' => '<iframe id="storybook" width="100%" height="1024" src="{{ url }}"></iframe>',
      '#context' => [
        'url' => $url,
      ],
    ];

    return;
  }
  $form['storybook']['markup'] = [
    '#markup' => t('Compiled Storybook cannot be found at @path. Try compiling it with <code>npm run build</code>.', [
      '@path' => $storybook_file,
    ]),
  ];
}

/**
 * Helper function for the system_theme_settings form.
 *
 * Attempts to validate normal system paths, paths relative to the public files
 * directory, or stream wrapper URIs. If the given path is any of the above,
 * returns a valid path or URI that the theme system can display.
 *
 * @param string $path
 *   A path relative to the Drupal root or to the public files directory, or
 *   a stream wrapper URI.
 *
 * @return mixed
 *   A valid path that can be displayed through the theme system, or FALSE if
 *   the path could not be validated.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_form_system_theme_settings_validate_path($path) {
  // Absolute local file paths are invalid.
  if (\Drupal::service('file_system')->realpath($path) == $path) {
    return FALSE;
  }
  // A path relative to the Drupal root or a fully qualified URI is valid.
  if (is_file($path)) {
    return $path;
  }
  // Prepend 'public://' for relative file paths within public filesystem.
  if (StreamWrapperManager::getScheme($path) === FALSE) {
    $path = 'public://' . $path;
  }
  if (is_file($path)) {
    return $path;
  }

  return FALSE;
}

/**
 * Convert path to a human-friendly path.
 *
 * @param string $original_path
 *   The original path.
 *
 * @return string
 *   Friendly path or original path if an invalid stream wrapper was provided.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_field_friendly_path($original_path) {
  // If path is a public:// URI, display the path relative to the files
  // directory; stream wrappers are not end-user friendly.
  $friendly_path = NULL;

  if ($original_path && StreamWrapperManager::getScheme($original_path) == 'public') {
    $friendly_path = StreamWrapperManager::getTarget($original_path);
  }

  return $friendly_path ?? $original_path;
}

/**
 * Provide a description for a path field.
 *
 * @param string $original_path
 *   The original path from the current field value.
 * @param string $fallback_path
 *   Fallback file name.
 *
 * @return string
 *   Description string.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_path_field_description($original_path, $fallback_path) {
  $theme_name = \Drupal::configFactory()->get('system.theme')->get('default');
  /** @var \Drupal\Core\Extension\ThemeHandler $theme_handler */
  $theme_handler = \Drupal::getContainer()->get('theme_handler');

  $friendly_path = _civictheme_field_friendly_path($original_path);

  $local_file = \Drupal::theme()->getActiveTheme()->getPath() . '/' . $fallback_path;

  // Prepare local file path for description.
  if ($original_path && !empty($friendly_path)) {
    $local_file = strtr($original_path, ['public:/' => PublicStream::basePath()]);
  }
  elseif ($theme_name) {
    $local_file = $theme_handler->getTheme($theme_name)->getPath() . '/' . $fallback_path;
  }

  return t('Examples: <code>@implicit-public-file</code> (for a file in the public filesystem), <code>@explicit-file</code>, or <code>@local-file</code>.', [
    '@implicit-public-file' => $friendly_path ?? $fallback_path,
    '@explicit-file' => StreamWrapperManager::getScheme($original_path ?? '') !== FALSE ? $original_path : 'public://' . $fallback_path,
    '@local-file' => $local_file,
  ]);
}

/**
 * Provide color settings to theme settings form.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _civictheme_form_system_theme_settings_colors(&$form, FormStateInterface &$form_state) {
  $form['colors'] = [
    '#type' => 'details',
    '#title' => t('Colors'),
    '#weight' => 40,
    '#open' => TRUE,
    '#tree' => TRUE,
  ];

  $form['colors']['use_brand_colors'] = [
    '#title' => t('Use Brand colors'),
    '#type' => 'checkbox',
    '#default_value' => theme_get_setting("colors.use_brand_colors") ?? TRUE,
  ];
  // Brand.
  $form['colors']['brand'] = [
    '#type' => 'fieldset',
    '#title' => t('Brand colors'),
    '#tree' => TRUE,
    '#states' => [
      'visible' => [
        ':input[name="colors[use_brand_colors"]' => ['checked' => TRUE],
      ],
    ],
  ];

  // Brand light.
  $form['colors']['brand']['light'] = [
    '#type' => 'fieldset',
    '#tree' => TRUE,
    '#attributes' => [
      'class' => 'color-light',
    ],
  ];
  $form['colors']['brand']['light']['brand1'] = [
    '#type' => 'color',
    '#title' => t('Brand1'),
    '#default_value' => theme_get_setting("colors.brand.light.brand1") ?? '#00698f',
  ];
  $form['colors']['brand']['light']['brand2'] = [
    '#type' => 'color',
    '#title' => t('Brand2'),
    '#default_value' => theme_get_setting("colors.brand.light.brand2") ?? '#e6e9eb',
  ];
  $form['colors']['brand']['light']['brand3'] = [
    '#type' => 'color',
    '#title' => t('Brand3'),
    '#default_value' => theme_get_setting("colors.brand.light.brand3") ?? '#121313',
  ];

  // Brand dark.
  $form['colors']['brand']['dark'] = [
    '#type' => 'fieldset',
    '#tree' => TRUE,
    '#attributes' => [
      'class' => 'color-dark',
    ],
  ];
  $form['colors']['brand']['dark']['brand1'] = [
    '#type' => 'color',
    '#title' => t('Brand1'),
    '#default_value' => theme_get_setting("colors.brand.dark.brand1") ?? '#61daff',
  ];
  $form['colors']['brand']['dark']['brand2'] = [
    '#type' => 'color',
    '#title' => t('Brand2'),
    '#default_value' => theme_get_setting("colors.brand.dark.brand2") ?? '#003a4f',
  ];
  $form['colors']['brand']['dark']['brand3'] = [
    '#type' => 'color',
    '#title' => t('Brand3'),
    '#default_value' => theme_get_setting("colors.brand.dark.brand3") ?? '#00698f',
  ];

  // Palette.
  $form['colors']['palette'] = [
    '#type' => 'details',
    '#title' => t('Palette colors'),
    '#open' => TRUE,
    '#tree' => TRUE,
  ];

  // Palette light.
  $form['colors']['palette']['light'] = [
    '#type' => 'fieldset',
    '#tree' => TRUE,
    '#attributes' => [
      'class' => 'color-light',
    ],
  ];
  // Palette light typography.
  $form['colors']['palette']['light']['typography'] = [
    '#type' => 'fieldset',
    '#title' => t('Typography'),
    '#tree' => TRUE,
  ];
  $form['colors']['palette']['light']['typography']['heading'] = [
    '#type' => 'color',
    '#title' => t('Heading'),
    '#default_value' => theme_get_setting("colors.palette.light.heading") ?? '#000000',
  ];
  $form['colors']['palette']['light']['typography']['body'] = [
    '#type' => 'color',
    '#title' => t('Body'),
    '#default_value' => theme_get_setting("colors.palette.light.body") ?? '#000000',
  ];
  // Palette light background.
  $form['colors']['palette']['light']['background'] = [
    '#type' => 'fieldset',
    '#title' => t('Background'),
  ];
  $form['colors']['palette']['light']['background']['background_light'] = [
    '#type' => 'color',
    '#title' => t('Background light'),
    '#default_value' => theme_get_setting("colors.palette.light.background_light") ?? '#000000',
  ];
  $form['colors']['palette']['light']['background']['background'] = [
    '#type' => 'color',
    '#title' => t('Background'),
    '#default_value' => theme_get_setting("colors.palette.light.background") ?? '#000000',
  ];
  $form['colors']['palette']['light']['background']['background_dark'] = [
    '#type' => 'color',
    '#title' => t('Background dark'),
    '#default_value' => theme_get_setting("colors.palette.light.background_dark") ?? '#000000',
  ];
  // Palette light border.
  $form['colors']['palette']['light']['border'] = [
    '#type' => 'fieldset',
    '#title' => t('Border'),
  ];
  $form['colors']['palette']['light']['border']['border_light'] = [
    '#type' => 'color',
    '#title' => t('Border light'),
    '#default_value' => theme_get_setting("colors.palette.light.border_light") ?? '#000000',
  ];
  $form['colors']['palette']['light']['border']['border'] = [
    '#type' => 'color',
    '#title' => t('Border'),
    '#default_value' => theme_get_setting("colors.palette.light.border") ?? '#000000',
  ];
  $form['colors']['palette']['light']['border']['border_dark'] = [
    '#type' => 'color',
    '#title' => t('Border Dark'),
    '#default_value' => theme_get_setting("colors.palette.light.border_dark") ?? '#000000',
  ];
  // Palette light interection.
  $form['colors']['palette']['light']['interection'] = [
    '#type' => 'fieldset',
    '#title' => t('Interection'),
  ];
  $form['colors']['palette']['light']['interection']['interaction_text'] = [
    '#type' => 'color',
    '#title' => t('Interaction text'),
    '#default_value' => theme_get_setting("colors.palette.light.interaction_text") ?? '#000000',
  ];
  $form['colors']['palette']['light']['interection']['interaction_background'] = [
    '#type' => 'color',
    '#title' => t('Interaction background'),
    '#default_value' => theme_get_setting("colors.palette.light.interaction_background") ?? '#000000',
  ];
  $form['colors']['palette']['light']['interection']['interaction_hover_text'] = [
    '#type' => 'color',
    '#title' => t('Interaction hover text'),
    '#default_value' => theme_get_setting("colors.palette.light.interaction_hover_text") ?? '#000000',
  ];
  $form['colors']['palette']['light']['interection']['interaction_hover_background'] = [
    '#type' => 'color',
    '#title' => t('Interaction hover background'),
    '#default_value' => theme_get_setting("colors.palette.light.interaction_hover_background") ?? '#000000',
  ];
  $form['colors']['palette']['light']['interection']['interaction_focus'] = [
    '#type' => 'color',
    '#title' => t('Interaction focus'),
    '#default_value' => theme_get_setting("colors.palette.light.interaction_focus") ?? '#8b5cd7',
  ];
  // Palette light highlight.
  $form['colors']['palette']['light']['highlight'] = [
    '#type' => 'fieldset',
    '#title' => t('Highlight'),
  ];
  $form['colors']['palette']['light']['highlight']['highlight'] = [
    '#type' => 'color',
    '#title' => t('Highlight'),
    '#default_value' => theme_get_setting("colors.palette.light.highlight") ?? '#000000',
  ];
  // Palette light status.
  $form['colors']['palette']['light']['status'] = [
    '#type' => 'fieldset',
    '#title' => t('Status'),
  ];
  $form['colors']['palette']['light']['status']['information'] = [
    '#type' => 'color',
    '#title' => t('Information'),
    '#default_value' => theme_get_setting("colors.palette.light.information") ?? '#007cbf',
  ];
  $form['colors']['palette']['light']['status']['warning'] = [
    '#type' => 'color',
    '#title' => t('Warning'),
    '#default_value' => theme_get_setting("colors.palette.light.warning") ?? '#c95100',
  ];
  $form['colors']['palette']['light']['status']['error'] = [
    '#type' => 'color',
    '#title' => t('Error'),
    '#default_value' => theme_get_setting("colors.palette.light.error") ?? '#ce3836',
  ];
  $form['colors']['palette']['light']['status']['success'] = [
    '#type' => 'color',
    '#title' => t('Success'),
    '#default_value' => theme_get_setting("colors.palette.light.success") ?? '#008482',
  ];

  // Palette dark.
  $form['colors']['palette']['dark'] = [
    '#type' => 'fieldset',
    '#tree' => TRUE,
    '#attributes' => [
      'class' => 'color-dark',
    ],
  ];
  // Palette dark typography.
  $form['colors']['palette']['dark']['typography'] = [
    '#type' => 'fieldset',
    '#title' => t('Typography'),
    '#tree' => TRUE,
  ];
  $form['colors']['palette']['dark']['typography']['heading'] = [
    '#type' => 'color',
    '#title' => t('Heading'),
    '#default_value' => theme_get_setting("colors.palette.dark.heading") ?? '#000000',
  ];
  $form['colors']['palette']['dark']['typography']['body'] = [
    '#type' => 'color',
    '#title' => t('Body'),
    '#default_value' => theme_get_setting("colors.palette.dark.body") ?? '#000000',
  ];
  // Palette dark background.
  $form['colors']['palette']['dark']['background'] = [
    '#type' => 'fieldset',
    '#title' => t('Background'),
  ];
  $form['colors']['palette']['dark']['background']['background_light'] = [
    '#type' => 'color',
    '#title' => t('Background light'),
    '#default_value' => theme_get_setting("colors.palette.dark.background_light") ?? '#000000',
  ];
  $form['colors']['palette']['dark']['background']['background'] = [
    '#type' => 'color',
    '#title' => t('Background'),
    '#default_value' => theme_get_setting("colors.palette.dark.background") ?? '#000000',
  ];
  $form['colors']['palette']['dark']['background']['background_dark'] = [
    '#type' => 'color',
    '#title' => t('Background dark'),
    '#default_value' => theme_get_setting("colors.palette.dark.background_dark") ?? '#000000',
  ];
  // Palette dark border.
  $form['colors']['palette']['dark']['border'] = [
    '#type' => 'fieldset',
    '#title' => t('Border'),
  ];
  $form['colors']['palette']['dark']['border']['border_light'] = [
    '#type' => 'color',
    '#title' => t('Border light'),
    '#default_value' => theme_get_setting("colors.palette.dark.border_light") ?? '#000000',
  ];
  $form['colors']['palette']['dark']['border']['border'] = [
    '#type' => 'color',
    '#title' => t('Border'),
    '#default_value' => theme_get_setting("colors.palette.dark.border") ?? '#000000',
  ];
  $form['colors']['palette']['dark']['border']['border_dark'] = [
    '#type' => 'color',
    '#title' => t('Border Dark'),
    '#default_value' => theme_get_setting("colors.palette.dark.border_dark") ?? '#000000',
  ];
  // Palette dark interection.
  $form['colors']['palette']['dark']['interection'] = [
    '#type' => 'fieldset',
    '#title' => t('Interection'),
  ];
  $form['colors']['palette']['dark']['interection']['interaction_text'] = [
    '#type' => 'color',
    '#title' => t('Interaction text'),
    '#default_value' => theme_get_setting("colors.palette.dark.interaction_text") ?? '#000000',
  ];
  $form['colors']['palette']['dark']['interection']['interaction_background'] = [
    '#type' => 'color',
    '#title' => t('Interaction background'),
    '#default_value' => theme_get_setting("colors.palette.dark.interaction_background") ?? '#000000',
  ];
  $form['colors']['palette']['dark']['interection']['interaction_hover_text'] = [
    '#type' => 'color',
    '#title' => t('Interaction hover text'),
    '#default_value' => theme_get_setting("colors.palette.dark.interaction_hover_text") ?? '#000000',
  ];
  $form['colors']['palette']['dark']['interection']['interaction_hover_background'] = [
    '#type' => 'color',
    '#title' => t('Interaction hover background'),
    '#default_value' => theme_get_setting("colors.palette.dark.interaction_hover_background") ?? '#000000',
  ];
  $form['colors']['palette']['dark']['interection']['interaction_focus'] = [
    '#type' => 'color',
    '#title' => t('Interaction focus'),
    '#default_value' => theme_get_setting("colors.palette.dark.interaction_focus") ?? '#8b5cd7',
  ];
  // Palette dark highlight.
  $form['colors']['palette']['dark']['highlight'] = [
    '#type' => 'fieldset',
    '#title' => t('Highlight'),
  ];
  $form['colors']['palette']['dark']['highlight']['highlight'] = [
    '#type' => 'color',
    '#title' => t('Highlight'),
    '#default_value' => theme_get_setting("colors.palette.dark.highlight") ?? '#000000',
  ];
  // Palette dark status.
  $form['colors']['palette']['dark']['status'] = [
    '#type' => 'fieldset',
    '#title' => t('Status'),
  ];
  $form['colors']['palette']['dark']['status']['information'] = [
    '#type' => 'color',
    '#title' => t('Information'),
    '#default_value' => theme_get_setting("colors.palette.dark.information") ?? '#4cc4fc',
  ];
  $form['colors']['palette']['dark']['status']['warning'] = [
    '#type' => 'color',
    '#title' => t('Warning'),
    '#default_value' => theme_get_setting("colors.palette.dark.warning") ?? '#e38444',
  ];
  $form['colors']['palette']['dark']['status']['error'] = [
    '#type' => 'color',
    '#title' => t('Error'),
    '#default_value' => theme_get_setting("colors.palette.dark.error") ?? '#e85653',
  ];
  $form['colors']['palette']['dark']['status']['success'] = [
    '#type' => 'color',
    '#title' => t('Success'),
    '#default_value' => theme_get_setting("colors.palette.dark.success") ?? '#12b0ae',
  ];
  // Colors submit handler.
  $form['#submit'][] = '_civictheme_form_system_theme_settings_colors_submit';
  $form['#attached']['library'][] = 'civictheme/theme-settings';
}

/**
 * Submit callback for theme settings form of colors.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function _civictheme_form_system_theme_settings_colors_submit(array &$form, FormStateInterface $form_state) {
  $palette_types = ['light', 'dark'];
  $values = $form_state->getValues();
  foreach ($palette_types as $palette_type) {
    foreach ($values['colors']['palette'][$palette_type] as $palette_type_key => $palette_type_value) {
      foreach ($palette_type_value as $palette_key => $palette_value) {
        $color_palette_field_name_key = [
          'colors',
          'palette',
          $palette_type,
          $palette_key,
        ];
        $form_state->setValue($color_palette_field_name_key, $palette_value);
      }
      $color_palette_field_name_key = [
        'colors',
        'palette',
        $palette_type,
        $palette_type_key,
      ];
      if($palette_type_key === 'highlight') {
        continue;
      }
      $form_state->unsetValue($color_palette_field_name_key);
    }
  }
}
