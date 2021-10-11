<?php

/**
 * @file
 * Theme utility functions.
 */

use Drupal\Core\Entity\EntityInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\media\MediaInterface;
use Drupal\Core\Template\Attribute;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Process media to generate image array for twig.
 *
 * @param \Drupal\media\MediaInterface $media
 *   Host Media entity for image.
 *
 * @return array
 *   Image array or false if not available.
 */
function _civic_media_image(MediaInterface $media) {
  if ($media instanceof Media) {
    $fid = $media->getSource()->getSourceFieldValue($media);
    $config = $media->getSource()->getConfiguration();
    $image_entity = $media->get($config['source_field'])->first()->getValue();

    $file = File::load($fid);
    if ($file) {
      // @todo fix image style generation from static content.
      $image['src'] = $file->createFileUrl();
      $image['alt'] = !empty($image_entity['alt']) ? $image_entity['alt'] : NULL;
      $image['title'] = !empty($image_entity['title']) ? $image_entity['title'] : NULL;
      return $image;
    }
  }
}

/**
 * Parse attribute classes into a string for modifier_class property.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_attributes(&$variables) {
  if (isset($variables['attributes'])) {
    $attributes = new Attribute($variables['attributes']);
    if ($classes = _civic_get_class_attributes($attributes)) {
      $variables['modifier_class'] = $classes;
    }
  }
}

/**
 * Get class attributes from Attribute object.
 *
 * @param \Drupal\Core\Template\Attribute $attributes
 *   Attributes object.
 *
 * @return string
 *   Classes as a string.
 */
function _civic_get_class_attributes(Attribute $attributes) {
  if ($attributes->hasAttribute('class')) {
    return implode(' ', $attributes->getClass()->value());
  }
  return FALSE;
}

/**
 * Get theme name from Paragraph Entity.
 *
 * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
 *   Paragraph Entity.
 */
function _civic_paragraph_get_theme(Paragraph $paragraph) {
  if ($paragraph->hasField('field_c_p_theme') && !$paragraph->get('field_c_p_theme')->isEmpty()) {
    return $paragraph->get('field_c_p_theme')->getString();
  }
  return FALSE;
}

/**
 * Process media to generate image array for twig.
 *
 * @param \Drupal\Core\Entity\EntityInterface $entity
 *   Entity to get media image url.
 * @param string $field_name
 *   Field name.
 *
 * @return string
 *   Image URL or false if not available.
 */
function _civic_media_image_url(EntityInterface $entity, string $field_name) {
  if ($entity->hasField($field_name) && !$entity->get($field_name)->isEmpty()) {
    $media = $entity->get($field_name)->first()->entity;
    if ($media) {
      $fid = $media->getSource()->getSourceFieldValue($media);
      $file = File::load($fid);
      if ($file) {
        return file_create_url($file->createFileUrl());
      }
    }
  }

  return FALSE;
}
