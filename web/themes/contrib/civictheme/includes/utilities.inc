<?php

/**
 * @file
 * Theme utility functions.
 *
 * @todo Move all functions to CivicthemeUtility class.
 */

declare(strict_types=1);

use Drupal\block_content\Entity\BlockContent;
use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\File\Exception\InvalidStreamWrapperException;
use Drupal\Core\StringTranslation\ByteSizeMarkup;
use Drupal\Core\Template\Attribute;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\media\MediaInterface;
use Drupal\media\Plugin\media\Source\OEmbedInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\Entity\Paragraph;
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
function civictheme_get_theme_config_manager(): CivicthemeConfigManager {
  return \Drupal::service('class_resolver')->getInstanceFromDefinition(CivicthemeConfigManager::class);
}

/**
 * Load node or the node revision if the route is a node revision.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_load_node_or_revision(array $variables): ?NodeInterface {
  $node = $variables['node'] ?? NULL;

  if (is_numeric($node)) {
    // Check to see if it's a node revision route.
    /** @var \Drupal\Core\Routing\RouteMatchInterface $route_match */
    $route_match = \Drupal::service('current_route_match');
    $node_revision = $route_match->getParameter('node_revision');
    if ($node_revision instanceof NodeInterface) {
      $node = $node_revision;
    }
  }

  return $node;
}

/**
 * Get Layout Builder settings for all view modes of an entity.
 *
 * @param string $entity_type_id
 *   Entity type ID.
 * @param string $bundle
 *   Bundle name.
 * @param bool $only_enabled
 *   (optional) Whether to return settings only for when the Layout Builder
 *   is enabled for the view mode. Defaults to TRUE.
 *
 * @return array<string, mixed>
 *   An array of Layout Builder settings keyed by view mode.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function civictheme_get_layout_builder_settings_per_view_mode(string $entity_type_id, string $bundle, $only_enabled = TRUE): array {
  $settings_per_mode = [];

  /** @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository */
  $entity_display_repository = \Drupal::service('entity_display.repository');
  $view_modes = array_keys($entity_display_repository->getViewModes($entity_type_id));
  array_unshift($view_modes, 'default');

  foreach ($view_modes as $view_mode) {
    $view_display = EntityViewDisplay::load("$entity_type_id.$bundle.$view_mode");
    if ($view_display) {
      $lb_settings = $view_display->getThirdPartySettings('layout_builder');
      if ($only_enabled && empty($lb_settings['enabled'])) {
        continue;
      }

      $settings_per_mode[$view_mode] = $lb_settings;
    }
  }

  return $settings_per_mode;
}

/**
 * Get variables from media.
 *
 * @param \Drupal\media\MediaInterface $media
 *   Host Media entity for image.
 *
 * @return array<string, mixed>|null
 *   Variables array or NULL if not available.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_media_get_variables(MediaInterface $media): ?array {
  $label = $media->label();
  $variables = [
    'media_name' => t('@name', ['@name' => $label]),
    'created' => civictheme_format_datetime((int) $media->getCreatedTime(), 'civictheme_short_date'),
    'changed' => civictheme_format_datetime($media->getChangedTime(), 'civictheme_short_date'),
  ];
  $source = $media->getSource();
  if ($source instanceof OEmbedInterface) {
    $variables['name'] = t('@name', ['@name' => $label]);
    $url = $source->getMetadata($media, 'url');
    if (!empty($url)) {
      $variables['url'] = $url;
    }
    return $variables;
  }

  $fid = $media->getSource()->getSourceFieldValue($media);
  /** @var \Drupal\file\FileInterface|null $file */
  $file = $fid ? File::load($fid) : NULL;

  if (empty($file)) {
    return NULL;
  }

  $variables += [
    'name' => t('@name', ['@name' => $file->label()]),
    'ext' => pathinfo((string) $file->getFileUri(), PATHINFO_EXTENSION) ?: '',
    'url' => civictheme_media_get_url($media),
    'size' => ByteSizeMarkup::create($file->getSize()),
    'icon' => civictheme_get_icon_from_file($file),
  ];

  return $variables;
}

