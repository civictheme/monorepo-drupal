<?php

/**
 * @file
 * Card theme alterations.
 *
 * The per-field processing functions were extracted into helpers in order to
 * preserve data consistency when preprocessing the same fields within different
 * cards.
 * This also avoids "hidden" processing and helps to navigate to the helper
 * within IDE.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Unicode;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\media\MediaInterface;
use Drupal\node\NodeInterface;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_promo_card(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $image = NULL;
  $media = civictheme_get_field_value($paragraph, 'field_c_p_image', TRUE, build: $variables);
  if ($media) {
    $image = civictheme_media_image_get_variables($media, CivicthemeConstants::PROMO_CARD_IMAGE_STYLE);
  }

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

  $tags = array_map(
    static fn($label) => ['content' => (string) $label],
    civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics', build: $variables)
  );

  $date = civictheme_get_field_value($paragraph, 'field_c_p_date', TRUE);
  $date_props = [];
  if ($date) {
    $date_props['date'] = civictheme_format_datetime($date->get('value')->getDateTime(), 'civictheme_short_date');
    $date_props['date_iso'] = civictheme_format_datetime_iso($date->get('value')->getDateTime());
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:promo-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'link' => $link,
      'tags' => $tags ?: NULL,
      'subtitle' => civictheme_get_field_value($paragraph, 'field_c_p_subtitle', TRUE),
      'modifier_class' => $modifier_class,
    ] + $date_props, fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
      'summary' => _civictheme_build_summary_slot($paragraph, 'field_c_p_summary', $paragraph->bundle()),
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_promo_card_ref(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  /** @var \Drupal\node\NodeInterface|null $node */
  $node = civictheme_get_field_value($paragraph, 'field_c_p_reference', TRUE, build: $variables);

  $image = NULL;
  if ($node) {
    $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE, build: $variables);
    if ($media instanceof MediaInterface && $media->getSource()->getPluginId() === 'image') {
      $image = civictheme_media_image_get_variables($media, CivicthemeConstants::PROMO_CARD_IMAGE_STYLE);
    }
  }

  $link = NULL;
  $tags = [];
  $title = NULL;
  $summary_slot = NULL;
  if ($node) {
    $link = [
      'text' => $node->getTitle(),
      'url' => $node->toUrl()->toString(),
      'is_new_window' => FALSE,
      'is_external' => FALSE,
    ];
    $title = $node->getTitle();
    $labels = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics', $variables);
    $tags = array_map(static fn($label) => ['content' => (string) $label], $labels);
    $summary_slot = _civictheme_build_summary_slot_from_node($node, 'field_c_n_summary', $paragraph->bundle());
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:promo-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'link' => $link,
      'tags' => $tags ?: NULL,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
      'summary' => $summary_slot,
    ]),
  ];
}

