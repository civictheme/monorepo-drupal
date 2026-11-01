<?php

declare(strict_types=1);

namespace Drupal\civictheme\Utility;

use Drupal\block_content\Entity\BlockContent;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Entity field value helpers.
 *
 * The procedural civictheme_*field*() functions delegate here.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
final class CivicthemeFieldHelper {

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
  public static function hasValue(FieldableEntityInterface $entity, string $field_name): bool {
    return $entity->hasField($field_name) && !$entity->get($field_name)->isEmpty();
  }

  /**
   * Gets values from fields that CivicTheme regularly uses.
   *
   * This helper complements the field API system, providing a convenient way
   * to retrieve commonly used field values specific to CivicTheme.
   *
   * If a field type is not listed, and you need to retrieve its value,
   * consider using the field API system directly.
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
   */
  public static function getValue(FieldableEntityInterface $entity, string $field_name, bool $only_first = FALSE, mixed $default = NULL, array &$build = []): mixed {
    $value = $default;

    if (!static::hasValue($entity, $field_name)) {
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
          $value = static::getReferencedEntity($entity, $field_name, $build);
        }
        else {
          $value = static::getReferencedEntities($entity, $field_name, $build);
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
   */
  public static function getReferencedEntities(FieldableEntityInterface $entity, string $field_name, array &$build = []): array {
    $trigger_bc = empty($build);
    if ($trigger_bc) {
      @trigger_error('Calling civictheme_get_field_referenced_entities() without the $build argument is deprecated in civictheme:1.12.0 It will be required in civictheme:1.13.0. Triggered when getting entity for ' . $field_name . '. See https://www.drupal.org/node/3552745', E_USER_DEPRECATED);
    }
    if (static::hasValue($entity, $field_name)) {
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
        // cacheability metadata added, so we can simply render it, and it
        // will bubble up to the current theme hook.
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
  public static function getReferencedEntity(FieldableEntityInterface $entity, string $field_name, array &$build = []): ?EntityInterface {
    $referenced_entity = NULL;

    $entities = static::getReferencedEntities($entity, $field_name, $build);
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
   */
  public static function getReferencedEntityLabels(FieldableEntityInterface $entity, string $field_name, array &$build = []): array {
    $labels = [];
    $referenced_entities = static::getReferencedEntities($entity, $field_name, $build);
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
  public static function getKeyValues(FieldableEntityInterface $entity, string $field_name, string $value_key = 'value'): array {
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
   *   Optional default theme value. Defaults to
   *   CivicthemeConstants::THEME_LIGHT.
   *
   * @return string
   *   The theme field value for the entity.
   *   If field does not have a value - $default is returned.
   */
  public static function getThemeValue(FieldableEntityInterface|null $entity, string $default = CivicthemeConstants::THEME_LIGHT): string {
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

    return $field_name ? (string) static::getValue($entity, $field_name, TRUE, $default) : $default;
  }

  /**
   * Check if the provided field type is a wysiwyg field type.
   *
   * @param string $field_type
   *   Field type to check.
   *
   * @return bool
   *   TRUE if the field is wysiwyg, FALSE otherwise.
   */
  public static function isWysiwygFieldType(string $field_type): bool {
    /** @var \Drupal\Core\Field\FieldTypePluginManager $field_type_manager */
    $field_type_manager = \Drupal::service('plugin.manager.field.field_type');
    $field_definition = $field_type_manager->getDefinition($field_type, FALSE);
    if ($field_definition === NULL || empty($field_definition['class'])) {
      return FALSE;
    }
    return is_subclass_of($field_definition['class'], '\Drupal\text\Plugin\Field\FieldType\TextItemBase');
  }

}