/**
 * Get variables from Image media.
 *
 * @param \Drupal\media\MediaInterface $media
 *   Host Media entity for image.
 * @param string $image_style
 *   Name of the image style to apply.
 *
 * @return array<string, mixed>|null
 *   Image variables array or NULL if not available.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_media_image_get_variables(MediaInterface $media, ?string $image_style = NULL): ?array {
  if (!in_array($media->bundle(), ['civictheme_image', 'civictheme_icon'])) {
    return NULL;
  }

  $variables = civictheme_media_get_variables($media);

  if ($variables['ext'] == 'svg') {
    $image_style = NULL;
  }

  $url = civictheme_media_image_get_url($media, $image_style);
  $image_url = \Drupal::service('file_url_generator')->generateString($url);
  $variables['url'] = \Drupal::service('file_url_generator')->transformRelative($image_url);

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
 * @param bool $is_relative
 *   (optional) Whether the URL should be root-relative, defaults to TRUE.
 *
 * @return string|null
 *   The relative URL for the source file or FALSE if the file was not found.
 *
 * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
 *   If a stream wrapper could not be found to generate an external URL.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function civictheme_media_get_url(MediaInterface $media, bool $is_relative = TRUE): ?string {
  $file = File::load($media->getSource()->getSourceFieldValue($media));
  try {
    return $file !== NULL ? $file->createFileUrl($is_relative) : NULL;
  }
  catch (InvalidStreamWrapperException $e) {
    return NULL;
  }
}

/**
 * Get Image media file URL, optionally using an image style.
 *
 * @param \Drupal\media\MediaInterface $media
 *   The media entity to derive the URL from.
 * @param string $image_style
 *   (optional) Image style name.
 * @param bool $is_relative
 *   (optional) Whether the URL should be root-relative, defaults to TRUE.
 *
 * @return string|null
 *   The relative URL for the source file or FALSE if the file was not found.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function civictheme_media_image_get_url(MediaInterface $media, ?string $image_style = NULL, bool $is_relative = TRUE): ?string {
  $src = NULL;

  $file = File::load($media->getSource()->getSourceFieldValue($media));

  if ($file !== NULL) {
    if ($image_style) {
      $style = ImageStyle::load($image_style);
      $src = $style !== NULL ? $style->buildUrl($file->get('uri')->getString()) : NULL;
    }
    else {
      $src = $file->createFileUrl($is_relative);
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
  // Excludes <a>, <script> and <style>.
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
 * Add modifier class to the variables array, preserving existing classes.
 *
 * @param array $variables
 *   Variables array.
 * @param string|array $classes
 *   Classes to add.
 */
function civictheme_add_modifier_class(array &$variables, string|array $classes): void {
  $classes = is_array($classes) ? $classes : [$classes];
  $existing_classes = explode(' ', $variables['modifier_class'] ?? '');
  $new_classes = array_merge($existing_classes, $classes);
  $variables['modifier_class'] = trim(implode(' ', array_unique($new_classes)));
}

/**
 * Remove modifier class from the variables array, preserving existing classes.
 *
 * @param array $variables
 *   Variables array.
 * @param string|array $classes
 *   Classes to remove.
 */
function civictheme_remove_modifier_class(array &$variables, string|array $classes): void {
  if (!isset($variables['modifier_class'])) {
    return;
  }
  $classes = is_array($classes) ? $classes : [$classes];
  $existing_classes = explode(' ', $variables['modifier_class']);
  $remaining_classes = array_diff($existing_classes, $classes);
  $variables['modifier_class'] = trim(implode(' ', $remaining_classes));
}