/**
 * Implements hook_preprocess_node__VIEW_MODE() for civictheme_promo_card.
 *
 * For Event nodes, swaps to the event-card component since it is not possible
 * to switch the view mode based on result type in views.
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function civictheme_preprocess_node__civictheme_promo_card(array &$variables): void {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  // @see civictheme_preprocess_views_view_grid()
  $theme = _civictheme_node_get_component_theme($node);
  $modifier_class = civictheme_extract_modifier_class($variables);

  $image = NULL;
  $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE, build: $variables);
  if ($media instanceof MediaInterface && $media->getSource()->getPluginId() === 'image') {
    $image = civictheme_media_image_get_variables($media, CivicthemeConstants::PROMO_CARD_IMAGE_STYLE);
  }

  $link = [
    'text' => $node->getTitle(),
    'url' => $node->toUrl()->toString(),
    'is_new_window' => FALSE,
    'is_external' => FALSE,
  ];

  $labels = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics', $variables);
  $tags = array_map(static fn($label) => ['content' => (string) $label], $labels);

  $title = $node->getTitle();

  // Special handling for Event nodes: swap Promo card with Event card
  // component. For nodes rendered in views, it is not possible to switch the
  // view mode based on the result type in the theme layer.
  if ($node->bundle() == 'civictheme_event') {
    $date_props = [];
    $date = civictheme_get_field_value($node, 'field_c_n_date_range', TRUE);
    if ($date) {
      $date_props['date'] = civictheme_format_datetime($date->get('value')->getDateTime());
      $date_props['date_iso'] = civictheme_format_datetime_iso($date->get('value')->getDateTime());
      if ($date->get('end_value')->getDateTime()) {
        $date_props['date_end'] = civictheme_format_datetime($date->get('end_value')->getDateTime());
        $date_props['date_end_iso'] = civictheme_format_datetime_iso($date->get('end_value')->getDateTime());
      }
    }

    $location_slot = NULL;
    /** @var \Drupal\paragraphs\Entity\Paragraph[] $locations */
    $locations = civictheme_get_field_value($node, 'field_c_n_location', build: $variables) ?? [];
    foreach ($locations as $location) {
      $address = civictheme_get_field_value($location, 'field_c_p_address', TRUE);
      if (!empty($address)) {
        $location_slot = ['#markup' => $address];
        break;
      }
    }

    $variables['content'] = [
      '#type' => 'component',
      '#component' => 'civictheme:event-card',
      '#props' => array_filter([
        'theme' => $theme,
        'image' => $image,
        'link' => $link,
        'tags' => $tags ?: NULL,
        'modifier_class' => $modifier_class,
      ] + $date_props, fn ($v) => $v !== NULL),
      '#slots' => array_filter([
        'title' => $title ? ['#markup' => $title] : NULL,
        'summary' => _civictheme_build_summary_slot_from_node($node, 'field_c_n_summary', 'civictheme_event_card'),
        'location' => $location_slot,
      ]),
    ];
  }
  else {
    $date_props = [
      'date' => civictheme_format_datetime($node->getChangedTime(), 'civictheme_short_date'),
      'date_iso' => civictheme_format_datetime_iso($node->getChangedTime()),
    ];

    $variables['content'] = [
      '#type' => 'component',
      '#component' => 'civictheme:promo-card',
      '#props' => array_filter([
        'theme' => $theme,
        'image' => $image,
        'link' => $link,
        'tags' => $tags ?: NULL,
        'modifier_class' => $modifier_class,
      ] + $date_props, fn ($v) => $v !== NULL),
      '#slots' => array_filter([
        'title' => $title ? ['#markup' => $title] : NULL,
        'summary' => _civictheme_build_summary_slot_from_node($node, 'field_c_n_summary', 'civictheme_promo_card'),
      ]),
    ];
  }
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_event_card(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $image = NULL;
  $media = civictheme_get_field_value($paragraph, 'field_c_p_image', TRUE, build: $variables);
  if ($media) {
    $image = civictheme_media_image_get_variables($media, CivicthemeConstants::EVENT_CARD_IMAGE_STYLE);
  }

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

  $tags = array_map(
    static fn($label) => ['content' => (string) $label],
    civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics', build: $variables)
  );

  $date_props = [];
  $date = civictheme_get_field_value($paragraph, 'field_c_p_date_range', TRUE);
  if ($date) {
    $date_props['date'] = civictheme_format_datetime($date->get('value')->getDateTime());
    $date_props['date_iso'] = civictheme_format_datetime_iso($date->get('value')->getDateTime());
    if ($date->get('end_value')->getDateTime()) {
      $date_props['date_end'] = civictheme_format_datetime($date->get('end_value')->getDateTime());
      $date_props['date_end_iso'] = civictheme_format_datetime_iso($date->get('end_value')->getDateTime());
    }
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:event-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'link' => $link,
      'tags' => $tags ?: NULL,
      'modifier_class' => $modifier_class,
    ] + $date_props, fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
      'summary' => _civictheme_build_summary_slot($paragraph, 'field_c_p_summary', $paragraph->bundle()),
      'location' => civictheme_get_field_value($paragraph, 'field_c_p_location', TRUE) ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_location', TRUE)] : NULL,
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function civictheme_preprocess_paragraph__civictheme_event_card_ref(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  /** @var \Drupal\node\NodeInterface|null $node */
  $node = civictheme_get_field_value($paragraph, 'field_c_p_reference', TRUE, build: $variables);

  $image = NULL;
  if ($node) {
    $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE, build: $variables);
    if ($media instanceof MediaInterface && $media->getSource()->getPluginId() === 'image') {
      $image = civictheme_media_image_get_variables($media, CivicthemeConstants::EVENT_CARD_IMAGE_STYLE);
    }
  }

  $link = NULL;
  $tags = [];
  $title = NULL;
  $summary_slot = NULL;
  $location_slot = NULL;
  $date_props = [];
  if ($node) {
    $link = [
      'text' => $node->getTitle(),
      'url' => $node->toUrl()->toString(),
      'is_new_window' => FALSE,
      'is_external' => FALSE,
    ];
    $title = $node->getTitle();
    $labels = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics', $variables);
    $tags = array_map(static fn($label) => ['content' => (string) $label], $labels);
    $summary_slot = _civictheme_build_summary_slot_from_node($node, 'field_c_n_summary', $paragraph->bundle());

    $date = civictheme_get_field_value($node, 'field_c_n_date_range', TRUE);
    if ($date) {
      $date_props['date'] = civictheme_format_datetime($date->get('value')->getDateTime());
      $date_props['date_iso'] = civictheme_format_datetime_iso($date->get('value')->getDateTime());
      if ($date->get('end_value')->getDateTime()) {
        $date_props['date_end'] = civictheme_format_datetime($date->get('end_value')->getDateTime());
        $date_props['date_end_iso'] = civictheme_format_datetime_iso($date->get('end_value')->getDateTime());
      }
    }

    // Extract location from the node's location paragraphs.
    /** @var \Drupal\paragraphs\Entity\Paragraph[] $locations */
    $locations = civictheme_get_field_value($node, 'field_c_n_location', build: $variables) ?? [];
    foreach ($locations as $location) {
      $address = civictheme_get_field_value($location, 'field_c_p_address', TRUE);
      if (!empty($address)) {
        $location_slot = ['#markup' => $address];
        break;
      }
    }
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:event-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'link' => $link,
      'tags' => $tags ?: NULL,
      'modifier_class' => $modifier_class,
    ] + $date_props, fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
      'summary' => $summary_slot,
      'location' => $location_slot,
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_navigation_card(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $image = NULL;
  $media = civictheme_get_field_value($paragraph, 'field_c_p_image', TRUE, build: $variables);
  if ($media) {
    $image = civictheme_media_image_get_variables($media, CivicthemeConstants::NAVIGATION_CARD_IMAGE_STYLE);
  }

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

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:navigation-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'link' => $link,
      'image_as_icon' => civictheme_get_field_value($paragraph, 'field_c_p_show_image_as_icon') ?? FALSE,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
      'summary' => _civictheme_build_summary_slot($paragraph, 'field_c_p_summary', $paragraph->bundle()),
    ]),
  ];
}

