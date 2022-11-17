<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Iframe.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_iframe() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Iframe component',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
        ],
      ],
    ],
    [
      'title' => 'Iframe component, Attribute - referrerpolicy',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
          'attributes' => 'referrerpolicy="no-referrer"',
        ],
      ],
    ],
    [
      'title' => 'Iframe component, 3 on page, Height 100 & 50 & 30, Width 100 & 50 & 30',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
          'height' => '100%',
          'width' => '100%',
        ],
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
          'height' => '50%',
          'width' => '50%',
        ],
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
          'height' => '30%',
          'width' => '30%',
        ],
      ],
    ],

    [
      'title' => 'Iframe component, Dark',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Iframe component, Light, BG',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Iframe component, Dark, BG',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Iframe component, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Iframe component, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Iframe component, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Iframe component, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::staticUrl(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
  ];
}