/**
 * Preprocess 'attribute' classes variables into 'modifier_class'.
 *
 * Preprocessors can set $variables['modifier_class'] = FALSE to disable this
 * conversion.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_convert_attributes_to_modifier_class(array &$variables): void {
  // Allow to skip this conversion.
  if (isset($variables['modifier_class']) && $variables['modifier_class'] === FALSE) {
    return;
  }

  if (!isset($variables['attributes']['class'])) {
    return;
  }

  $attributes = new Attribute($variables['attributes']);
  civictheme_add_modifier_class($variables, $attributes->getClass()->value());

  // Remove class from attributes to avoid duplicated 'class' attribute on
  // the element.
  unset($variables['attributes']['class']);
  $variables['attributes'] = new Attribute($variables['attributes']);
}

/**
 * Format date and time.
 *
 * This is a centralized function for formatting dates in CivicTheme.
 *
 * @param int|string|\Drupal\Core\Datetime\DrupalDateTime $datetime
 *   Timestamp, date as a string or DrupalDateTime object.
 * @param string $format_or_type
 *   CivicTheme format type (starting with 'civictheme_') or actual PHP's
 *   date format string. Defaults to 'civictheme_short_date_and_time'.
 *
 * @return string
 *   Formatted date and time.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_format_datetime(int|string|DrupalDateTime $datetime, string $format_or_type = 'civictheme_short_date_and_time'): string {
  $type = 'custom';
  $format = $format_or_type;

  if (str_starts_with($format_or_type, 'civictheme_')) {
    $type = $format_or_type;
    $format = '';
  }

  if ($datetime instanceof DrupalDateTime) {
    $datetime = $datetime->getTimestamp();
  }
  elseif (is_string($datetime)) {
    $datetime = (int) strtotime($datetime);
  }

  return \Drupal::service('date.formatter')->format($datetime, $type, $format);
}

/**
 * Format date and time in ISO format.
 *
 * @param int|string|\Drupal\Core\Datetime\DrupalDateTime $datetime
 *   Timestamp, date as a string or DrupalDateTime object.
 *
 * @return string
 *   Formatted date and time.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_format_datetime_iso(int|string|DrupalDateTime $datetime): string {
  return civictheme_format_datetime($datetime, 'c');
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
function civictheme_field_has_value(FieldableEntityInterface $entity, string $field_name): bool {
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
 *
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
      $value = strip_tags($value);
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
      if ($only_first) {
        $value = $field->first()->view();
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
 *
 * @return array<int|string, \Drupal\Core\Entity\EntityInterface>
 *   Referenced entities.
 *
 * @SuppressWarnings(PHPMD.ErrorControlOperator)
 * @SuppressWarnings(PHPMD.StaticAccess)
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
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 *
 * @return array<int,string>
 *   The label(s).
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_referenced_entity_labels(FieldableEntityInterface $entity, string $field_name, array &$build = []): array {
  $labels = [];
  $referenced_entities = civictheme_get_field_referenced_entities($entity, $field_name, $build);
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
 * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
 *   The entity to get the values from.
 * @param string $field_name
 *   The field name.
 * @param string $value_key
 *   (optional) The value key. Default to 'value'.
 *
 * @return array<int|string, mixed>
 *   Array of field values. If field does not exist or incorrect $value_key
 *   provided, empty array will be returned.
 */
