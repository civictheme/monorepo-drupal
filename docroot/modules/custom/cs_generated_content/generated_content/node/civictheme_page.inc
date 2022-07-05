<?php

/**
 * @file
 * Create demo page nodes.
 */

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;

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
  $nodes = [];

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = $helper::variationFetchAll(
    '_cs_generated_content_create_node_civictheme_page__variations__',
    __DIR__ . DIRECTORY_SEPARATOR . 'civictheme_page_variations'
  );

  foreach ($variations as $i => $variation) {
    /** @var \Drupal\node\Entity\Node $node */
    $node = $helper::variationCreateNode('civictheme_page', $variation, $i, '_cs_generated_content_create_node_civictheme_page__variation_to_fields');

    $variation_info = $helper::variationFormatInfo($variation);
    $node->revision_log = $variation_info;

    $node->save();

    $helper::log(
      'Created "%s" node "%s" [ID: %s] %s',
      $node->bundle(),
      $node->toLink()->toString(),
      $node->id(),
      Link::createFromRoute('Edit', 'entity.node.edit_form', ['node' => $node->id()])->toString()
    );

    $nodes[] = $node;
  }

  return $nodes;
}

/**
 * Processor to convert variation values to 'civictheme_page' node fields.
 */
function _cs_generated_content_create_node_civictheme_page__variation_to_fields($node, $variation) {
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

  if (!empty($variation['hide_breadcrumb']) && $node->hasField('field_c_n_banner_hide_breadcrumb')) {
    $node->field_c_n_banner_hide_breadcrumb = $variation['hide_breadcrumb'];
  }

  if (!empty($variation['banner_title']) && $node->hasField('field_c_n_banner_title')) {
    $node->field_c_n_banner_title = $variation['banner_title'];
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

  // Banner Components.
  if (!empty($variation['banner_components']) && $node->hasField('field_c_n_banner_components')) {
    for ($i = 0; $i < count($variation['banner_components']); $i++) {
      // Extract component type.
      $component_type = $variation['banner_components'][$i]['type'];
      // Remove component type and passthrough properties.
      $component_options = array_diff_key($variation['banner_components'][$i], ['type' => TRUE]);
      $helper::civicthemeComponentAttach($node, 'field_c_n_banner_components', $component_type, $component_options);
    }
  }

  // Banner Components Bottom.
  if (!empty($variation['banner_components_bott']) && $node->hasField('field_c_n_banner_components_bott')) {
    for ($i = 0; $i < count($variation['banner_components_bott']); $i++) {
      // Extract component type.
      $component_type = $variation['banner_components_bott'][$i]['type'];
      // Remove component type and passthrough properties.
      $component_options = array_diff_key($variation['banner_components_bott'][$i], ['type' => TRUE]);
      $helper::civicthemeComponentAttach($node, 'field_c_n_banner_components_bott', $component_type, $component_options);
    }
  }
}
