<?php

/**
 * @file
 * Theme settings form for Civic theme.
 */

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function civic_form_system_theme_settings_alter(&$form, &$form_state) {
  $form['civic'] = [
    '#type' => 'details',
    '#title' => t('Civic'),
    '#open' => TRUE,
  ];

  $form['civic']['font_size'] = [
    '#type' => 'number',
    '#title' => t('Font size'),
    '#min' => 12,
    '#max' => 18,
    '#default_value' => theme_get_setting('font_size'),
  ];

  // Show compiled Storybook.
  // @note For development of components, please use `npm run storybook`.
  $theme = \Drupal::theme()->getActiveTheme();
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
      '#template' => '<iframe width="100%" height="1024" src="{{ url }}"></iframe>',
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
