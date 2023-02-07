<?php

/**
 * @file
 * Card theme alterations.
 */

use Drupal\civictheme\CivicthemeConstants;
use Drupal\file\Entity\File;


///**
// * Implements hook_theme_suggestions_HOOK_alter().
// */
//function civictheme_theme_suggestions_paragraph_alter(array &$suggestions, array $variables) {
//  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
//  $paragraph = $variables['elements']['#paragraph'];
//  $type = $paragraph->getType();
//
//  // Add a generic card theme suggestion for reference cards since the component
//  // is being handled via node preprocessing functions.
//  if (in_array($type, _civictheme_get_card_types()) && $paragraph->hasField('field_c_p_reference')) {
//    $suggestions[] = 'paragraph__civictheme_reference_card';
//  }
//}
//
///**
// * Generic setter of global variables for paragraphs as card renders.
// */
//function _civictheme_preprocess_paragraph__civictheme_card(&$variables) {
//  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
//  $paragraph = $variables['paragraph'];
//  $bundle = $paragraph->getType();
//  if (!in_array($bundle, _civictheme_get_card_types())) {
//    return;
//  }
//  // Cards that are generated via reference are handled separately.
//  if ($paragraph->hasField('field_c_p_reference')) {
//    _civictheme_preprocess_paragraph__reference_card($variables);
//
//    return;
//  }
//  // @todo documentation for the field api ie they must have certain fields in
//  // card fielded paragraph types.
//  _civictheme_preprocess_paragraph__card__icon($variables);
//  _civictheme_preprocess_paragraph__field__image($variables);
//
//}


/**
 * Gets CivicTheme card types.
 */
function _civictheme_get_card_types() {
  // @todo extend to allow for child theme - implement as theme setting.
  return [
    'civictheme_manual_list',
    'civictheme_event_card_ref',
    'civictheme_event_card',
    'civictheme_navigation_card_ref',
    'civictheme_navigation_card',
    'civictheme_promo_card',
    'civictheme_promo_card_ref',
    'civictheme_publication_card',
    'civictheme_subject_card_ref',
    'civictheme_subject_card',
    'civictheme_service_card',
  ];
}

//
///**
// * Preprocessing reference cards paragraphs.
// */
//function _civictheme_preprocess_paragraph__reference_card(&$variables) {
//  // Card reference paragraph types are handled on a generic template.
//  // Card type are handled at node level via view modes.
//  // @see civictheme_theme_suggestions_paragraph_alter()
//  $variables['reference_card'] = $variables['content']['field_c_p_reference'];
//}
//
//
///**
// * Implements template_preprocess_paragraph().
// */
//function civictheme_preprocess_paragraph__civictheme_event_card(&$variables) {
//  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
//  $paragraph = $variables['paragraph'] ?? NULL;
//  if (!$paragraph) {
//    return;
//  }
//
//  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
//  if ($link) {
//    $variables['url'] = $link->getUrl()->toString();
//  }
//
//  $date_range = civictheme_get_field_value($paragraph, 'field_c_p_date_range', TRUE);
//  if ($date_range) {
//    $variables['date'] = civictheme_format_date('civictheme_short_date', $date_range->get('value')->getDateTime(), $date_range->get('end_value')->getDateTime());
//  }
//
//  $variables['tags'] = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics');
//  $variables['location'] = civictheme_get_field_value($paragraph, 'field_c_p_location');
//}
//
///**
// * Implements template_preprocess_paragraph().
// */
//function civictheme_preprocess_paragraph__civictheme_navigation_card(&$variables) {
//  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
//  $paragraph = $variables['paragraph'];
//  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|NULL $link */
//  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
//  if ($link) {
//    $variables['url'] = $link->getUrl()->toString();
//    $variables['is_external'] = $link->isExternal();
//  }
//
//  // Show image as icon.
//  $variables['image_as_icon'] = civictheme_get_field_value($paragraph, 'field_c_p_show_image_as_icon') ?? FALSE;
//}


