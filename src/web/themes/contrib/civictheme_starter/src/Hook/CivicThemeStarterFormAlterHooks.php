<?php

declare(strict_types=1);

namespace Drupal\civictheme_starter\Hook;

use Drupal\civictheme_starter\Theme\Form\FormElementHelper;
use Drupal\civictheme_starter\Utility\CivicthemeField;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Render\Element;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;
use Drupal\views\ViewExecutable;

/**
 * Form alter hook implementations for CivicTheme.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class CivicThemeStarterFormAlterHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_form_alter().
   */
  #[Hook('form_alter')]
  public function formAlter(array &$form, FormStateInterface $form_state, string $form_id): void {
    $this->alterWebformSubmission($form, $form_state, $form_id);
    $this->alterNonFormElements($form);
    $this->alterRequiredRadios($form);
  }

  /**
   * Implements hook_form_FORM_ID_alter() for views_exposed_form.
   *
   * @SuppressWarnings("PHPMD.CyclomaticComplexity")
   */
  #[Hook('form_views_exposed_form_alter')]
  public function viewsExposedFormAlter(array &$form, FormStateInterface $form_state): void {
    $storage = $form_state->getStorage();
    // Pass view ID and display ID in attributes to the exposed filter
    // preprocessor.
    // @see \Drupal\civictheme_starter\Hook\Preprocess\CivicThemeStarterViewsHooks::preprocessViewsExposedForm()
    if (!empty($storage['view']) && $storage['view'] instanceof ViewExecutable) {
      $form['#attributes']['view_id'] = $storage['view']->id();
      $form['#attributes']['view_display'] = $storage['view']->current_display;
    }

    // Restrict Topics exposed filter to contextual filter term(s) when
    // present.
    $view = $form_state->get('view');
    if (!$view instanceof ViewExecutable || $view->id() !== 'civictheme_automated_list') {
      return;
    }
    if (!isset($form['topic']) || !isset($form['topic']['#type'])) {
      return;
    }
    try {
      $contextual_filter_tids = $this->getTopicsContextualArgumentTids($view);
      if ($contextual_filter_tids !== []) {
        $options = $this->buildTopicOptionsFromTids($contextual_filter_tids);
        if ($options !== []) {
          $form['topic']['#empty_option'] = $this->t('- Any -');
          $form['topic']['#empty_value'] = '';
          $form['topic']['#options'] = $options;
        }
      }
    }
    catch (\Throwable $throwable) {
      \Drupal::logger('civictheme')->warning('Exposed form alter failed: @message', [
        '@message' => $throwable->getMessage(),
      ]);
    }
  }

  /**
   * Form alter for webform submission form.
   */
  protected function alterWebformSubmission(array &$form, FormStateInterface $form_state, string $form_id): void {
    if (!empty($form_id) && str_starts_with($form_id, 'webform_submission_')) {
      /** @var \Drupal\webform\WebformSubmissionForm $form_object */
      $form_object = $form_state->getFormObject();

      /** @var \Drupal\webform\WebformSubmissionInterface $webform_submission */
      $webform_submission = $form_object->getEntity();
      $source = $webform_submission->getSourceEntity();

      if ($source instanceof Paragraph && !empty($form['elements'])) {
        $theme = CivicthemeField::getThemeValue($source);
        // The webform theme wrapper renders the form tag from the form root:
        // carry the theme there as well as through the element tree.
        $form['#attributes']['#civictheme_theme'] = $theme;
        $form['#attributes']['#component_theme'] = $theme;
        FormElementHelper::propagateComponentTheme($form['elements'], $theme);
      }
    }
  }

  /**
   * Marks the options of required radios groups as required.
   *
   * Core does not propagate #required from a radios group to its options, so
   * native browser validation never fires for them. Setting the attribute on
   * the group element propagates it onto every option input via the element
   * process callback.
   */
  protected function alterRequiredRadios(array &$elements): void {
    foreach (Element::children($elements) as $key) {
      if (($elements[$key]['#type'] ?? '') === 'radios' && !empty($elements[$key]['#required'])) {
        $elements[$key]['#attributes']['required'] = 'required';
      }
      $this->alterRequiredRadios($elements[$key]);
    }
  }

  /**
   * Alter elements in forms that are not categorised as form elements.
   *
   * Form elements should be preprocessed in an associated preprocess
   * functions.
   *
   * However, there area some types of Element in form are not form elements
   * eg link and html_tag. This method provides the ability to alter these
   * elements if required.
   *
   * @SuppressWarnings("PHPMD.CyclomaticComplexity")
   */
  protected function alterNonFormElements(array &$elements): void {
    $element_keys = Element::children($elements);
    foreach ($element_keys as $element_key) {
      $element_type = $elements[$element_key]['#type'] ?? NULL;
      if (is_array($elements[$element_key])) {
        $nested_elements = Element::children($elements[$element_key]);
        if (!empty($nested_elements)) {
          $this->alterNonFormElements($elements[$element_key]);
        }
      }
      if ($element_type !== NULL) {
        // Add elements to modify to this switch. Display-only markup
        // elements (processed_text, webform_markup) are themed at the
        // template layer via form_element suggestions instead of a render
        // array rebuild.
        switch ($elements[$element_key]['#type']) {
          case 'link':
            $this->alterNonFormElementsLink($elements[$element_key]);
            break;

          default:
            continue 2;
        }
      }
    }
  }

  /**
   * Alter link elements within forms.
   */
  protected function alterNonFormElementsLink(array &$element): void {
    // Convert "button" links into CivicTheme button links.
    if (!empty($element['#attributes']['class']) && in_array('button', $element['#attributes']['class'])) {
      $element_theme = FormElementHelper::getComponentTheme($element);
      $element['#attributes']['class'] = array_merge($element['#attributes']['class'], [
        'ct-button',
        'ct-button--regular',
        'ct-button--secondary',
        'ct-theme-' . $element_theme,
      ]);
      return;
    }
    $element['#attributes']['class'][] = 'ct-link';
  }

  /**
   * Gets term IDs from the Topics contextual filter (term_node_tid_depth).
   *
   * @param \Drupal\views\ViewExecutable $view
   *   The view.
   *
   * @return int[]
   *   Term IDs from the contextual argument, or empty if none/any.
   *
   * @SuppressWarnings("PHPMD.CyclomaticComplexity")
   * @SuppressWarnings("PHPMD.NPathComplexity")
   */
  protected function getTopicsContextualArgumentTids(ViewExecutable $view): array {
    $display = $view->getDisplay();
    $argument_options = $display->getOption('arguments') ?? [];
    if (!is_array($argument_options)) {
      return [];
    }

    $argument_ids = array_keys($argument_options);
    $argument_index = array_search('term_node_tid_depth', $argument_ids, TRUE);
    if ($argument_index === FALSE) {
      return [];
    }

    $args = $view->args;
    if (!isset($args[$argument_index]) || $args[$argument_index] === '' || $args[$argument_index] === 'all') {
      return [];
    }

    $parts = preg_split('/[\s+]+/', (string) $args[$argument_index]);
    if ($parts === FALSE) {
      return [];
    }

    $tids = [];
    foreach ($parts as $part) {
      $part = trim($part);
      if ($part !== '' && is_numeric($part)) {
        $tids[] = (int) $part;
      }
    }

    return array_unique($tids);
  }

  /**
   * Builds option list for the Topics exposed filter from term IDs.
   *
   * @param int[] $tids
   *   Taxonomy term IDs (civictheme_topics).
   *
   * @return array<int|string, string>
   *   Options array keyed by term ID, value is term name.
   */
  protected function buildTopicOptionsFromTids(array $tids): array {
    if ($tids === []) {
      return [];
    }

    $terms = Term::loadMultiple($tids);
    $options = [];
    foreach ($terms as $term) {
      $options[$term->id()] = (string) $term->label();
    }

    return $options;
  }

}
