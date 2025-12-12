<?php

/**
 * @file
 * Theme utility functions.
 *
 * @todo Move all functions to CivicthemeUtility class.
 */

use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\StringTranslation\ByteSizeMarkup;
use Drupal\Core\Template\Attribute;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\media\MediaInterface;
use Drupal\node\NodeInterface;
use Drupal\views\ViewExecutable;

/**
 * Get a theme config manager.
 *
 * We are using our own theme config manager as a facade for Drupal's
 * theme_get_setting() in order to provide more robust functionality and error
 * handling.
 *
 * This function should be used everywhere in CivicTheme code until all
 * procedural theme code is refactored into services. Then, it will be removed
 * and theme config manager will be used directly via DI.
 *
 * @return \Drupal\civictheme\CivicthemeConfigManager
 *   The theme config manager.
 */
function civictheme_get_theme_config_manager() {
  return \Drupal::service('class_resolver')->getInstanceFromDefinition(CivicthemeConfigManager::class);
}

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
 * Get variables from media.
 *
 * @param \Drupal\media\MediaInterface $media
 *   Host Media entity for image.
 *
 * @return array|null
 *   Variables array or NULL if not available.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_media_get_variables(MediaInterface $media) {
  $fid = $media->getSource()->getSourceFieldValue($media);
  /** @var \Drupal\file\FileInterface $file */
  $file = $fid ? File::load($fid) : NULL;

  if (empty($file)) {
    return NULL;
  }

  return [
    'name' => t('@name', ['@name' => $file->label()]),
    'ext' => pathinfo($file->getFileUri(), PATHINFO_EXTENSION) ?? '',
    'url' => civictheme_media_get_url($media),
    'size' => ByteSizeMarkup::create($file->getSize()),
    'created' => civictheme_format_date('civictheme_short_date', $media->getCreatedTime()),
    'changed' => civictheme_format_date('civictheme_short_date', $media->getChangedTime()),
    'icon' => civictheme_get_icon_from_file($file),
  ];
}

/**
 * Get variables from Image media.
 *
 * @param \Drupal\media\MediaInterface $media
 *   Host Media entity for image.
 * @param string $image_style
 *   Name of the image style to apply.
 *
 * @return array|null
 *   Image variables array or NULL if not available.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_media_image_get_variables(MediaInterface $media, $image_style = NULL) {
  if (!in_array($media->bundle(), ['civictheme_image', 'civictheme_icon'])) {
    return NULL;
  }

  $variables = civictheme_media_get_variables($media);

  $variables['url'] = civictheme_media_image_get_url($media, $image_style);

  $image = $media
    ->get($media->getSource()->getConfiguration()['source_field'])
    ->first()
    ->getValue();

  $variables['alt'] = $image['alt'] ?? NULL;
  $variables['title'] = $image['title'] ?? NULL;

  return $variables;
}

/**
 * Get the media source file URL.
 *
 * @param \Drupal\media\MediaInterface $media
 *   The media entity to derive the URL from.
 * @param bool $relative
 *   (optional) Whether the URL should be root-relative, defaults to TRUE.
 *
 * @return string|false
 *   The relative URL for the source file or FALSE if the file was not found.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function civictheme_media_get_url(MediaInterface $media, $relative = TRUE) {
  $file = File::load($media->getSource()->getSourceFieldValue($media));

  return $file ? $file->createFileUrl($relative) : FALSE;
}

/**
 * Get Image media file URL, optionally using an image style.
 *
 * @param \Drupal\media\MediaInterface $media
 *   The media entity to derive the URL from.
 * @param string $image_style
 *   (optional) Image style name.
 * @param bool $relative
 *   (optional) Whether the URL should be root-relative, defaults to TRUE.
 *
 * @return string|false
 *   The relative URL for the source file or FALSE if the file was not found.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function civictheme_media_image_get_url(MediaInterface $media, $image_style = NULL, $relative = TRUE) {
  $src = NULL;

  $file = File::load($media->getSource()->getSourceFieldValue($media));

  if ($file) {
    if ($image_style) {
      $style = ImageStyle::load($image_style);
      $src = $style ? $style->buildUrl($file->get('uri')->getString()) : NULL;
    }
    else {
      $src = $file->createFileUrl($relative);
    }
  }

  return $src;
}

/**
 * Embed SVG from provided URL.
 *
 * @param string $url
 *   Local URL or local path to retrieve SVG from.
 * @param array $css_classes
 *   Array of CSS classes to add.
 *
 * @return string|null
 *   Loaded SVG or NULL if unable to load SVG.
 */
