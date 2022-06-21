<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Accordion.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_accordion() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Accordion component',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, 2 panels',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, 2 panels, all expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'expand_all' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, 2 panels, first and all expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'expand_all' => TRUE,
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Accordion component, Light, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Light, BG, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Light, BG, Both spaces, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Light, No BG, Both spaces, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Accordion component, Dark, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Dark, BG, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Dark, BG, Both spaces, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Dark, No BG, Both spaces, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
  ];
}
