<?php

/**
 * @file
 * Create demo page nodes.
 *
 * @see https://www.drupal.org/project/coder/issues/2877475
 * phpcs:disable DrupalPractice.CodeAnalysis.VariableAnalysis.UndefinedVariable
 */

use Drupal\cs_demo\CsDemoHelper;
use Drupal\cs_demo\CsDemoRandom;

require_once 'helper.inc';

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_demo_create_node_civictheme_page_weight() {
  return 35;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_demo_create_node_civictheme_page_tracking() {
  return TRUE;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE().
 */
function cs_demo_create_node_civictheme_page() {
  $total_nodes = 5;

  $nodes = [];

  /** @var Drupal\cs_demo\CsDemoHelper $helper */
  $helper = CsDemoHelper::getInstance();

  $types = CsDemoHelper::randomFieldAllowedBundles('node', 'civictheme_page', 'field_c_n_components', 3);

  if (!is_array($types) && count($types) > 3){
    $types = array_combine($types, [rand(1, 2), rand(1, 3), rand(1, 3)]);
  }
  else {
    $types = [
      'content' => rand(1,2),
    ];
  }

  for ($i = 0; $i < $total_nodes; $i++) {
    $variation = CsDemoHelper::variationRandom([
      'status' => NULL,
      'summary' => NULL,
      'thumbnail' => NULL,
      'space' => 'both',
      'toc' => NULL,
      'topics' => rand(0, 5),
      'banner_type' => CsDemoRandom::arrayItem(CsDemoHelper::civicBannerTypes()),
      'banner_theme' => CsDemoRandom::arrayItem(CsDemoHelper::civicThemes()),
      'banner_background' => NULL,
      'banner_featured_image' => NULL,
      'banner_components' => rand(0, 2),
      'banner_components_bottom' => rand(0, 2),
    ] + $types);

    $variation_info = CsDemoHelper::variationFormatInfo($variation);
    $title = sprintf('Demo Page %s %s (%s)', count($nodes) + 1, CsDemoRandom::sentence(1, 1), $variation_info);

    /** @var \Drupal\node\Entity\Node $node */
    $node = _cs_demo_create_node_from_variation('civictheme_page', $variation);

    _cs_demo_create_node_from_variation_process_civictheme_page($node, $variation);

    $node->setTitle($title);
    $node->revision_log = $variation_info;

    $node->save();

    $helper::log(
      'Created "%s" node "%s" (id: %s)',
      $node->bundle(),
      $node->toLink()->toString(),
      $node->id()
    );

    $nodes[] = $node;
  }

  return $nodes;
}

/**
 * Process node variation for Civic Page.
 */
function _cs_demo_create_node_from_variation_process_civictheme_page($node, $variation) {
  // General.
  if (!empty($variation['space']) && $node->hasField('field_c_n_space')) {
    $node->field_c_n_space = $variation['space'];
  }

  if (!empty($variation['summary']) && $node->hasField('field_c_n_summary')) {
    $node->field_c_n_summary = CsDemoRandom::sentence(2, 10);
  }

  if (empty($variation['thumbnail']) && $node->hasField('field_c_n_thumbnail')) {
    $node->field_c_n_thumbnail = CsDemoHelper::randomImage();
  }

  if (!empty($variation['topics']) && $node->hasField('field_c_n_topics')) {
    $node->field_c_n_topics = CsDemoHelper::randomTopics($variation['topics']);
  }

  // Banner.
  if (!empty($variation['banner_type']) && $node->hasField('field_c_n_banner_type')) {
    $node->field_c_n_banner_type = $variation['banner_type'];
  }

  if (!empty($variation['banner_theme']) && $node->hasField('field_c_n_banner_theme')) {
    $node->field_c_n_banner_theme = $variation['banner_theme'];
  }

  if (!empty($variation['banner_background']) && $node->hasField('field_c_n_banner_background')) {
    $node->field_c_n_banner_background = CsDemoHelper::randomImage();
  }

  if (!empty($variation['banner_featured_image']) && $node->hasField('field_c_n_banner_featured_image')) {
    $node->field_c_n_banner_featured_image = CsDemoHelper::randomImage();
  }

  if (!empty($variation['banner_components']) && $node->hasField('field_c_n_banner_components')) {
    for ($i = 0; $i < $variation['banner_components']; $i++) {
      CsDemoHelper::civicParagraphContentAttach($node, 'field_c_n_banner_components', [
        'content' => CsDemoRandom::richText(rand(1, 2), rand(2, 4), sprintf('Banner component %s ', $i + 1)),
        'theme' => $variation['banner_theme'] ?: CsDemoHelper::civicThemeLight(),
      ]);
    }
  }

  if (!empty($variation['banner_components_bottom']) && $node->hasField('field_c_n_banner_components_bott')) {
    for ($i = 0; $i < $variation['banner_components_bottom']; $i++) {
      CsDemoHelper::civicParagraphContentAttach($node, 'field_c_n_banner_components_bott', [
        'content' => CsDemoRandom::richText(rand(1, 2), rand(2, 4), sprintf('Banner bottom component %s ', $i + 1)),
        'theme' => CsDemoHelper::civicThemeLight(),
      ]);
    }
  }

  $themes = CsDemoHelper::civicThemes();

  foreach ($themes as $theme) {
    // Content.
    if (!empty($variation['content']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['content']; $i++) {
        CsDemoHelper::civicParagraphContentAttach($node, 'field_c_n_components', [
          'content' => CsDemoRandom::richText(rand(1, 3), rand(5, 7), sprintf('Content %s ', $i + 1)),
          'theme' => $theme,
        ]);
      }
    }

    if(!empty($variation['accordion']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['accordion']; $i++) {
        CsDemoHelper::civicParagraphAccordionAttach($node, 'field_c_n_components', [
          'content' => CsDemoRandom::richText(rand(1, 3), rand(5, 7), sprintf('Content %s ', $i + 1)),
          'panels' => [
            'title' => CsDemoRandom::sentence(5, 20),
            'content' => CsDemoRandom::richText(rand(1, 3), rand(5, 7), sprintf('Content %s ', $i + 1)),
            ],
          'expand_all' => rand(0, 1),
          'theme' => $theme,
        ]);
      }
    }
  }

  if (!empty($variation['toc']) && $node->hasField('field_c_n_show_toc')) {
    $node->field_c_n_show_toc = TRUE;
  }
}