function civictheme_embed_svg(string $url, array $css_classes = []): ?string {
  $svg_path = DRUPAL_ROOT . (str_starts_with($url, 'http') ? parse_url(str_replace('.png', '.svg', $url), PHP_URL_PATH) : str_replace('.png', '.svg', $url));
  if (!file_exists($svg_path)) {
    return NULL;
  }

  $content = (string) file_get_contents($svg_path);

  if (!empty($css_classes)) {
    $content = str_replace('<svg ', '<svg class="' . implode(' ', $css_classes) . '" ', $content);
  }

  // Tag list taken from
  // https://developer.mozilla.org/en-US/docs/Web/SVG/Reference/Element.
  // // Excludes <a>, <script> and <style>.

  $supported_svg_tags = implode('', [
    '<animate>',
    '<animateMotion>',
    '<animateTransform>',
    '<circle>',
    '<clipPath>',
    '<defs>',
    '<desc>',
    '<ellipse>',
    '<feBlend>',
    '<feColorMatrix>',
    '<feComponentTransfer>',
    '<feComposite>',
    '<feConvolveMatrix>',
    '<feDiffuseLighting>',
    '<feDisplacementMap>',
    '<feDistantLight>',
    '<feDropShadow>',
    '<feFlood>',
    '<feFuncA>',
    '<feFuncB>',
    '<feFuncG>',
    '<feFuncR>',
    '<feGaussianBlur>',
    '<feImage>',
    '<feMerge>',
    '<feMergeNode>',
    '<feMorphology>',
    '<feOffset>',
    '<fePointLight>',
    '<feSpecularLighting>',
    '<feSpotLight>',
    '<feTile>',
    '<feTurbulence>',
    '<filter>',
    '<foreignObject>',
    '<g>',
    '<image>',
    '<line>',
    '<linearGradient>',
    '<marker>',
    '<mask>',
    '<metadata>',
    '<mpath>',
    '<path>',
    '<pattern>',
    '<polygon>',
    '<polyline>',
    '<radialGradient>',
    '<rect>',
    '<set>',
    '<stop>',
    '<svg>',
    '<switch>',
    '<symbol>',
    '<text>',
    '<textPath>',
    '<title>',
    '<tspan>',
    '<use>',
    '<view>',
  ]);

  return strip_tags($content, $supported_svg_tags);
}

