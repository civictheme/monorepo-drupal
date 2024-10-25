<?php

declare(strict_types=1);

namespace Drupal\civictheme_dev\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;
use Drupal\Core\Render\ElementInfoManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Styleguide form.
 */
class StyleguideForm extends FormBase implements ContainerInjectionInterface {

  /**
   * StyleguideForm constructor.
   *
   * @param \Drupal\Core\Render\ElementInfoManager $elementInfoManager
   *   Element info manager service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity type manager service.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $themeHandler
   *   The theme handler.
   */
  public function __construct(protected ElementInfoManager $elementInfoManager, protected EntityTypeManagerInterface $entityTypeManager, protected ThemeHandlerInterface $themeHandler) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    // @phpstan-ignore-next-line
    return new static(
      $container->get('plugin.manager.element_info'),
      $container->get('entity_type.manager'),
      $container->get('theme_handler'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'styleguide_form';
  }

  /**
   * {@inheritDoc}
   *
   * @phpstan-ignore-next-line
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    // Getting form element plugin manager.
    $plugin_manager = $this->elementInfoManager;

    // Getting all defaults form element types.
    $element_types = $plugin_manager->getDefinitions();
    $element_types = array_filter($element_types, static function (array $element) : bool {
        $provider_list = [
          'core',
          'linkit',
          // 'webform', WebformInterface is not available in this context.
        ];
        return in_array($element['provider'], $provider_list);
    });
    ksort($element_types);
    // Creating form fields for each element.
    foreach (array_keys($element_types) as $id) {
      $element = $plugin_manager->createInstance($id);
      if (!$element instanceof FormElement) {
        continue;
      }
      $form[$id] = $this->createFormElement($id, $element);
    }

    $form['link'] = [
      '#type' => 'link',
      '#title' => $this->t('Examples'),
      '#url' => \Drupal\Core\Url::fromUri('https://www.drupal.org/docs/8/api/form-api/examples'),
    ];

    return $form;
  }

  /**
   * Create form element.
   *
   * @param string $id
   *   Element id.
   * @param \Drupal\Core\Render\Element\FormElement $element
   *   Actual element.
   *
   * @return array<int|string, mixed>
   *   Form element.
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   */
  protected function createFormElement(string $id, FormElement $element): array {
    $form_element = [
      '#type' => $id,
      '#title' => ucwords(str_replace('_', ' ', $id)) . ' (' . $id . ')',
      '#description' => $this->t('Test of @id element.', ['@id' => $id]),
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
          '#machine_name' => [
            'exists' => function ($value) : bool {
              return $this->entityTypeManager->getStorage('node_type')->load($value) !== NULL;
            },
          ],
          '#description' => $this->t("A unique machine-readable name. Can only contain lowercase letters, numbers, and underscores."),
        ];
        break;
    }

    return $form_element;
  }

  /**
   * {@inheritDoc}
   *
   * @phpstan-ignore-next-line
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Generates entity autocomplete form element.
   *
   * @param array $form_element
   *   Form element.
   * @param \Drupal\Core\Render\Element\FormElement $element
   *   Form element.
   *
   * @return array<string, mixed>
   *   Form element.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
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
   *   Form element.
   * @param \Drupal\Core\Render\Element\FormElement $element
   *   Form element.
   *
   * @return array<string, mixed>
   *   Form element.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  protected function createOptionsFormElement(array $form_element, FormElement $element): array {
    $form_element['#options'] = [
      'option1' => 'Option 1',
      'option2' => 'Option 2',
      'option3' => 'Option 3',
    ];

    return $form_element;
  }

  /**
   * Create table form element helper.
   *
   * @param array $form_element
   *   Form element.
   * @param \Drupal\Core\Render\Element\FormElement $element
   *   Form element.
   *
   * @return array<string, mixed>
   *   Form element.
   *
   * @SuppressWarnings(PHPMD.ElseExpression)
   */
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

  /**
   * Create image button helper.
   *
   * @param array $form_element
   *   Form element.
   * @param \Drupal\Core\Render\Element\FormElement $element
   *   Form element.
   *
   * @return array<int, array>
   *   Form element.
   *
   * @phpstan-ignore-next-line
   */
  protected function createImageButton(array $form_element, FormElement $element): array {
    $theme_path = $this->themeHandler->getTheme('civictheme')->getPath();
    $icon_path = '/' . $theme_path . '/assets/icons/download.svg';
    $form_element['#src'] = $icon_path;

    return $this->createButton($form_element, $element);
  }

  /**
   * Create button helper.
   *
   * @param array $form_element
   *   Form element.
   * @param \Drupal\Core\Render\Element\FormElement $element
   *   Form element.
   *
   * @return array<int, array>
   *   Form element.
   *
   * @phpstan-ignore-next-line
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  protected function createButton(array $form_element, FormElement $element): array {
    $form_element['#value'] = $form_element['#title'];

    return [
      [
        '#type' => 'html_tag',
        '#tag' => 'h6',
        '#value' => $form_element['#title'],
      ],
      $form_element,
    ];
  }

}
