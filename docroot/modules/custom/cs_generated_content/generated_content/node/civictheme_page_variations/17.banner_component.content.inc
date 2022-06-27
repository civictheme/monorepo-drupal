<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner Components - Content.
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner_component_content() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Banner Content component',
      'banner_components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Content component, 3 on page',
      'banner_components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
        ],
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
        ],
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Banner Content component, Dark',
      'banner_components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Content component, Light, BG',
      'banner_components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Content component, Dark, BG',
      'banner_components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Content component, Light, No BG, Both spaces',
      'banner_components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Content component, Dark, No BG, Both spaces',
      'banner_components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Content component, Light, BG, Both spaces',
      'banner_components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Content component, Dark, BG, Both spaces',
      'banner_components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
  ];
}
