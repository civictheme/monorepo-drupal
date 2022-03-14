<?php

/**
 * @file
 * Theme utility functions.
 */

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Template\Attribute;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\media\MediaInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Process media to generate image array for twig.
 *
 * @param \Drupal\media\MediaInterface $media
 *   Host Media entity for image.
 * @param string $image_style
 *   Name of image style to apply.
 *
 * @return array
 *   Image array or false if not available.
 */
function _civic_media_image(MediaInterface $media, $image_style = NULL) {
  if ($media instanceof Media) {
    $fid = $media->getSource()->getSourceFieldValue($media);
    $config = $media->getSource()->getConfiguration();
    $image_entity = $media->get($config['source_field'])->first()->getValue();

    $file = File::load($fid);
    if ($file) {
      $image_src = $file->createFileUrl();
      if (!empty($image_style)) {
        $style = ImageStyle::load($image_style);
        if (!empty($style)) {
          $path = $file->get('uri')->getString();
          $image_src = $style->buildUrl($path);
        }
      }
      // @todo fix image style generation from static content.
      $image['src'] = $image_src;
      $image['alt'] = !empty($image_entity['alt']) ? $image_entity['alt'] : NULL;
      $image['title'] = !empty($image_entity['title']) ? $image_entity['title'] : NULL;
      return $image;
    }
  }
}

/**
 * Parse attribute classes into a string for modifier_class property.
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
 * Get URL of the media entity from field on provided entity.
 *
 * @param \Drupal\Core\Entity\EntityInterface $entity
 *   The entity to get the media from.
 * @param string $field_name
 *   The field name that has media.
 *
 * @return string
 *   Image URL or FALSE if not available.
 */
function civic_media_get_url_from_field(EntityInterface $entity, string $field_name) {
  if ($entity->hasField($field_name) && !$entity->get($field_name)->isEmpty()) {
    /** @var \Drupal\media\Entity\Media $media */
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
