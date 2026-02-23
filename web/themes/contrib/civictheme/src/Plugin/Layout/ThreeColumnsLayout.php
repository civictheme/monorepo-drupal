<?php

namespace Drupal\civictheme\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Three Column Layout plugin class.
 */
class ThreeColumnsLayout extends LayoutDefault implements PluginFormInterface {

  /**
   * Default mobile stack order for the five layout regions.
   *
   * Keys are CSS custom property names used by the layout SCSS.
   */
  const MOBILE_STACK_ORDER_DEFAULTS = [
    'stl' => 1,
    'str' => 2,
    'm' => 3,
    'sbl' => 4,
    'sbr' => 5,
  ];

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $configuration = parent::defaultConfiguration();

    return $configuration + [
      'is_contained' => FALSE,
      'mobile_stack_order_enabled' => FALSE,
      'mobile_stack_order' => self::MOBILE_STACK_ORDER_DEFAULTS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['is_contained'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Contained'),
      '#default_value' => $this->configuration['is_contained'],
      '#description' => $this->t('Check if the layout elements should be contained. Leave unchecked for edge-to-edge width. If sidebar regions are present - the layout will be contained regardless of this setting.'),
    ];

    $form['vertical_spacing'] = [
      '#type' => 'select',
      '#title' => $this->t('Vertical spacing'),
      '#default_value' => $this->configuration['vertical_spacing'],
      '#options' => [
        'none' => $this->t('None'),
        'top' => $this->t('Top'),
        'bottom' => $this->t('Bottom'),
        'both' => $this->t('Both'),
        'auto' => $this->t('Automatic'),
      ],
    ];

    $form['mobile_stack_order_enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable custom mobile stack order'),
      '#default_value' => $this->configuration['mobile_stack_order_enabled'],
      '#description' => $this->t('Override the default mobile stacking order of layout regions for this section.'),
    ];

    $weight_options = array_combine(range(1, 5), range(1, 5));
    $mobile_stack_order = $this->configuration['mobile_stack_order'] ?? self::MOBILE_STACK_ORDER_DEFAULTS;

    $region_labels = [
      'stl' => $this->t('Sidebar top left'),
      'str' => $this->t('Sidebar top right'),
      'm' => $this->t('Main'),
      'sbl' => $this->t('Sidebar bottom left'),
      'sbr' => $this->t('Sidebar bottom right'),
    ];

    $form['mobile_stack_order'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Mobile stack order'),
      '#description' => $this->t('Configure the order in which regions stack on mobile. Lower numbers appear first. Each weight must be unique.'),
      '#tree' => TRUE,
      '#states' => [
        'visible' => [
          ':input[name="layout_settings[mobile_stack_order_enabled]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    foreach ($region_labels as $key => $label) {
      $form['mobile_stack_order'][$key] = [
        '#type' => 'select',
        '#title' => $label,
        '#default_value' => $mobile_stack_order[$key] ?? self::MOBILE_STACK_ORDER_DEFAULTS[$key],
        '#options' => $weight_options,
      ];
    }

    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    if (!$form_state->getValue('mobile_stack_order_enabled')) {
      return;
    }
    $mobile_stack_order = $form_state->getValue('mobile_stack_order');
    if (is_array($mobile_stack_order)) {
      $values = array_map('intval', $mobile_stack_order);
      if (count($values) !== count(array_unique($values))) {
        $form_state->setErrorByName('mobile_stack_order', $this->t('Each mobile stack order weight must be unique.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['is_contained'] = $form_state->getValue('is_contained');
    $this->configuration['vertical_spacing'] = $form_state->getValue('vertical_spacing');
    $this->configuration['mobile_stack_order_enabled'] = (bool) $form_state->getValue('mobile_stack_order_enabled');
    $mobile_stack_order = $form_state->getValue('mobile_stack_order');
    if (is_array($mobile_stack_order)) {
      $this->configuration['mobile_stack_order'] = array_map('intval', $mobile_stack_order);
    }
  }

}
