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
      'title' => 'Webform component',
      'components' => [
        [
          'type' => 'webform',
          'webform' => $helper::randomRealWebform(),
        ],
      ],
    ],
    [
      'title' => 'Webform component, 2 on page',
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
      'title' => 'Webform component, Dark',
      'components' => [
        [
          'type' => 'content',
          'webform' => $helper::randomRealWebform(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Webform component, Light, BG',
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
      'title' => 'Webform component, Dark, BG',
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
      'title' => 'Webform component, Light, No BG, Both spaces',
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
      'title' => 'Webform component, Dark, No BG, Both spaces',
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
      'title' => 'Webform component, Light, BG, Both spaces',
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
      'title' => 'Webform component, Dark, BG, Both spaces',
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
