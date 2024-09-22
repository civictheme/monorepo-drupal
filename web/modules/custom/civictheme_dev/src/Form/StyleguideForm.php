<?php

namespace Drupal\civictheme_dev\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;
use Drupal\node\Entity\NodeType;

/**
 * Styleguide form.
 */
class StyleguideForm extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'styleguide_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Getting form element plugin manager.
    $plugin_manager = \Drupal::service('plugin.manager.element_info');

    // Getting all defaults form element types.
    $element_types = $plugin_manager->getDefinitions();
    $element_types = array_filter($element_types, function ($element) {
      return $element['provider'] === 'core';
    });
    ksort($element_types);
    // Creating form fields for each element.
    foreach ($element_types as $id => $element_type) {
      $element = $plugin_manager->createInstance($id);
      if (!$element instanceof FormElement) {
        continue;
      }
      $form[$id] = $this->createFormElement($id, $element);
    }

    return $form;
  }

  /**
   * Create form element.
   *
   * @param string $id
   *   Element id.
   * @param array $info
   *   Element info.
   *
   * @return array
   *   Form element.
   */
  protected function createFormElement(string $id, FormElement $element): array {
    $form_element = [
      '#type' => $id,
      '#title' => ucwords(str_replace('_', ' ', $id)) . ' (' . $id . ')',
      '#description' => 'Test of ' . $id . ' element.',
    ];

    switch ($id) {
      case 'entity_autocomplete':
        $form_element = $this->createEntityAutoCompleteFormElement($form_element, $element);
        break;
      case 'radios':
      case 'checkboxes':
        $form_element = $this->createOptionsFormElement($form_element, $element);
        break;
      case 'tableselect':
      case 'table':
        $form_element = $this->createTableFormElement($form_element, $element);
        break;
      case 'image_button':
        $form_element = $this->createImageButton($form_element, $element);
        break;
      case 'button':
      case 'submit':
        $form_element = $this->createButton($form_element, $element);
        break;
      case 'machine_name':
        $form_element += [
          '#default_value' => '',
          '#machine_name' => array(
            'exists' => function($value) {
              return NodeType::load($value) !== null;
            },
          ),
          '#description' => t("A unique machine-readable name. Can only contain lowercase letters, numbers, and underscores."),
        ];
        break;
    }

    return $form_element;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Generates entity autocomplete form element.
   *
   * @param array $form_element
   * @param \Drupal\Core\Render\Element\FormElement $element
   *
   * @return array
   */
  protected function createEntityAutoCompleteFormElement(array $form_element, FormElement $element): array {
    $form_element['#type'] = 'entity_autocomplete';
    $form_element['#target_type'] = 'node';
    $form_element['#selection_settings'] = [
      'target_bundles' => ['civictheme_page'],
    ];

    return $form_element;
  }

  /**
   * Generates options-style form elements.
   *
   * @param array $form_element
   * @param \Drupal\Core\Render\Element\FormElement $element
   *
   * @return array
   */
  protected function createOptionsFormElement(array $form_element, FormElement $element): array {
    $form_element['#options'] = [
      'option1' => 'Option 1',
      'option2' => 'Option 2',
      'option3' => 'Option 3',
    ];

    return $form_element;
  }

  protected function createTableFormElement(array $form_element, FormElement $element): array {
    $form_element['#header'] = ['Header 1', 'Header 2', 'Header 3'];
    if ($element->getPluginId() === 'tableselect') {
      $form_element['#options'] = [
        'row1' => ['Row 1', 'Row 1', 'Row 1'],
        'row2' => ['Row 2', 'Row 2', 'Row 2'],
        'row3' => ['Row 3', 'Row 3', 'Row 3'],
        'row4' => ['Row 4', 'Row 4', 'Row 4'],
      ];
    }
    else {
      $form_element['#rows'] = [
        ['Header 1' => 'Row 1', 'Header 2' => 'Row 1', 'Header 3' => 'Row 1'],
        ['Header 1' => 'Row 1', 'Header 2' => 'Row 1', 'Header 3' => 'Row 1'],
        ['Header 1' => 'Row 1', 'Header 2' => 'Row 1', 'Header 3' => 'Row 1'],
        ['Header 1' => 'Row 1', 'Header 2' => 'Row 1', 'Header 3' => 'Row 1'],
      ];
    }

    return $form_element;
  }

  protected function createImageButton(array $form_element, FormElement $element): array {
    /** @var \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler */
    $theme_handler = \Drupal::service('theme_handler');
    $theme_path = $theme_handler->getTheme('civictheme')->getPath();
    $icon_path = '/' . $theme_path . '/assets/icons/download.svg';
    $form_element['#src'] = $icon_path;

    return $this->createButton($form_element, $element);
  }

  protected function createButton(array $form_element, FormElement $element): array {
    $form_element['#value'] = $form_element['#title'];
    $form_element = [
      [
        '#type' => 'html_tag',
        '#tag' => 'h6',
        '#value' => $form_element['#title'],
      ],
      $form_element,
    ];

    return $form_element;
  }

}
