<?php

/**
 * @file
 * Paragraph theme alterations.
 */

use Drupal\file\Entity\File;

require_once 'includes/paragraph--civic_attachment.inc';

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function civic_theme_suggestions_paragraph_alter(array &$suggestions, array $variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['elements']['#paragraph'];
  $type = $paragraph->getType();

  // Add a generic card theme suggestion for reference cards since the component
  // is being handled via node preprocessing functions.
  if (in_array($type, _civic_get_card_types()) && $paragraph->hasField('field_c_p_reference')) {
    $suggestions[] = 'paragraph__civic_reference_card';
  }
}

/**
 * Generic setter of global variables for paragraphs as card renders.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $type = $paragraph->getType();
  if (!in_array($type, _civic_get_card_types())) {
    return;
  }
  // Cards that are generated via reference are handled separately.
  if ($paragraph->hasField('field_c_p_reference')) {
    _civic_preprocess_paragraph_reference_card($variables);
  }
  else {
    // @todo documentation for the field api ie they must have certain fields in
    // card fielded paragraph types.
    _civic_preprocess_paragraph_card__icon($variables);
    _civic_preprocess_paragraph_card__image($variables);
  }

}

/**
 * Global paragraph preprocessor calls.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph(&$variables) {
  // Global paragraph fields.
  _civic_preprocess_paragraph_theme($variables);
  _civic_preprocess_paragraph_title($variables);
  _civic_preprocess_paragraph_summary($variables);

  _civic_preprocess_paragraph_card($variables);

  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $type = $paragraph->getType();
  $preprocessor_function = '_civic_preprocess_paragraph_' . $type;
  if (function_exists($preprocessor_function)) {
    $preprocessor_function($variables);
  }
}

/**
 * Preprocess summary for Civic paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_summary(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_summary') && !$paragraph->get('field_c_p_summary')->isEmpty()) {
    $variables['summary'] = text_summary($paragraph->get('field_c_p_summary')->getString(), NULL, 100);
  }
}

/**
 * Preprocess title for Civic paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_title(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_title') && !$paragraph->get('field_c_p_title')->isEmpty()) {
    $variables['title'] = $paragraph->get('field_c_p_title')->getString();
  }
}

/**
 * Preprocess Theme fields for Civic paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_theme(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_theme') && !$paragraph->get('field_c_p_theme')->isEmpty()) {
    $variables['theme'] = $paragraph->get('field_c_p_theme')->getString();
  }
}

/**
 * Preprocess icon for Civic cards.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_card__icon(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_icon') && !$paragraph->get('field_c_p_icon')->isEmpty()) {
    if ($image = _civic_media_image($paragraph->get('field_c_p_icon')->referencedEntities()[0], 'field_c_m_icon')) {
      $variables['image'] = $image;
    }
  }
}

/**
 * Preprocess Icon for Civic Paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_card__image(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_image') && !$paragraph->get('field_c_p_image')->isEmpty()) {
    $variables['image'] = _civic_media_image($paragraph->get('field_c_p_image')->referencedEntities()[0], 'field_media_image');
  }
}

/**
 * Gets Civic card types.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_get_card_types() {
  // @todo extend to allow for child theme - implement as theme setting.
  return [
    'civic_card_task',
    'civic_card_event_reference',
    'civic_card_event',
    'civic_card_navigation_reference',
    'civic_card_navigation',
    'civic_card_promo',
    'civic_card_reference_promo',
    'civic_card_subject_reference',
    'civic_card_subject',
    'civic_service_card',
  ];
}

/**
 * Preprocessing reference cards paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_reference_card(&$variables) {
  // Card reference paragraph types are handled on a generic template.
  // Card type are handled at node level via view modes.
  // @see civic_theme_suggestions_paragraph_alter()
  $variables['reference_card'] = $variables['content']['field_c_p_reference'];
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_attachment(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if ($paragraph->hasField('field_c_p_attachments') && !$paragraph->get('field_c_p_attachments')->isEmpty()) {
    $attachments = [];
    foreach ($paragraph->field_c_p_attachments->referencedEntities() as $attachment) {
      $fid = $attachment->getSource()->getSourceFieldValue($attachment);
      /** @var \Drupal\file\FileInterface $file */
      $file = $fid ? File::load($fid) : NULL;
      if (!empty($file)) {
        $url = $file->createFileUrl(FALSE);
        $file_size = format_size($file->getSize());
        $file_extension = pathinfo($file->getFileUri(), PATHINFO_EXTENSION);
        $attachments[] = [
          'url' => $url,
          'title' => t('@name <span class="extension">(@ext, @size)</span>', [
            '@name' => $file->label(),
            '@ext' => strtoupper($file_extension),
            '@size' => strtoupper($file_size),
          ]),
          'last_updated' => \Drupal::service('date.formatter')->format($attachment->getChangedTime(), 'custom', 'd M Y'),
        ];
      }
    }

    $variables['links'] = $attachments;
  }
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_callout(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if ($paragraph->hasField('field_c_p_links') && !$paragraph->get('field_c_p_links')->isEmpty()) {
    $links = [];
    foreach ($paragraph->field_c_p_links as $link) {
      if (!empty($link)) {
        $links[] = [
          'url' => $link->url,
          'text' => $link->title,
          'type' => 'primary',
          'size' => 'regular',
        ];
      }
    }

    $variables['links'] = $links;
  }
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_card_container(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['column_count'] = $paragraph->hasField('field_c_p_column_count') ? $paragraph->get('field_c_p_column_count')->getString() : '';
  $variables['fill_width'] = $paragraph->hasField('field_c_p_fill_width') ? (bool) $paragraph->get('field_c_p_fill_width')->getString() : FALSE;
  $link = $paragraph->hasField('field_c_p_link') ? $paragraph->get('field_c_p_link')->getValue() : [];
  $variables['link_text'] = $link['title'] ?? NULL;
  $variables['url'] = $link['url'] ?? NULL;
  $variables['cards'] = array_filter($variables['content']['field_c_p_cards'], function ($key) {
    return is_numeric($key);
  }, ARRAY_FILTER_USE_KEY);
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_card_promo(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $link = $paragraph->hasField('field_c_p_link') ? $paragraph->get('field_c_p_link')->getValue() : [];
  $variables['link_text'] = $link['title'] ?? NULL;
  $variables['url'] = $link['url'] ?? NULL;
  $variables['date'] = $paragraph->get('field_c_p_date')->getValue()[0]['value'];
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_card_navigation(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $link = $paragraph->hasField('field_c_p_link') ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
  $variables['url'] = $link['uri'] ?? NULL;
}
