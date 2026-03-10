<?php

/**
 * @file
 * Layout builder related functions.
 */

declare(strict_types=1);

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_layout_builder_update_block_alter().
 */
function civictheme_form_layout_builder_update_block_alter(array &$form, FormStateInterface $form_state): void {
  _civictheme_layout_builder_block_form_alter($form, $form_state);
}

/**
 * Implements hook_form_layout_builder_add_block_alter().
 */
function civictheme_form_layout_builder_add_block_alter(array &$form, FormStateInterface $form_state): void {
  _civictheme_layout_builder_block_form_alter($form, $form_state);
}

/**
 * Shared form alter for layout builder block add/update forms.
 */
function _civictheme_layout_builder_block_form_alter(array &$form, FormStateInterface $form_state): void {
  /** @var \Drupal\layout_builder\Form\ConfigureBlockFormBase $form_object */
  $form_object = $form_state->getFormObject();
  $component = $form_object->getCurrentComponent();
  /** @var \Drupal\Core\Block\BlockPluginInterface $plugin */
  $plugin = $component->getPlugin();
  $formatter = $plugin->getConfiguration()['formatter'] ?? [];
  $settings = $formatter['third_party_settings']['civictheme'] ?? [];

  $form['civictheme_settings_enabled'] = [
    '#type' => 'checkbox',
    '#title' => t('Use block settings to set CivicTheme component settings'),
    '#default_value' => $settings['enabled'] ?? FALSE,
    '#weight' => 89,
  ];

  $form['civictheme_settings'] = [
    '#type' => 'fieldset',
    '#title' => t('CivicTheme settings'),
    '#tree' => TRUE,
    '#weight' => 90,
    '#states' => [
      'visible' => [
        ':input[name="civictheme_settings_enabled"]' => ['checked' => TRUE],
      ],
    ],
  ];

  $enabled_state = [
    'visible' => [
      ':input[name="civictheme_settings_enabled"]' => ['checked' => TRUE],
    ],
  ];

  $form['civictheme_settings']['theme'] = [
    '#type' => 'radios',
    '#title' => t('Theme'),
    '#options' => [
      'light' => t('Light'),
      'dark' => t('Dark'),
    ],
    '#default_value' => $settings['theme'] ?? 'light',
    '#states' => $enabled_state,
  ];

  $form['civictheme_settings']['vertical_spacing'] = [
    '#type' => 'radios',
    '#title' => t('Vertical spacing'),
    '#options' => [
      'none' => t('None'),
      'top' => t('Top'),
      'bottom' => t('Bottom'),
      'both' => t('Both'),
    ],
    '#default_value' => $settings['vertical_spacing'] ?? 'none',
    '#states' => $enabled_state,
  ];

  $form['civictheme_settings']['with_background'] = [
    '#type' => 'checkbox',
    '#title' => t('Background'),
    '#description' => t('Apply a themed background colour and provide horizontal spacing to the component.'),
    '#default_value' => $settings['with_background'] ?? FALSE,
    '#states' => $enabled_state,
  ];

  $form['civictheme_settings']['label_heading_level'] = [
    '#type' => 'select',
    '#title' => t('Label heading level'),
    '#options' => array_combine(range(1, 6), array_map(fn($n) => 'H' . $n, range(1, 6))),
    '#default_value' => $settings['label_heading_level'] ?? '2',
    '#states' => [
      'visible' => [
        ':input[name="civictheme_settings_enabled"]' => ['checked' => TRUE],
        ':input[name="settings[label_display]"]' => ['checked' => TRUE],
      ],
    ],
  ];

  _civictheme_layout_builder_block_form_alter_link($form, $formatter, $settings, $enabled_state);

  // Add list settings when the formatter uses a card view mode.
  $view_mode = $formatter['settings']['view_mode'] ?? NULL;
  $card_view_modes = _civictheme_card_view_modes();
  if ($view_mode && isset($card_view_modes[$view_mode])) {
    $form['civictheme_settings']['column_count'] = [
      '#type' => 'select',
      '#title' => t('List column count'),
      '#options' => array_combine([1, 2, 3, 4], [1, 2, 3, 4]),
      '#default_value' => $settings['column_count'] ?? $card_view_modes[$view_mode],
      '#states' => $enabled_state,
    ];

    $form['civictheme_settings']['fill_width'] = [
      '#type' => 'checkbox',
      '#title' => t('Fill width'),
      '#description' => t('Stretch remaining items to fill the full row width.'),
      '#default_value' => $settings['fill_width'] ?? FALSE,
      '#states' => $enabled_state,
    ];
  }

  $form['actions']['#weight'] = 100;

  array_unshift($form['#submit'], 'civictheme_layout_builder_block_submit');
}

