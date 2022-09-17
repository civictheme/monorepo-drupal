<?php

namespace Drupal\civictheme_styleguide;

use Drupal\block_content\Entity\BlockContent;
use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Class Styleguide.
 *
 * Styleguide module integration.
 *
 * @package Drupal\civictheme_styleguide
 */
class Styleguide {

  /**
   * Component hook implementation prefix.
   */
  const HOOK_PREFIX = '_civictheme_styleguide_component_';

  /**
   * Alter items.
   *
   * @param array $items
   *   Array of items passed by reference.
   */
  public function alter(array &$items) {
    $this->removeUnused($items);
    $this->addComponents($items);
  }

  /**
   * Alter header link variables.
   *
   * @param array $variables
   *   Array of header link variables passed by reference.
   */
  public function preprocessLinks(array &$variables) {
    $this->wrapLinksinColumns($variables);
  }

  /**
   * Preprocess items.
   */
  public function preprocessItem(array &$variables) {
    if (empty($variables['item']['options']['edge-to-edge'])) {
      $variables['attributes'] = new Attribute();
      $variables['attributes']->addClass('container');
    }
  }

  /**
   * Removed unused items.
   *
   * @param array $items
   *   Array of items passed by reference.
   */
  protected function removeUnused(array &$items) {
    $items = array_diff_key($items, array_flip($this->getUnused()));
  }

  /**
   * List of unused items.
   *
   * @return string[]
   *   Array of unused items.
   */
  protected function getUnused() {
    return [
      'text_format',
      'filter_tips',
      'filter_tips_long',
      'system_powered_by',
      'feed_icon',
      'menu_tree',
      'Display Suite',
      'form-vertical-tabs',
      'progress_bar',
      'maintenance_page',
      'menu_link',
    ];
  }

  /**
   * Wrap header links to turn the into columns for readbility.
   *
   * @param array $variables
   *   Array of header link variables passed by reference.
   */
  protected function wrapLinksinColumns(array &$variables) {
    $items = [];

    foreach ($variables['items'] as $k => $item) {
      $prefix = '';
      $suffix = '';

      if ($k == 0) {
        $prefix = '<div style="display: flex; flex-wrap: wrap;">';
      }

      $items[] = [
        '#type' => 'inline_template',
        '#template' => $prefix . '<div class="column" style="width: 25%">',
      ];

      $items[] = $item;

      if ($k == count($variables['items']) - 1) {
        $suffix = '</div>';
      }

      $items[] = ['#markup' => $suffix . '</div>'];
    }

    $variables['items'] = $items;
  }

  /**
   * Add components to a list of items.
   */
  protected function addComponents(&$items) {
    $components = $this->collectComponents();
    ksort($components);
    $items = $components + $items;
  }

  /**
   * Collect information about components from hook implementations.
   *
   * Implement _civictheme_styleguide_component_NAME() hook within
   * 'components/[name].inc' file for automated discovery.
   *
   * @return array
   *   Array of components information.
   */
  protected function collectComponents() {
    $items = [];

    foreach (glob(__DIR__ . '/../components/*.inc') as $filename) {
      require_once $filename;
    }

    // Call _civictheme_styleguide_component_NAME() implementation.
    // @todo Replace with module handler hook invocation.
    $functions = get_defined_functions();
    foreach ($functions['user'] as $function) {
      if (strpos($function, self::HOOK_PREFIX) === 0 && strpos($function, '__') === FALSE) {
        $ret = call_user_func($function);
        if ($ret) {
          $items = array_merge($items, $this->normaliseComponentInfo($ret, substr($function, strlen(self::HOOK_PREFIX))));
        }
      }
    }

    return $items;
  }

  /**
   * Normalise component information.
   */
  protected function normaliseComponentInfo($info, $name) {
    $defaults = [
      'title' => NULL,
      'description' => NULL,
      'content' => NULL,
      'group' => ' civictheme',
      'options' => [
        'edge-to-edge' => TRUE,
      ],
    ];

    if (is_string($info)) {
      $info = [$name => ['content' => $info] + $defaults];
    }
    elseif (is_array($info) && !empty($info['content'])) {
      $info = [$name => $info + $defaults];
    }
    elseif (is_array($info)) {
      foreach ($info as $k => $v) {
        $key = $k;
        // Create new key for numeric keys.
        if (is_numeric($k)) {
          $key = $name . '_' . $k;
        }
        unset($info[$k]);
        $info += self::normaliseComponentInfo($v, $key);
      }
    }
    else {
      throw new \RuntimeException('Unknown component info type.');
    }

    return $info;
  }

  /**
   * Render a block placement.
   */
  public static function renderBlockPlacement(BlockContent $block) {
    $view_builder = \Drupal::entityTypeManager()
      ->getViewBuilder('block_content');
    $pre_render = $view_builder->view($block);

    $bundle = Html::cleanCssIdentifier($block->bundle());

    // Replicated preprocess behaviours for block placement field.
    // @see themes/custom/civictheme/templates/field/field--paragraph--field-bp-block--block-placement.html.twig
    return [
      '#type' => 'inline_template',
      '#template' => '<div class="block__{{ bundle }}">{{ content }}</div>',
      '#context' => [
        'content' => $pre_render,
        'bundle' => $bundle,
      ],
    ];
  }

  /**
   * Render a paragraph.
   */
  public static function renderParagraph($options, $do_render = FALSE) {
    // Create fake paragraph to show.
    $paragraph = is_array($options) ? Paragraph::create($options) : $options;

    $view_builder = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
    $pre_render = $view_builder->build($view_builder->view($paragraph));

    return $do_render ? (string) \Drupal::service('renderer')->render($pre_render) : $pre_render;
  }

  /**
   * Attach paragraph to entity.
   */
  public static function paragraphFromOptions($type, $options, $save = FALSE) {
    $paragraph = Paragraph::create([
      'type' => $type,
    ]);

    // Attaching all fields to paragraph.
    foreach ($options as $field_name => $value) {
      $field_name = 'field_c_p_' . $field_name;
      if ($paragraph->hasField($field_name)) {
        $paragraph->{$field_name} = $value;
      }
    }

    if ($save) {
      $paragraph->save();
    }

    return $paragraph;
  }

  /**
   * Render a entity.
   */
  public static function renderEntity($entity_type, $entity, $do_render = FALSE) {
    $view_builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
    $pre_render = $view_builder->build($view_builder->view($entity));

    return $do_render ? (string) \Drupal::service('renderer')->render($pre_render) : $pre_render;
  }

  /**
   * Create entity from options.
   */
  public static function entityFromOptions($entity_type, $bundle, $options, $save = FALSE) {
    $storage = \Drupal::entityTypeManager()->getStorage($entity_type);

    if (!$storage) {
      return NULL;
    }

    $entity = $storage->create([
      'bundle' => $bundle,
    ]);

    // Attaching all fields to paragraph.
    foreach ($options as $field_name => $value) {
      $field_name = static::getFieldPrefix($entity_type) . $field_name;
      if ($entity->hasField($field_name)) {
        $entity->{$field_name} = $value;
      }
    }

    if ($save) {
      $entity->save();
    }

    return $entity;
  }

  /**
   * Get field prefix.
   */
  private static function getFieldPrefix($entity_tyoe) {
    $prefix = [
      'media' => 'field_c_m_',
      'paragraph' => 'field_c_p_',
      'node' => 'field_c_n_',
    ];

    return $prefix[$entity_tyoe] ?? '';
  }

}
