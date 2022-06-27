<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner Bottom Components - Webform.
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner_components_bott_webform() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Banner Bottom Webform component',
      'banner_components_bott' => [
        [
          'type' => 'webform',
          'webform' => $helper::randomRealWebform(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Webform component, 2 on page',
      'banner_components_bott' => [
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
