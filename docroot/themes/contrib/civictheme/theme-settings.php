<?php

/**
 * @file
 * Theme settings form for CivicTheme theme.
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\StreamWrapper\StreamWrapperManager;
use Drupal\Core\Url;

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function civictheme_form_system_theme_settings_alter(&$form, &$form_state) {
  _civictheme_form_system_theme_settings_theme_version($form);
  _civictheme_form_system_theme_settings_logo($form);
  _civictheme_form_system_theme_settings_components($form);
  _civictheme_form_system_theme_settings_content_provision($form, $form_state);
  _civictheme_form_system_theme_settings_storybook($form);
}

/**
 * Provide theme version to theme settings.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_form_system_theme_settings_theme_version(&$form) {
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
 * Provide logo settings to theme settings form.
 */
function _civictheme_form_system_theme_settings_logo(&$form) {
  // Disable default settings as we do not support uploading of custom logos
  // through config form (yet).
  // @todo Remove this once support for uploading of the custom logos is
  // added.
  $form['logo']['settings']['logo_upload']['#access'] = FALSE;
  $form['logo']['settings']['logo_path']['#title'] = t('Header desktop logo path');
  $form['logo']['settings']['civictheme_header_logo_mobile'] = [
    '#type' => 'textfield',
    '#title' => t('Header mobile logo path'),
    '#description' => _civictheme_path_field_description(theme_get_setting('civictheme_header_logo_mobile'), 'logo-header-mobile.svg'),
    '#default_value' => _civictheme_field_friendly_path(theme_get_setting('civictheme_header_logo_mobile')),
  ];

  $form['logo']['settings']['civictheme_footer_logo_desktop'] = [
    '#type' => 'textfield',
    '#title' => t('Footer desktop logo path'),
    '#description' => _civictheme_path_field_description(theme_get_setting('civictheme_footer_logo_desktop'), 'logo-header-desktop.svg'),
    '#default_value' => _civictheme_field_friendly_path(theme_get_setting('civictheme_footer_logo_desktop')),
  ];

  $form['logo']['settings']['civictheme_footer_logo_mobile'] = [
    '#type' => 'textfield',
    '#title' => t('Footer mobile logo path'),
    '#description' => _civictheme_path_field_description(theme_get_setting('civictheme_footer_logo_mobile'), 'logo-footer-mobile.svg'),
    '#default_value' => _civictheme_field_friendly_path(theme_get_setting('civictheme_footer_logo_mobile')),
  ];

  $form['logo']['settings']['civictheme_site_logo_alt'] = [
    '#type' => 'textfield',
    '#title' => t('Logo alt attribute text'),
    '#description' => t('Text for the alt attribute of the site logo image.'),
    '#default_value' => theme_get_setting('civictheme_site_logo_alt'),
  ];
}

/**
 * Provide components settings to theme settings form.
 */
function _civictheme_form_system_theme_settings_components(&$form) {
  $form['components'] = [
    '#type' => 'details',
    '#title' => t('CivicTheme components'),
    '#weight' => 50,
    '#open' => TRUE,
  ];

  $form['components']['header'] = [
    '#type' => 'details',
    '#title' => t('Header'),
    '#weight' => 50,
    '#open' => TRUE,
  ];

  $form['components']['header']['civictheme_header_theme'] = [
    '#title' => t('@component theme', ['@component' => 'Header']),
    '#description' => t('Set the theme option for the @component.', ['@component' => 'Header']),
    '#type' => 'radios',
    '#required' => TRUE,
    '#options' => [
      'light' => t('Light'),
      'dark' => t('Dark'),
    ],
    '#default_value' => theme_get_setting('civictheme_header_theme') ?? 'light',
  ];

  $form['components']['footer'] = [
    '#type' => 'details',
    '#title' => t('Footer'),
    '#weight' => 50,
    '#open' => TRUE,
  ];

  $form['components']['footer']['civictheme_footer_theme'] = [
    '#title' => t('@component theme', ['@component' => 'Footer']),
    '#description' => t('Set the theme option for the @component.', ['@component' => 'Footer']),
    '#type' => 'radios',
    '#required' => TRUE,
    '#options' => [
      'light' => t('Light'),
      'dark' => t('Dark'),
    ],
    '#default_value' => theme_get_setting('civictheme_footer_theme') ?? 'dark',
  ];

  $form['components']['footer']['civictheme_footer_background_image'] = [
    '#type' => 'textfield',
    '#title' => t('Footer background image path'),
    '#description' => _civictheme_path_field_description(theme_get_setting('civictheme_footer_background_image'), 'footer-background.png'),
    '#default_value' => _civictheme_field_friendly_path(theme_get_setting('civictheme_footer_background_image')),
  ];
}

/**
 * Provide content provision to theme settings form.
 */
function _civictheme_form_system_theme_settings_content_provision(&$form, &$form_state) {
  // Programmatically provision content.
  $civictheme_path = \Drupal::service('extension.list.theme')->getPath('civictheme');
  $provision_file = $civictheme_path . DIRECTORY_SEPARATOR . 'civictheme.provision.inc';
  if (file_exists($provision_file)) {
    require_once $provision_file;
    _civictheme_form_system_theme_settings_alter_provision($form, $form_state);
  }
}

/**
 * Provide storybook to theme settings form.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_form_system_theme_settings_storybook(&$form) {
  $theme_name = \Drupal::service('theme.manager')->getActiveTheme()->getName();
  $theme_path = \Drupal::service('extension.list.theme')->getPath($theme_name);

  // Show compiled Storybook.
  // @note For development of components, please use `npm run storybook`.
  $form['storybook'] = [
    '#type' => 'details',
    '#title' => t('Storybook for %theme theme', [
      '%theme' => $theme_name,
    ]),
    '#open' => TRUE,
    '#weight' => 51,
  ];

  $form['#validate'][] = '_civictheme_form_system_theme_settings_validate';

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
 * {@inheritdoc}
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function _civictheme_form_system_theme_settings_validate(array $form, FormStateInterface &$form_state) {
  $values = $form_state->getValues();

  if (!is_array($values)) {
    return;
  }

  $field_to_validate = [
    'civictheme_header_logo_mobile',
    'civictheme_footer_logo_desktop',
    'civictheme_footer_logo_mobile',
    'civictheme_footer_background_image',
  ];

  foreach ($field_to_validate as $field) {
    if (!empty($values[$field])) {
      $path = _civictheme_form_system_theme_settings_validate_path($values[$field]);
      if ($path) {
        $path = \Drupal::service('file_url_generator')->generateString($path);
        $form_state->setValue($field, ltrim($path, '/'));
        continue;
      }
      $form_state->setErrorByName($field, t('The image path is invalid.'));
    }
  }
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

  // Prepare local file path for description.
  if ($original_path && isset($friendly_path)) {
    $local_file = strtr($original_path, ['public:/' => PublicStream::basePath()]);
  }
  elseif ($theme_name) {
    $local_file = $theme_handler->getTheme($theme_name)->getPath() . '/' . $fallback_path;
  }
  else {
    $local_file = $theme_handler->getActiveTheme()->getPath() . '/' . $fallback_path;
  }

  return t('Examples: <code>@implicit-public-file</code> (for a file in the public filesystem), <code>@explicit-file</code>, or <code>@local-file</code>.', [
    '@implicit-public-file' => $friendly_path ?? $fallback_path,
    '@explicit-file' => StreamWrapperManager::getScheme($original_path) !== FALSE ? $original_path : 'public://' . $fallback_path,
    '@local-file' => $local_file,
  ]);
}
