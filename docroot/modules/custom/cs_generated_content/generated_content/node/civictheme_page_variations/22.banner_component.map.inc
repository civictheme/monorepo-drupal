<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner Components - Map.
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner_component_map() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Banner Map component',
      'banner_components' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Map component, 3 on page, View link, Share link',
      'banner_components' => [
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
      'title' => 'Demo Page, Banner Map component, Dark',
      'banner_components' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Map component, Light, BG',
      'banner_components' => [
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
      'title' => 'Demo Page, Banner Map component, Dark, BG',
      'banner_components' => [
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
      'title' => 'Demo Page, Banner Map component, Light, No BG, Both spaces',
      'banner_components' => [
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
      'title' => 'Demo Page, Banner Map component, Dark, No BG, Both spaces',
      'banner_components' => [
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
      'title' => 'Demo Page, Banner Map component, Light, BG, Both spaces',
      'banner_components' => [
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
      'title' => 'Demo Page, Banner Map component, Dark, BG, Both spaces',
      'banner_components' => [
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
