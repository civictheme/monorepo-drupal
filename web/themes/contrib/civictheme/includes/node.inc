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

  $bundle = str_replace('_', '-', $node->bundle());
  $view_mode = str_replace('_', '-', $variables['view_mode'] ?? '');

  $variables['attributes']['class'][] = 'node';
  $variables['attributes']['class'][] = 'node--type-' . $bundle;
  if ($node->isPromoted()) {
    $variables['attributes']['class'][] = 'node--promoted';
  }
  if ($node->isSticky()) {
    $variables['attributes']['class'][] = 'node--sticky';
  }
  if (!$node->isPublished()) {
    $variables['attributes']['class'][] = 'node--unpublished';
  }
  if ($view_mode) {
    $variables['attributes']['class'][] = 'node--view-mode-' . $view_mode;
  }
}

/**
 * Implements hook_preprocess_node__BUNDLE() for civictheme_page.
 */
function civictheme_preprocess_node__civictheme_page(array &$variables): void {
  $view_mode = $variables['elements']['#view_mode'] ?? NULL;
  if (!in_array($view_mode, ['full', 'default'])) {
    return;
  }

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
 * Implements hook_preprocess_node__BUNDLE() for civictheme_alert.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_node__civictheme_alert(array &$variables): void {
  $view_mode = $variables['elements']['#view_mode'] ?? NULL;
  if (!in_array($view_mode, ['full', 'default'])) {
    return;
  }

  $node = $variables['node'] ?? NULL;
  if (!$node instanceof NodeInterface) {
    return;
  }

  $modifier_class = civictheme_extract_modifier_class($variables);
  $title = strip_tags(Xss::filter((string) $node->label()));

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:alert',
    '#props' => array_filter([
      'id' => $node->id(),
      'type' => civictheme_get_field_value($node, 'field_c_n_alert_type', default: 'information'),
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
      // Force dark theme to make sure that text colour has enough contrast
      // with a background.
      'description' => civictheme_get_wysiwyg_field_value($node, 'field_c_n_body', TRUE, CivicthemeConstants::THEME_DARK),
    ]),
  ];
}

/**
 * Get the validated component theme from a node.
 *
 * Returns the node's component_theme if it is a valid SDC theme value
 * ('light' or 'dark'), otherwise falls back to 'light'. The
 * component_theme property is set by the views grid preprocessor and may
 * contain values like 'inherit' which are not valid for SDC components.
 *
 * @see civictheme_preprocess_views_view_grid()
 */
function _civictheme_node_get_component_theme(NodeInterface $node): string {
  $theme = $node->component_theme ?? CivicthemeConstants::THEME_LIGHT;

  return in_array($theme, [CivicthemeConstants::THEME_LIGHT, CivicthemeConstants::THEME_DARK], TRUE)
    ? $theme
    : CivicthemeConstants::THEME_LIGHT;
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