/**
 * Submit handler for Layout Builder block form.
 *
 * Injects civictheme settings into the formatter's third party settings so
 * they are persisted as part of the block's configuration.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function civictheme_layout_builder_block_submit(array &$form, FormStateInterface $form_state): void {
  $settings = $form_state->getValue('civictheme_settings') ?? [];
  $settings['enabled'] = (bool) $form_state->getValue('civictheme_settings_enabled');

  // Inject into the formatter's third_party_settings. When
  // ConfigureBlockFormBase::submitForm() runs next, FieldBlock::blockSubmit()
  // saves $form_state->getValue('formatter') (via subform state) into
  // $this->configuration['formatter'], which then gets persisted.
  $formatter = $form_state->getValue(['settings', 'formatter']) ?? [];
  $formatter['third_party_settings']['civictheme'] = $settings['enabled'] ? $settings : NULL;
  $form_state->setValue(['settings', 'formatter'], $formatter);
}

/**
 * Preprocess block to apply civictheme layout builder settings.
 *
 * Reads settings from the block's formatter third party settings.
 */
function _civictheme_preprocess_block__civictheme_layout_builder_settings(array &$variables): void {
  // This preprocess only applies to field blocks set in layout builder.
  // @see \Drupal\layout_builder\Plugin\Block\FieldBlock
  if (($variables['elements']['#base_plugin_id'] ?? NULL) !== 'field_block') {
    return;
  }

  $settings = $variables['elements']['#configuration']['formatter']['third_party_settings']['civictheme'] ?? NULL;
  if (!$settings || empty($settings['enabled'])) {
    return;
  }

  $variables['theme'] = $settings['theme'] ?? $variables['theme'];
  $variables['vertical_spacing'] = $settings['vertical_spacing'] ?? NULL;
  $variables['with_background'] = !empty($settings['with_background']);
  $variables['label_heading_level'] = $settings['label_heading_level'] ?? $variables['label_heading_level'];
}

/**
 * Preprocess field to apply civictheme layout builder settings.
 *
 * Reads settings from the field's third party settings which are passed
 * through from the formatter configuration by FieldBlock::build().
 */
function _civictheme_preprocess_field__civictheme_layout_builder_settings(array &$variables): void {
  $settings = $variables['element']['#third_party_settings']['civictheme'] ?? NULL;
  if (!$settings || empty($settings['enabled'])) {
    return;
  }

  $variables['theme'] = _civictheme_field_get_theme($variables);
  $variables['vertical_spacing'] = _civictheme_field_get_vertical_spacing($variables);
  $variables['with_background'] = _civictheme_field_get_with_background($variables);
}

/**
 * Add item-list settings to the layout builder block form for link formatters.
 *
 * @param array $form
 *   The form array.
 * @param array $formatter
 *   The formatter configuration.
 * @param array $settings
 *   The existing CivicTheme third party settings.
 * @param array $enabled_state
 *   The #states array for visibility when CivicTheme settings are enabled.
 */
function _civictheme_layout_builder_block_form_alter_link(array &$form, array $formatter, array $settings, array $enabled_state): void {
  $formatter_type = $formatter['type'] ?? NULL;
  if ($formatter_type !== 'link') {
    return;
  }

  $form['civictheme_settings']['direction'] = [
    '#type' => 'radios',
    '#title' => t('Direction'),
    '#options' => [
      'horizontal' => t('Horizontal'),
      'vertical' => t('Vertical'),
    ],
    '#default_value' => $settings['direction'] ?? 'vertical',
    '#states' => $enabled_state,
  ];

  $form['civictheme_settings']['size'] = [
    '#type' => 'radios',
    '#title' => t('Size'),
    '#options' => [
      'large' => t('Large'),
      'regular' => t('Regular'),
      'small' => t('Small'),
    ],
    '#default_value' => $settings['size'] ?? 'regular',
    '#states' => $enabled_state,
  ];

  $form['civictheme_settings']['no_gap'] = [
    '#type' => 'checkbox',
    '#title' => t('No gap'),
    '#description' => t('Remove gaps between list items.'),
    '#default_value' => $settings['no_gap'] ?? FALSE,
    '#states' => $enabled_state,
  ];
}
