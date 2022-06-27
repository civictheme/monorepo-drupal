<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner Bottom Components - Map.
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner_components_bott_map() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Banner Bottom Map component',
      'banner_components_bott' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Map component, 3 on page, View link, Share link',
      'banner_components_bott' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'share_link' => $helper::randomUrl(),
          'view_link' => $helper::randomUrl(),
        ],
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'share_link' => $helper::randomUrl(),
          'view_link' => $helper::randomUrl(),
        ],
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'share_link' => $helper::randomUrl(),
          'view_link' => $helper::randomUrl(),
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Banner Bottom Map component, Dark',
      'banner_components_bott' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Map component, Light, BG',
      'banner_components_bott' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Map component, Dark, BG',
      'banner_components_bott' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Map component, Light, No BG, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Map component, Dark, No BG, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Map component, Light, BG, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Map component, Dark, BG, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
  ];
}
