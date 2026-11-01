<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook\Preprocess;

use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\Theme\Paragraph\FieldMapper;
use Drupal\civictheme\Theme\Views\AutomatedListManager;
use Drupal\civictheme\Utility\CivicthemeFieldHelper;
use Drupal\civictheme\Utility\CivicthemeMediaHelper;
use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Paragraph preprocess hook implementations for CivicTheme.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class ParagraphHooks {

  use StringTranslationTrait;

  /**
   * Implements template_preprocess_paragraph() for civictheme_accordion.
   */
  #[Hook('preprocess_paragraph__civictheme_accordion')]
  public function accordion(array &$variables): void {
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
    FieldMapper::paragraphFieldBackground($variables);

    /** @var \Drupal\paragraphs\Entity\Paragraph[] $panels */
    $panels = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_panels', build: $variables) ?? [];
    foreach ($panels as $panel) {
      $panel_variables = [
        'expanded' => CivicthemeFieldHelper::getValue($panel, 'field_c_p_expand'),
        'title' => CivicthemeFieldHelper::getValue($panel, 'field_c_p_title'),
      ];
      $panel_variables['paragraph'] = $panel;
      FieldMapper::paragraphFieldContent($panel_variables);

      $variables['panels'][] = $panel_variables;
    }

    $variables['expand_all'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_expand') ?? FALSE;
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_attachment.
   */
  #[Hook('preprocess_paragraph__civictheme_attachment')]
  public function attachment(array &$variables): void {
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldContent($variables);
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
    FieldMapper::paragraphFieldBackground($variables);

    $attachments = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_attachments', build: $variables) ?? [];
    foreach ($attachments as $attachment) {
      $vars = CivicthemeMediaHelper::getVariables($attachment);
      if ($vars) {
        if ($this->configManager()->load('components.attachment.use_media_name', TRUE)) {
          $vars['name'] = $vars['media_name'];
        }
        $variables['files'][] = $vars;
      }
    }
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_automated_list.
   */
  #[Hook('preprocess_paragraph__civictheme_automated_list')]
  public function automatedList(array &$variables): void {
    /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
    $paragraph = $variables['paragraph'];

    // Remove existing content.
    $variables['content'] = NULL;

    // The paragraph contains 2 types of values:
    // 1. Content values that should be passed as variables to the render
    // image.
    // 2. View settings values that should be passed to a view for
    // alterations in child preprocess hooks.
    //
    // Content values.
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldContent($variables);
    FieldMapper::paragraphFieldLinkAbove($variables);
    FieldMapper::paragraphFieldLinkBelow($variables);
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
    FieldMapper::paragraphFieldBackground($variables);

    // Views settings.
    // We want to have a single place to gather all initial view settings to
    // pass them further in the pipeline. There should not be any other
    // places to access these fields variables directly (separation of
    // concerns). Only these settings should be used.
    // Consumer sites can alter these settings in
    // hook_civictheme_automated_list_view_alter().
    $settings = [];
    // Store paragraph entity in the settings to allow accessing the values
    // of fields that may be added in the consumer sites (in addition to the
    // fields provided by the CivicTheme Automated List paragraph).
    $settings['paragraph'] = $paragraph;
    $settings['cache_tags'] = $paragraph->getCacheTags();
    // Settings provided by the CivicTheme Automated List paragraph's fields.
    $settings['type'] = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_list_type');
    $settings['content_type'] = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_list_content_type');
    $settings['limit'] = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_list_limit');
    $settings['limit_type'] = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_list_limit_type');
    $settings['filters_exp'] = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_list_filters_exp', TRUE, '');
    $settings['view_as'] = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_list_item_view_as');
    $settings['item_theme'] = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_list_item_theme');
    $settings['topics'] = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_list_topics', build: $variables);
    $settings['site_sections'] = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_list_site_sections', build: $variables);
    $settings['theme'] = $variables['theme'];
    FieldMapper::paragraphFieldTheme($settings);
    FieldMapper::paragraphFieldTitle($settings);
    FieldMapper::paragraphFieldColumnCount($settings);
    FieldMapper::paragraphFieldFillWidth($settings);

    // Get view with already set display.
    try {
      /** @var \Drupal\views\ViewExecutable $view */
      $view = AutomatedListManager::getView($settings);
    }
    catch (\Exception $e) {
      \Drupal::logger('civictheme')->error($e->getMessage());
      return;
    }

    $settings['view_id'] = $view->id();
    $settings['view_display'] = $view->current_display;

    // Make component settings available to the processing functions to
    // further alter view settings.
    // @see \Drupal\civictheme\Hook\Preprocess\ViewsHooks::preprocessViewsView()
    // @phpstan-ignore-next-line
    $view->component_settings = $settings;

    // Preprocess view properties based on the settings above.
    AutomatedListManager::updateView($view);

    // Render view as rows.
    $variables['rows'] = $view->executeDisplay();

    // Pass the CSS class from the view display to the template.
    $css_class = $view->getDisplay()->getOption('css_class');
    if (!empty($css_class)) {
      $variables['modifier_class'] = $variables['modifier_class'] ?? '' . $css_class;
    }

    // Add unique HTML ID.
    $attributes = [];
    $attributes['id'] = Html::getUniqueId('ct-automated-list-' . $paragraph->id());

    // Run merging of the attributes last. Do not add any code below this
    // line.
    $variables['attributes'] = ($variables['attributes'] ?? []) + $attributes;
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_callout.
   */
  #[Hook('preprocess_paragraph__civictheme_callout')]
  public function callout(array &$variables): void {
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldContent($variables);
    FieldMapper::paragraphFieldLinks($variables);
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_campaign.
   */
  #[Hook('preprocess_paragraph__civictheme_campaign')]
  public function campaign(array &$variables): void {
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldImage($variables, CivicthemeConstants::CAMPAIGN_IMAGE_STYLE);
    FieldMapper::paragraphFieldImagePosition($variables);
    FieldMapper::paragraphFieldTopics($variables);
    FieldMapper::paragraphFieldDate($variables);
    FieldMapper::paragraphFieldContent($variables);
    FieldMapper::paragraphFieldLinks($variables);
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_content.
   */
  #[Hook('preprocess_paragraph__civictheme_content')]
  public function content(array &$variables): void {
    FieldMapper::paragraphFieldContent($variables);
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
    FieldMapper::paragraphFieldBackground($variables);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_fast_fact_card.
   */
  #[Hook('preprocess_paragraph__civictheme_fast_fact_card')]
  public function fastFactCard(array &$variables): void {
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldSummary($variables);
    FieldMapper::paragraphFieldLink($variables);
    $media = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_icon', TRUE);
    if ($media) {
      $variables['image'] = CivicthemeMediaHelper::getImageVariables($media);
    }
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_iframe.
   */
  #[Hook('preprocess_paragraph__civictheme_iframe')]
  public function iframe(array &$variables): void {
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
    FieldMapper::paragraphFieldBackground($variables);

    $variables['url'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_url');
    $width = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_width');
    $variables['width'] = !empty($width) ? (int) $width : NULL;
    $height = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_height');
    $variables['height'] = !empty($height) ? (int) $height : NULL;
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_manual_list.
   */
  #[Hook('preprocess_paragraph__civictheme_manual_list')]
  public function manualList(array &$variables): void {
    /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
    $paragraph = $variables['paragraph'];

    // Remove existing content.
    $variables['content'] = NULL;

    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldContent($variables);
    FieldMapper::paragraphFieldLinkAbove($variables);
    FieldMapper::paragraphFieldLinkBelow($variables);
    FieldMapper::paragraphFieldColumnCount($variables);
    FieldMapper::paragraphFieldFillWidth($variables);
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
    FieldMapper::paragraphFieldBackground($variables);

    $variables += [
      'card_theme' => CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_list_item_theme'),
      'cache_tags' => $paragraph->getCacheTags(),
    ];
    $variables['#cache']['tags'] = $variables['cache_tags'];

    /** @var \Drupal\Core\Entity\ContentEntityInterface[] $items */
    $items = CivicthemeFieldHelper::getReferencedEntities($paragraph, 'field_c_p_list_items', $variables);
    $builder = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
    if ($items) {
      foreach ($items as $item) {
        if (!$item->hasField('field_c_p_reference')) {
          $variables['rows'][] = $builder->view($item);
          continue;
        }
        $referenced_item = CivicthemeFieldHelper::getReferencedEntity($item, 'field_c_p_reference', $variables);
        if ($referenced_item instanceof EntityInterface) {
          $variables['rows'][] = $builder->view($item);
        }
      }
    }

    $attributes = [];
    $attributes['id'] = Html::getUniqueId('ct-manual-list-' . $paragraph->id());

    $variables['attributes'] = ($variables['attributes'] ?? []) + $attributes;
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_map.
   */
  #[Hook('preprocess_paragraph__civictheme_map')]
  public function map(array &$variables): void {
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
    FieldMapper::paragraphFieldBackground($variables);

    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
    $link = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_embed_url', TRUE);
    if ($link !== NULL) {
      $variables['url'] = $link->getUrl()->toString();
    }

    $variables['address'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_address', default: '');

    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $view_link */
    $view_link = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_view_link', TRUE);
    if ($view_link !== NULL) {
      $variables['view_url'] = $view_link->getUrl()->toString();
    }
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_message.
   */
  #[Hook('preprocess_paragraph__civictheme_message')]
  public function message(array &$variables): void {
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldContent($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
    FieldMapper::paragraphFieldBackground($variables);

    $variables['type'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_message_type');
    $variables['has_aria'] = FALSE;
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_next_step.
   */
  #[Hook('preprocess_paragraph__civictheme_next_step')]
  public function nextStep(array &$variables): void {
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldContent($variables);
    FieldMapper::paragraphFieldLink($variables);
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_promo.
   */
  #[Hook('preprocess_paragraph__civictheme_promo')]
  public function promo(array &$variables): void {
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldContent($variables);
    FieldMapper::paragraphFieldLink($variables);
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
    FieldMapper::paragraphFieldBackground($variables);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_promo_card.
   */
  #[Hook('preprocess_paragraph__civictheme_promo_card')]
  public function promoCard(array &$variables): void {
    FieldMapper::paragraphFieldImage($variables, CivicthemeConstants::PROMO_CARD_IMAGE_STYLE);
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldSummary($variables);
    FieldMapper::paragraphFieldLink($variables);
    FieldMapper::paragraphFieldTopics($variables);
    FieldMapper::paragraphFieldTheme($variables);

    $variables['subtitle'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_subtitle', TRUE);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_promo_card_ref.
   */
  #[Hook('preprocess_paragraph__civictheme_promo_card_ref')]
  public function promoCardRef(array &$variables): void {
    FieldMapper::nodeFieldImage($variables, CivicthemeConstants::PROMO_CARD_IMAGE_STYLE);
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldSummary($variables);
    FieldMapper::nodeFieldLink($variables);
    FieldMapper::nodeFieldTopics($variables);
    FieldMapper::paragraphFieldTheme($variables);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_event_card.
   */
  #[Hook('preprocess_paragraph__civictheme_event_card')]
  public function eventCard(array &$variables): void {
    FieldMapper::paragraphFieldImage($variables, CivicthemeConstants::EVENT_CARD_IMAGE_STYLE);
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldSummary($variables);
    FieldMapper::paragraphFieldDateRange($variables);
    FieldMapper::paragraphFieldLink($variables);
    FieldMapper::paragraphFieldTopics($variables);
    FieldMapper::paragraphFieldTheme($variables);

    $variables['location'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_location', TRUE);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_event_card_ref.
   */
  #[Hook('preprocess_paragraph__civictheme_event_card_ref')]
  public function eventCardRef(array &$variables): void {
    FieldMapper::nodeFieldImage($variables, CivicthemeConstants::EVENT_CARD_IMAGE_STYLE);
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldLocation($variables);
    FieldMapper::nodeFieldSummary($variables);
    FieldMapper::nodeFieldLink($variables);
    FieldMapper::nodeFieldDateRange($variables);
    FieldMapper::nodeFieldTopics($variables);
    FieldMapper::paragraphFieldTheme($variables);
  }

  /**
   * Implements preprocess for civictheme_navigation_card.
   */
  #[Hook('preprocess_paragraph__civictheme_navigation_card')]
  public function navigationCard(array &$variables): void {
    FieldMapper::paragraphFieldImage($variables, CivicthemeConstants::NAVIGATION_CARD_IMAGE_STYLE);
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldSummary($variables);
    FieldMapper::paragraphFieldLink($variables);
    FieldMapper::paragraphFieldTheme($variables);

    $variables['image_as_icon'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_show_image_as_icon') ?? FALSE;
  }

  /**
   * Implements preprocess for civictheme_navigation_card_ref.
   */
  #[Hook('preprocess_paragraph__civictheme_navigation_card_ref')]
  public function navigationCardRef(array &$variables): void {
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldSummary($variables);
    FieldMapper::nodeFieldImage($variables, CivicthemeConstants::NAVIGATION_CARD_IMAGE_STYLE);
    FieldMapper::nodeFieldLink($variables);
    FieldMapper::paragraphFieldTheme($variables);
  }

  /**
   * Implements preprocess for civictheme_publication_card.
   */
  #[Hook('preprocess_paragraph__civictheme_publication_card')]
  public function publicationCard(array &$variables): void {
    FieldMapper::paragraphFieldImage($variables, CivicthemeConstants::PUBLICATION_CARD_IMAGE_STYLE);
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldSummary($variables);
    FieldMapper::paragraphFieldTheme($variables);

    $document = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_document', TRUE, build: $variables) ?? NULL;
    if ($document) {
      $vars = CivicthemeMediaHelper::getVariables($document);
      if ($vars) {
        if ($this->configManager()->load('components.publication_card.use_media_name', TRUE)) {
          $vars['name'] = $vars['media_name'];
        }
        $variables['file'] = $vars;
      }
    }
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_subject_card.
   */
  #[Hook('preprocess_paragraph__civictheme_subject_card')]
  public function subjectCard(array &$variables): void {
    FieldMapper::paragraphFieldImage($variables, CivicthemeConstants::SUBJECT_CARD_IMAGE_STYLE);
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldLink($variables);
    FieldMapper::paragraphFieldTheme($variables);
  }

  /**
   * Implements preprocess for civictheme_subject_card_ref.
   */
  #[Hook('preprocess_paragraph__civictheme_subject_card_ref')]
  public function subjectCardRef(array &$variables): void {
    FieldMapper::nodeFieldImage($variables, CivicthemeConstants::SUBJECT_CARD_IMAGE_STYLE);
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldLink($variables);
    FieldMapper::paragraphFieldTheme($variables);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_service_card.
   */
  #[Hook('preprocess_paragraph__civictheme_service_card')]
  public function serviceCard(array &$variables): void {
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldLinks($variables);
    FieldMapper::paragraphFieldTheme($variables);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_slider.
   */
  #[Hook('preprocess_paragraph__civictheme_slider')]
  public function slider(array &$variables): void {
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
    FieldMapper::paragraphFieldBackground($variables);

    // @todo Make these configurable in theme settings.
    $variables['previous_label'] = $this->t('Previous');
    $variables['next_label'] = $this->t('Next');

    $slides = $variables['content']['field_c_p_slides'] ?? NULL;
    if (!is_array($slides) || $slides === []) {
      return;
    }

    /** @var \Drupal\Core\Render\RendererInterface $renderer */
    $renderer = \Drupal::service('renderer');

    // Render slides so markup can be checked.
    $output = $renderer->hasRenderContext() ? $renderer->render($slides) : $renderer->renderInIsolation($slides);

    // Check for content in the rendered slides.
    if (trim(strip_tags((string) $output, '<img><svg><drupal-render-placeholder>')) === '') {
      return;
    }

    $variables['slides'] = $output;
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_slider_slide.
   */
  #[Hook('preprocess_paragraph__civictheme_slider_slide')]
  public function sliderSlide(array &$variables): void {
    /** @var \Drupal\paragraphs\Entity\Paragraph|null $paragraph */
    $paragraph = $variables['paragraph'] ?? NULL;
    if ($paragraph === NULL || !$paragraph instanceof Paragraph) {
      return;
    }
    $parent = $paragraph->getParentEntity();
    if ($parent instanceof Paragraph && $parent->bundle() === 'civictheme_slider') {
      $variables['heading_level'] = CivicthemeFieldHelper::hasValue($parent, 'field_c_p_title') ? 3 : 2;
    }

    FieldMapper::paragraphFieldImage($variables, CivicthemeConstants::SLIDER_SLIDE_IMAGE_STYLE);
    FieldMapper::paragraphFieldImagePosition($variables);
    FieldMapper::paragraphFieldTopics($variables);
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldDate($variables);
    FieldMapper::paragraphFieldContent($variables);
    FieldMapper::paragraphFieldLinks($variables);
    FieldMapper::paragraphFieldTheme($variables);
  }

  /**
   * Implements preprocess for civictheme_slider_slide_ref.
   *
   * @SuppressWarnings(PHPMD.ElseExpression)
   */
  #[Hook('preprocess_paragraph__civictheme_slider_slide_ref')]
  public function sliderSlideRef(array &$variables): void {
    $variables['slide_available'] = FALSE;

    /** @var \Drupal\paragraphs\Entity\Paragraph|null $paragraph */
    $paragraph = $variables['paragraph'] ?? NULL;
    if ($paragraph === NULL || !$paragraph instanceof Paragraph) {
      return;
    }

    $parent = $paragraph->getParentEntity();
    if ($parent instanceof Paragraph && $parent->bundle() === 'civictheme_slider') {
      $variables['heading_level'] = CivicthemeFieldHelper::hasValue($parent, 'field_c_p_title') ? 3 : 2;
    }

    /** @var \Drupal\node\NodeInterface|null $node */
    $node = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_reference', TRUE, build: $variables);
    if (empty($node)) {
      return;
    }

    $variables['slide_available'] = TRUE;

    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldTopics($variables);
    FieldMapper::nodeFieldImage($variables, CivicthemeConstants::SLIDER_SLIDE_IMAGE_STYLE);
    FieldMapper::paragraphFieldImagePosition($variables);
    FieldMapper::paragraphFieldTheme($variables);

    if ($node->bundle() == 'civictheme_event') {
      FieldMapper::nodeFieldDateRange($variables);
    }
    else {
      FieldMapper::nodeFieldDate($variables);
    }

    // Special case to re-use 'summary' processor for 'content' field.
    FieldMapper::nodeFieldSummary($variables);
    $variables['content'] = $variables['summary'] ?? FALSE;
    unset($variables['summary']);

    $link_text = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_link_text', TRUE);
    if ($link_text) {
      $variables['links'][] = [
        'text' => $link_text,
        'url' => $node->toUrl()->toString(),
        'is_external' => $node->toUrl()->isExternal(),
        'is_new_window' => $node->toUrl()->isExternal(),
      ];
    }
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_snippet.
   */
  #[Hook('preprocess_paragraph__civictheme_snippet')]
  public function snippet(array &$variables): void {
    FieldMapper::paragraphFieldTitle($variables);
    FieldMapper::paragraphFieldSummary($variables);
    FieldMapper::paragraphFieldLink($variables);
    FieldMapper::paragraphFieldTopics($variables);
    FieldMapper::paragraphFieldTheme($variables);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_snippet_ref.
   */
  #[Hook('preprocess_paragraph__civictheme_snippet_ref')]
  public function snippetRef(array &$variables): void {
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldSummary($variables);
    FieldMapper::nodeFieldLink($variables);
    FieldMapper::nodeFieldTopics($variables);
    FieldMapper::paragraphFieldTheme($variables);
  }

  /**
   * Implements template_preprocess_paragraph() for civictheme_webform.
   */
  #[Hook('preprocess_paragraph__civictheme_webform')]
  public function webform(array &$variables): void {
    FieldMapper::paragraphFieldTheme($variables);
    FieldMapper::paragraphFieldVerticalSpacing($variables);
    FieldMapper::paragraphFieldBackground($variables);

    $paragraph = $variables['paragraph'] ?? NULL;
    if ($paragraph && CivicthemeFieldHelper::hasValue($paragraph, 'field_c_p_webform')) {
      $variables['referenced_webform'] = $paragraph->get('field_c_p_webform')->view();
    }
  }

  /**
   * Get the theme config manager.
   */
  protected function configManager(): CivicthemeConfigManager {
    /** @var \Drupal\civictheme\CivicthemeConfigManager $theme_config_manager */
    $theme_config_manager = \Drupal::classResolver(CivicthemeConfigManager::class);

    return $theme_config_manager;
  }

}
