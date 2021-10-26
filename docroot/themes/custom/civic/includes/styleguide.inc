<?php

/**
 * @file
 * Theming functions for IPART Blue theme.
 */

use Drupal\block_content\Entity\BlockContent;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Xss;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Flag to show only custom IPART components on the Styleguide page.
 */
define('CIVIC_STYLEGUIDE_SHOW_ONLY_CUSTOM_COMPONENTS', TRUE);

/**
 * Implements hook_styleguide().
 */
function civic_styleguide_alter(&$items) {
  // Allow to debug a specific component by providing it's name as a value of
  // debug-component query parameter.
  // @code
  // /admin/appearance/styleguide/civic?debug-component=slider
  // @endcode
  $debug_component = Drupal::request()->get('debug-component');

  // Cleanup component name.
  $debug_component = !empty($debug_component) ? Xss::filter($debug_component) : NULL;

  // Remove all Styleguide's components.
  if (!empty($debug_component)) {
    $items = [];
  }

  // Implement _civic_styleguide_component_NAME() and place them in the
  // name.inc file alongside with any other component hooks.
  $functions = get_defined_functions();
  foreach ($functions['user'] as $function) {
    if (strpos($function, '_civic_styleguide_component_') === 0 && strpos($function, '__') === FALSE) {
      if (!empty($debug_component)) {
        $name = str_replace('_civic_styleguide_component_', '', $function);
        if ($name != $debug_component) {
          continue;
        }
      }

      call_user_func_array($function, [&$items]);
    }
  }

  // Add a group and sort alphabetically.
  $components = [];
  foreach ($items as $k => $item) {
    if (strpos($k, 'ipart_') === 0) {
      if (empty($items[$k]['group'])) {
        $name = $item['component'] ?? preg_replace('/_[\d]+$/', '', str_replace('ipart_', '', $k));
        // Begin with a space to keep at the start of the list.
        $items[$k]['group'] = ' IPART ' . Xss::filter(ucfirst($name));
      }
      $components[$k] = $items[$k];
      unset($items[$k]);
    }
  }
  ksort($components);
  $items = $components + $items;

  // Remove items that are obsolete and may be breaking the grid.
  // @see modules/contrib/styleguide/src/Plugin/Styleguide/DefaultStyleguide.php
  $unused_items = [
    'text_format',
    'filter_tips',
    'filter_tips_long',
    'system_powered_by',
    'feed_icon',
    'menu_tree',
    'Display Suite',
    'form-vertical-tabs',
  ];

  foreach ($items as $k => $item) {
    if (
      strpos($k, 'image') === 0
      || strpos($k, 'GovCMS') === 0
      || strpos($k, 'Columns') === 0
    ) {
      $unused_items[] = $k;
    }
  }

  $items = CIVIC_STYLEGUIDE_SHOW_ONLY_CUSTOM_COMPONENTS ? $components : array_diff_key($items, array_flip($unused_items));
}

/**
 * Render a paragraph.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_styleguide_paragraph_render($options, $do_render = FALSE) {
  // Create fake paragraph to show.
  $paragraph = is_array($options) ? Paragraph::create($options) : $options;

  $view_builder = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
  $pre_render = $view_builder->build($view_builder->view($paragraph));
  return $do_render ? Drupal::service('renderer')->render($pre_render) : $pre_render;
}

/**
 * Render a block placement.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_styleguide_block_placement_render(BlockContent $block) {
  $view_builder = \Drupal::entityTypeManager()
    ->getViewBuilder('block_content');
  $pre_render = $view_builder->view($block);

  $bundle = Html::cleanCssIdentifier($block->bundle());
  $breakout_class = in_array($bundle, _civic_breakout_components()) ? 'container-breakout' : '';

  // Replicated preprocess behaviours for block placement field.
  // @see themes/custom/civic/templates/field/field--paragraph--field-bp-block--block-placement.html.twig
  return [
    '#type' => 'inline_template',
    '#template' => '<div class="block__{{ bundle }} {{ breakout_class }}">{{ content }}</div>',
    '#context' => [
      'content' => $pre_render,
      'bundle' => $bundle,
      'breakout_class' => $breakout_class,
    ],
  ];
}

/**
 * Render a HTML.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_styleguide_html_render($html) {
  return [
    '#type' => 'inline_template',
    '#template' => $html,
  ];
}

/**
 * Implements hook_preprocess_styleguide_item().
 */
function civic_preprocess_styleguide_item(&$variables) {
  // Implement _civic_styleguide_preprocess_component_NAME() and place them
  // in the name.inc file alongside with any other component hooks.
  $functions = get_defined_functions();
  foreach ($functions['user'] as $function) {
    if (strpos($function, '_civic_styleguide_preprocess_component_') === 0) {
      call_user_func_array($function, [&$variables]);
    }
  }
}

/**
 * Implements block preprocess for styleguide.
 *
 * Used as a factory for "shutdown" functions which runs after rendering of
 * all styleguide items.
 * "Shutdown" functions are usually used for cleanup to make sure that
 * styleguide-only data does not pollute global context.
 */
function _civic_preprocess_block_styleguide(&$variables) {
  // Implement _civic_styleguide_cleanup_component_NAME() and place them in
  // the name.inc file alongside with any other component hooks.
  $functions = get_defined_functions();
  foreach ($functions['user'] as $function) {
    if (strpos($function, '_civic_styleguide_cleanup_component_') === 0) {
      call_user_func_array($function, [&$variables]);
    }
  }
}

