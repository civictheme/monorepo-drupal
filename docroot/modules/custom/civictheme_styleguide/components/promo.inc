<?php

/**
 * @file
 * Promo component styleguide integration.
 */

use Drupal\civictheme_styleguide\Styleguide;
use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Implements _civictheme_styleguide_component_NAME().
 */
function _civictheme_styleguide_component_promo() {
  $items = [];

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [
    [
      'title' => 'Promo component',
      'components' => [
        [
          'type' => 'civictheme_promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
        ],
      ],
    ],
    [
      'title' => 'Promo component, No link text',
      'components' => [
        [
          'type' => 'civictheme_promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
          ],
        ],
      ],
    ],
    [
      'title' => 'Promo component, Summary',
      'components' => [
        [
          'type' => 'civictheme_promo',
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
      'title' => 'Promo component, No link text, Summary',
      'components' => [
        [
          'type' => 'civictheme_promo',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
          ],
        ],
      ],
    ],
    [
      'title' => 'Promo component, 2 on page, Link text & No link text',
      'components' => [
        [
          'type' => 'civictheme_promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
        ],
        [
          'type' => 'civictheme_promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
          ],
        ],
      ],
    ],
    [
      'title' => 'Promo component, Dark',
      'components' => [
        [
          'type' => 'civictheme_promo',
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
      'title' => 'Promo component, Light',
      'components' => [
        [
          'type' => 'civictheme_promo',
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
      'title' => 'Promo component, Light, Both spaces',
      'components' => [
        [
          'type' => 'civictheme_promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Promo component, Dark, Both spaces',
      'components' => [
        [
          'type' => 'civictheme_promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Promo component, Dark, Summary',
      'components' => [
        [
          'type' => 'civictheme_promo',
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
      'title' => 'Promo component, Light, Summary',
      'components' => [
        [
          'type' => 'civictheme_promo',
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
      'title' => 'Promo component, Light, Both spaces, Summary',
      'components' => [
        [
          'type' => 'civictheme_promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'summary' => $helper::staticSentence(8),
        ],
      ],
    ],
    [
      'title' => 'Promo component, Dark, Both spaces, Summary',
      'components' => [
        [
          'type' => 'civictheme_promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'summary' => $helper::staticSentence(8),
        ],
      ],
    ],
  ];

  foreach ($variations as $variation) {
    $para = Styleguide::paragraphFromOptions($variation['components'][0]['type'], $variation['components'][0]);
    $items[] = [
      'title' => $variation['title'],
      'content' => Styleguide::renderParagraph($para, TRUE),
      'options' => [
        'edge-to-edge' => TRUE,
      ],
    ];
  }

  return $items;
}
