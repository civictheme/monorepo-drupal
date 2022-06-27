<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner Bottom Components - Iframe.
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner_components_bott_iframe() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Banner Bottom Iframe component',
      'banner_components_bott' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Iframe component, Attribute - referrerpolicy',
      'banner_components_bott' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'attributes' => 'referrerpolicy="no-referrer"',
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Iframe component, 3 on page, Height 100 & 50 & 30, Width 100 & 50 & 30',
      'banner_components_bott' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'height' => '100%',
          'width' => '100%',
        ],
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'height' => '50%',
          'width' => '50%',
        ],
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'height' => '30%',
          'width' => '30%',
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Banner Bottom Iframe component, Dark',
      'banner_components_bott' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Iframe component, Light, BG',
      'banner_components_bott' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Iframe component, Dark, BG',
      'banner_components_bott' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Iframe component, Light, No BG, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Iframe component, Dark, No BG, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Iframe component, Light, BG, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Iframe component, Dark, BG, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
  ];
}
