<?php

namespace Drupal\civictheme_dev\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class IndividualElementForm extends FormBase {

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'individual_element_form';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['test'] = [
      '#type' => 'password_confirm',
    ];
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}
