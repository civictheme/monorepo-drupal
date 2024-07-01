<?php

/**
 * @file
 * Provides styleguide elements for the Civictheme Dev module.
 */

declare(strict_types=1);

use Drupal\civictheme_dev\Form\StyleguideTestForm;

/**
 * Styleguide textfield elements.
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _civictheme_dev_styleguide__form_test__textfield(array &$items): void {
  $items['form-civictheme-test-textfield']['group'] = t('Form Civictheme Test');
  $items['form-civictheme-test-textfield']['title'] = t('Form Civictheme Test: textfield');

  $content = [];

  $content[] = [
    '#type' => 'textfield',
    '#title' => t('Textfield, Title visible, no default value, no description, no error, so size'),
    '#title_display' => 'visible',
    '#default_value' => '',
    '#description' => '',
    '#size' => NULL,
    '#required' => FALSE,
    '#disabled' => FALSE,
  ];
  $content[] = [
    '#type' => 'textfield',
    '#title' => t('Textfield, Title visible, default value, no description, no error, required, with attributes'),
    '#title_display' => 'visible',
    '#default_value' => 'Default value',
    '#description' => '',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#wrapper_attributes' => [
      'class' => ['test-wrapper-class'],
      'data-wrapper-test' => 'test-wrapper-attribute-value',
    ],
    '#maxlength' => 10,
    '#size' => 10,
    '#attributes' => [
      'class' => ['test-class'],
      'data-test' => 'test-attribute-value',
    ],
  ];
  $content[] = [
    '#type' => 'textfield',
    '#title' => t('Textfield, Title visually hidden, default value, description, no error'),
    '#title_display' => 'visually_hidden',
    '#default_value' => 'Textfield, Title visually hidden, default value, description, no error',
    '#description' => t('This is a description'),
    '#description_display' => 'before',
    '#required' => FALSE,
    '#disabled' => FALSE,
  ];
  $content[] = [
    '#type' => 'textfield',
    '#title' => t('Textfield, Title hidden, no default value, placeholder, description, required, error'),
    '#title_display' => 'hidden',
    '#placeholder' => t('Placeholder. Textfield, Title hidden, default value, placeholder, description, required, error'),
    '#description' => t('This is a description'),
    '#description_display' => 'after',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#errors' => t('This is an error message'),
  ];
  $content[] = [
    '#type' => 'textfield',
    '#title' => t('Textfield, Title visible, default value, no description, disabled'),
    '#title_display' => 'visible',
    '#default_value' => 'Default value',
    '#description' => '',
    '#required' => FALSE,
    '#disabled' => TRUE,
  ];

  $elements = [];
  foreach ($content as $k => $element) {
    $elements['test-textfield-' . ($k + 1)] = $element;
  }

  $items['form-civictheme-test-textfield']['content'] = \Drupal::service('form_builder')->getForm(StyleguideTestForm::class, $elements);
}

/**
 * Styleguide textarea elements.
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _civictheme_dev_styleguide__form_test__textarea(array &$items): void {
  $items['form-civictheme-test-textarea']['group'] = t('Form Civictheme Test');
  $items['form-civictheme-test-textarea']['title'] = t('Form Civictheme Test: textarea');

  $content = [];

  $content[] = [
    '#type' => 'textarea',
    '#title' => t('Textarea, Title visible, no default value, no description, no error, so size'),
    '#title_display' => 'visible',
    '#default_value' => '',
    '#description' => '',
    '#rows' => NULL,
    '#required' => FALSE,
    '#disabled' => FALSE,
  ];
  $content[] = [
    '#type' => 'textarea',
    '#title' => t('Textarea, Title visible, default value, no description, no error, required, with attributes'),
    '#title_display' => 'visible',
    '#default_value' => 'Default value',
    '#description' => '',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#wrapper_attributes' => [
      'class' => ['test-wrapper-class'],
      'data-wrapper-test' => 'test-wrapper-attribute-value',
    ],
    '#rows' => 10,
    '#attributes' => [
      'class' => ['test-class'],
      'data-test' => 'test-attribute-value',
    ],
  ];
  $content[] = [
    '#type' => 'textarea',
    '#title' => t('Textarea, Title visually hidden, default value, description, no error'),
    '#title_display' => 'visually_hidden',
    '#default_value' => 'Textarea, Title visually hidden, default value, description, no error',
    '#description' => t('This is a description'),
    '#description_display' => 'before',
    '#required' => FALSE,
    '#disabled' => FALSE,
  ];
  $content[] = [
    '#type' => 'textarea',
    '#title' => t('Textarea, Title hidden, no default value, placeholder, description, required, error'),
    '#title_display' => 'hidden',
    '#placeholder' => t('Placeholder. Textarea, Title hidden, default value, placeholder, description, required, error'),
    '#description' => t('This is a description'),
    '#description_display' => 'after',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#errors' => t('This is an error message'),
  ];
  $content[] = [
    '#type' => 'textarea',
    '#title' => t('Textarea, Title visible, default value, no description, disabled'),
    '#title_display' => 'visible',
    '#default_value' => 'Default value',
    '#description' => '',
    '#required' => FALSE,
    '#disabled' => TRUE,
  ];

  $elements = [];
  foreach ($content as $k => $element) {
    $elements['test-textarea-' . ($k + 1)] = $element;
  }

  $items['form-civictheme-test-textarea']['content'] = \Drupal::service('form_builder')->getForm(StyleguideTestForm::class, $elements);
}

/**
 * Styleguide select elements.
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _civictheme_dev_styleguide__form_test__select(array &$items): void {
  $items['form-civictheme-test-select']['group'] = t('Form Civictheme Test');
  $items['form-civictheme-test-select']['title'] = t('Form Civictheme Test: select');

  $content = [];

  $content[] = [
    '#type' => 'select',
    '#title' => t('Select, Title visible, no default value, no description, no error, no size'),
    '#title_display' => 'visible',
    '#default_value' => '',
    '#description' => '',
    '#options' => [
      '' => t('- None -'),
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
    ],
    '#required' => FALSE,
    '#disabled' => FALSE,
  ];
  $content[] = [
    '#type' => 'select',
    '#title' => t('Select, Title visible, default value, no description, no error, required, with attributes, multiselect'),
    '#title_display' => 'visible',
    '#default_value' => 'option_1',
    '#description' => '',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#multiple' => TRUE,
    '#size' => 5,
    '#wrapper_attributes' => [
      'class' => ['test-wrapper-class'],
      'data-wrapper-test' => 'test-wrapper-attribute-value',
    ],
    '#options' => [
      '' => t('- None -'),
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
      'option_3' => t('Option 3'),
      'option_4' => t('Option 4'),
      'option_5' => t('Option 5'),
      'option_6' => t('Option 6'),
    ],
    '#attributes' => [
      'class' => ['test-class'],
      'data-test' => 'test-attribute-value',
    ],
  ];
  $content[] = [
    '#type' => 'select',
    '#title' => t('Select, Title visually hidden, default value, description, no error'),
    '#title_display' => 'visually_hidden',
    '#default_value' => 'option_2',
    '#description' => t('This is a description. Select, Title visually hidden, default value, description, no error'),
    '#description_display' => 'before',
    '#required' => FALSE,
    '#disabled' => FALSE,
    '#options' => [
      '' => t('- None -'),
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
    ],
  ];
  $content[] = [
    '#type' => 'select',
    '#title' => t('Select, Title hidden, no default value, placeholder, description, required, error'),
    '#title_display' => 'hidden',
    '#description' => t('This is a description. Select, Title hidden, default value, placeholder, description, required, error'),
    '#description_display' => 'after',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#errors' => t('This is an error message'),
    '#options' => [
      '' => t('- None -'),
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
    ],
  ];
  $content[] = [
    '#type' => 'select',
    '#title' => t('Select, Title visible, default value, no description, disabled'),
    '#title_display' => 'visible',
    '#default_value' => 'option_1',
    '#description' => '',
    '#required' => FALSE,
    '#disabled' => TRUE,
    '#options' => [
      '' => t('- None -'),
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
    ],
  ];

  $elements = [];
  foreach ($content as $k => $element) {
    $elements['test-select-' . ($k + 1)] = $element;
  }

  $items['form-civictheme-test-select']['content'] = \Drupal::service('form_builder')->getForm(StyleguideTestForm::class, $elements);
}

/**
 * Styleguide checkbox element.
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _civictheme_dev_styleguide__form_test__checkbox(array &$items): void {
  $items['form-civictheme-test-checkbox']['group'] = t('Form Civictheme Test');
  $items['form-civictheme-test-checkbox']['title'] = t('Form Civictheme Test: checkbox');

  $content = [];

  $content[] = [
    '#type' => 'checkbox',
    '#title' => t('Checkbox, Title visible, no default value, no description, no error'),
    '#title_display' => 'visible',
    '#default_value' => '',
    '#description' => '',
    '#required' => FALSE,
    '#disabled' => FALSE,
  ];
  $content[] = [
    '#type' => 'checkbox',
    '#title' => t('Checkbox, Title visible, default value, no description, no error, required, with attributes'),
    '#title_display' => 'visible',
    '#default_value' => 1,
    '#description' => '',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#wrapper_attributes' => [
      'class' => ['test-wrapper-class'],
      'data-wrapper-test' => 'test-wrapper-attribute-value',
    ],
    '#attributes' => [
      'class' => ['test-class'],
      'data-test' => 'test-attribute-value',
    ],
  ];
  $content[] = [
    '#type' => 'checkbox',
    '#title' => t('Checkbox, Title visually hidden, default value, description, no error'),
    '#title_display' => 'visually_hidden',
    '#default_value' => 1,
    '#description' => t('This is a description'),
    '#description_display' => 'before',
    '#required' => FALSE,
    '#disabled' => FALSE,
  ];
  $content[] = [
    '#type' => 'checkbox',
    '#title' => t('Checkbox, Title hidden, no default value, description, required, error'),
    '#title_display' => 'hidden',
    '#description' => t('This is a description'),
    '#description_display' => 'after',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#errors' => t('This is an error message'),
  ];
  $content[] = [
    '#type' => 'checkbox',
    '#title' => t('Checkbox, Title visible, default value, no description, disabled'),
    '#title_display' => 'visible',
    '#default_value' => 1,
    '#description' => '',
    '#required' => FALSE,
    '#disabled' => TRUE,
  ];

  $elements = [];
  foreach ($content as $k => $element) {
    $elements['test-checkbox-' . ($k + 1)] = $element;
  }

  $items['form-civictheme-test-checkbox']['content'] = \Drupal::service('form_builder')->getForm(StyleguideTestForm::class, $elements);
}

/**
 * Styleguide checkboxes elements.
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _civictheme_dev_styleguide__form_test__checkboxes(array &$items): void {
  $items['form-civictheme-test-checkboxes']['group'] = t('Form Civictheme Test');
  $items['form-civictheme-test-checkboxes']['title'] = t('Form Civictheme Test: checkboxes');

  $content = [];

  $content[] = [
    '#type' => 'checkboxes',
    '#title' => t('Checkboxes, Title visible, no default value, no description, no error'),
    '#title_display' => 'visible',
    '#default_value' => [],
    '#description' => '',
    '#options' => [
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
      'option_3' => t('Option 3'),
      'option_4' => t('Option 4'),
      'option_5' => t('Option 5'),
    ],
    '#required' => FALSE,
    '#disabled' => FALSE,
  ];
  $content[] = [
    '#type' => 'checkboxes',
    '#title' => t('Checkboxes, Title visible, default value, no description, no error, required, with attributes'),
    '#title_display' => 'visible',
    '#default_value' => ['option_1'],
    '#description' => '',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#wrapper_attributes' => [
      'class' => ['test-wrapper-class'],
      'data-wrapper-test' => 'test-wrapper-attribute-value',
    ],
    '#options' => [
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
      'option_3' => t('Option 3'),
      'option_4' => t('Option 4'),
      'option_5' => t('Option 5'),
    ],
    '#attributes' => [
      'class' => ['test-class'],
      'data-test' => 'test-attribute-value',
    ],
  ];
  $content[] = [
    '#type' => 'checkboxes',
    '#title' => t('Checkboxes, Title visually hidden, default value, description, no error'),
    '#title_display' => 'visually_hidden',
    '#default_value' => ['option_2'],
    '#description' => t('This is a description'),
    '#description_display' => 'before',
    '#required' => FALSE,
    '#disabled' => FALSE,
    '#options' => [
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
      'option_3' => t('Option 3'),
      'option_4' => t('Option 4'),
      'option_5' => t('Option 5'),
    ],
  ];
  $content[] = [
    '#type' => 'checkboxes',
    '#title' => t('Checkboxes, Title hidden, no default value, description, required, error'),
    '#title_display' => 'hidden',
    '#description' => t('This is a description'),
    '#description_display' => 'after',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#errors' => t('This is an error message'),
    '#options' => [
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
      'option_3' => t('Option 3'),
      'option_4' => t('Option 4'),
      'option_5' => t('Option 5'),
    ],
  ];
  $content[] = [
    '#type' => 'checkboxes',
    '#title' => t('Checkboxes, Title visible, default value, no description, disabled'),
    '#title_display' => 'visible',
    '#default_value' => ['option_1'],
    '#description' => '',
    '#required' => FALSE,
    '#disabled' => TRUE,
    '#options' => [
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
      'option_3' => t('Option 3'),
      'option_4' => t('Option 4'),
      'option_5' => t('Option 5'),
    ],
  ];

  $elements = [];
  foreach ($content as $k => $element) {
    $elements['test-checkboxes-' . ($k + 1)] = $element;
  }

  $items['form-civictheme-test-checkboxes']['content'] = \Drupal::service('form_builder')->getForm(StyleguideTestForm::class, $elements);
}

/**
 * Styleguide radios elements.
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _civictheme_dev_styleguide__form_test__radios(array &$items): void {
  $items['form-civictheme-test-radios']['group'] = t('Form Civictheme Test');
  $items['form-civictheme-test-radios']['title'] = t('Form Civictheme Test: radios');

  $content = [];

  $content[] = [
    '#type' => 'radios',
    '#title' => t('Radios, Title visible, no default value, no description, no error'),
    '#title_display' => 'visible',
    '#default_value' => [],
    '#description' => '',
    '#options' => [
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
      'option_3' => t('Option 3'),
      'option_4' => t('Option 4'),
      'option_5' => t('Option 5'),
    ],
    '#required' => FALSE,
    '#disabled' => FALSE,
  ];
  $content[] = [
    '#type' => 'radios',
    '#title' => t('Radios, Title visible, default value, no description, no error, required, with attributes'),
    '#title_display' => 'visible',
    '#default_value' => 'option_1',
    '#description' => '',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#wrapper_attributes' => [
      'class' => ['test-wrapper-class'],
      'data-wrapper-test' => 'test-wrapper-attribute-value',
    ],
    '#options' => [
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
      'option_3' => t('Option 3'),
      'option_4' => t('Option 4'),
      'option_5' => t('Option 5'),
    ],
    '#attributes' => [
      'class' => ['test-class'],
      'data-test' => 'test-attribute-value',
    ],
  ];
  $content[] = [
    '#type' => 'radios',
    '#title' => t('Radios, Title visually hidden, default value, description, no error'),
    '#title_display' => 'visually_hidden',
    '#default_value' => 'option_2',
    '#description' => t('This is a description'),
    '#description_display' => 'before',
    '#required' => FALSE,
    '#disabled' => FALSE,
    '#options' => [
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
      'option_3' => t('Option 3'),
      'option_4' => t('Option 4'),
      'option_5' => t('Option 5'),
    ],
  ];
  $content[] = [
    '#type' => 'radios',
    '#title' => t('Radios, Title hidden, no default value, description, required, error'),
    '#title_display' => 'hidden',
    '#description' => t('This is a description'),
    '#description_display' => 'after',
    '#required' => TRUE,
    '#disabled' => FALSE,
    '#errors' => t('This is an error message'),
    '#options' => [
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
      'option_3' => t('Option 3'),
      'option_4' => t('Option 4'),
      'option_5' => t('Option 5'),
    ],
  ];
  $content[] = [
    '#type' => 'radios',
    '#title' => t('Radios, Title visible, default value, no description, disabled'),
    '#title_display' => 'visible',
    '#default_value' => 'option_1',
    '#description' => '',
    '#required' => FALSE,
    '#disabled' => TRUE,
    '#options' => [
      'option_1' => t('Option 1'),
      'option_2' => t('Option 2'),
      'option_3' => t('Option 3'),
      'option_4' => t('Option 4'),
      'option_5' => t('Option 5'),
    ],
  ];

  $elements = [];
  foreach ($content as $k => $element) {
    $elements['test-radios-' . ($k + 1)] = $element;
  }

  $items['form-civictheme-test-radios']['content'] = \Drupal::service('form_builder')->getForm(StyleguideTestForm::class, $elements);
}
