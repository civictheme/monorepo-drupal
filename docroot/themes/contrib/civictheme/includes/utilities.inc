<?php

/**
 * @file
 * Theme utility functions.
 */

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Template\Attribute;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\media\Entity\Media;
use Drupal\media\MediaInterface;
use Drupal\node\NodeInterface;

/**
 * Load node or the node revision if the route is a node revision.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_load_node_or_revision($variables) {
  $node = $variables['node'] ?? NULL;

  if (is_numeric($node)) {
    // Check to see if it's a node revision route.
    /** @var \Drupal\Core\Routing\RouteMatchInterface $route_match */
    $route_match = \Drupal::service('current_route_match');
    $node_revision = $route_match->getParameter('node_revision');
    // Handling API change in Drupal 9.3.0.
    if (is_numeric($node_revision)) {
      $node = node_revision_load($node_revision);
    }
    elseif ($node_revision instanceof NodeInterface) {
      $node = $node_revision;
    }
  }

  return $node;
}

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
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_media_image_process_variables(MediaInterface $media, $image_style = NULL) {
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
      $image = [];
      $image['src'] = $image_src;
      $image['alt'] = !empty($image_entity['alt']) ? $image_entity['alt'] : NULL;
      $image['title'] = !empty($image_entity['title']) ? $image_entity['title'] : NULL;

      return $image;
    }
  }
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
function civictheme_media_get_url_from_field(EntityInterface $entity, $field_name) {
  /** @var \Drupal\media\Entity\Media|NULL $media */
  $media = civictheme_get_field_value($entity, $field_name, TRUE);
  if ($media) {
    return civictheme_media_get_url($media);
  }

  return FALSE;
}

/**
 * Get the media source file url.
 *
 * @param \Drupal\media\MediaInterface $media
 *   The media entity to derive the url from.
 *
 * @return string|false
 *   The url for the source file or false.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_media_get_url(MediaInterface $media) {
  $fid = $media->getSource()->getSourceFieldValue($media);
  $file = File::load($fid);
  if ($file) {
    return file_create_url($file->createFileUrl());
  }

  return FALSE;
}

/**
 * Embed SVG from provided URL.
 *
 * @param string $url
 *   Local URL or local path to retrieve SVG from.
 * @param array $css_classes
 *   Array of CSS classes to add.
 *
 * @return string
 *   Loaded SVG or FALSE if unable to load SVG.
 */
function civictheme_embed_svg($url, array $css_classes = []) {
  $svg_path = DRUPAL_ROOT . (strpos($url, 'http') === 0 ? parse_url(str_replace('.png', '.svg', $url), PHP_URL_PATH) : str_replace('.png', '.svg', $url));
  if (!file_exists($svg_path)) {
    return FALSE;
  }

  $content = file_get_contents($svg_path);

  if (!empty($css_classes)) {
    $content = str_replace('<svg ', '<svg class="' . implode(' ', $css_classes) . '" ', $content);
  }

  return $content;
}

/**
 * Parse a string of attributes into an array.
 *
 * @param string $string
 *   String to parse.
 *
 * @return array
 *   Array of attributes.
 */
function civictheme_parse_attributes($string) {
  $attributes = [];

  if (preg_match_all('/\s*(?:([a-z0-9-]+)\s*=\s*"([^"]*)")|(?:\s+([a-z0-9-]+)(?=\s*|>|\s+[a-z0-9]+))/i', $string, $matches)) {
    for ($i = 0; $i < count($matches[0]); $i++) {
      if ($matches[3][$i]) {
        $attributes[$matches[3][$i]] = NULL;
        continue;
      }
      $attributes[$matches[1][$i]] = $matches[2][$i];
    }
  }

  return $attributes;
}

