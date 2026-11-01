<?php

/**
 * @file
 * CivicTheme public API functions.
 *
 * Procedural wrappers around the CivicTheme helper classes, kept for
 * sub-themes and consumer sites that call the civictheme_*() functions.
 * New code should use the classes under \Drupal\civictheme directly:
 * - \Drupal\civictheme\Utility\CivicthemeFieldHelper
 * - \Drupal\civictheme\Utility\CivicthemeMediaHelper
 * - \Drupal\civictheme\Utility\CivicthemeDateHelper
 * - \Drupal\civictheme\Utility\CivicthemeLinkHelper
 * - \Drupal\civictheme\Utility\CivicthemeAttributeHelper
 * - \Drupal\civictheme\Utility\CivicthemeNodeHelper
 * - \Drupal\civictheme\Utility\CivicthemeOptionsHelper
 * - \Drupal\civictheme\CivicthemeUtility.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;
use Drupal\civictheme\Theme\Form\FormElementHelper;
use Drupal\civictheme\Utility\CivicthemeAttributeHelper;
use Drupal\civictheme\Utility\CivicthemeDateHelper;
use Drupal\civictheme\Utility\CivicthemeFieldHelper;
use Drupal\civictheme\Utility\CivicthemeLinkHelper;
use Drupal\civictheme\Utility\CivicthemeMediaHelper;
use Drupal\civictheme\Utility\CivicthemeNodeHelper;
use Drupal\civictheme\Utility\CivicthemeOptionsHelper;
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
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_theme_config_manager(): CivicthemeConfigManager {
  return \Drupal::service('class_resolver')->getInstanceFromDefinition(CivicthemeConfigManager::class);
}

/**
 * Load node or the node revision if the route is a node revision.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeNodeHelper::loadNodeOrRevision()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_load_node_or_revision(array $variables): ?NodeInterface {
  return CivicthemeNodeHelper::loadNodeOrRevision($variables);
}

/**
 * Get Layout Builder settings for all view modes of an entity.
 *
 * @return array<string, mixed>
 *   Layout Builder settings keyed by view mode.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeNodeHelper::getLayoutBuilderSettingsPerViewMode()
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_layout_builder_settings_per_view_mode(string $entity_type_id, string $bundle, bool $only_enabled = TRUE): array {
  return CivicthemeNodeHelper::getLayoutBuilderSettingsPerViewMode($entity_type_id, $bundle, $only_enabled);
}

/**
 * Get variables from media.
 *
 * @return array<string, mixed>|null
 *   Media variables or NULL if not resolvable.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMediaHelper::getVariables()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_media_get_variables(MediaInterface $media): ?array {
  return CivicthemeMediaHelper::getVariables($media);
}

/**
 * Get variables from Image media.
 *
 * @return array<string, mixed>|null
 *   Image variables or NULL if not resolvable.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMediaHelper::getImageVariables()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_media_image_get_variables(MediaInterface $media, ?string $image_style = NULL): ?array {
  return CivicthemeMediaHelper::getImageVariables($media, $image_style);
}

/**
 * Get the media source file URL.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMediaHelper::getUrl()
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_media_get_url(MediaInterface $media, bool $is_relative = TRUE): ?string {
  return CivicthemeMediaHelper::getUrl($media, $is_relative);
}

/**
 * Get Image media file URL, optionally using an image style.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMediaHelper::getImageUrl()
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_media_image_get_url(MediaInterface $media, ?string $image_style = NULL, bool $is_relative = TRUE): ?string {
  return CivicthemeMediaHelper::getImageUrl($media, $image_style, $is_relative);
}

/**
 * Embed SVG from provided URL.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMediaHelper::embedSvg()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_embed_svg(string $url, array $css_classes = []): ?string {
  return CivicthemeMediaHelper::embedSvg($url, $css_classes);
}

/**
 * File icon symbol provider.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeMediaHelper::getIconFromFile()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_icon_from_file(FileInterface $file): string {
  return CivicthemeMediaHelper::getIconFromFile($file);
}

/**
 * Add modifier class to the variables array, preserving existing classes.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeAttributeHelper::addModifierClass()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_add_modifier_class(array &$variables, string|array $classes): void {
  CivicthemeAttributeHelper::addModifierClass($variables, $classes);
}

/**
 * Remove modifier class from the variables array.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeAttributeHelper::removeModifierClass()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_remove_modifier_class(array &$variables, string|array $classes): void {
  CivicthemeAttributeHelper::removeModifierClass($variables, $classes);
}

/**
 * Preprocess 'attribute' classes variables into 'modifier_class'.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeAttributeHelper::convertAttributesToModifierClass()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_convert_attributes_to_modifier_class(array &$variables): void {
  CivicthemeAttributeHelper::convertAttributesToModifierClass($variables);
}

/**
 * Format date and time.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeDateHelper::format()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_format_datetime(int|string|DrupalDateTime $datetime, string $format_or_type = 'civictheme_short_date_and_time'): string {
  return CivicthemeDateHelper::format($datetime, $format_or_type);
}

/**
 * Format date and time in ISO format.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeDateHelper::formatIso()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_format_datetime_iso(int|string|DrupalDateTime $datetime): string {
  return CivicthemeDateHelper::formatIso($datetime);
}

/**
 * Checks whether the field exists and has a value on a given entity.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeFieldHelper::hasValue()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_field_has_value(FieldableEntityInterface $entity, string $field_name): bool {
  return CivicthemeFieldHelper::hasValue($entity, $field_name);
}

/**
 * Gets values from fields that CivicTheme regularly uses.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeFieldHelper::getValue()
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_field_value(FieldableEntityInterface $entity, string $field_name, bool $only_first = FALSE, mixed $default = NULL, array &$build = []): mixed {
  return CivicthemeFieldHelper::getValue($entity, $field_name, $only_first, $default, $build);
}

/**
 * Gets the referenced entities in a field of an entity.
 *
 * @return array<int|string, \Drupal\Core\Entity\EntityInterface>
 *   Referenced entities.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeFieldHelper::getReferencedEntities()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_field_referenced_entities(FieldableEntityInterface $entity, string $field_name, array &$build = []): array {
  return CivicthemeFieldHelper::getReferencedEntities($entity, $field_name, $build);
}

/**
 * Gets the first referenced entity in a field of an entity.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeFieldHelper::getReferencedEntity()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_field_referenced_entity(FieldableEntityInterface $entity, string $field_name, array &$build = []): ?EntityInterface {
  return CivicthemeFieldHelper::getReferencedEntity($entity, $field_name, $build);
}

/**
 * Get labels of the referenced entities.
 *
 * @return array<int, string>
 *   Entity labels.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeFieldHelper::getReferencedEntityLabels()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_referenced_entity_labels(FieldableEntityInterface $entity, string $field_name, array &$build = []): array {
  return CivicthemeFieldHelper::getReferencedEntityLabels($entity, $field_name, $build);
}

/**
 * Get raw multivalued field values from the entity.
 *
 * @return array<int|string, mixed>
 *   Field values.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeFieldHelper::getKeyValues()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_entity_field_key_values(FieldableEntityInterface $entity, string $field_name, string $value_key = 'value'): array {
  return CivicthemeFieldHelper::getKeyValues($entity, $field_name, $value_key);
}

/**
 * Get an entity's theme field value.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeFieldHelper::getThemeValue()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_get_field_theme_value(FieldableEntityInterface|null $entity, string $default = CivicthemeConstants::THEME_LIGHT): string {
  return CivicthemeFieldHelper::getThemeValue($entity, $default);
}

/**
 * Theme options suitable for FormAPI.
 *
 * @return array<string, string>|array<string>
 *   Theme options.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeOptionsHelper::themeOptions()
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_theme_options(bool $only_keys = FALSE): array {
  return CivicthemeOptionsHelper::themeOptions($only_keys);
}

/**
 * Type options suitable for FormAPI.
 *
 * @return array<string, string>
 *   Type options.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeOptionsHelper::typeOptions()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_type_options(): array {
  return CivicthemeOptionsHelper::typeOptions();
}

/**
 * Render a render array to string.
 *
 * @see \Drupal\civictheme\CivicthemeUtility::render()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_render(mixed $render): string {
  return CivicthemeUtility::render($render);
}

/**
 * Check whether element is a webform element.
 *
 * @see \Drupal\civictheme\Theme\Form\FormElementHelper::isWebformElement()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_is_webform_element(array $element): bool {
  return FormElementHelper::isWebformElement($element);
}

/**
 * Check if the provided link is external.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeLinkHelper::urlIsExternal()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_url_is_external(string $url, string $base_url, array $override_domains = []): bool {
  return CivicthemeLinkHelper::urlIsExternal($url, $base_url, $override_domains);
}

/**
 * Check if a URL is valid.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeLinkHelper::urlIsValid()
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_url_is_valid(string $url, bool $absolute = FALSE): bool {
  return CivicthemeLinkHelper::urlIsValid($url, $absolute);
}

/**
 * Normalise domain.
 *
 * @see \Drupal\civictheme\Utility\CivicthemeLinkHelper::normalizeDomain()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_external_link_normalize_domain(string $domain): string {
  return CivicthemeLinkHelper::normalizeDomain($domain);
}
