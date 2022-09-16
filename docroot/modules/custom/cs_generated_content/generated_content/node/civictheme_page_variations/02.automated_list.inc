<?php

/**
 * @file
 * Variations for CivicTheme Page nodes used for automated_lists.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Variations of pages used for automated_lists.
 */
function _cs_generated_content_create_node_civictheme_page__variations__for_automated_lists() {
  $total_nodes = 50;

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [];

  for ($i = 0; $i < $total_nodes; $i++) {
    $variation['title'] = sprintf('Demo Page for automated_list %s %s', $i + 1, $helper::staticSentence(1));
    // Set every 4th page to be unpublished.
    $variation['status'] = $i % 4 != 0;
    $variation['summary'] = $helper::staticPlainParagraph($i);
    $variation['components'] = _cs_generated_content_create_node_civictheme_page__variations_components_content_default();
    $variation['topics'] = $i % 2 != 0 ? $helper::civicthemeStaticTopic() : NULL;
    $variation['site_section'] = $i % 2 != 0 ? $helper::civicthemeStaticSiteSection() : NULL;
    $variation['alias'] = '/demo/civictheme_page/{title}';

    $variations[] = $variation;
  }

  return $variations;
}
