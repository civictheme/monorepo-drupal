<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Webform.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_webform() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Webform component',
      'components' => [
        [
          'type' => 'webform',
          'webform' => $helper::randomRealWebform(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Webform component, 2 on page',
      'components' => [
        [
          'type' => 'webform',
          'webform' => $helper::randomRealWebform(),
        ],
        [
          'type' => 'webform',
          'webform' => $helper::randomRealWebform(),
        ],
      ],
    ],
  ];
}
