<?php

/**
 * @file
 * Theme settings form for CivicTheme theme.
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManager;

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function civictheme_form_system_theme_settings_alter(&$form, &$form_state) {
  $theme_name = \Drupal::configFactory()->get('system.theme')->get('default');
  $theme_path = \Drupal::service('extension.list.theme')->getPath($theme_name);

  // Disable default settings as we do not support uploading of custom logos
  // through config form (yet).
  // @todo Remove this once support for uploading of the custom logos is
  // added.
  $form['logo']['settings']['logo_upload']['#access'] = FALSE;

  $form['logo']['settings']['logo_path']['#title'] = t('Header desktop logo path');

  $form['logo']['settings']['civictheme_header_logo_mobile'] = [
    '#type' => 'textfield',
    '#title' => t('Header mobile logo path'),
    '#default_value' => theme_get_setting('civictheme_header_logo_mobile'),
    '#description' => t('Examples: logo.svg (for a file in the public filesystem), public://logo-header-mobile.svg, or themes/contrib/civictheme/assets/images/svg/logo-header-mobile.svg.'),
  ];

  $form['logo']['settings']['civictheme_footer_logo_desktop'] = [
    '#type' => 'textfield',
    '#title' => t('Footer desktop logo path'),
    '#default_value' => theme_get_setting('civictheme_footer_logo_desktop'),
    '#description' => t('Examples: logo.svg (for a file in the public filesystem), public://logo-footer-desktop.svg, or themes/contrib/civictheme/dist/images/svg/logo-footer-desktop.svg.'),
  ];

  $form['logo']['settings']['civictheme_footer_logo_mobile'] = [
    '#type' => 'textfield',
    '#title' => t('Footer mobile logo path'),
    '#default_value' => theme_get_setting('civictheme_footer_logo_mobile'),
    '#description' => t('Examples: logo.svg (for a file in the public filesystem), public://logo-footer-mobile.svg, or themes/contrib/civictheme/dist/images/svg/logo-footer-mobile.svg.'),
  ];

  $form['logo']['settings']['civictheme_site_logo_alt'] = [
    '#type' => 'textfield',
    '#title' => t('Logo alt attribute text'),
    '#default_value' => theme_get_setting('civictheme_site_logo_alt'),
    '#description' => t('Text for the alt attribute of site logo image'),
  ];

  $logo_fields = [
    'civictheme_header_logo_mobile',
    'civictheme_footer_logo_desktop',
    'civictheme_footer_logo_mobile',
  ];
  foreach ($logo_fields as $type) {
    if (isset($form['logo']['settings'][$type])) {
      $element = &$form['logo']['settings'][$type];

      // If path is a public:// URI, display the path relative to the files
      // directory; stream wrappers are not end-user friendly.
      $original_path = $element['#default_value'];
      if (StreamWrapperManager::getScheme($original_path) == 'public') {
        $friendly_path = StreamWrapperManager::getTarget($original_path);
        $element['#default_value'] = $friendly_path;
      }
    }
  }

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
    '#default_value' => theme_get_setting('civictheme_footer_background_image'),
    '#description' => t('Examples: footer-background.png (for a file in the public filesystem), public://footer-background.png, or themes/contrib/civictheme/dist/images/svg/footer-background.png.'),
  ];

  // Programmatically provision content.
  $civictheme_path = \Drupal::service('extension.list.theme')->getPath('civictheme');
  $provision_file = $civictheme_path . DIRECTORY_SEPARATOR . 'civictheme.provision.inc';
  if (file_exists($provision_file)) {
    require_once $provision_file;
    _civictheme_form_system_theme_settings_alter_provision($form, $form_state);
  }

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

  $storybook_file = $theme_path . '/storybook-static/index.html';
  if (file_exists($storybook_file)) {
    $form['storybook']['markup'] = [
      '#type' => 'inline_template',
      '#template' => '<iframe id="storybook" width="100%" height="1024" src="{{ url }}"></iframe>',
      '#context' => [
        'url' => file_create_url($storybook_file) . '?cachebust=' . time(),
      ],
    ];
  }
  else {
    $form['storybook']['markup'] = [
      '#markup' => t('Compiled Storybook cannot be found at @path. Try compiling it with <code>npm run build</code>.', [
        '@path' => $storybook_file,
      ]),
    ];
  }

  $form['#validate'][] = '_civictheme_form_system_theme_settings_validate';
}

/**
 * {@inheritdoc}
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
  ];

  foreach ($field_to_validate as $field) {
    if (!empty($values[$field])) {
      $path = _civictheme_form_system_theme_settings_validate_path($values[$field]);
      if ($path) {
        $path = \Drupal::service('file_url_generator')->generateString($path);
        $form_state->setValue($field, ltrim($path, '/'));
      }
      else {
        $form_state->setErrorByName($field, t('The image path is invalid.'));
      }
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
