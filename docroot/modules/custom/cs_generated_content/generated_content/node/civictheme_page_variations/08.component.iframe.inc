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
      'title' => 'Demo Page, Iframe component',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Iframe component, Attribute - referrerpolicy',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'attributes' => 'referrerpolicy="no-referrer"',
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Iframe component, 3 on page, Height 100 & 50 & 30, Width 100 & 50 & 30',
      'components' => [
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
      'title' => 'Demo Page, Iframe component, Dark',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Iframe component, Light, BG',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Iframe component, Dark, BG',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Iframe component, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Iframe component, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Iframe component, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Iframe component, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
  ];
}
