<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Content.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_content() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Content component',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, 3 on page',
      'components' => [
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
      'title' => 'Demo Page, Content component, Dark',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Light, BG',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Dark, BG',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Light, BG, Both spaces',
      'components' => [
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
      'title' => 'Demo Page, Content component, Dark, No BG, Both spaces',
      'components' => [
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
      'title' => 'Demo Page, Content component, Light, BG, Both spaces',
      'components' => [
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
      'title' => 'Demo Page, Content component, Dark, BG, Both spaces',
      'components' => [
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
