<?php

declare(strict_types=1);

namespace Drupal\civictheme\Settings;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Form\FormStateInterface;

/**
 * CivicTheme settings section to opt-out from features.
 */
class CivicthemeSettingsFormSectionOptout extends CivicthemeSettingsFormSectionBase {

  /**
   * {@inheritdoc}
   */
  public function weight(): int {
    return 35;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    $form['optout_details'] = [
      '#type' => 'details',
      '#title' => $this->t('Opt-out from features'),
      '#open' => FALSE,
      '#weight' => 60,
    ];

    $form['optout_details']['optouts'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Opt-out flags'),
      '#rows' => 3,
      '#description' => $this->t('Add opt-out feature flags to disable CivicTheme features. One per line. Expand the fieldset below to get a list of available flags.'),
      '#element_validate' => [[self::class, 'multilineToArray']],
      '#default_value' => implode("\n", $this->themeConfigManager->load('optouts', [])),
    ];

    $form['optout_details']['mapping'] = [
      '#type' => 'details',
      '#title' => $this->t('Available flags'),
      '#open' => FALSE,
      '#weight' => 60,
    ];

    $form['optout_details']['mapping']['content'] = [
      '#theme' => 'item_list',
      '#items' => array_map(static function ($key, $description): FormattableMarkup {
        return new FormattableMarkup('<code>@key</code>: @description', [
          '@key' => $key,
          '@description' => $description,
        ]);
      }, array_keys(_civictheme_feature_optout_flags()), _civictheme_feature_optout_flags()),
    ];
  }

  /**
   * Convert element value from multiline string to an array.
   */
  public static function multilineToArray(array $element, FormStateInterface $form_state): void {
    $lines = is_array($element['#value']) ? $element['#value'] : explode("\n", str_replace("\r\n", "\n", (string) $element['#value']));
    $form_state->setValueForElement($element, array_values(array_filter(array_map('trim', $lines))));
  }

}
