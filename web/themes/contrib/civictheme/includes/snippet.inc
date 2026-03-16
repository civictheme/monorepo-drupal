<?php

/**
 * @file
 * Snippet theme alterations.
 */

declare(strict_types=1);

use Drupal\Component\Utility\Xss;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_snippet(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $link = NULL;
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link_item */
  $link_item = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  if ($link_item !== NULL) {
    $link = [
      'text' => html_entity_decode(Xss::filter($link_item->get('title')->getString())),
      'url' => $link_item->getUrl()->toString(),
      'is_new_window' => $link_item->isExternal(),
      'is_external' => $link_item->isExternal(),
    ];
  }

  $tags = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics', build: $variables);
  $tags_slot = NULL;
  if (!empty($tags)) {
    $tags_slot = ['#markup' => implode(', ', array_map(fn($label) => (string) $label, $tags))];
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:snippet',
    '#props' => array_filter([
      'theme' => $theme,
      'link' => $link,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
      'summary' => _civictheme_build_summary_slot($paragraph, 'field_c_p_summary', $paragraph->bundle()),
      'tags' => $tags_slot,
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_snippet_ref(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  /** @var \Drupal\node\NodeInterface|null $node */
  $node = civictheme_get_field_value($paragraph, 'field_c_p_reference', TRUE, build: $variables);

  $title = NULL;
  $link = NULL;
  $tags_slot = NULL;
  $summary_slot = NULL;
  if ($node) {
    $title = $node->getTitle();
    $link = [
      'text' => $node->getTitle(),
      'url' => $node->toUrl()->toString(),
      'is_new_window' => FALSE,
      'is_external' => FALSE,
    ];
    $labels = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics', $variables);
    if (!empty($labels)) {
      $tags_slot = ['#markup' => implode(', ', array_map(fn($label) => (string) $label, $labels))];
    }
    $summary_slot = _civictheme_build_summary_slot_from_node($node, 'field_c_n_summary', $paragraph->bundle());
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:snippet',
    '#props' => array_filter([
      'theme' => $theme,
      'link' => $link,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
      'summary' => $summary_slot,
      'tags' => $tags_slot,
    ]),
  ];
}

/**
 * Implements hook_preprocess_node__VIEW_MODE() for civictheme_snippet.
 */
function civictheme_preprocess_node__civictheme_snippet(array &$variables): void {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  // @see civictheme_preprocess_views_view_grid()
  $theme = _civictheme_node_get_component_theme($node);
  $modifier_class = civictheme_extract_modifier_class($variables);

  $title = $node->getTitle();

  $link = [
    'text' => $node->getTitle(),
    'url' => $node->toUrl()->toString(),
    'is_new_window' => FALSE,
    'is_external' => FALSE,
  ];

  $labels = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics', $variables);
  $tags_slot = NULL;
  if (!empty($labels)) {
    $tags_slot = ['#markup' => implode(', ', array_map(fn($label) => (string) $label, $labels))];
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:snippet',
    '#props' => array_filter([
      'theme' => $theme,
      'link' => $link,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
      'summary' => _civictheme_build_summary_slot_from_node($node, 'field_c_n_summary', $variables['view_mode'] ?? 'civictheme_snippet'),
      'tags' => $tags_slot,
    ]),
  ];
}