/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_promo_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'] ?? NULL;
  if (!$paragraph) {
    return;
  }


  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__content($variables);
  _civictheme_preprocess_paragraph__field__summary($variables);

  _civictheme_preprocess_paragraph__field__theme($variables);
  _civictheme_preprocess_paragraph__field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__field__background($variables);
  _civictheme_preprocess_paragraph__field__image($variables);

  if ($paragraph) {
    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
    $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
    if ($link) {
      $variables['url'] = $link->getUrl()->toString();
    }

    $variables['subtitle'] = civictheme_get_field_value($paragraph, 'field_c_p_subtitle', TRUE);
    $variables['tags'] = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics');
  }
}
//
///**
// * Implements template_preprocess_paragraph().
// *
// * @SuppressWarnings(PHPMD.StaticAccess)
// */
//function civictheme_preprocess_paragraph__civictheme_publication_card(&$variables) {
//  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
//  $paragraph = $variables['paragraph'];
//  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
//  if ($link) {
//    $variables['url'] = $link->getUrl()->toString();
//    $variables['is_external'] = $link->isExternal();
//  }
//
//  $document = civictheme_get_field_value($paragraph, 'field_c_p_document', TRUE);
//  if ($document) {
//    $fid = $document->getSource()->getSourceFieldValue($document);
//
//    $file = $fid ? File::load($fid) : NULL;
//    if (!empty($file)) {
//      $url = $file->createFileUrl(FALSE);
//      if ($url) {
//        $file_size = format_size($file->getSize());
//        $file_extension = pathinfo($file->getFileUri(), PATHINFO_EXTENSION);
//        $variables['link']['url'] = $url;
//        $variables['link']['text'] = sprintf('%s (%s, %s)', $file->label(), strtoupper($file_extension), strtoupper($file_size));
//        $variables['url'] = $url;
//      }
//    }
//  }
//}
//
//
///**
// * Implements template_preprocess_paragraph().
// *
// * @SuppressWarnings(PHPMD.StaticAccess)
// */
//function civictheme_preprocess_paragraph__civictheme_service_card(&$variables) {
//  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
//  $paragraph = $variables['paragraph'];
//
//  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem[]|null $links */
//  $links = civictheme_get_field_value($paragraph, 'field_c_p_links') ?? [];
//  foreach ($links as $link) {
//    $variables['links'][] = [
//      'url' => $link->getUrl()->toString(),
//      'text' => $link->get('title')->getString(),
//      'is_external' => $link->isExternal(),
//      'is_new_window' => $link->isExternal(),
//    ];
//  }
//}
//
//
///**
// * Implements template_preprocess_paragraph().
// */
//function civictheme_preprocess_paragraph__civictheme_subject_card(&$variables) {
//  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
//  $paragraph = $variables['paragraph'];
//  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
//  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
//  if ($link) {
//    $variables['url'] = $link->getUrl()->toString();
//    $variables['is_external'] = $link->isExternal();
//  }
//}
//
//

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

