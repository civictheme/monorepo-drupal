<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Map.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_map() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Map component',
      'components' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Map component, 3 on page, View link',
      'components' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'view_link' => $helper::randomUrl(),
        ],
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'view_link' => $helper::randomUrl(),
        ],
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'view_link' => $helper::randomUrl(),
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Map component, Dark',
      'components' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Map component, Light, BG',
      'components' => [
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
      'title' => 'Demo Page, Map component, Dark, BG',
      'components' => [
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
      'title' => 'Demo Page, Map component, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Map component, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Map component, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Map component, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
  ];
}
