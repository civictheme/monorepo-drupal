<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner Bottom Components - Callout.
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner_components_bott_callout() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Banner Bottom Callout component',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Callout component, 2 links',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            [
              'uri' => $helper::randomUrl(),
              'title' => $helper::staticSentence(2),
            ],
            [
              'uri' => $helper::randomUrl(),
              'title' => $helper::staticSentence(2),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Callout component, 3 links',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            [
              'uri' => $helper::randomUrl(),
              'title' => $helper::staticSentence(2),
            ],
            [
              'uri' => $helper::randomUrl(),
              'title' => $helper::staticSentence(2),
            ],
            [
              'uri' => $helper::randomUrl(),
              'title' => $helper::staticSentence(2),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Callout component, No link text',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Callout component, 3 on page',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
        ],
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
        ],
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Banner Bottom Callout component, Dark',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Callout component, Light, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Callout component, Dark, Both spaces',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Callout component, Light, Top space',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'space' => $helper::civicthemeSpaceTypeTop(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Callout component, Dark, Top space',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'space' => $helper::civicthemeSpaceTypeTop(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Callout component, Light, Bottom space',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'space' => $helper::civicthemeSpaceTypeBottom(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Callout component, Dark, Bottom space',
      'banner_components_bott' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'space' => $helper::civicthemeSpaceTypeBottom(),
        ],
      ],
    ],
  ];
}