/**
 * Parse attribute classes into a string for modifier_class property.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_attributes_to_modifier_class(&$variables) {
  if (isset($variables['attributes'])) {
    $attributes = new Attribute($variables['attributes']);
    $classes = civictheme_get_css_class_attributes($attributes);
    if ($classes) {
      $variables['modifier_class'] = $classes;
    }
  }
}

/**
 * Get CSS class attributes from Attribute object.
 *
 * @param \Drupal\Core\Template\Attribute $attributes
 *   Attributes object.
 *
 * @return string
 *   Classes as a string.
 */
function civictheme_get_css_class_attributes(Attribute $attributes) {
  if ($attributes->hasAttribute('class')) {
    return implode(' ', $attributes->getClass()->value());
  }

  return FALSE;
}

/**
 * Format dates for cards.
 *
 * @return mixed
 *   Formatted date.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_format_short_date($date) {
  return \Drupal::service('date.formatter')->format(strtotime($date), 'civictheme_short_date');
}

/**
 * Checks whether the field exists and has a value on a given entity.
 *
 * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
 *   Entity to check field existence.
 * @param string $field_name
 *   Field name to check for.
 *
 * @return bool
 *   Whether the field exists and is not empty.
 */
function civictheme_field_has_value(FieldableEntityInterface $entity, $field_name) {
  return $entity->hasField($field_name) && !$entity->get($field_name)->isEmpty();
}

/**
 * Gets values from fields that CivicTheme regularly uses.
 *
 * It is not a replacement for the field api system for getting values. It gets
 * commonly required types of values by CivicTheme.
 *
 * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
 *   Entity to check field existence.
 * @param string $field_name
 *   Field name to get the value for.
 * @param bool $only_first
 *   Return only the first value of a multi-value field.
 * @param mixed $default
 *   Default value to return.
 *
 * @return mixed|null
 *   Whether the field exists and is not empty.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function civictheme_get_field_value(FieldableEntityInterface $entity, $field_name, $only_first = FALSE, $default = NULL) {
  $value = $default;

  if (!civictheme_field_has_value($entity, $field_name)) {
    return $value;
  }

  $field = $entity->get($field_name);
  $field_type = $field->getFieldDefinition()->getType();

  switch ($field_type) {
    case 'boolean':
      $value = (bool) $field->getString();
      break;

    case 'integer':
      $value = (integer) $field->getString();
      break;

    case 'list_integer':
      $value = (int) $field->getString();
      break;

    case 'list_string':
    case 'string':
    case 'string_long':
      $value = $field->getString();
      break;

    // Field types where we want to return field item.
    case 'datetime':
    case 'daterange':
    case 'image':
    case 'link':
      $list = $field;
      if (!$list->isEmpty()) {
        $value = $only_first ? $list->first() : $list;
      }
      break;

    // Field types where we want to return entities.
    case 'entity_reference':
    case 'entity_reference_revisions':
      $value = civictheme_get_field_referenced_entities($entity, $field_name, $only_first);
      break;

    case 'text_long':
      // Opinionated. We want to render field if single or allow view() to
      // handle it if it's all values.
      if ($only_first) {
        $field = $field->first();
        $field_value = $field->get('value')->getString();
        $field_format = $field->get('format')->getString();
        $value = !empty($field_format) ? check_markup($field_value, $field_format) : $field_value;
        break;
      }

      $value = $field->view();
      break;
  }

  return $value;
}

/**
 * Gets the entity referenced entities in a field of an entity.
 *
 * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
 *   The host entity.
 * @param string $field_name
 *   The entity reference field.
 *
 * @return \Drupal\Core\Entity\EntityInterface[]|EntityInterface|null
 *   Referenced entities, or referenced entity if found.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function civictheme_get_field_referenced_entities(FieldableEntityInterface $entity, $field_name, bool $only_first = FALSE) {
  $entities = NULL;

  if (civictheme_field_has_value($entity, $field_name)) {
    $entities = $entity->get($field_name)->referencedEntities();
    $entities = $only_first ? reset($entities) : $entities;
  }

  return $entities;
}

/**
 * Get the label of referenced entities in a field of an entity.
 *
 * @param \Drupal\Core\Entity\FieldableEntityInterface|null $entity
 *   The host entity.
 * @param string $field_name
 *   The entity reference field.
 *
 * @return string[]
 *   The label(s).
 */
