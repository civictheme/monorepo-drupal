<?php

declare(strict_types=1);

namespace Drupal\civictheme\Settings;

use Drupal\Core\Form\FormStateInterface;

/**
 * CivicTheme settings section to reset settings.
 */
class CivicthemeSettingsFormSectionReset extends CivicthemeSettingsFormSectionBase {

  /**
   * {@inheritdoc}
   */
  public function weight(): int {
    return 100;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    $form['reset'] = [
      '#type' => 'details',
      '#title' => $this->t('Reset to defaults'),
      '#open' => TRUE,
      '#weight' => 60,
      '#tree' => TRUE,
      '#description' => $this->t('Reset theme settings to defaults from CivicTheme configuration.'),
    ];

    $form['reset']['confirm'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Confirm settings reset'),
      '#description' => $this->t('Check to confirm theme settings reset to defaults.'),
    ];

    $form['reset']['reset_to_defaults'] = [
      '#type' => 'submit',
      '#value' => $this->t('Reset to defaults'),
      '#name' => 'reset_to_defaults',
      '#validate' => [$this->resetValidate(...)],
      '#submit' => [$this->resetSubmit(...)],
      '#states' => [
        'enabled' => [
          'input[name="reset[confirm]"' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['#process'][] = $this->processForm(...);
  }

  /**
   * Process callback.
   *
   * @param array $element
   *   Form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   *
   * @return array<string, mixed>
   *   Form element.
   */
  public function processForm(array &$element, FormStateInterface $form_state): array {
    $form_state->addCleanValueKey('reset');

    return $element;
  }

  /**
   * Validation callback.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function resetValidate(array &$form, FormStateInterface $form_state): void {
    if (!$form_state->getValue(['reset', 'confirm'])) {
      $form_state->setErrorByName(implode('][', ['reset', 'confirm']), (string) $this->t('Please check the box to confirm theme settings reset.'));
    }
  }

  /**
   * Submit callback.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function resetSubmit(array &$form, FormStateInterface $form_state): void {
    $triggering_element = $form_state->getTriggeringElement();
    $button_name = $triggering_element['#name'] ?? '';

    if ($button_name === 'reset_to_defaults') {
      try {
        $this->themeConfigManager->resetToDefaults();
      }
      catch (\Exception $exception) {
        // @phpstan-ignore-next-line
        \Drupal::messenger()->addError($this->t('Unable to reset theme configuration to defaults: @message', [
          '@message' => $exception->getMessage(),
        ]));

        return;
      }

      // @phpstan-ignore-next-line
      \Drupal::messenger()->addStatus($this->t('Theme configuration was reset to defaults.'));
    }
  }

}
