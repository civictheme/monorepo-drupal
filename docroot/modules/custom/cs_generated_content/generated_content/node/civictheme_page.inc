<?php

/**
 * @file
 * Create demo page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

require_once 'helper.inc';
require_once 'civictheme_page.variations.inc';

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_node_civictheme_page_weight() {
  return 35;
}

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_node_civictheme_page_tracking() {
  return TRUE;
}

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE().
 */
function cs_generated_content_generated_content_create_node_civictheme_page() {
  $variations = _cs_generated_content_create_node_civictheme_page__for_listings__variations();
  $variations = array_merge($variations, _cs_generated_content_create_node_civictheme_page__components__variations());

  return _cs_generated_content_create_nodes_from_variations('civictheme_page', $variations);
}

/**
 * Processor to convert variation values to 'civictheme_page' node fields.
 */
function _cs_generated_content_create_node_civictheme_page__fields_from_variation($node, $variation) {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  if (!empty($variation['title'])) {
    $node->setTitle($variation['title']);
  }

  if (!empty($variation['alias'])) {
    // Replace tokens in an alias.
    $variation['alias'] = $helper::replaceTokens($variation['alias'], $variation, function ($value) {
      return is_string($value) ? \Drupal::service('pathauto.alias_cleaner')->cleanString($value) : $value;
    });

    $node->set('path', [
      'pathauto' => FALSE,
      'alias' => $variation['alias'],
    ]);
  }

  // General.
  if (!empty($variation['summary']) && $node->hasField('field_c_n_summary')) {
    $node->field_c_n_summary = $variation['summary'];
  }

  if (!empty($variation['thumbnail']) && $node->hasField('field_c_n_thumbnail')) {
    $node->field_c_n_thumbnail = $variation['thumbnail'];
  }

  if (!empty($variation['topics']) && $node->hasField('field_c_n_topics')) {
    $node->field_c_n_topics = $variation['topics'];
  }

  if (isset($variation['space']) && $node->hasField('field_c_n_space')) {
    $node->field_c_n_space = $variation['space'];
  }

  // Banner.
  if (!empty($variation['banner_type']) && $node->hasField('field_c_n_banner_type')) {
    $node->field_c_n_banner_type = $variation['banner_type'];
  }

  if (!empty($variation['banner_theme']) && $node->hasField('field_c_n_banner_theme')) {
    $node->field_c_n_banner_theme = $variation['banner_theme'];
  }

  if (!empty($variation['banner_background']) && $node->hasField('field_c_n_banner_background')) {
    $node->field_c_n_banner_background = $variation['banner_background'];
  }

  if (!empty($variation['banner_featured_image']) && $node->hasField('field_c_n_banner_featured_image')) {
    $node->field_c_n_banner_featured_image = $variation['banner_featured_image'];
  }

  // Components.
  if (!empty($variation['components']) && $node->hasField('field_c_n_components')) {
    for ($i = 0; $i < count($variation['components']); $i++) {
      // Extract component type.
      $component_type = $variation['components'][$i]['type'];
      // Remove component type and passthrough properties.
      $component_options = array_diff_key($variation['components'][$i], ['type' => TRUE]);
      $helper::civicthemeComponentAttach($node, 'field_c_n_components', $component_type, $component_options);
    }
  }
}