function civictheme_get_referenced_entity_labels(FieldableEntityInterface $entity, $field_name) {
  $labels = [];
  $referenced_entities = civictheme_get_field_value($entity, $field_name) ?? [];
  foreach ($referenced_entities as $referenced_entity) {
    if ($referenced_entity instanceof EntityInterface) {
      $labels[] = $referenced_entity->label();
    }
  }

  return $labels;
}

/**
 * Get raw multivalued field values from the entity.
 *
 * @param \Drupal\Core\Entity\EntityInterface $entity
 *   The entity to get the values from.
 * @param string $field_name
 *   The field name.
 * @param string $value_key
 *   (optional) The value key. Default to 'value'.
 *
 * @return array
 *   Array of field values. If field does not exist or incorrect $value_key
 *   provided, empty array will be returned.
 */
function civictheme_get_entity_field_key_values(EntityInterface $entity, $field_name, $value_key = 'value') {
  $values = [];

  if ($entity->hasField($field_name)) {
    foreach ($entity->get($field_name)->getValue() as $value) {
      if (isset($value[$value_key])) {
        $values[] = $value[$value_key];
      }
    }
  }

  return $values;
}

/**
 * Get an entity's theme field value.
 *
 * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
 *   Entity to check field existence.
 * @param string $default
 *   Optional default theme value. Defaults to NULL.
 *
 * @return string
 *   The theme field value for the entity.
 *   If field does not have a value - $default is returned.
 */
function civictheme_get_field_theme_value(FieldableEntityInterface $entity, $default = NULL) {
  $field_name = FALSE;

  if (empty($entity)) {
    return $default;
  }

  switch (get_class($entity)) {
    case 'Drupal\paragraphs\Entity\Paragraph':
      $field_name = 'field_c_p_theme';
      break;

    case 'Drupal\node\Entity\Node':
      $field_name = 'field_c_n_banner_theme';
      break;

    case 'Drupal\block_content\Entity\BlockContent':
      $field_name = 'field_c_b_theme';
      break;
  }

  return $field_name ? civictheme_get_field_value($entity, $field_name) : $default;
}

/**
 * Theme options suitable for FormAPI.
 */
function civictheme_theme_options() {
  return [
    CIVICTHEME_THEME_LIGHT => CIVICTHEME_THEME_LIGHT_LABEL,
    CIVICTHEME_THEME_DARK => CIVICTHEME_THEME_DARK_LABEL,
  ];
}

/**
 * Convert multi-line strings to a list of strings.
 *
 * @param string $string
 *   The string to be processed.
 *
 * @return array
 *   A list of strings.
 */
function civictheme_multiline_to_array($string) {
  $lines = is_array($string) ? $string : explode("\n", str_replace("\r\n", "\n", $string));

  return array_values(array_filter(array_map('trim', $lines)));
}

/**
 * Convert an array to multi-line string value.
 *
 * @param string|array $array
 *   Array to convert.
 * @param string $delimiter
 *   Delimiter to use for multiline. Defaults to PHP_EOL.
 *
 * @return string
 *   String value of the array.
 */
function civictheme_array_to_multiline($array, $delimiter = PHP_EOL) {
  $array = is_array($array) ? $array : [$array];

  return implode($delimiter, array_filter($array));
}

/**
 * Get the nid that the listing block is embedded in.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_get_node_id_from_form_state($form_state) {
  $nid = $form_state->getUserInput()['nid'] ?? NULL;
  $nid = is_numeric($nid) ? $nid : NULL;
  if (!$nid) {
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node) {
      $nid = $node->id();
    }
  }

  return $nid;
}

/**
 * Get DOMDocument object from HTML content.
 *
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function _civictheme_get_dom_document($content) {
  $doc = new \DOMDocument();

  $doc->loadHTML($content);

  return $doc;
}
