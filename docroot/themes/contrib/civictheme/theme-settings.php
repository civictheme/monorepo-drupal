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
        '#open' => TRUE,
        '#tree' => FALSE,
      ];
      foreach ($breakpoints as $breakpoint) {
        $form['components']['logo'][$logo_type][$theme]["image_{$logo_type}_{$theme}_{$breakpoint}_group"] = [
          '#type' => 'fieldset',
          '#title' => t('Logo @logo_type @theme @breakpoint', [
            '@theme' => $theme_label,
            '@breakpoint' => $breakpoint,
            '@logo_type' => $logo_type,
          ]),
          '#open' => TRUE,
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
    '#title' => t('Header theme'),
    '#description' => t('Set the theme option for the Header component.'),
    '#type' => 'radios',
    '#required' => TRUE,
    '#options' => civictheme_theme_options(),
    '#default_value' => theme_get_setting('components.header.theme') ?? CIVICTHEME_HEADER_THEME_DEFAULT,
  ];

  $form['components']['footer'] = [
    '#type' => 'details',
    '#title' => t('Footer'),
    '#group' => 'components',
    '#tree' => TRUE,
  ];

  $form['components']['footer']['theme'] = [
    '#title' => t('Footer theme'),
    '#description' => t('Set the theme option for the Footer component.'),
    '#type' => 'radios',
    '#required' => TRUE,
    '#options' => civictheme_theme_options(),
    '#default_value' => theme_get_setting('components.footer.theme') ?? CIVICTHEME_FOOTER_THEME_DEFAULT,
  ];

  $form['components']['footer']['background_image'] = [
    '#type' => 'textfield',
    '#title' => t('Footer background image path'),
    '#description' => _civictheme_path_field_description(theme_get_setting('components.footer.background_image'), 'footer-background.png'),
    '#default_value' => _civictheme_field_friendly_path(theme_get_setting('components.footer.background_image')),
  ];

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
    '#default_value' => theme_get_setting('components.link.new_window'),
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
        $field_name_key = ['components', 'logo', "image_{$logo_type}_{$theme}_{$breakpoint}"];
        $path = $form_state->getValue($field_name_key);

        // Check for a new uploaded logo.
        if (isset($form['components']['logo']["image_{$logo_type}_{$theme}_{$breakpoint}_group"]["image_{$logo_type}_{$theme}_{$breakpoint}_upload"])) {
          $file = _file_save_upload_from_form($form['components']['logo']["image_{$logo_type}_{$theme}_{$breakpoint}_group"]["image_{$logo_type}_{$theme}_{$breakpoint}_upload"], $form_state, 0, FileSystemInterface::EXISTS_REPLACE);
          if ($file) {
            // Put the temporary file in form_values so we can save it on submit.
            $form_state->setValue("image_{$logo_type}_{$theme}_{$breakpoint}_upload", $file);
          }
        }

        if (!empty($path)) {
          $path = _civictheme_form_system_theme_settings_validate_path($path);
          if ($path) {
            $path = \Drupal::service('file_url_generator')->generateString($path);
            $form_state->setValue($field_name_key, ltrim($path, '/'));
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
