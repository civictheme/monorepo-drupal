<?php

namespace Drupal\civictheme_migrate;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\civictheme_migrate\Utils\CivicthemeTrait;
use Drupal\migrate\Plugin\MigratePluginManager;
use Drupal\paragraphs\ParagraphInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\migrate\MigrateLookupInterface;

/**
 * Content Component Factory.
 *
 * @package Drupal\civictheme_migrate
 */
class ContentComponentFactory {
  use CivicthemeTrait;

  /**
   * The MigratePluginManager instance.
   *
   * @var \Drupal\migrate\Plugin\MigratePluginManagerInterface
   */
  protected $migratePluginManager;

  /**
   * Entity Type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The migrate lookup service.
   *
   * @var \Drupal\migrate\MigrateLookupInterface
   */
  protected $migrateLookup;

  /**
   * ContentComponentFactory constructor.
   *
   * @param \Drupal\migrate\Plugin\MigratePluginManager $migrate_plugin_manager
   *   Migrate plugin manager.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   * @param \Drupal\migrate\MigrateLookupInterface $migrate_lookup
   *   The migrate lookup service.
   */
  public function __construct(MigratePluginManager $migrate_plugin_manager, EntityTypeManagerInterface $entity_type_manager, MigrateLookupInterface $migrate_lookup) {
    $this->migratePluginManager = $migrate_plugin_manager;
    $this->entityTypeManager = $entity_type_manager;
    $this->migrateLookup = $migrate_lookup;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.migrate.process'),
      $container->get('entity_type.manager'),
      $container->get('migrate.lookup'),
    );
  }

  /**
   * Create components.
   *
   * @param mixed $items
   *   The list of items to create.
   * @param array $context
   *   The migration context array with 4 keys:
   *    - configuration: the configuration of the current field.
   *    - migration: the current migrate executable.
   *    - row: the current row.
   *    - destination_property: destination property currently worked on.
   *
   * @return array|null
   *   The components.
   */
  public function createComponents($items, array &$context) {
    if (!is_array($items)) {
      return NULL;
    }

    $components = [];
    foreach ($items as $item) {
      foreach ($item as $component_type => $item_data) {
        $component = $this->createComponent($component_type, $item_data, $context);
        if ($component instanceof ParagraphInterface) {
          $components[] = $component;
        }
      }
    }

    return $components;
  }

  /**
   * Create a single component.
   *
   * @param string $component_type
   *   The type of the component to create.
   * @param mixed $item_data
   *   The item data to create the component.
   * @param array $context
   *   The migration context.
   *
   * @return mixed|null
   *   The component.
   */
  public function createComponent(string $component_type, $item_data, array &$context) {
    $method = $this->getFactoryMethod($component_type);
    if ($method) {
      return call_user_func_array([$this, $method], [$item_data, &$context]);
    }
    return NULL;
  }

  /**
   * Get the factory method for an item type.
   *
   * @param string $item_type
   *   The item type.
   *
   * @return string|null
   *   The method.
   */
  protected function getFactoryMethod($item_type) : ?string {
    $converter = new CamelCaseToSnakeCaseNameConverter();
    $method = (string) $converter->denormalize('generate_' . $item_type);
    return is_callable([$this, $method]) ? $method : NULL;
  }

  /**
   * Produce Basic Text component.
   *
   * @param mixed $item_data
   *   The item data to create the component.
   * @param array $context
   *   The migration context.
   *
   * @return \Drupal\paragraphs\ParagraphInterface|null
   *   The component.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function generateTextContent($item_data, array &$context) : ?ParagraphInterface {
    if (!empty($item_data['value'])) {
      $options = [
        'content' => $item_data['value'],
      ];
      return $this->civicthemeComponentCreate('content', $options);
    }

    return NULL;
  }

  /**
   * Produce Manual list components.
   *
   * @param mixed $item_data
   *   The item data to create the component.
   * @param array $context
   *   The migration context.
   *
   * @return \Drupal\paragraphs\ParagraphInterface|null
   *   The component.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function generateManualList($item_data, array &$context): ?ParagraphInterface {
    if (!empty($item_data['children'])) {
      $cards = [];
      if (!empty($item_data['children']['cards']['children'])) {
        foreach ($item_data['children']['cards']['children'] as $children) {
          foreach ($children as $type => $card) {
            $summary = $card['item_content']['value'] ?? '';
            $option = [
              'type' => 'civictheme_' . $type,
              'title' => $card['item_title'],
              'summary' => strip_tags($summary),
              'links' => $card['item_links'] ?? [],
              'location' => $card['item_location'] ?? '',
            ];

            // Document for publication card.
            if (!empty($card['item_document'][0])) {
              $lookup_result = $this->migrateLookup->lookup('media_civictheme_document', [$card['item_document'][0]]);
              if (!empty($lookup_result)) {
                $option['document'] = $lookup_result[0]['mid'];
              }
            }

            // Image lookup.
            if (!empty($card['item_image'][0])) {
              $lookup_result = $this->migrateLookup->lookup('media_civictheme_image', [$card['item_image'][0]]);
              if (!empty($lookup_result)) {
                $option['image'] = $lookup_result[0]['mid'];
              }
            }

            // Topics.
            if (!empty($card['item_topics'])) {
              $topics = explode(',', $card['item_topics']);
              if (count($topics) > 0) {
                foreach ($topics as $topic) {
                  $props = [
                    'name' => $topic,
                    'vid' => 'civictheme_topics',
                  ];
                  $term = $this->entityTypeManager
                    ->getStorage('taxonomy_term')
                    ->loadByProperties($props);
                  $term = reset($term);
                  if ($term) {
                    $option['topics'][] = $term->id();
                  }
                  else {
                    $option['topics'][] = $this->entityTypeManager->getStorage('taxonomy_term')->create([
                      'name' => $topic,
                      'vid' => 'civictheme_topics',
                    ])->save();
                  }
                }
              }
            }

            $cards[] = $option;
          }
        }

        $options = [
          'title' => $item_data['children']['title'] ?? '',
          'content' => $item_data['children']['content'] ?? '',
          'cards' => $cards,
        ];

        return $this->civicthemeComponentCreate('manual_list', $options);
      }
    }

    return NULL;
  }

  /**
   * Produce Accordion components.
   *
   * @param mixed $item_data
   *   The item data to create the component.
   * @param array $context
   *   The migration context.
   *
   * @return \Drupal\paragraphs\ParagraphInterface|null
   *   The component.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function generateAccordion($item_data, array &$context) : ?ParagraphInterface {
    if (!empty($item_data['children'])) {
      $accordion_items = [];
      if (!empty($item_data['children']['accordion_list']['children'])) {
        foreach ($item_data['children']['accordion_list']['children'] as $items) {
          $children = NestedArray::getValue($items,
            ['accordion_items', 'children']);
          foreach ($children as $child) {
            $accordion_items[] = [
              'title' => $child['item_title'],
              'content' => $child['item_content']['value'],
            ];
          }
        }
      }

      $options = [
        'panels' => $accordion_items,
      ];

      return $this->civicthemeComponentCreate('accordion', $options);
    }
    return NULL;
  }

  /**
   * Produce Attachments components.
   *
   * @param mixed $item_data
   *   The item data to create the component.
   * @param array $context
   *   The migration context.
   *
   * @return \Drupal\paragraphs\ParagraphInterface|null
   *   The component.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function generateAttachments($item_data, array &$context): ?ParagraphInterface {
    if (!empty($item_data['children'])) {
      $attachments = [];
      if (!empty($item_data['children']['attachments']['children'])) {
        foreach ($item_data['children']['attachments']['children'] as $children) {
          // Document lookup.
          $lookup_result = $this->migrateLookup->lookup('media_civictheme_document', [$children]);
          if (!empty($lookup_result)) {
            $attachments[] = $lookup_result[0]['mid'];
          }
        }

        $options = [
          'title' => $item_data['children']['title'] ?? '',
          'content' => $item_data['children']['content'] ?? '',
          'attachments' => $attachments,
        ];

        return $this->civicthemeComponentCreate('attachment', $options);
      }
    }

    return NULL;
  }

}
