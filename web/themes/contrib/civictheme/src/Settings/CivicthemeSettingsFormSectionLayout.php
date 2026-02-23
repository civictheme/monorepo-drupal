<?php

declare(strict_types=1);

namespace Drupal\civictheme\Settings;

use Drupal\civictheme\Plugin\Layout\ThreeColumnsLayout;
use Drupal\Core\Form\FormStateInterface;

/**
 * CivicTheme settings section to display layout configuration.
 */
class CivicthemeSettingsFormSectionLayout extends CivicthemeSettingsFormSectionBase {

  /**
   * {@inheritdoc}
   */
  public function weight(): int {
    return 31;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    $form['components']['layout'] = [
      '#type' => 'details',
      '#title' => $this->t('Layout'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['layout']['mobile_stack_order_enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable custom mobile stack order'),
      '#description' => $this->t('Override the default mobile stacking order of layout regions. When enabled, the configured order is applied via inline CSS custom properties on the page layout.'),
      '#default_value' => $this->themeConfigManager->load('components.layout.mobile_stack_order_enabled', FALSE),
    ];

    $weight_options = array_combine(range(1, 5), range(1, 5));
    $defaults = ThreeColumnsLayout::MOBILE_STACK_ORDER_DEFAULTS;

    $region_labels = [
      'stl' => $this->t('Sidebar top left'),
      'str' => $this->t('Sidebar top right'),
      'm' => $this->t('Main'),
      'sbl' => $this->t('Sidebar bottom left'),
      'sbr' => $this->t('Sidebar bottom right'),
    ];

    $form['components']['layout']['mobile_stack_order'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Mobile stack order'),
      '#description' => $this->t('Configure the order in which regions stack on mobile. Lower numbers appear first. Each weight must be unique.'),
      '#tree' => TRUE,
      '#states' => [
        'visible' => [
          ':input[name="components[layout][mobile_stack_order_enabled]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    foreach ($region_labels as $key => $label) {
      $form['components']['layout']['mobile_stack_order'][$key] = [
        '#type' => 'select',
        '#title' => $label,
        '#default_value' => $this->themeConfigManager->load('components.layout.mobile_stack_order.' . $key, $defaults[$key]),
        '#options' => $weight_options,
      ];
    }

    $form['#validate'][] = [$this, 'validateLayout'];
  }

  /**
   * Validate callback for layout settings.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function validateLayout(array &$form, FormStateInterface $form_state): void {
    $enabled = $form_state->getValue(['components', 'layout', 'mobile_stack_order_enabled']);
    if (!$enabled) {
      return;
    }

    $mobile_stack_order = $form_state->getValue(['components', 'layout', 'mobile_stack_order']);
    if (is_array($mobile_stack_order)) {
      $values = array_map('intval', $mobile_stack_order);
      if (count($values) !== count(array_unique($values))) {
        $form_state->setErrorByName('components][layout][mobile_stack_order', (string) $this->t('Each mobile stack order weight must be unique.'));
      }
    }
  }

}
