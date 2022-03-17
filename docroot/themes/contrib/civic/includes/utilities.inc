<?php

/**
 * @file
 * Theme utility functions.
 */

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Template\Attribute;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\media\Entity\Media;
use Drupal\media\MediaInterface;

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
function civic_media_image_variables(MediaInterface $media, $image_style = NULL) {
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
function civic_attributes_to_modifier_class(&$variables) {
  if (isset($variables['attributes'])) {
    $attributes = new Attribute($variables['attributes']);
    if ($classes = civic_get_class_attributes($attributes)) {
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
function civic_get_class_attributes(Attribute $attributes) {
  if ($attributes->hasAttribute('class')) {
    return implode(' ', $attributes->getClass()->value());
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

/**
 * Embed SVG from provided URL.
 *
 * @param string $url
 *   Local URL or local path to retrieve SVG from.
 * @param array $classes
 *   Array of CSS classes to add.
 *
 * @return string
 *   Loaded SVG or FALSE if unable to load SVG.
 */
function civic_embed_svg($url, array $classes = []) {
  $svg_path = DRUPAL_ROOT . (strpos($url, 'http') === 0 ? parse_url(str_replace('.png', '.svg', $url), PHP_URL_PATH) : str_replace('.png', '.svg', $url));
  if (!file_exists($svg_path)) {
    return FALSE;
  }

  $content = file_get_contents($svg_path);

  if (!empty($classes)) {
    $content = str_replace('<svg ', '<svg class="' . implode(' ', $classes) . '" ', $content);
  }

  return $content;
}

/**
 * Format dates for cards.
 *
 * @return mixed
 *   Formatted date.
 */
function civic_format_short_date($date) {
  return \Drupal::service('date.formatter')->format(strtotime($date), 'civic_short_date');
}
