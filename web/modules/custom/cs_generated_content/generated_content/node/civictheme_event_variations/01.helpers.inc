<?php

/**
 * @file
 * Helpers for variations for CivicTheme Event nodes.
 */

declare(strict_types=1);

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Variations post-process callback.
 *
 * @param array $variations
 *   Variations to post-process.
 *
 * @return array<string, mixed>
 *   Post-processed variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _cs_generated_content_create_node_civictheme_event__variations__post_process(array $variations): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  // Add common variation properties to all variations, but only if they were
  // not provided within variations.
  foreach ($variations as &$variation) {
    $variation += [
      'status' => TRUE,
      'alias' => '/generated-content/civictheme_event/{title}',
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeNone(),
    ];
  }

  return $variations;
}

/**
 * Default values for Content component to use in variations.
 *
 * @return array<int, array<string, string>>
 *   Default values for Content component.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _cs_generated_content_create_node_civictheme_event__variations_components_content_default(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'type' => 'content',
      'content' => $helper::staticRichText(3),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeNone(),
    ],
  ];
}
