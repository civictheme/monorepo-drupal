<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner Bottom Components - Promo.
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner_components_bott_promo() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Banner Bottom Promo component',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, No link text',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, Summary',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, No link text, Summary',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, 2 on page, Link text & No link text',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, Dark',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, Light',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, Light, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, Dark, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, Dark, Summary',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'summary' => $helper::staticSentence(8),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, Light, Summary',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'summary' => $helper::staticSentence(8),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, Light, Both spaces, Summary',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'summary' => $helper::staticSentence(8),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Promo component, Dark, Both spaces, Summary',
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'summary' => $helper::staticSentence(8),
        ],
      ],
    ],
  ];
}
