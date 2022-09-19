<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Promo.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_promo() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Promo component',
      'components' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Promo component, No link text',
      'components' => [
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
      'title' => 'Demo Page, Promo component, Summary',
      'components' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Promo component, No link text, Summary',
      'components' => [
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
      'title' => 'Demo Page, Promo component, 2 on page, Link text & No link text',
      'components' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
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
      'title' => 'Demo Page, Promo component, Dark',
      'components' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Promo component, Light',
      'components' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Promo component, Light, Both spaces',
      'components' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Promo component, Dark, Both spaces',
      'components' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Promo component, Dark, Summary',
      'components' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'summary' => $helper::staticSentence(8),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Promo component, Light, Summary',
      'components' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'summary' => $helper::staticSentence(8),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Promo component, Light, Both spaces, Summary',
      'components' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'summary' => $helper::staticSentence(8),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Promo component, Dark, Both spaces, Summary',
      'components' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'summary' => $helper::staticSentence(8),
        ],
      ],
    ],
  ];
}
