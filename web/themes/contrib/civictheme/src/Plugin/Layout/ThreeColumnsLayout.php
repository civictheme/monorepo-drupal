<?php

declare(strict_types=1);

namespace Drupal\civictheme\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Three Column Layout plugin class.
 */
class ThreeColumnsLayout extends LayoutDefault implements PluginFormInterface {

  /**
   * {@inheritdoc}
   *
   * @return array<string, mixed>
   *   The default configuration.
   */
  public function defaultConfiguration() {
    $configuration = parent::defaultConfiguration();

    return $configuration + [
      'is_contained' => FALSE,
      'vertical_spacing' => 'auto',
    ];
  }

  /**
   * {@inheritdoc}
   *
   * @param array<string, mixed> $form
   *   The form structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array<string, mixed>
   *   The form structure.
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

    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   *
   * @param array<string, mixed> $form
   *   The form structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state): void {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['is_contained'] = $form_state->getValue('is_contained');
    $this->configuration['vertical_spacing'] = $form_state->getValue('vertical_spacing');
  }

}