/**
 * Implements hook_preprocess_node__VIEW_MODE() for civictheme_navigation_card.
 */
function civictheme_preprocess_node__civictheme_navigation_card(array &$variables): void {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  // @see civictheme_preprocess_views_view_grid()
  $theme = _civictheme_node_get_component_theme($node);
  $modifier_class = civictheme_extract_modifier_class($variables);

  $image = NULL;
  $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE, build: $variables);
  if ($media instanceof MediaInterface && $media->getSource()->getPluginId() === 'image') {
    $image = civictheme_media_image_get_variables($media, CivicthemeConstants::NAVIGATION_CARD_IMAGE_STYLE);
  }

  $link = [
    'text' => $node->getTitle(),
    'url' => $node->toUrl()->toString(),
    'is_new_window' => FALSE,
    'is_external' => FALSE,
  ];

  $title = $node->getTitle();

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:navigation-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'link' => $link,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
      'summary' => _civictheme_build_summary_slot_from_node($node, 'field_c_n_summary', 'civictheme_navigation_card'),
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_navigation_card_ref(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  /** @var \Drupal\node\NodeInterface|null $node */
  $node = civictheme_get_field_value($paragraph, 'field_c_p_reference', TRUE, build: $variables);

  $image = NULL;
  if ($node) {
    $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE, build: $variables);
    if ($media instanceof MediaInterface && $media->getSource()->getPluginId() === 'image') {
      $image = civictheme_media_image_get_variables($media, CivicthemeConstants::NAVIGATION_CARD_IMAGE_STYLE);
    }
  }

  $link = NULL;
  $title = NULL;
  $summary_slot = NULL;
  if ($node) {
    $link = [
      'text' => $node->getTitle(),
      'url' => $node->toUrl()->toString(),
      'is_new_window' => FALSE,
      'is_external' => FALSE,
    ];
    $title = $node->getTitle();
    $summary_slot = _civictheme_build_summary_slot_from_node($node, 'field_c_n_summary', $paragraph->bundle());
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:navigation-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'link' => $link,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
      'summary' => $summary_slot,
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_publication_card(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $image = NULL;
  $media = civictheme_get_field_value($paragraph, 'field_c_p_image', TRUE, build: $variables);
  if ($media) {
    $image = civictheme_media_image_get_variables($media, CivicthemeConstants::PUBLICATION_CARD_IMAGE_STYLE);
  }

  $file = NULL;
  $document = civictheme_get_field_value($paragraph, 'field_c_p_document', TRUE, build: $variables);
  if ($document) {
    $vars = civictheme_media_get_variables($document);
    if ($vars) {
      if (civictheme_get_theme_config_manager()->load('components.publication_card.use_media_name', TRUE)) {
        $vars['name'] = $vars['media_name'];
      }
      $file = $vars;
    }
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:publication-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'file' => $file,
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title'),
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'summary' => _civictheme_build_summary_slot($paragraph, 'field_c_p_summary', $paragraph->bundle()),
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_subject_card(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $image = NULL;
  $media = civictheme_get_field_value($paragraph, 'field_c_p_image', TRUE, build: $variables);
  if ($media) {
    $image = civictheme_media_image_get_variables($media, CivicthemeConstants::SUBJECT_CARD_IMAGE_STYLE);
  }

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

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:subject-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'link' => $link,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
    ]),
  ];
}

