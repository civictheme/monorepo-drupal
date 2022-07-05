<?php

/**
 * @file
 * Variations for CivicTheme Page nodes used for listings.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Variations of pages used for listings.
 */
function _cs_generated_content_create_node_civictheme_page__variations__for_listings() {
  $total_nodes = 30;

  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [];

  for ($i = 0; $i < $total_nodes; $i++) {
    $variation['title'] = sprintf('Demo Page for listing %s %s', $i + 1, $helper::staticSentence(1));
    // Set every 4th page to be unpublished.
    $variation['status'] = $i % 4 != 0;
    $variation['summary'] = $helper::staticPlainParagraph($i);
    $variation['components'] = _cs_generated_content_create_node_civictheme_page__variations_components_content_default();
    $variation['alias'] = '/demo/civictheme_page/{title}';
    $variations[] = $variation;
  }

  return $variations;
}
