<?php

/**
 * @file
 * Create demo site sections terms.
 */

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_taxonomy_term_civictheme_site_sections_weight() {
  return 25;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_taxonomy_term_civictheme_site_sections_tracking() {
  return TRUE;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE().
 */
function cs_generated_content_generated_content_create_taxonomy_term_civictheme_site_sections() {
  $total_terms_count = 10;

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $term_names = [];
  for ($i = 0; $i < $total_terms_count; $i++) {
    $term_names[] = sprintf('Demo site section %s %s', $i + 1, $helper::staticSentence(2));
  }

  $terms = $helper::saveTermTree('civictheme_site_sections', $term_names);

  foreach ($terms as $term) {
    $helper::log(
      'Created "%s" term "%s" [ID: %s] %s',
      $term->bundle(),
      $term->toLink()->toString(),
      $term->id(),
      Link::createFromRoute('Edit', 'entity.taxonomy_term.edit_form', ['taxonomy_term' => $term->id()])->toString()
    );
  }

  return $terms;
}
