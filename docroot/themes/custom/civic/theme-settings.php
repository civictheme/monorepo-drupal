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

  // Show compiled Storybook.
  // @note For development of components, please use `npm run storybook`.
  $form['storybook'] = [
    '#type' => 'details',
    '#title' => t('Storybook for %theme theme', [
      '%theme' => $theme->getName(),
    ]),
    '#open' => TRUE,
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

  $form['civic_custom_configuration_options'] = [
    '#type' => 'details',
    '#title' => 'Custom Civic',
    '#weight' => 50,
    '#open' => TRUE,
  ];

  $form['civic_custom_configuration_options']['civic_header_logo_mobile'] = [
    '#type' => 'textfield',
    '#title' => t('Header Mobile Logo'),
    '#default_value' => theme_get_setting('civic_header_logo_mobile'),
    '#description' => t('Examples: logo.svg (for a file in the public filesystem), public://logo-header-mobile.svg, or themes/custom/civic/dist/images/svg/logo-header-mobile.svg.'),
  ];

  $form['civic_custom_configuration_options']['civic_footer_logo_desktop'] = [
    '#type' => 'textfield',
    '#title' => t('Footer desktop logo'),
    '#default_value' => theme_get_setting('civic_footer_logo_desktop'),
    '#description' => t('Examples: logo.svg (for a file in the public filesystem), public://logo-footer-desktop.svg, or themes/custom/civic/dist/images/svg/logo-footer-desktop.svg.'),
  ];

  $form['civic_custom_configuration_options']['civic_footer_logo_mobile'] = [
    '#type' => 'textfield',
    '#title' => t('Footer mobile logo'),
    '#default_value' => theme_get_setting('civic_footer_logo_mobile'),
    '#description' => t('Examples: logo.svg (for a file in the public filesystem), public://logo-footer-mobile.svg, or themes/custom/civic/dist/images/svg/logo-footer-mobile.svg.'),
  ];

  $form['civic_custom_configuration_options']['civic_site_logo_alt'] = [
    '#type' => 'textfield',
    '#title' => t('Logo alt attribute text'),
    '#default_value' => theme_get_setting('civic_site_logo_alt'),
    '#description' => t('Text for the alt attribute of site logo image'),
  ];

  $form['civic_custom_configuration_options']['civic_footer_background_image'] = [
    '#type' => 'textfield',
    '#title' => t('Footer background image'),
    '#default_value' => theme_get_setting('civic_footer_background_image'),
    '#description' => t('Examples: footer-background.png (for a file in the public filesystem), public://footer-background.png, or themes/custom/civic/dist/images/svg/footer-background.png.'),
  ];

  // Allow selecting theme for footer and header.
  $themeable_components = [
    'Header' => 'civic_header_theme',
    'Footer' => 'civic_footer_theme',
  ];

  foreach ($themeable_components as $component_name => $component_key) {
    $form['civic_custom_configuration_options'][$component_key] = [
      '#title' => t('@region theme', ['@region' => $component_name]),
      '#description' => t('Set the theme option for the @region', ['@region' => $component_name]),
      '#type' => 'radios',
      '#options' => [
        'light' => t('Light'),
        'dark' => t('Dark'),
      ],
      '#default_value' => 'light',
    ];
  }
}