/**
 * Implements hook_preprocess_node__VIEW_MODE() for civictheme_subject_card.
 */
function civictheme_preprocess_node__civictheme_subject_card(array &$variables): void {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  // @see civictheme_preprocess_views_view_grid()
  $theme = _civictheme_node_get_component_theme($node);
  $modifier_class = civictheme_extract_modifier_class($variables);

  $image = NULL;
  $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE, build: $variables);
  if ($media instanceof MediaInterface && $media->getSource()->getPluginId() === 'image') {
    $image = civictheme_media_image_get_variables($media, CivicthemeConstants::SUBJECT_CARD_IMAGE_STYLE);
  }

  $link = [
    'text' => $node->getTitle(),
    'url' => $node->toUrl()->toString(),
    'is_new_window' => FALSE,
    'is_external' => FALSE,
  ];

  $title = $node->getTitle();

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:subject-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'link' => $link,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_subject_card_ref(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  /** @var \Drupal\node\NodeInterface|null $node */
  $node = civictheme_get_field_value($paragraph, 'field_c_p_reference', TRUE, build: $variables);

  $image = NULL;
  if ($node) {
    $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE, build: $variables);
    if ($media instanceof MediaInterface && $media->getSource()->getPluginId() === 'image') {
      $image = civictheme_media_image_get_variables($media, CivicthemeConstants::SUBJECT_CARD_IMAGE_STYLE);
    }
  }

  $link = NULL;
  $title = NULL;
  if ($node) {
    $link = [
      'text' => $node->getTitle(),
      'url' => $node->toUrl()->toString(),
      'is_new_window' => FALSE,
      'is_external' => FALSE,
    ];
    $title = $node->getTitle();
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:subject-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'link' => $link,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_service_card(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $links = [];
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem[]|null $link_items */
  $link_items = civictheme_get_field_value($paragraph, 'field_c_p_links');
  if ($link_items) {
    foreach ($link_items as $link) {
      $links[] = [
        'text' => Xss::filter($link->get('title')->getString()),
        'url' => $link->getUrl()->toString(),
        'is_new_window' => $link->isExternal(),
        'is_external' => $link->isExternal(),
      ];
    }
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:service-card',
    '#props' => array_filter([
      'theme' => $theme,
      'links' => $links ?: NULL,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_fast_fact_card(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $image = NULL;
  $media = civictheme_get_field_value($paragraph, 'field_c_p_icon', TRUE);
  if ($media) {
    $image = civictheme_media_image_get_variables($media);
  }

  $link = NULL;
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link_item */
  $link_item = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  if ($link_item !== NULL) {
    $link = [
      'url' => $link_item->getUrl()->toString(),
      'is_new_window' => $link_item->isExternal(),
    ];
  }

  // Fast fact card uses title and summary as props, not slots.
  $summary = civictheme_get_field_value($paragraph, 'field_c_p_summary', TRUE);
  $summary_text = NULL;
  if (!empty($summary)) {
    $summary_text = _civictheme_truncate_summary($summary, $paragraph->bundle());
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:fast-fact-card',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title'),
      'summary' => $summary_text,
      'link' => $link,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
  ];
}

/**
 * Build a summary slot value from a paragraph field.
 *
 * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
 *   The entity containing the summary field.
 * @param string $field_name
 *   The field name to extract the summary from.
 * @param string $bundle
 *   The bundle name for configurable summary length.
 *
 * @return array|null
 *   A render array for the summary slot, or NULL.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_build_summary_slot(FieldableEntityInterface $entity, string $field_name, string $bundle): ?array {
  $summary = civictheme_get_field_value($entity, $field_name, TRUE);
  if (empty($summary)) {
    return NULL;
  }

  $summary_text = _civictheme_truncate_summary($summary, $bundle);

  return $summary_text ? ['#markup' => $summary_text] : NULL;
}

/**
 * Build a summary slot value from a referenced node.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The referenced node.
 * @param string $field_name
 *   The field name to extract the summary from.
 * @param string $bundle
 *   The bundle name for configurable summary length.
 *
 * @return array|null
 *   A render array for the summary slot, or NULL.
 */
function _civictheme_build_summary_slot_from_node(NodeInterface $node, string $field_name, string $bundle): ?array {
  return _civictheme_build_summary_slot($node, $field_name, $bundle);
}

/**
 * Truncate summary text according to component configuration.
 *
 * @param string $summary
 *   The raw summary text.
 * @param string $bundle
 *   The bundle name for configurable summary length lookup.
 *
 * @return string|null
 *   The truncated summary text, or NULL if empty.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_truncate_summary(string $summary, string $bundle): ?string {
  $length = CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH;

  $component_name = _civictheme_get_configurable_component_name_from_bundle($bundle);
  if ($component_name) {
    $length = civictheme_get_theme_config_manager()->loadForComponent($component_name, 'summary_length', CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH);
  }

  $summary_trimmed = text_summary($summary);

  if (!_civictheme_feature_is_optedout('process', CivicthemeConstants::OPTOUT_SUMMARY_HIDE_ELLIPSIS)) {
    $summary_trimmed = Unicode::truncate($summary_trimmed, $length, TRUE, TRUE);
  }

  return !empty($summary_trimmed) ? $summary_trimmed : NULL;
}
