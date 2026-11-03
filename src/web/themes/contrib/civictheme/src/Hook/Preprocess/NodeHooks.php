<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook\Preprocess;

use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\Theme\HtmlContentProcessor;
use Drupal\civictheme\Theme\Paragraph\FieldMapper;
use Drupal\civictheme\Utility\CivicthemeField;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;

/**
 * Node preprocess hook implementations for CivicTheme.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class NodeHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_preprocess_HOOK() for node.
   */
  #[Hook('preprocess_node')]
  public function preprocessNode(array &$variables): void {
    $node = $variables['node'] ?? NULL;
    if (!$node instanceof NodeInterface) {
      return;
    }

    $variables['attributes']['class'][] = 'node';
    if (!$node->isPublished()) {
      $variables['attributes']['class'][] = 'node--unpublished';
    }
  }

  /**
   * Implements hook_preprocess_HOOK() for node__civictheme_page__full.
   *
   * Registered for both the 'full' and 'default' view modes. Deliberately not
   * implemented as a bundle-level node__civictheme_page hook: no bundle
   * template exists, and creating that registry entry would outrank the card
   * view mode entries in suggestion priority.
   */
  #[Hook('preprocess_node__civictheme_page__full')]
  #[Hook('preprocess_node__civictheme_page__default')]
  public function preprocessNodeCivicthemePage(array &$variables): void {
    $node = $variables['node'] ?? NULL;
    if (!$node instanceof NodeInterface) {
      return;
    }

    // Do not display node title on revision pages.
    unset($variables['label']);

    // Add Table of Contents if the value is set on node.
    if (CivicthemeField::getValue($node, 'field_c_n_show_toc')) {
      array_unshift($variables['content'], [
        '#theme' => 'civictheme_table_of_contents',
        '#position' => 'prepend',
        '#anchor_selector' => 'h2',
        '#title' => $this->t('On this page'),
        '#scope_selector' => '.ct-layout__main',
      ]);
    }

    // Add Tags if hide tags is not set on node.
    if (!CivicthemeField::getValue($node, 'field_c_n_hide_tags')) {
      $topics = CivicthemeField::getReferencedEntityLabels($node, 'field_c_n_topics', $variables);
      if (!empty($topics)) {
        $tags = array_map(static fn($label): array => ['content' => (string) $label], $topics);
        $variables['content'][] = [
          '#theme' => 'civictheme_tag_list',
          '#tags' => $tags,
          '#vertical_spacing' => 'both',
        ];
      }
    }
  }

  /**
   * Implements hook_preprocess_HOOK() for node__civictheme_alert__full.
   *
   * Registered for both the 'full' and 'default' view modes.
   */
  #[Hook('preprocess_node__civictheme_alert__full')]
  #[Hook('preprocess_node__civictheme_alert__default')]
  public function preprocessNodeCivicthemeAlert(array &$variables): void {
    $node = $variables['node'] ?? NULL;
    if (!$node instanceof NodeInterface) {
      return;
    }

    $variables['id'] = $node->id();
    $variables['title'] = Xss::filter((string) $node->label());
    $variables['title'] = strip_tags($variables['title']);
    $body_values = CivicthemeField::getValue($node, 'field_c_n_body');
    $body = is_array($body_values) && !empty($body_values) ? $body_values[0] : NULL;
    if ($body !== NULL && !empty($body['#text']) && !empty($body['#format'])) {
      $text_format = $body['#format'];
      $variables['description'] = [
        '#type' => 'processed_text',
        '#text' => HtmlContentProcessor::processHtmlContent($body['#text'], [
          // Force dark theme to make sure that text colour has enough
          // contrast with a background.
          'theme' => CivicthemeConstants::THEME_DARK,
        ], $text_format),
        '#format' => $text_format,
        '#langcode' => $body['#langcode'],
      ];
    }
    $variables['type'] = CivicthemeField::getValue($node, 'field_c_n_alert_type', default: 'information');
  }

  /**
   * Implements hook_preprocess_HOOK() for node__civictheme_promo_card.
   *
   * @SuppressWarnings("PHPMD.ElseExpression")
   */
  #[Hook('preprocess_node__civictheme_promo_card')]
  public function preprocessNodePromoCard(array &$variables): void {
    /** @var \Drupal\node\NodeInterface $node */
    $node = $variables['node'];

    FieldMapper::nodeFieldImage($variables, CivicthemeConstants::PROMO_CARD_IMAGE_STYLE);
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldLink($variables);
    FieldMapper::nodeFieldTopics($variables);
    // @see \Drupal\civictheme\Hook\Preprocess\ViewsHooks::preprocessViewsViewGrid()
    $variables['theme'] = $variables['node']->component_theme ?? CivicthemeConstants::THEME_LIGHT;

    // Special handling for Event nodes as we are swapping the Promo card
    // with Event card FE component.
    //
    // For nodes rendered in views, it is not possible to switch the view
    // mode based on the result type in the theme layer (the hook does not
    // run). So we are swapping a FE component for Promo card view mode on
    // Event content type to 'Event card' FE component.
    //
    // @see node--civictheme-event--civictheme-promo-card.html.twig
    if ($node->bundle() == 'civictheme_event') {
      FieldMapper::nodeFieldDateRange($variables);
      FieldMapper::nodeFieldSummary($variables, 'civictheme_event_card');
    }
    else {
      FieldMapper::nodeFieldSummary($variables, 'civictheme_promo_card');
      FieldMapper::nodeFieldDate($variables);
    }
  }

  /**
   * Implements hook_preprocess_HOOK() for node__civictheme_navigation_card.
   */
  #[Hook('preprocess_node__civictheme_navigation_card')]
  public function preprocessNodeNavigationCard(array &$variables): void {
    FieldMapper::nodeFieldImage($variables, CivicthemeConstants::NAVIGATION_CARD_IMAGE_STYLE);
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldSummary($variables, 'civictheme_navigation_card');
    FieldMapper::nodeFieldLink($variables);
    // @see \Drupal\civictheme\Hook\Preprocess\ViewsHooks::preprocessViewsViewGrid()
    $variables['theme'] = $variables['node']->component_theme ?? CivicthemeConstants::THEME_LIGHT;
  }

  /**
   * Implements hook_preprocess_HOOK() for node__civictheme_subject_card.
   */
  #[Hook('preprocess_node__civictheme_subject_card')]
  public function preprocessNodeSubjectCard(array &$variables): void {
    FieldMapper::nodeFieldImage($variables, CivicthemeConstants::SUBJECT_CARD_IMAGE_STYLE);
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldLink($variables);
    // @see \Drupal\civictheme\Hook\Preprocess\ViewsHooks::preprocessViewsViewGrid()
    $variables['theme'] = $variables['node']->component_theme ?? CivicthemeConstants::THEME_LIGHT;
  }

  /**
   * Implements hook_preprocess_HOOK() for node__civictheme_snippet.
   */
  #[Hook('preprocess_node__civictheme_snippet')]
  public function preprocessNodeSnippet(array &$variables): void {
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldSummary($variables, $variables['view_mode'] ?? 'civictheme_snippet');
    FieldMapper::nodeFieldLink($variables);
    FieldMapper::nodeFieldTopics($variables);
    // @see \Drupal\civictheme\Hook\Preprocess\ViewsHooks::preprocessViewsViewGrid()
    $variables['theme'] = $variables['node']->component_theme ?? CivicthemeConstants::THEME_LIGHT;
  }

}
