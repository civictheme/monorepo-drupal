<?php

/**
 * @file
 * Node related functions.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Xss;
use Drupal\node\NodeInterface;

/**
 * Generic pre-process for all view mode of nodes.
 */
function _civictheme_preprocess_node(array &$variables): void {
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
 * Implements hook_preprocess_node__BUNDLE__VIEW_MODE() for civictheme_page full.
 */
function civictheme_preprocess_node__civictheme_page__full(array &$variables): void {
  $node = $variables['node'] ?? NULL;
  if (!$node instanceof NodeInterface) {
    return;
  }

  // Do not display node title on revision pages.
  unset($variables['label']);

  // Add Table of Contents if the value is set on node.
  if (civictheme_get_field_value($node, 'field_c_n_show_toc')) {
    array_unshift($variables['content'], [
      '#theme' => 'civictheme_table_of_contents',
      '#position' => 'prepend',
      '#anchor_selector' => 'h2',
      '#title' => t('On this page'),
      '#scope_selector' => '.ct-layout__main',
    ]);
  }

  // Add Tags if hide tags is not set on node.
  if (!civictheme_get_field_value($node, 'field_c_n_hide_tags')) {
    $topics = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics', $variables);
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
 * Implements hook_preprocess_node__VIEW_MODE() for full view mode.
 */
function civictheme_preprocess_node__full(array &$variables): void {
}

/**
 * Implements hook_preprocess_node__VIEW_MODE() for default view mode.
 *
 * Falls back to the full view mode preprocessing.
 */
function civictheme_preprocess_node__default(array &$variables): void {
  civictheme_preprocess_node__full($variables);
}

/**
 * Implements hook_preprocess_node__BUNDLE__VIEW_MODE() for civictheme_alert full.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_node__civictheme_alert__full(array &$variables): void {
  $node = $variables['node'] ?? NULL;
  if (!$node instanceof NodeInterface) {
    return;
  }

  $variables['id'] = $node->id();
  $variables['title'] = Xss::filter((string) $node->label());
  $variables['title'] = strip_tags($variables['title']);
  // Force dark theme to make sure that text colour has enough contrast
  // with a background.
  $variables['description'] = civictheme_get_wysiwyg_field_value($node, 'field_c_n_body', TRUE, CivicthemeConstants::THEME_DARK);
  $variables['type'] = civictheme_get_field_value($node, 'field_c_n_alert_type', default: 'information');
}

/**
 * Determine last updated date for a page.
 */
function _civictheme_node_get_updated_date(NodeInterface $node, string $format = 'civictheme_short_date'): ?string {
  $show_last_updated = civictheme_get_field_value($node, 'field_c_n_show_last_updated');
  $custom_updated_value = civictheme_get_field_value($node, 'field_c_n_custom_last_updated', TRUE);

  $date = NULL;

  if ($show_last_updated && !empty($custom_updated_value)) {
    $date = civictheme_format_datetime($custom_updated_value->get('value')->getDateTime(), $format);
  }
  elseif ($show_last_updated) {
    $date = civictheme_format_datetime($node->getChangedTime(), $format);
  }

  return $date;
}