function civictheme_get_entity_field_key_values(FieldableEntityInterface $entity, string $field_name, string $value_key = 'value'): array {
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
 * @param \Drupal\Core\Entity\FieldableEntityInterface|null $entity
 *   Entity to check field existence.
 * @param string $default
 *   Optional default theme value. Defaults to CivicthemeConstants::THEME_LIGHT.
 *
 * @return string
 *   The theme field value for the entity.
 *   If field does not have a value - $default is returned.
 */
function civictheme_get_field_theme_value(FieldableEntityInterface|null $entity, string $default = CivicthemeConstants::THEME_LIGHT): string {
  $field_name = FALSE;

  if (!$entity instanceof FieldableEntityInterface) {
    return $default;
  }

  switch ($entity::class) {
    case Paragraph::class:
      $field_name = 'field_c_p_theme';
      break;

    case Node::class:
      $field_name = 'field_c_n_banner_theme';
      break;

    case BlockContent::class:
      $field_name = 'field_c_b_theme';
      break;
  }

  return $field_name ? (string) civictheme_get_field_value($entity, $field_name, TRUE, $default) : $default;
}

/**
 * Theme options suitable for FormAPI.
 *
 * @param bool $only_keys
 *   Whether to return only keys or keys and labels.
 *
 * @return array<string, string>|array<string>
 *   Array of theme options.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function civictheme_theme_options(bool $only_keys = FALSE): array {
  $map = [
    CivicthemeConstants::THEME_LIGHT => CivicthemeConstants::THEME_LIGHT_LABEL,
    CivicthemeConstants::THEME_DARK => CivicthemeConstants::THEME_DARK_LABEL,
  ];

  return $only_keys ? array_keys($map) : $map;
}

/**
 * Type options suitable for FormAPI.
 *
 * @return array<string, string>
 *   Array of type options.
 */
function civictheme_type_options(): array {
  return [
    'default' => 'Default',
    'stacked' => 'Stacked',
    'inline' => 'Inline',
    'inline_stacked' => 'Inline Stacked',
  ];
}

/**
 * Render a render array to string.
 *
 * @param mixed $render
 *   Array or an object to render.
 *
 * @return string
 *   Rendered string.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_render(mixed $render): string {
  $render = is_array($render) ? $render : ['#markup' => $render];

  return (string) \Drupal::service('renderer')->render($render);
}

/**
 * Get DOMDocument object from HTML content.
 *
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function _civictheme_get_dom_document(string $content): \DOMDocument {
  $doc = new \DOMDocument();

  $doc->loadHTML($content);

  return $doc;
}

/**
 * Check if the provided field is wysiwyg.
 *
 * @param string $field_type
 *   Field type to check.
 *
 * @return bool
 *   TRUE if the field is wysiwyg, FALSE otherwise.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_field_is_wysiwyg(string $field_type): bool {
  /** @var \Drupal\Core\Field\FieldTypePluginManager $field_type_manager */
  $field_type_manager = \Drupal::service('plugin.manager.field.field_type');
  $field_definition = $field_type_manager->getDefinition($field_type, FALSE);
  if ($field_definition === NULL || empty($field_definition['class'])) {
    return FALSE;
  }
  return is_subclass_of($field_definition['class'], '\Drupal\text\Plugin\Field\FieldType\TextItemBase');
}

