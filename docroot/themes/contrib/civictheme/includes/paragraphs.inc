<?php

/**
 * @file
 * Paragraph-related theme helpers.
 */

use Drupal\civictheme\CivicthemeConstants;
use Drupal\file\Entity\File;

/**
 * Pre-process paragraph fields using field preprocessors.
 */
function _civictheme_preprocess_paragraph__fields(&$variables, array $fields) {
  foreach ($fields as $field_name) {
    $callback = '_civictheme_preprocess_paragraph__field__' . $field_name;
    if (function_exists($callback)) {
      call_user_func_array($callback, [&$variables]);
    }
  }
}

/**
 * Pre-process for Title field.
 */
function _civictheme_preprocess_paragraph__field__title(&$variables) {
  $variables['title'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_title');
}

/**
 * Pre-process for Content field.
 */
function _civictheme_preprocess_paragraph__field__content(&$variables) {
  $variables['content'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_content');
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
 * Pre-process for Icon field.
 */
function _civictheme_preprocess_paragraph__field__icon(&$variables) {
  $media = civictheme_get_field_value($variables['paragraph'], 'field_c_p_icon', TRUE);
  if ($media) {
    $variables['image'] = civictheme_media_image_get_variables($media);
  }
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
      ];
    }
  }
}

/**
 * Pre-process for View link field.
 */
function _civictheme_preprocess_paragraph__field__view_link(&$variables) {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($variables['paragraph'], 'field_c_p_view_link', TRUE);
  if ($link) {
    $variables['view_url'] = $link->getUrl()->toString();
  }
}

/**
 * Pre-process for URL field.
 */
function _civictheme_preprocess_paragraph__field__url(&$variables) {
  $variables['url'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_url');
}

/**
 * Pre-process for Embed URL field.
 */
function _civictheme_preprocess_paragraph__field__embed_url(&$variables) {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($variables['paragraph'], 'field_c_p_embed_url', TRUE);
  if ($link) {
    $variables['url'] = $link->getUrl()->toString();
  }
}

/**
 * Pre-process for Topics field.
 */
function _civictheme_preprocess_paragraph__field__topics(&$variables) {
  $variables['tags'] = civictheme_get_referenced_entity_labels($variables['paragraph'], 'field_c_p_topics');
}

/**
 * Pre-process for Date field.
 */
function _civictheme_preprocess_paragraph__field__date(&$variables) {
  $date = civictheme_get_field_value($variables['paragraph'], 'field_c_p_date', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_date('civictheme_short_date', $date->get('value')->getDateTime());
  }
}

/**
 * Pre-process for Date range field.
 */
function _civictheme_preprocess_paragraph__field__date_range(&$variables) {
  $date = civictheme_get_field_value($variables['paragraph'], 'field_c_p_date_range', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_date('civictheme_short_date', $date->get('value')->getDateTime(), $date->get('end_value')->getDateTime());
  }
}

/**
 * Pre-process for Author field.
 */
function _civictheme_preprocess_paragraph__field__author(&$variables) {
  $variables['author'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_author');
}

/**
 * Pre-process for Address field.
 */
function _civictheme_preprocess_paragraph__field__address(&$variables) {
  $variables['author'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_address');
}

/**
 * Pre-process for Width field.
 */
function _civictheme_preprocess_paragraph__field__width(&$variables) {
  $variables['width'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_width');
}

/**
 * Pre-process for Height field.
 */
function _civictheme_preprocess_paragraph__field__height(&$variables) {
  $variables['height'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_height');
}

/**
 * Pre-process for Component attributes field.
 */
function _civictheme_preprocess_paragraph__field__component_attributes(&$variables) {
  $variables['component_attributes'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_attributes');
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

/**
 * Pre-process for Attachements field.
 */
function _civictheme_preprocess_paragraph__field__attachments(&$variables) {
  $links = [];

  $attachments = civictheme_get_field_value($variables['paragraph'], 'field_c_p_attachments') ?? [];
  foreach ($attachments as $attachment) {
    $fid = $attachment->getSource()->getSourceFieldValue($attachment);
    /** @var \Drupal\file\FileInterface $file */
    $file = $fid ? File::load($fid) : NULL;
    if (!empty($file)) {
      $url = $file->createFileUrl(FALSE);
      $file_size = format_size($file->getSize());

      $icon = civictheme_get_icon_from_file($file);
      $file_extension = pathinfo($file->getFileUri(), PATHINFO_EXTENSION);

      $links[] = [
        'url' => $url,
        'text' => t('@name', [
          '@name' => $file->label(),
        ]),
        'ext' => $file_extension ?? '',
        'size' => $file_size ?? '',
        'icon' => $icon,
        'last_updated' => civictheme_format_date('civictheme_short_date', $attachment->getChangedTime()),
      ];
    }
  }

  $variables['links'] = $links;
}

/**
 * Pre-process for Theme field.
 */
function _civictheme_preprocess_paragraph__field__theme(&$variables) {
  $variables['theme'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_theme');
}

/**
 * Pre-process for Vertical spacing field.
 */
function _civictheme_preprocess_paragraph__field__vertical_spacing(&$variables) {
  $variables['vertical_spacing'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_vertical_spacing');
}

/**
 * Pre-process for With background field.
 */
function _civictheme_preprocess_paragraph__field__background(&$variables) {
  $variables['with_background'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_background');
}
