<?php

/**
 * @file
 * CivicTheme public API functions.
 *
 * Procedural wrappers around the CivicTheme helper classes, kept for
 * sub-themes and consumer sites that call the civictheme_*() functions.
 * New code should use the classes under \Drupal\civictheme directly:
 * - \Drupal\civictheme\Utility\CivicthemeField
 * - \Drupal\civictheme\Utility\CivicthemeMedia
 * - \Drupal\civictheme\Utility\CivicthemeDate
 * - \Drupal\civictheme\Utility\CivicthemeLink
 * - \Drupal\civictheme\Utility\CivicthemeAttribute
 * - \Drupal\civictheme\Utility\CivicthemeNode
 * - \Drupal\civictheme\Utility\CivicthemeOptions
 * - \Drupal\civictheme\CivicthemeUtility.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;
use Drupal\civictheme\Theme\Form\FormElementHelper;
use Drupal\civictheme\Utility\CivicthemeAttribute;
use Drupal\civictheme\Utility\CivicthemeDate;
use Drupal\civictheme\Utility\CivicthemeField;
use Drupal\civictheme\Utility\CivicthemeLink;
use Drupal\civictheme\Utility\CivicthemeMedia;
use Drupal\civictheme\Utility\CivicthemeNode;
use Drupal\civictheme\Utility\CivicthemeOptions;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\file\FileInterface;
use Drupal\media\MediaInterface;
use Drupal\node\NodeInterface;

/**
 * Get a theme config manager.
 *
 * @return \Drupal\civictheme\CivicthemeConfigManager
 *   The theme config manager.
 *
 * @see \Drupal\civictheme\CivicthemeConfigManager
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_get_theme_config_manager(): CivicthemeConfigManager {
  return \Drupal::service('class_resolver')->getInstanceFromDefinition(CivicthemeConfigManager::class);
}

/**
 * Load node or the node revision if the route is a node revision.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeNode::loadNodeOrRevision()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_load_node_or_revision(array $variables): ?NodeInterface {
  return CivicthemeNode::loadNodeOrRevision($variables);
}

/**
 * Get Layout Builder settings for all view modes of an entity.
 *
 * @return array<string, mixed>
 *   Layout Builder settings keyed by view mode.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeNode::getLayoutBuilderSettingsPerViewMode()
 *
 * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_get_layout_builder_settings_per_view_mode(string $entity_type_id, string $bundle, bool $only_enabled = TRUE): array {
  return CivicthemeNode::getLayoutBuilderSettingsPerViewMode($entity_type_id, $bundle, $only_enabled);
}

/**
 * Get variables from media.
 *
 * @return array<string, mixed>|null
 *   Media variables or NULL if not resolvable.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMedia::getVariables()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_media_get_variables(MediaInterface $media): ?array {
  return CivicthemeMedia::getVariables($media);
}

/**
 * Get variables from Image media.
 *
 * @return array<string, mixed>|null
 *   Image variables or NULL if not resolvable.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMedia::getImageVariables()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_media_image_get_variables(MediaInterface $media, ?string $image_style = NULL): ?array {
  return CivicthemeMedia::getImageVariables($media, $image_style);
}

/**
 * Get the media source file URL.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMedia::getUrl()
 *
 * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_media_get_url(MediaInterface $media, bool $is_relative = TRUE): ?string {
  return CivicthemeMedia::getUrl($media, $is_relative);
}

/**
 * Get Image media file URL, optionally using an image style.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMedia::getImageUrl()
 *
 * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_media_image_get_url(MediaInterface $media, ?string $image_style = NULL, bool $is_relative = TRUE): ?string {
  return CivicthemeMedia::getImageUrl($media, $image_style, $is_relative);
}

/**
 * Embed SVG from provided URL.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMedia::embedSvg()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_embed_svg(string $url, array $css_classes = []): ?string {
  return CivicthemeMedia::embedSvg($url, $css_classes);
}

/**
 * File icon symbol provider.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMedia::getIconFromFile()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_get_icon_from_file(FileInterface $file): string {
  return CivicthemeMedia::getIconFromFile($file);
}

/**
 * Add modifier class to the variables array, preserving existing classes.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeAttribute::addModifierClass()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_add_modifier_class(array &$variables, string|array $classes): void {
  CivicthemeAttribute::addModifierClass($variables, $classes);
}

/**
 * Remove modifier class from the variables array.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeAttribute::removeModifierClass()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_remove_modifier_class(array &$variables, string|array $classes): void {
  CivicthemeAttribute::removeModifierClass($variables, $classes);
}

/**
 * Preprocess 'attribute' classes variables into 'modifier_class'.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeAttribute::convertAttributesToModifierClass()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_convert_attributes_to_modifier_class(array &$variables): void {
  CivicthemeAttribute::convertAttributesToModifierClass($variables);
}

/**
 * Format date and time.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeDate::format()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_format_datetime(int|string|DrupalDateTime $datetime, string $format_or_type = 'civictheme_short_date_and_time'): string {
  return CivicthemeDate::format($datetime, $format_or_type);
}

/**
 * Format date and time in ISO format.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeDate::formatIso()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_format_datetime_iso(int|string|DrupalDateTime $datetime): string {
  return CivicthemeDate::formatIso($datetime);
}

/**
 * Checks whether the field exists and has a value on a given entity.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeField::hasValue()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_field_has_value(FieldableEntityInterface $entity, string $field_name): bool {
  return CivicthemeField::hasValue($entity, $field_name);
}

/**
 * Gets values from fields that CivicTheme regularly uses.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeField::getValue()
 *
 * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_get_field_value(FieldableEntityInterface $entity, string $field_name, bool $only_first = FALSE, mixed $default = NULL, array &$build = []): mixed {
  return CivicthemeField::getValue($entity, $field_name, $only_first, $default, $build);
}

/**
 * Gets the referenced entities in a field of an entity.
 *
 * @return array<int|string, \Drupal\Core\Entity\EntityInterface>
 *   Referenced entities.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeField::getReferencedEntities()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_get_field_referenced_entities(FieldableEntityInterface $entity, string $field_name, array &$build = []): array {
  return CivicthemeField::getReferencedEntities($entity, $field_name, $build);
}

/**
 * Gets the first referenced entity in a field of an entity.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeField::getReferencedEntity()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_get_field_referenced_entity(FieldableEntityInterface $entity, string $field_name, array &$build = []): ?EntityInterface {
  return CivicthemeField::getReferencedEntity($entity, $field_name, $build);
}

/**
 * Get labels of the referenced entities.
 *
 * @return array<int, string>
 *   Entity labels.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeField::getReferencedEntityLabels()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_get_referenced_entity_labels(FieldableEntityInterface $entity, string $field_name, array &$build = []): array {
  return CivicthemeField::getReferencedEntityLabels($entity, $field_name, $build);
}

/**
 * Get raw multivalued field values from the entity.
 *
 * @return array<int|string, mixed>
 *   Field values.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeField::getKeyValues()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_get_entity_field_key_values(FieldableEntityInterface $entity, string $field_name, string $value_key = 'value'): array {
  return CivicthemeField::getKeyValues($entity, $field_name, $value_key);
}

/**
 * Get an entity's theme field value.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeField::getThemeValue()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_get_field_theme_value(FieldableEntityInterface|null $entity, string $default = CivicthemeConstants::THEME_LIGHT): string {
  return CivicthemeField::getThemeValue($entity, $default);
}

/**
 * Theme options suitable for FormAPI.
 *
 * @return array<string, string>|array<string>
 *   Theme options.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeOptions::themeOptions()
 *
 * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_theme_options(bool $only_keys = FALSE): array {
  return CivicthemeOptions::themeOptions($only_keys);
}

/**
 * Type options suitable for FormAPI.
 *
 * @return array<string, string>
 *   Type options.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeOptions::typeOptions()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_type_options(): array {
  return CivicthemeOptions::typeOptions();
}

/**
 * Render a render array to string.
 *
 * @see \Drupal\civictheme\CivicthemeUtility::render()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_render(mixed $render): string {
  return CivicthemeUtility::render($render);
}

/**
 * Check whether element is a webform element.
 *
 * @see \Drupal\civictheme\Theme\Form\FormElementHelper::isWebformElement()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_is_webform_element(array $element): bool {
  return FormElementHelper::isWebformElement($element);
}

/**
 * Check if the provided link is external.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeLink::urlIsExternal()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_url_is_external(string $url, string $base_url, array $override_domains = []): bool {
  return CivicthemeLink::urlIsExternal($url, $base_url, $override_domains);
}

/**
 * Check if a URL is valid.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeLink::urlIsValid()
 *
 * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_url_is_valid(string $url, bool $absolute = FALSE): bool {
  return CivicthemeLink::urlIsValid($url, $absolute);
}

/**
 * Normalise domain.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeLink::normalizeDomain()
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_external_link_normalize_domain(string $domain): string {
  return CivicthemeLink::normalizeDomain($domain);
}
