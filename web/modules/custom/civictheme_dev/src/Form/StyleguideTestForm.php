<?php

declare(strict_types=1);

namespace Drupal\civictheme_dev\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Generate individual form element.
 */
class StyleguideTestForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'civictheme_dev_styleguide_form_test';
  }

  /**
   * Build form.
   *
   * @param array<mixed> $form
   *   Form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   * @param array<mixed> $form_elements
   *   Form elements.
   *
   * @return array<mixed>
   *   Form array.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function buildForm(array $form, FormStateInterface $form_state, array $form_elements = []): array {
    return array_merge($form_elements, $form);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Noop.
  }

}
