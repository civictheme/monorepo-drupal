<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Next Step.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_next_step() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Next Step component',
      'components' => [
        [
          'type' => 'next_step',
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
      'title' => 'Demo Page, Next Step component, No link text',
      'components' => [
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Next Step component, Image',
      'components' => [
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'image' => $helper->staticMediaItem('civictheme_image'),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Next Step component, No link text, Image',
      'components' => [
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
          ],
          'image' => $helper->staticMediaItem('civictheme_image'),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Next Step component, 2 on page, Link text & No link text',
      'components' => [
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
          ],
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Next Step component, Dark',
      'components' => [
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Next Step component, Light, BG',
      'components' => [
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Next Step component, Dark, BG',
      'components' => [
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Next Step component, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Next Step component, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Next Step component, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Next Step component, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'next_step',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
  ];
}