/**
 * Format variation information.
 */
function _civic_styleguide_variation_format_info($variation, $name_length = 3, $value_length = 3, $field_prefix = 'field_') {
  $output = [];

  foreach ($variation as $name => $value) {
    $value_string = 'N';

    if (is_array($value)) {
      $value_string = count($value);
    }
    elseif (is_int($value)) {
      $value_string = $value === 0 ? 'N' : $value;
    }
    elseif (is_string($value)) {
      $value_string = !is_null($value_length) ? substr($value, 0, $value_length) : $value;
      $value_string = trim($value_string);
    }
    elseif ($value) {
      $value_string = 'Y';
    }

    if (!empty($field_prefix)) {
      $name = str_replace($field_prefix, '', $name);
    }

    $name = str_replace([' ', '-', '_'], ' ', $name);
    if (!is_null($name_length)) {
      $name = substr($name, 0, $name_length);
    }
    $name = ucfirst($name);

    $output[] = sprintf('%s: %s', $name, $value_string);
  }

  return implode(', ', $output);
}

/**
 * Load media entity by name.
 */
function _civic_styleguide_media_load_by_name($bundle, $name) {
  $query = \Drupal::entityQuery('media')
    ->condition('bundle', $bundle)
    ->condition('name', $name);
  $ids = $query->execute();

  if (empty($ids)) {
    return FALSE;
  }

  $id = array_shift($ids);

  return Media::load($id);
}

/**
 * Select a random node.
 *
 * @param string $type
 *   The type of the node to return. If not provided - random type will be
 *   returned.
 *
 * @return \Drupal\node\Entity\Node
 *   Node entity.
 */
function _civic_styleguide_random_node($type = NULL) {
  $query = \Drupal::entityQuery('node');
  if ($type) {
    $query->condition('type', $type);
  }
  $ids = $query->execute();

  if (empty($ids)) {
    return NULL;
  }

  shuffle($ids);

  $id = array_shift($ids);

  return Node::load($id);
}

/**
 * Helper to get random Image media.
 */
function _civic_styleguide_random_media_image($name = NULL) {
  /** @var \Drupal\styleguide\Generator $generator */
  $generator = \Drupal::service('styleguide.generator');

  return Media::create([
    'bundle' => 'civic_image',
    'name' => $name ? $name : $generator->words(3),
    'field_c_m_image' => File::create([
      'uri' => $generator->image($generator->words(), 'png'),
    ]),
  ]);
}

/**
 * Helper to get random Document media.
 */
function _civic_styleguide_random_media_document($name = NULL, $extension = '.txt') {
  /** @var \Drupal\styleguide\Generator $generator */
  $generator = \Drupal::service('styleguide.generator');

  $filename = \Drupal::getContainer()->get('config.factory')->get('system.file')->get('default_scheme') . '://styleguide-text-' . $name . $extension;
  if (!file_exists($filename)) {
    file_put_contents($filename, 'Content of file ' . $filename);
  }

  return Media::create([
    'bundle' => 'civic_document',
    'name' => $name ? $name : $generator->words(3),
    'field_c_m_document' => File::create([
      'uri' => $filename,
    ]),
  ]);
}

/**
 * Get random Link field link.
 */
function _civic_styleguide_random_link($words = 2) {
  $generator = \Drupal::service('styleguide.generator');

  return [
    'title' => $generator->words($words),
    'uri' => 'http://example.com',
  ];
}

/**
 * Helper to get random array items.
 */
function _civic_styleguide_random_array_items($haystack, $count) {
  if ($count == 0) {
    return [];
  }

  $haystack_keys = array_keys($haystack);
  shuffle($haystack_keys);
  $haystack_keys = array_slice($haystack_keys, 0, $count);

  return array_intersect_key($haystack, array_flip($haystack_keys));
}

/**
 * Helper to get a single random array item.
 */
function _civic_styleguide_random_array_item($haystack) {
  if (empty($haystack)) {
    return FALSE;
  }

  $items = _civic_styleguide_random_array_items($haystack, 1);

  return count($items) > 0 ? reset($items) : FALSE;
}

/**
 * Create an entity from variation.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_styleguide_create_entity_from_variation($variation) {
  if (empty($variation['entity'])) {
    return NULL;
  }

  $values = $variation['entity'];

  if (empty($values['entity_type'])) {
    return NULL;
  }

  $entity_type = $values['entity_type'];
  unset($values['entity_type']);

  return \Drupal::entityTypeManager()->getStorage($entity_type)->create($values);
}

/**
 * Generate a random date with or without time.
 *
 * @param string $start
 *   (optional) Start offset in format suitable for strtotime().
 *   Defaults to "now".
 * @param string $finish
 *   (optional) Finish offset in format suitable for strtotime().
 *   Defaults to "now".
 * @param bool $with_time
 *   (optional) Whether or not to include time. Defaults to FALSE.
 *
 * @return string
 *   Random date string with or without time.
 */
function _civic_styleguide_random_date($start = 'now', $finish = 'now', $with_time = FALSE) {
  $start = strtotime($start);
  $finish = strtotime($finish);

  $start = min($start, $finish);
  $finish = max($start, $finish);

  $format = 'Y-m-d';
  if ($with_time) {
    $format .= '\TH:i:00';
  }

  $timestamp = rand($start, $finish);

  return date($format, $timestamp);
}
