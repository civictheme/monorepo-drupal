<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Callout.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_callout() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Callout component',
      'components' => [
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
      'title' => 'Demo Page, Callout component, 2 links',
      'components' => [
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
      'title' => 'Demo Page, Callout component, 3 links',
      'components' => [
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
      'title' => 'Demo Page, Callout component, No link text',
      'components' => [
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
      'title' => 'Demo Page, Callout component, 3 on page',
      'components' => [
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
      'title' => 'Demo Page, Callout component, Dark',
      'components' => [
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
      'title' => 'Demo Page, Callout component, Light, Both spaces',
      'components' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Callout component, Dark, Both spaces',
      'components' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Callout component, Light, Top space',
      'components' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeTop(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Callout component, Dark, Top space',
      'components' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeTop(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Callout component, Light, Bottom space',
      'components' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBottom(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Callout component, Dark, Bottom space',
      'components' => [
        [
          'type' => 'callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBottom(),
        ],
      ],
    ],
  ];
}
