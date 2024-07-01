<?php

namespace Drupal\civictheme\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;

class ThreeColumnsLayout extends LayoutDefault implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $configuration = parent::defaultConfiguration();

    return $configuration + ['is_contained' => FALSE];
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

    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['is_contained'] = $form_state->getValue('is_contained');
  }

}