/**
 * Preprocess 'attribute' classes variables into 'modifier_class'.
 *
 * Preprocessors can set $variables['modifier_class'] = FALSE to disable this
 * conversion.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_convert_attributes_to_modifier_class(&$variables) {
  // Allow to skip this conversion.
  if (isset($variables['modifier_class']) && $variables['modifier_class'] === FALSE) {
    return;
  }

  if (!isset($variables['attributes']['class'])) {
    return;
  }

  $attributes = new Attribute($variables['attributes']);
  $variables['modifier_class'] = implode(' ', $attributes->getClass()->value());
  // Remove class from attributes to avoid duplicated 'class' attribute on
  // the element.
  unset($variables['attributes']['class']);
}

/**
 * Format date.
 *
 * @param string $format_name
 *   Drupal format type.
 * @param mixed $date
 *   Timestamp or date as a string.
 *
 * @return mixed
 *   Formatted date or date range.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_format_date($format_name, $date) {
  return \Drupal::service('date.formatter')->format(is_numeric($date) ? $date : strtotime($date), $format_name);
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
 * This function complements the field API system, providing a convenient way to
 * retrieve commonly used field values specific to CivicTheme.
 *
 * If a field type is not listed, and you need to retrieve its value, consider
 * using the field API system directly.
 *
 * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
 *   Entity to check field existence.
 * @param string $field_name
 *   Field name to get the value for.
 * @param bool $only_first
 *   Return only the first value of a multi-value field.
 * @param mixed $default
 *   Default value to return.
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 *   (required for entity reference fields).
 * @return mixed|null
 *   Whether the field exists and is not empty.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ElseExpression)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_field_value(FieldableEntityInterface $entity, string $field_name, bool $only_first = FALSE, mixed $default = NULL, array &$build = []): mixed {
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

    case 'list_integer':
      $value = (int) $field->getString();
      break;

    case 'list_string':
    case 'string':
    case 'string_long':
      $value = $field->getString();
      $value = Xss::filter($value);
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
      if ($only_first) {
        $value = civictheme_get_field_referenced_entity($entity, $field_name, $build);
      }
      else {
        $value = civictheme_get_field_referenced_entities($entity, $field_name, $build);
      }
      break;

    case 'text_long':
    case 'text_with_summary':
    case 'text':
      // Opinionated.
      // This implementation renders the field if it has a single value.
      // If the field contains multiple values, it relies on view() to handle
      // the rendering.
      if ($only_first) {
        $field = $field->first();
        $field_value = $field->get('value')->getString();
        $field_format = $field->get('format')->getString();
        $value = empty($field_format) ? $field_value : check_markup($field_value, $field_format);
        break;
      }

      $value = $field->view();
      break;
  }

  return $value;
}

/**
 * Gets the referenced entities in a field of an entity.
 *
 * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
 *   The host entity.
 * @param string $field_name
 *   The entity reference field.
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 * * @return array<int|string, \Drupal\Core\Entity\EntityInterface>
 *   Referenced entities.
 */
function civictheme_get_field_referenced_entities(FieldableEntityInterface $entity, string $field_name, array &$build = []): array {
  $trigger_bc = empty($build);
  if ($trigger_bc) {
    @trigger_error('Calling ' . __FUNCTION__ . ' without the $build argument is deprecated in civictheme:1.12.0 It will be required in civictheme:1.13.0. Triggered when getting entity for ' . $field_name . '. See https://www.drupal.org/node/3552745', E_USER_DEPRECATED);
  }
  if (civictheme_field_has_value($entity, $field_name)) {
    /** @var \Drupal\Core\Entity\EntityInterface[] $referenced_entities */
    $referenced_entities = $entity->get($field_name)->referencedEntities();
    if (empty($referenced_entities)) {
      return [];
    }
    $access_checked_referenced_entities = [];
    foreach ($referenced_entities as $referenced_entity) {
      $access = $referenced_entity->access('view', NULL, TRUE);
      $cacheability = $cacheability ?? CacheableMetadata::createFromRenderArray($build);
      $cacheability->addCacheableDependency($access);
      $cacheability->addCacheableDependency($referenced_entity);

      if ($access->isAllowed()) {
        $access_checked_referenced_entities[$referenced_entity->id()] = $referenced_entity;
      }
    }
    $cacheability->applyTo($build);
    if ($trigger_bc && \Drupal::service('renderer')->hasRenderContext()) {
      // The calling code didn't pass a render array that we can attach
      // cacheable metadata to. There is a render context and $build has had
      // cacheability metadata added, so we can simply render it, and it will
      // bubble up to the current theme hook.
      \Drupal::service('renderer')->render($build);
    }
  }

  return $access_checked_referenced_entities ?? [];
}

/**
 * Gets the first referenced entity in a field of an entity.
 *
 * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
 *   The host entity.
 * @param string $field_name
 *   The entity reference field.
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 *
 * @return \Drupal\Core\Entity\EntityInterface|null
 *   Referenced entity.
 */
