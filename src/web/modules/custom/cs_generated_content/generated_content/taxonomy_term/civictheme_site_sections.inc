<?php

/**
 * @file
 * Create generated site sections terms.
 */

declare(strict_types=1);

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_taxonomy_term_civictheme_site_sections_weight(): int {
  return 25;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_taxonomy_term_civictheme_site_sections_tracking(): bool {
  return TRUE;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE().
 *
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function cs_generated_content_generated_content_create_taxonomy_term_civictheme_site_sections(): array {
  $total_terms_count = 10;

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $term_names = [];
  for ($i = 0; $i < $total_terms_count; $i++) {
    $term_names[] = sprintf('G %s %s', $i + 1, $helper::staticSentence(2));
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
