<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook\Preprocess;

use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\Theme\HtmlContentProcessor;
use Drupal\civictheme\Theme\Paragraph\FieldMapper;
use Drupal\civictheme\Utility\CivicthemeFieldHelper;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;

/**
 * Node preprocess hook implementations for CivicTheme.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class NodeHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_preprocess_HOOK() for node.
   */
  #[Hook('preprocess_node')]
  public function preprocessNode(array &$variables): void {
    // All nodes.
    $this->preprocessNodeGeneric($variables);

    // Preprocessing for each node view mode.
    $view_mode = $variables['elements']['#view_mode'] ?? NULL;
    if ($view_mode) {
      $handlers = $this->viewModeHandlers();
      if (isset($handlers[$view_mode])) {
        $this->{$handlers[$view_mode]}($variables);
        return;
      }

      // Allow sub-themes to provide procedural view mode preprocessors using
      // the legacy naming convention.
      $preprocessor_function = '_civictheme_preprocess_node__' . $view_mode;
      if (function_exists($preprocessor_function)) {
        $preprocessor_function($variables);
      }
    }
  }

  /**
   * Map of view modes to preprocess method names.
   *
   * @return array<string, string>
   *   Map of view mode to method name.
   */
  protected function viewModeHandlers(): array {
    return [
      'full' => 'preprocessNodeFull',
      'default' => 'preprocessNodeDefault',
      'civictheme_promo_card' => 'preprocessNodePromoCard',
      'civictheme_navigation_card' => 'preprocessNodeNavigationCard',
      'civictheme_subject_card' => 'preprocessNodeSubjectCard',
      'civictheme_snippet' => 'preprocessNodeSnippet',
    ];
  }

  /**
   * Map of node types to full view mode preprocess method names.
   *
   * @return array<string, string>
   *   Map of node type to method name.
   */
  protected function nodeTypeFullHandlers(): array {
    return [
      'civictheme_page' => 'preprocessNodeCivicthemePageFull',
      'civictheme_alert' => 'preprocessNodeCivicthemeAlertFull',
    ];
  }

  /**
   * Generic pre-process for all view mode of nodes.
   */
  protected function preprocessNodeGeneric(array &$variables): void {
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
   * Pre-process Full view mode of nodes.
   */
  protected function preprocessNodeFull(array &$variables): void {
    $node = $variables['node'] ?? NULL;
    if (!$node instanceof NodeInterface) {
      return;
    }

    $this->preprocessNodeTypeFull($variables, $node->getType());
  }

  /**
   * Default view mode fallback preprocessor for nodes.
   */
  protected function preprocessNodeDefault(array &$variables): void {
    $node = $variables['node'] ?? NULL;
    if (!$node instanceof NodeInterface) {
      return;
    }

    $this->preprocessNodeTypeFull($variables, $node->getType());
  }

  /**
   * Dispatch the full view mode preprocessing for a node type.
   */
  protected function preprocessNodeTypeFull(array &$variables, string $type): void {
    $handlers = $this->nodeTypeFullHandlers();
    if (isset($handlers[$type])) {
      $this->{$handlers[$type]}($variables);
      return;
    }

    // Allow sub-themes to provide procedural node type preprocessors using
    // the legacy naming convention.
    $type_callback = '_civictheme_preprocess_node__' . $type . '__full';
    if (function_exists($type_callback)) {
      $type_callback($variables);
    }
  }

  /**
   * Pre-process CivicTheme page nodes.
   */
  protected function preprocessNodeCivicthemePageFull(array &$variables): void {
    $node = $variables['node'] ?? NULL;
    if (!$node instanceof NodeInterface) {
      return;
    }

    // Do not display node title on revision pages.
    unset($variables['label']);

    // Add Table of Contents if the value is set on node.
    if (CivicthemeFieldHelper::getValue($node, 'field_c_n_show_toc')) {
      array_unshift($variables['content'], [
        '#theme' => 'civictheme_table_of_contents',
        '#position' => 'prepend',
        '#anchor_selector' => 'h2',
        '#title' => $this->t('On this page'),
        '#scope_selector' => '.ct-layout__main',
      ]);
    }

    // Add Tags if hide tags is not set on node.
    if (!CivicthemeFieldHelper::getValue($node, 'field_c_n_hide_tags')) {
      $topics = CivicthemeFieldHelper::getReferencedEntityLabels($node, 'field_c_n_topics', $variables);
      if (!empty($topics)) {
        $tags = array_map(static fn($label) => ['content' => (string) $label], $topics);
        $variables['content'][] = [
          '#theme' => 'civictheme_tag_list',
          '#tags' => $tags,
          '#vertical_spacing' => 'both',
        ];
      }
    }
  }

  /**
   * Pre-process CivicTheme Alert nodes.
   */
  protected function preprocessNodeCivicthemeAlertFull(array &$variables): void {
    $node = $variables['node'] ?? NULL;
    if (!$node instanceof NodeInterface) {
      return;
    }

    $variables['id'] = $node->id();
    $variables['title'] = Xss::filter((string) $node->label());
    $variables['title'] = strip_tags($variables['title']);
    $body_values = CivicthemeFieldHelper::getValue($node, 'field_c_n_body');
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
    $variables['type'] = CivicthemeFieldHelper::getValue($node, 'field_c_n_alert_type', default: 'information');
  }

  /**
   * Pre-process for the Promo card view mode of nodes.
   *
   * @SuppressWarnings(PHPMD.ElseExpression)
   */
  protected function preprocessNodePromoCard(array &$variables): void {
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
   * Pre-process for the Navigation card view mode of nodes.
   */
  protected function preprocessNodeNavigationCard(array &$variables): void {
    FieldMapper::nodeFieldImage($variables, CivicthemeConstants::NAVIGATION_CARD_IMAGE_STYLE);
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldSummary($variables, 'civictheme_navigation_card');
    FieldMapper::nodeFieldLink($variables);
    // @see \Drupal\civictheme\Hook\Preprocess\ViewsHooks::preprocessViewsViewGrid()
    $variables['theme'] = $variables['node']->component_theme ?? CivicthemeConstants::THEME_LIGHT;
  }

  /**
   * Pre-process for the Subject card view mode of nodes.
   */
  protected function preprocessNodeSubjectCard(array &$variables): void {
    FieldMapper::nodeFieldImage($variables, CivicthemeConstants::SUBJECT_CARD_IMAGE_STYLE);
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldLink($variables);
    // @see \Drupal\civictheme\Hook\Preprocess\ViewsHooks::preprocessViewsViewGrid()
    $variables['theme'] = $variables['node']->component_theme ?? CivicthemeConstants::THEME_LIGHT;
  }

  /**
   * Pre-process for the Snippet view mode of nodes.
   */
  protected function preprocessNodeSnippet(array &$variables): void {
    FieldMapper::nodeFieldTitle($variables);
    FieldMapper::nodeFieldSummary($variables, $variables['view_mode'] ?? 'civictheme_snippet');
    FieldMapper::nodeFieldLink($variables);
    FieldMapper::nodeFieldTopics($variables);
    // @see \Drupal\civictheme\Hook\Preprocess\ViewsHooks::preprocessViewsViewGrid()
    $variables['theme'] = $variables['node']->component_theme ?? CivicthemeConstants::THEME_LIGHT;
  }

}
