<?php

/**
 * @file
 * Create demo Events terms.
 *
 * @see https://www.drupal.org/project/coder/issues/2877475
 * phpcs:disable DrupalPractice.CodeAnalysis.VariableAnalysis.UndefinedVariable
 */

use Drupal\cs_demo\CsDemoHelper;
use Drupal\cs_demo\CsDemoRandom;

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_demo_create_taxonomy_term_topics_weight() {
  return 11;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_demo_create_taxonomy_term_topics_tracking() {
  return TRUE;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE().
 */
function cs_demo_create_taxonomy_term_topics() {
  $total_terms_count = 10;

  /** @var Drupal\cs_demo\CsDemoHelper $helper */
  $helper = CsDemoHelper::getInstance();

  $term_names = [];
  for ($i = 0; $i < $total_terms_count; $i++) {
    $term_names[] = sprintf('Topic %s %s', $i + 1, CsDemoRandom::sentence(2, 2));
  }

  $terms = CsDemoHelper::saveTermTree('topics', $term_names);

  foreach ($terms as $term) {
    $helper::log(
      'Created "%s" term "%s" (id: %s)',
      $term->bundle(),
      $term->toLink()->toString(),
      $term->id()
    );
  }

  return $terms;
}
