<?php

/**
 * @file
 * Theme settings form for Civic theme.
 */

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function civic_form_system_theme_settings_alter(&$form, &$form_state) {
  $theme = \Drupal::theme()->getActiveTheme();

  // Disable default settings as we do not support uploading of custom logos
  // through config form (yet).
  // @todo Remove this once support for uploading of the custom logos is
  // added.
  $form['logo']['settings']['logo_upload']['#access'] = FALSE;

  $form['logo']['settings']['logo_path']['#title'] = t('Header desktop logo path');

  $form['logo']['settings']['civic_header_logo_mobile'] = [
    '#type' => 'textfield',
    '#title' => t('Header mobile logo path'),
    '#default_value' => theme_get_setting('civic_header_logo_mobile'),
    '#description' => t('Examples: logo.svg (for a file in the public filesystem), public://logo-header-mobile.svg, or themes/contrib/civic/assets/images/svg/logo-header-mobile.svg.'),
  ];

  $form['logo']['settings']['civic_footer_logo_desktop'] = [
    '#type' => 'textfield',
    '#title' => t('Footer desktop logo path'),
    '#default_value' => theme_get_setting('civic_footer_logo_desktop'),
    '#description' => t('Examples: logo.svg (for a file in the public filesystem), public://logo-footer-desktop.svg, or themes/contrib/civic/dist/images/svg/logo-footer-desktop.svg.'),
  ];

  $form['logo']['settings']['civic_footer_logo_mobile'] = [
    '#type' => 'textfield',
    '#title' => t('Footer mobile logo path'),
    '#default_value' => theme_get_setting('civic_footer_logo_mobile'),
    '#description' => t('Examples: logo.svg (for a file in the public filesystem), public://logo-footer-mobile.svg, or themes/contrib/civic/dist/images/svg/logo-footer-mobile.svg.'),
  ];

  $form['logo']['settings']['civic_site_logo_alt'] = [
    '#type' => 'textfield',
    '#title' => t('Logo alt attribute text'),
    '#default_value' => theme_get_setting('civic_site_logo_alt'),
    '#description' => t('Text for the alt attribute of site logo image'),
  ];

  $form['components'] = [
    '#type' => 'details',
    '#title' => t('Civic components'),
    '#weight' => 50,
    '#open' => TRUE,
  ];

  $form['components']['header'] = [
    '#type' => 'details',
    '#title' => t('Header'),
    '#weight' => 50,
    '#open' => TRUE,
  ];

  $form['components']['header']['civic_header_theme'] = [
    '#title' => t('@component theme', ['@component' => 'Header']),
    '#description' => t('Set the theme option for the @component.', ['@component' => 'Header']),
    '#type' => 'radios',
    '#options' => [
      'light' => t('Light'),
      'dark' => t('Dark'),
    ],
    '#default_value' => theme_get_setting('civic_header_theme') ?? 'light',
  ];

  $form['components']['footer'] = [
    '#type' => 'details',
    '#title' => t('Footer'),
    '#weight' => 50,
    '#open' => TRUE,
  ];

  $form['components']['footer']['civic_footer_theme'] = [
    '#title' => t('@component theme', ['@component' => 'Footer']),
    '#description' => t('Set the theme option for the @component.', ['@component' => 'Footer']),
    '#type' => 'radios',
    '#options' => [
      'light' => t('Light'),
      'dark' => t('Dark'),
    ],
    '#default_value' => theme_get_setting('civic_footer_theme') ?? 'dark',
  ];

  $form['components']['footer']['civic_footer_background_image'] = [
    '#type' => 'textfield',
    '#title' => t('Footer background image path'),
    '#default_value' => theme_get_setting('civic_footer_background_image'),
    '#description' => t('Examples: footer-background.png (for a file in the public filesystem), public://footer-background.png, or themes/contrib/civic/dist/images/svg/footer-background.png.'),
  ];

  // Show compiled Storybook.
  // @note For development of components, please use `npm run storybook`.
  $form['storybook'] = [
    '#type' => 'details',
    '#title' => t('Storybook for %theme theme', [
      '%theme' => $theme->getName(),
    ]),
    '#open' => TRUE,
    '#weight' => 51,
  ];

  $storybook_file = $theme->getPath() . '/storybook-static/index.html';
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
}
