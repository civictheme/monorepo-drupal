<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Webform.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_webform() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Webform component',
      'components' => [
        [
          'type' => 'webform',
          'webform' => $helper::randomRealWebform(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Webform component, 2 on page',
      'components' => [
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
    [
      'title' => 'Demo Page, Webform component, Dark',
      'components' => [
        [
          'type' => 'content',
          'webform' => $helper::randomRealWebform(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Webform component, Light, BG',
      'components' => [
        [
          'type' => 'content',
          'webform' => $helper::randomRealWebform(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Webform component, Dark, BG',
      'components' => [
        [
          'type' => 'content',
          'webform' => $helper::randomRealWebform(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Webform component, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'webform' => $helper::randomRealWebform(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Webform component, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'webform' => $helper::randomRealWebform(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Webform component, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'webform' => $helper::randomRealWebform(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Webform component, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'webform' => $helper::randomRealWebform(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
  ];
}