function civictheme_get_field_referenced_entity(FieldableEntityInterface $entity, string $field_name, array &$build = []): ?EntityInterface {
  $referenced_entity = NULL;

  $entities = civictheme_get_field_referenced_entities($entity, $field_name, $build);
  if (!empty($entities)) {
    $referenced_entity = reset($entities);
    if (!$referenced_entity instanceof EntityInterface) {
      $referenced_entity = NULL;
    }
  }

  return $referenced_entity;
}

/**
 * Get labels of the referenced entities.
 *
 * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
 *   The host entity.
 * @param string $field_name
 *   The entity reference field.
 * @return array<int,string>
 *   The label(s).
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_referenced_entity_labels(FieldableEntityInterface $entity, string $field_name): array {
  $labels = [];
  $referenced_entities = civictheme_get_field_value($entity, $field_name) ?? [];
  foreach ($referenced_entities as $referenced_entity) {
    if ($referenced_entity instanceof EntityInterface) {
      $labels[] = Xss::filter((string) $referenced_entity->label());
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

  return $field_name ? civictheme_get_field_value($entity, $field_name, TRUE, $default) : $default;
}

/**
 * Theme options suitable for FormAPI.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function civictheme_theme_options($keys = FALSE) {
  $map = [
    CivicthemeConstants::THEME_LIGHT => CivicthemeConstants::THEME_LIGHT_LABEL,
    CivicthemeConstants::THEME_DARK => CivicthemeConstants::THEME_DARK_LABEL,
  ];

  return $keys ? array_keys($map) : $map;
}

/**
 * Type options suitable for FormAPI.
 */
function civictheme_type_options() {
  return [
    'default' => 'Default',
    'stacked' => 'Stacked',
    'inline' => 'Inline',
    'inline_stacked' => 'Inline Stacked',
  ];
}

/**
 * Get the nid that the Automated list block is embedded in.
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

/**
 * Check if the provided field is wysiwyg.
 *
 * @param string $field_name
 *   Field name to check.
 *
 * @return bool
 *   TRUE if the field is wysiwyg, FALSE otherwise.
 */
function _civictheme_field_is_wysiwyg(string $field_name) {
  $wysiwyg_field_list = [
    'field_c_n_body',
    'field_c_p_content',
  ];
  if (in_array($field_name, $wysiwyg_field_list)) {
    return TRUE;
  }

  return FALSE;
}