//
///**
// * Generic setter of global variables for nodes as card renders.
// */
//function _civictheme_preprocess_node__card(&$variables) {
//  /** @var \Drupal\node\NodeInterface $node */
//  $node = $variables['node'];
//  $type = $node->getType();
//  unset($variables['date']);
//  $type_callback = '_civictheme_preprocess_node__' . $type . '__summary';
//  if (function_exists($type_callback)) {
//    $type_callback($variables);
//  }
//  $variables['title'] = $node->getTitle();
//  civictheme_attributes_to_modifier_class($variables);
//  _civictheme_preprocess_node__theme($variables);
//}
//
///**
// * Preprocess.
// */
//function _civictheme_preprocess_node__civictheme_event_card(&$variables) {
//  _civictheme_preprocess_node__card($variables);
//  _civictheme_preprocess_node__date_range($variables);
//  _civictheme_preprocess_node__thumbnail($variables, 'civictheme_promo_card');
//  _civictheme_preprocess_node__topics($variables);
//}
//
///**
// * Pre-process reference Theme fields for CivicTheme Nodes.
// */
//function _civictheme_preprocess_node__theme(&$variables) {
//  /** @var \Drupal\node\NodeInterface $node */
//  $node = $variables['node'];
//
//  if (isset($node->_referringItem)) {
//    /** @var \Drupal\paragraphs\Entity\Paragraph $referring_paragraph */
//    $referring_paragraph = $node->_referringItem->getEntity() ?? NULL;
//    $variables['theme'] = civictheme_get_field_theme_value($referring_paragraph);
//  }
//
//  // For when theme is set by view or other third party.
//  if (isset($node->civictheme_theme)) {
//    $variables['theme'] = $node->civictheme_theme;
//  }
//}
//
///**
// * Pre-process a Node for navigation_card view mode.
// */
//function _civictheme_preprocess_node__civictheme_navigation_card(&$variables) {
//  _civictheme_preprocess_node__card($variables);
//  _civictheme_preprocess_node__thumbnail($variables, 'civictheme_navigation_card');
//}
//
///**
// * Pre-process a Node for civictheme_subject view mode.
// */
//function _civictheme_preprocess_node__civictheme_promo_card(&$variables) {
//  /** @var \Drupal\node\NodeInterface $node */
//  $node = $variables['node'];
//  $variables['date'] = civictheme_format_date('civictheme_short_date', $node->getChangedTime());
//  _civictheme_preprocess_node__card($variables);
//  _civictheme_preprocess_node__date_range($variables);
//  _civictheme_preprocess_node__thumbnail($variables, 'civictheme_promo_card');
//  _civictheme_preprocess_node__topics($variables);
//}
//
///**
// * Pre-process a Node for civictheme_subject_card view mode.
// */
//function _civictheme_preprocess_node__civictheme_subject_card(&$variables) {
//  _civictheme_preprocess_node__card($variables);
//  _civictheme_preprocess_node__thumbnail($variables);
//}
//
///**
// * Pre-process date field.
// */
//function _civictheme_preprocess_node__date_range(&$variables) {
//  /** @var \Drupal\node\NodeInterface $node */
//  $node = $variables['node'];
//  $date_range = civictheme_get_field_value($node, 'field_c_n_date_range', TRUE);
//  if ($date_range) {
//    $variables['date'] = civictheme_format_date('civictheme_short_date', $date_range->get('value')->getString());
//  }
//}
//
///**
// * Topic field preprocessor.
// */
//function _civictheme_preprocess_node__topics(&$variables) {
//  /** @var \Drupal\node\NodeInterface $node */
//  $node = $variables['node'];
//  $tags = [];
//  $topic_terms = civictheme_get_field_value($node, 'field_c_n_topics') ?? [];
//  foreach ($topic_terms as $topic_term) {
//    $tags[] = $topic_term->getName();
//  }
//  $variables['tags'] = $tags;
//}
//
///**
// * Pre-process image thumbnail for nodes.
// */
//function _civictheme_preprocess_node__thumbnail(&$variables, $image_style = NULL) {
//  /** @var \Drupal\node\NodeInterface $node */
//  $node = $variables['node'];
//  $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE);
//  if ($media) {
//    $variables['image'] = civictheme_media_image_process_variables($media, $image_style);
//  }
//}
//
///**
// * Pre-process Summary for civictheme_page nodes.
// */
//function _civictheme_preprocess_node__civictheme_page__summary(&$variables) {
//  /** @var \Drupal\node\NodeInterface $node */
//  $node = $variables['node'];
//  $summary = civictheme_get_field_value($node, 'field_c_n_summary', TRUE);
//  if ($summary) {
//    $length = CivicthemeConstants::CARD_SUMMARY_DEFAULT_LENGTH;
//    if (!empty($variables['view_mode']) && str_ends_with($variables['view_mode'], '_card')) {
//      $length = civictheme_get_theme_config_manager()->loadForComponent(str_replace('civictheme_', '', $variables['view_mode']), 'summary_length', CivicthemeConstants::CARD_SUMMARY_DEFAULT_LENGTH);
//    }
//    $variables['summary'] = text_summary($summary, NULL, $length);
//  }
//}
//
///**
// * Pre-process Summary for civictheme_event.
// */
//function _civictheme_preprocess_node__civictheme_event__summary(&$variables) {
//  /** @var \Drupal\node\NodeInterface $node */
//  $node = $variables['node'];
//  $summary = civictheme_get_field_value($node, 'field_c_n_summary', TRUE);
//  if ($summary) {
//    $length = CivicthemeConstants::CARD_SUMMARY_DEFAULT_LENGTH;
//    if (!empty($variables['view_mode']) && str_ends_with($variables['view_mode'], '_card')) {
//      $length = civictheme_get_theme_config_manager()->loadForComponent(str_replace('civictheme_', '', $variables['view_mode']), 'summary_length', CivicthemeConstants::CARD_SUMMARY_DEFAULT_LENGTH);
//    }
//    $variables['summary'] = text_summary($summary, NULL, $length);
//  }
//}
