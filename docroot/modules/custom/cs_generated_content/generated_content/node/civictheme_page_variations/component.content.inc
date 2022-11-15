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
      'title' => 'Content component',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
        ],
      ],
    ],
    [
      'title' => 'Content component, 3 on page',
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
      'title' => 'Content component, Dark',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Content component, Light, BG',
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
      'title' => 'Content component, Dark, BG',
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
      'title' => 'Content component, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Content component, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Content component, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Content component, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
  ];
}
