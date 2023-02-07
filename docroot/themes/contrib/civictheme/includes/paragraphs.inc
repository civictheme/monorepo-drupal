<?php

/**
 * @file
 * Paragraph-related theme helpers.
 */

use Drupal\civictheme\CivicthemeConstants;

/**
 * Pre-process paragraph fields using field preprocessors.
 */
function _civictheme_preprocess_paragraph__fields(&$variables, array $fields) {
  foreach ($fields as $field) {
    $callback = '_civictheme_preprocess_paragraph__field__' . $field;
    if (function_exists($callback)) {
      call_user_func_array($callback, [&$variables]);
    }
  }
}

/**
 * Pre-process for Title field.
 */
function _civictheme_preprocess_paragraph__field__title(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['title'] = civictheme_get_field_value($paragraph, 'field_c_p_title');
}

/**
 * Pre-process for Summary field.
 */
function _civictheme_preprocess_paragraph__field__summary(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if (civictheme_field_has_value($paragraph, 'field_c_p_summary')) {
    $variables['summary'] = civictheme_get_field_value($paragraph, 'field_c_p_summary');
    $length = civictheme_get_theme_config_manager()->loadForComponent(str_replace('civictheme_', '', $paragraph->bundle()), 'summary_length', CivicthemeConstants::CARD_SUMMARY_DEFAULT_LENGTH);
    if ($length) {
      $variables['summary'] = text_summary($variables['summary'], NULL, $length);
    }
  }
}

/**
 * Pre-process for Image field.
 */
function _civictheme_preprocess_paragraph__field__image(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $media = civictheme_get_field_value($paragraph, 'field_c_p_image', TRUE);
  if ($media) {
    $variables['image'] = civictheme_media_image_get_variables($media);
  }
}

/**
 * Pre-process for Icon field.
 */
function _civictheme_preprocess_paragraph__field__icon(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $media = civictheme_get_field_value($paragraph, 'field_c_p_icon', TRUE);
  if ($media) {
    $variables['image'] = civictheme_media_image_get_variables($media);
  }
}

/**
 * Pre-process for Link field.
 */
function _civictheme_preprocess_paragraph__field__link(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
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
 * Pre-process for Content field.
 */
function _civictheme_preprocess_paragraph__field__content(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['content'] = civictheme_get_field_value($paragraph, 'field_c_p_content');
}

/**
 * Pre-process for Theme field.
 */
function _civictheme_preprocess_paragraph__field__theme(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['theme'] = civictheme_get_field_value($paragraph, 'field_c_p_theme');
}

/**
 * Pre-process for Vertical spacing field.
 */
function _civictheme_preprocess_paragraph__field__vertical_spacing(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['vertical_spacing'] = civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing');
}

/**
 * Pre-process for With background field.
 */
function _civictheme_preprocess_paragraph__field__background(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['with_background'] = civictheme_get_field_value($paragraph, 'field_c_p_background');
}
