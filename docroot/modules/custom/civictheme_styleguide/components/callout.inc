<?php

/**
 * @file
 * Callout component styleguide integration.
 */

use Drupal\civictheme_styleguide\Styleguide;
use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Implements _civictheme_styleguide_component_NAME().
 */
function _civictheme_styleguide_component_callout() {
  $items = [];

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [
    [
      'title' => 'Callout component',
      'components' => [
        [
          'type' => 'civictheme_callout',
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
      'title' => 'Callout component, 2 on page',
      'components' => [
        [
          'type' => 'civictheme_callout',
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
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBottom(),
        ],
        [
          'type' => 'civictheme_callout',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'links' => [
            [
              'uri' => $helper::randomUrl(),
              'title' => $helper::staticSentence(2),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Callout component, Dark',
      'components' => [
        [
          'type' => 'civictheme_callout',
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
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Callout component, Light',
      'components' => [
        [
          'type' => 'civictheme_callout',
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
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Callout component, Light, Both spaces',
      'components' => [
        [
          'type' => 'civictheme_callout',
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
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Callout component, Dark, Both spaces',
      'components' => [
        [
          'type' => 'civictheme_callout',
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
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
  ];

  foreach ($variations as $variation) {
    $content = '';

    foreach ($variation['components'] as $component) {
      $para = Styleguide::paragraphFromOptions($component['type'], $component);
      $content .= Styleguide::renderParagraph($para, TRUE);
    }

    $items[] = [
      'title' => $variation['title'],
      'content' => $content,
      'options' => [
        'edge-to-edge' => FALSE,
      ],
    ];
  }

  return $items;
}
