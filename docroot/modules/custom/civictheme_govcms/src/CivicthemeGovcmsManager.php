<?php

namespace Drupal\civictheme_govcms;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a civictheme_govcms govcms manager.
 */
class CivicthemeGovcmsManager {

  /**
   * The list of removal configurations(methods).
   *
   * @var array
   */
  protected $removalList;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a GovcmsManager Manager object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->removalList = [
      // Item name in CLI => callable.
      'media_types' => [static::class, 'removeGovcmsMediaTypes'],
      'text_format' => [static::class, 'removeGovcmsTextFormat'],
      'fields' => [static::class, 'removeGovcmsFields'],
      'content_types' => [static::class, 'removeGovcmsContentTypes'],
      'vocabularies' => [static::class, 'removeGovcmsVocabularies'],
      'user_roles' => [static::class, 'removeGovcmsUserRoles'],
      'menus' => [static::class, 'removeGovcmsMenus'],
      'pathauto_patterns' => [static::class, 'removeGovcmsPathautoPatterns'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
    $container->get('entity_type.manager')
    );
  }

  /**
   * Removes the requested config.
   *
   * @param string $preserve
   *   The list of config types to preserve.
   */
  public function civicthemeGovcmsRemoveConfig(string $preserve = '') {
    $preserve_list = [];
    if (!empty($preserve)) {
      $preserve_list = explode(',', $preserve);
    }
    foreach ($this->removalList as $key => $function) {
      if (!in_array($key, $preserve_list)) {
        call_user_func($function);
      }
    }
  }

  /**
   * Removes vanilla media types.
   */
  protected function removeGovcmsMediaTypes() {
    $names = [
      'audio',
      'document',
      'image',
      'remote_video',
      'video',
    ];

    foreach ($names as $name) {
      $entity = $this->entityTypeManager->getStorage('media_type')->load($name);
      if ($entity) {
        $entity->delete();
      }
    }
  }

  /**
   * Removes vanilla text formats.
   */
  protected function removeGovcmsTextFormat() {
    $names = [
      'plain_text',
      'rich_text',
    ];

    foreach ($names as $name) {
      $entity = $this->entityTypeManager->getStorage('filter_format')->load($name);
      if ($entity) {
        $entity->delete();
      }
    }
  }

  /**
   * Removes GovCMS fields.
   */
  protected function removeGovcmsFields() {
    // A list of shared fields that should be deleted before deleting
    // types. Non-shared fields will be removed when content types are removed.
    //
    // Note that this update will trigger critical messages that certain fields'
    // bundle configuration is not valid. These messages should be ignored since
    // fields are removed anyway.
    $info = [
      'node' => [
        'govcms_blog_article' => [
          'field_attachments',
          'field_thumbnail',
        ],
        'govcms_event' => [
          'field_attachments',
          'field_featured_image',
          'field_thumbnail',
        ],
        'govcms_news_and_media' => [
          'field_featured_image',
          'field_thumbnail',
        ],
        'govcms_standard_page' => [
          'field_featured_image',
          'field_thumbnail',
          'field_components',
        ],
        'govcms_foi' => [
          'field_thumbnail',
        ],
      ],
    ];

    foreach ($info as $entity_type => $field_info) {
      foreach ($field_info as $bundle => $field_names) {
        try {
          field_entity_bundle_delete($entity_type, $bundle);
        }
        catch (\Exception $e) {
          // Do nothing - try to remove fields manually below.
        }
        foreach ($field_names as $field_name) {
          $field_config = FieldConfig::loadByName($entity_type, $bundle, $field_name);
          if ($field_config) {
            $field_config->delete();
          }
        }
      }
    }

    foreach ($info as $entity_type => $field_info) {
      foreach ($field_info as $field_names) {
        foreach ($field_names as $field_name) {
          $field_storage = FieldStorageConfig::loadByName($entity_type, $field_name);
          if ($field_storage) {
            $field_storage->delete();
          }
        }
      }
    }

    drupal_flush_all_caches();
  }

  /**
   * Removes GovCMS content types.
   */
  protected function removeGovcmsContentTypes() {
    $names = [
      'govcms_blog_article',
      'govcms_event',
      'govcms_foi',
      'govcms_news_and_media',
      'govcms_standard_page',
    ];

    foreach ($names as $name) {
      $entity = $this->entityTypeManager->getStorage('node_type')->load($name);
      if ($entity) {
        $entity->delete();
      }
    }
  }

  /**
   * Removes GovCMS vocabularies.
   */
  protected function removeGovcmsVocabularies() {
    $names = [
      'govcms_blog_article_categories',
      'govcms_event_categories',
      'govcms_media_tags',
      'govcms_news_categories',
    ];

    foreach ($names as $name) {
      $entity = $this->entityTypeManager->getStorage('taxonomy_vocabulary')->load($name);
      if ($entity) {
        $entity->delete();
      }
    }
  }

  /**
   * Removes GovCMS user roles.
   */
  protected function removeGovcmsUserRoles() {
    $names = [
      'govcms_site_administrator',
      'govcms_content_approver',
      'govcms_content_author',
    ];

    foreach ($names as $name) {
      $entity = $this->entityTypeManager->getStorage('user_role')->load($name);
      if ($entity) {
        $entity->delete();
      }
    }
  }

  /**
   * Removes GovCMS menus.
   */
  protected function removeGovcmsMenus() {
    $names = [
      'govcms-about',
      'govcms-community',
      'main',
      'footer',
    ];

    foreach ($names as $name) {
      $entity = $this->entityTypeManager->getStorage('menu')->load($name);
      if ($entity) {
        $entity->delete();
      }
    }
  }

  /**
   * Removes GovCMS Pathauto patterns.
   */
  protected function removeGovcmsPathautoPatterns() {
    $names = [
      'blog_article',
      'event',
      'foi',
      'news_and_media',
      'standard_page',
    ];

    foreach ($names as $name) {
      $entity = $this->entityTypeManager->getStorage('pathauto_pattern')->load($name);
      if ($entity) {
        $entity->delete();
      }
    }
  }

}
