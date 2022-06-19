<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, General.
 */
function _cs_generated_content_create_node_civictheme_page__variations__general() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [
    [
      'title' => 'Demo Page, default values',
    ],
    [
      'title' => 'Demo Page, Summary',
      'summary' => $helper::staticPlainParagraph(),
    ],
    [
      'title' => 'Demo Page, Summary, Content',
      'summary' => $helper::staticPlainParagraph(),
      'components' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
    ],
    [
      'title' => 'Demo Page, Summary, Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'components' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
    ],
    [
      'title' => 'Demo Page, Summary, Topics (1), Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'components' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
    ],
    [
      'title' => 'Demo Page, Summary, Topics (3), Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 3),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'components' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
    ],
    [
      'title' => 'Demo Page, Summary, Topics (1), Thumbnail, Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'components' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
    ],
  ];

  return $variations;
}
