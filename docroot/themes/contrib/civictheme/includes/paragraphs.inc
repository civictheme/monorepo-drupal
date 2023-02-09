<?php

/**
 * @file
 * Paragraph-related theme helpers.
 */

use Drupal\civictheme\CivicthemeConstants;

/**
 * Pre-process for With background field.
 */
function _civictheme_preprocess_paragraph__field__background(&$variables) {
  $variables['with_background'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_background');
}

/**
 * Pre-process for Content field.
 */
function _civictheme_preprocess_paragraph__field__content(&$variables) {
  $variables['content'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_content');
}

/**
 * Pre-process for Date field.
 */
function _civictheme_preprocess_paragraph__field__date(&$variables) {
  $date = civictheme_get_field_value($variables['paragraph'], 'field_c_p_date', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_date('civictheme_short_date', $date->get('value')->getDateTime());
    $variables['date_iso'] = date('c', strtotime($date->get('value')->getDateTime()));
  }
}

/**
 * Pre-process for Date range field.
 */
function _civictheme_preprocess_paragraph__field__date_range(&$variables) {
  $date = civictheme_get_field_value($variables['paragraph'], 'field_c_p_date_range', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_date('civictheme_short_date_and_time', $date->get('value')->getDateTime());
    $variables['date_iso'] = date('c', strtotime($date->get('value')->getDateTime()));
    if ($date->get('end_value')->getDateTime()) {
      $variables['date_end'] = civictheme_format_date('civictheme_short_date_and_time', $date->get('end_value')->getDateTime(), 'j M Y H:i');
      $variables['date_end_iso'] = date('c', strtotime($date->get('end_value')->getDateTime()));
    }
  }
}

/**
 * Pre-process for Image field.
 */
function _civictheme_preprocess_paragraph__field__image(&$variables) {
  $media = civictheme_get_field_value($variables['paragraph'], 'field_c_p_image', TRUE);
  if ($media) {
    $variables['image'] = civictheme_media_image_get_variables($media);
  }
}

/**
 * Pre-process for Image field.
 */
function _civictheme_preprocess_paragraph__field__image_position(&$variables) {
  $variables['image_position'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_image_position');
}

/**
 * Pre-process for Link field.
 */
function _civictheme_preprocess_paragraph__field__link(&$variables) {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($variables['paragraph'], 'field_c_p_link', TRUE);
  if ($link) {
    $variables['link'] = [
      'text' => $link->get('title')->getString(),
      'url' => $link->getUrl()->toString(),
      'is_new_window' => $link->isExternal(),
      'is_external' => $link->isExternal(),
    ];
  }
}

/**
 * Pre-process for Links field.
 */
function _civictheme_preprocess_paragraph__field__links(&$variables) {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null[] $link */
  $links = civictheme_get_field_value($variables['paragraph'], 'field_c_p_links');
  if ($links) {
    foreach ($links as $link) {
      $variables['links'][] = [
        'text' => $link->get('title')->getString(),
        'url' => $link->getUrl()->toString(),
        'is_new_window' => $link->isExternal(),
        'is_external' => $link->isExternal(),
      ];
    }
  }
}

/**
 * Pre-process for Summary field.
 */
function _civictheme_preprocess_paragraph__field__summary(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if (civictheme_field_has_value($paragraph, 'field_c_p_summary')) {
    $variables['summary'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_summary');
    $length = civictheme_get_theme_config_manager()->loadForComponent(str_replace('civictheme_', '', $paragraph->bundle()), 'summary_length', CivicthemeConstants::CARD_SUMMARY_DEFAULT_LENGTH);
    if ($length) {
      $variables['summary'] = text_summary($variables['summary'], NULL, $length);
    }
  }
}

/**
 * Pre-process for Theme field.
 */
function _civictheme_preprocess_paragraph__field__theme(&$variables) {
  $variables['theme'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_theme');
}

/**
 * Pre-process for Title field.
 */
function _civictheme_preprocess_paragraph__field__title(&$variables) {
  $variables['title'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_title');
}

/**
 * Pre-process for Topics field.
 */
function _civictheme_preprocess_paragraph__field__topics(&$variables) {
  $variables['tags'] = civictheme_get_referenced_entity_labels($variables['paragraph'], 'field_c_p_topics');
}

/**
 * Pre-process for Vertical spacing field.
 */
function _civictheme_preprocess_paragraph__field__vertical_spacing(&$variables) {
  $variables['vertical_spacing'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_vertical_spacing');
}

/**
 * Pre-process for Webform field.
 */
function _civictheme_preprocess_paragraph__field__webform(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if (civictheme_field_has_value($paragraph, 'field_c_p_webform')) {
    $variables['referenced_webform'] = $paragraph->get('field_c_p_webform')->view();
  }
}