/**
 * File icon symbol provider.
 *
 * @param \Drupal\file\FileInterface $file
 *   File object.
 *
 * @return string
 *   Return Symbol.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function civictheme_get_icon_from_file(FileInterface $file): string {
  $mime_type = $file->getMimeType();

  return match ($mime_type) {
    'application/msword', 'application/vnd.ms-word.document.macroEnabled.12', 'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.text-template', 'application/vnd.oasis.opendocument.text-master', 'application/vnd.oasis.opendocument.text-web', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.stardivision.writer', 'application/vnd.sun.xml.writer', 'application/vnd.sun.xml.writer.template', 'application/vnd.sun.xml.writer.global', 'application/vnd.wordperfect', 'application/x-abiword', 'application/x-applix-word', 'application/x-kword', 'application/x-kword-crypt' => 'word-file',
    'application/vnd.ms-excel', 'application/vnd.ms-excel.sheet.macroEnabled.12', 'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.spreadsheet-template', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.stardivision.calc', 'application/vnd.sun.xml.calc', 'application/vnd.sun.xml.calc.template', 'application/vnd.lotus-1-2-3', 'application/x-applix-spreadsheet', 'application/x-gnumeric', 'application/x-kspread', 'application/x-kspread-crypt' => 'download-file',
    'application/vnd.ms-powerpoint', 'application/vnd.ms-powerpoint.presentation.macroEnabled.12', 'application/vnd.oasis.opendocument.presentation', 'application/vnd.oasis.opendocument.presentation-template', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.stardivision.impress', 'application/vnd.sun.xml.impress', 'application/vnd.sun.xml.impress.template', 'application/x-kpresenter' => 'download-file',
    'application/pdf', 'application/x-pdf', 'applications/vnd.pdf', 'text/pdf', 'text/x-pdf' => 'pdf-file',
    default => 'download-file',
  };
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
function civictheme_url_is_external(string $url, string $base_url, array $override_domains = []): bool {
  if (!civictheme_url_is_valid($url)) {
    return FALSE;
  }

  if (!UrlHelper::isExternal($url)) {
    return FALSE;
  }

  $override_domains[] = $base_url;
  foreach ($override_domains as $override_domain) {
    $override_domain = civictheme_external_link_normalize_domain($override_domain);

    if (!civictheme_url_is_valid($override_domain, TRUE)) {
      continue;
    }

    try {
      if (UrlHelper::externalIsLocal($url, $override_domain)) {
        return FALSE;
      }
    }
    catch (\InvalidArgumentException) {
      return FALSE;
    }
  }

  return TRUE;
}

/**
 * Check if a URL is valid.
 *
 * @param string $url
 *   The URL to be checked.
 * @param bool $absolute
 *   Whether the URL is absolute (beginning with a scheme such as "http:").
 *
 * @return bool
 *   TRUE if the provided URL is valid.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function civictheme_url_is_valid(string $url, bool $absolute = FALSE): bool {
  if (empty($url) || !UrlHelper::isValid($url, $absolute)) {
    return FALSE;
  }

  $url_parts = parse_url($url);

  return !(!empty($url_parts['scheme']) && empty($url_parts['host']));
}

/**
 * Normalise domain.
 *
 * @param string $domain
 *   The domain to be normalised.
 *
 * @return string
 *   Normalised domain.
 */
function civictheme_external_link_normalize_domain(string $domain): string {
  // Check that the part of the domain before first dot has protocol and
  // add one if it does not.
  if (!str_contains(substr($domain, 0, strpos($domain, '.') ?: 0), ':')) {
    $domain = 'https://' . (str_starts_with($domain, '//') ? str_replace('//', '', $domain) : $domain);
  }

  return $domain;
}

/**
 * Extract a configurable component name from a paragraph bundle.
 */
function _civictheme_get_configurable_component_name_from_bundle(string $bundle): ?string {
  // Accommodate for existing and additional components that could be added in
  // the sub-themes.
  if (preg_match('/civictheme_(.*_card|.*_?snippet).*/', $bundle, $matches)) {
    return $matches[1];
  }

  return NULL;
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
function _civictheme_feature_is_optedout(string $type, string $name, mixed $context = NULL): bool {
  $optedout = FALSE;

  switch ($type) {
    case 'views':
      // For views, use view Tag as an opt-out data source.
      if (!empty($context) && $context instanceof ViewExecutable) {
        $view = $context;
        $tags = $view->storage->get('tag');
        $tags = empty($tags) ? [] : explode(',', (string) $tags);
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
      catch (\Exception) {
        $optedout = FALSE;
      }
  }

  return $optedout;
}

/**
 * Map of available opt-out flags.
 *
 * @return array<string,\Drupal\Core\StringTranslation\TranslatableMarkup>
 *   Array of opt-out flags.
 */
function _civictheme_feature_optout_flags(): array {
  $flags = [
    'components.link' => t('Links processing'),
  ];
  $flags[CivicthemeConstants::OPTOUT_SUMMARY_HIDE_ELLIPSIS] = t('Hide card summary ellipsis');
  return $flags;
}
