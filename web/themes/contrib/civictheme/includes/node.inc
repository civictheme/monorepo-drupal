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
 * Pre-process CivicTheme page nodes.
 */
function _civictheme_preprocess_node__civictheme_page__full(array &$variables): void {
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
      $variables['content'][] = [
        '#theme' => 'civictheme_tag_list',
        '#tags' => $topics,
        '#vertical_spacing' => 'both',
      ];
    }
  }
}

/**
 * Pre-process Full view mode of nodes.
 */
function _civictheme_preprocess_node__full(array &$variables): void {
  $node = $variables['node'] ?? NULL;
  if (!$node instanceof NodeInterface) {
    return;
  }

  $type = $node->getType();
  $type_callback = '_civictheme_preprocess_node__' . $type . '__full';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
}

/**
 * Default view mode fallback preprocessor for nodes.
 */
function _civictheme_preprocess_node__default(array &$variables): void {
  $node = $variables['node'] ?? NULL;
  if (!$node instanceof NodeInterface) {
    return;
  }

  $type = $node->getType();
  $type_callback = '_civictheme_preprocess_node__' . $type . '__full';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
}

/**
 * Pre-process CivicTheme Alert nodes.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_node__civictheme_alert__full(array &$variables): void {
  $node = $variables['node'] ?? NULL;
  if (!$node instanceof NodeInterface) {
    return;
  }

  $variables['id'] = $node->id();
  $variables['title'] = Xss::filter((string) $node->label());
  $variables['title'] = strip_tags($variables['title']);
  $body_values = civictheme_get_field_value($node, 'field_c_n_body');
  $body = is_array($body_values) && !empty($body_values) ? $body_values[0] : NULL;
  if ($body !== NULL && !empty($body['#text']) && !empty($body['#format'])) {
    $text_format = $body['#format'];
    $variables['description'] = [
      '#type' => 'processed_text',
      '#text' => _civictheme_process__html_content($body['#text'], [
        // Force dark theme to make sure that text colour has enough contrast
        // with a background.
        'theme' => CivicthemeConstants::THEME_DARK,
      ], $text_format),
      '#format' => $text_format,
      '#langcode' => $body['#langcode'],
    ];
  }
  $variables['type'] = civictheme_get_field_value($node, 'field_c_n_alert_type');
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