/**
 * File icon symbol provider.
 *
 * @param \Drupal\file\Entity\File $file
 *   File object.
 *
 * @return string
 *   Return Symbol.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function civictheme_get_icon_from_file(File $file) {
  $mime_type = $file->getMimeType();

  switch ($mime_type) {
    // Word document types.
    case 'application/msword':
    case 'application/vnd.ms-word.document.macroEnabled.12':
    case 'application/vnd.oasis.opendocument.text':
    case 'application/vnd.oasis.opendocument.text-template':
    case 'application/vnd.oasis.opendocument.text-master':
    case 'application/vnd.oasis.opendocument.text-web':
    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
    case 'application/vnd.stardivision.writer':
    case 'application/vnd.sun.xml.writer':
    case 'application/vnd.sun.xml.writer.template':
    case 'application/vnd.sun.xml.writer.global':
    case 'application/vnd.wordperfect':
    case 'application/x-abiword':
    case 'application/x-applix-word':
    case 'application/x-kword':
    case 'application/x-kword-crypt':
      return 'word-file';

    // Spreadsheet document types.
    case 'application/vnd.ms-excel':
    case 'application/vnd.ms-excel.sheet.macroEnabled.12':
    case 'application/vnd.oasis.opendocument.spreadsheet':
    case 'application/vnd.oasis.opendocument.spreadsheet-template':
    case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
    case 'application/vnd.stardivision.calc':
    case 'application/vnd.sun.xml.calc':
    case 'application/vnd.sun.xml.calc.template':
    case 'application/vnd.lotus-1-2-3':
    case 'application/x-applix-spreadsheet':
    case 'application/x-gnumeric':
    case 'application/x-kspread':
    case 'application/x-kspread-crypt':
      return 'download-file';

    // Presentation document types.
    case 'application/vnd.ms-powerpoint':
    case 'application/vnd.ms-powerpoint.presentation.macroEnabled.12':
    case 'application/vnd.oasis.opendocument.presentation':
    case 'application/vnd.oasis.opendocument.presentation-template':
    case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
    case 'application/vnd.stardivision.impress':
    case 'application/vnd.sun.xml.impress':
    case 'application/vnd.sun.xml.impress.template':
    case 'application/x-kpresenter':
      return 'download-file';

    // PDF types.
    case 'application/pdf':
    case 'application/x-pdf':
    case 'applications/vnd.pdf':
    case 'text/pdf':
    case 'text/x-pdf':
      return 'pdf-file';

    default:
      return 'download-file';
  }
}

/**
 * Check if the provided link is external.
 *
 * @param string $url
 *   The url to be validated.
 * @param string $base_url
 *   The base URL string to check against, such as "http://example.com/".
 * @param array $override_domains
 *   Array of overridden domains.
 *
 * @return bool
 *   TRUE if the provided URL is external, FALSE if not external or not a valid
 *   URL.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_url_is_external($url, $base_url, array $override_domains = []) {
  if (empty($url) || !UrlHelper::isValid($url)) {
    return FALSE;
  }

  $url_parts = parse_url($url);
  if (!empty($url_parts['scheme']) && empty($url_parts['host'])) {
    return FALSE;
  }

  if (UrlHelper::isExternal($url)) {
    $override_domains[] = $base_url;
    foreach ($override_domains as $override_domain) {
      $override_domain = civictheme_external_link_normalize_domain($override_domain);

      if (!UrlHelper::isValid($override_domain, TRUE)) {
        continue;
      }

      if (UrlHelper::externalIsLocal($url, $override_domain)) {
        return FALSE;
      }
    }

    return TRUE;
  }

  return FALSE;
}

/**
 * Normalise domain.
 */
function civictheme_external_link_normalize_domain($domain) {
  // Check that the part of the domain before first dot has protocol and
  // add one if it does not.
  if (!str_contains(substr($domain, 0, strpos($domain, '.')), ':')) {
    $domain = 'https://' . (str_starts_with($domain, '//') ? str_replace('//', '', $domain) : $domain);
  }

  return $domain;
}

/**
 * Extract card name from card and reference card paragraph bundles.
 */
function _civictheme_get_card_name_from_bundle($bundle) {
  if (preg_match('/civictheme_(.*_card).*/', $bundle, $matches)) {
    if (!empty($matches[1])) {
      return $matches[1];
    }
  }

  return FALSE;
}

/**
 * Check if the CivicTheme feature was opted out.
 *
 * @param string $type
 *   The type of the optout.
 * @param string $name
 *   The name of the optout flag.
 * @param mixed $context
 *   Optional context to pass to assess the optout.
 *
 * @return bool
 *   TRUE if a feature should be opted out, FALSE otherwise.
 */
function _civictheme_feature_is_optedout($type, $name, $context = NULL) {
  $optedout = FALSE;

  switch ($type) {
    case 'views':
      // For views, use view Tag as an opt-out data source.
      if (!empty($context) && $context instanceof ViewExecutable) {
        $view = $context;
        $tags = $view->storage->get('tag');
        $tags = !empty($tags) ? explode(',', $tags) : [];
        $tags = array_map('trim', $tags);

        if (in_array($name, $tags)) {
          $optedout = TRUE;
        }
      }
      break;

    default:
      try {
        $optouts = civictheme_get_theme_config_manager()->load('optouts', []);
        // Additionally filter using available flags to ensure that only
        // correctly mapped flags are used.
        if (array_key_exists($name, _civictheme_feature_optout_flags())) {
          $optedout = in_array($name, $optouts);
        }
      }
      catch (\Exception $e) {
        $optedout = FALSE;
      }
  }

  return $optedout;
}

/**
 * Map of available opt-out flags.
 */
function _civictheme_feature_optout_flags() {
  return [
    'components.link' => t('Links processing'),
    'components.link.email' => t('Email links processing'),
  ];
}
