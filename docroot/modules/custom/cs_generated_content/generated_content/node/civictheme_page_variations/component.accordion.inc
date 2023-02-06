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
      'title' => 'Page. Component. Accordion',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => 'Panel 1 of 1, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => 'Panel 1 of 2, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
            [
              'title' => 'Panel 2 of 2, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => 'Panel 1 of 2, all expanded, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
            [
              'title' => 'Panel 2 of 2, all expanded, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'expand_all' => TRUE,
        ],
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => 'Panel 1 of 2, expanded, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => 'Panel 2 of 2, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],

        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => 'Panel 1 of 2, expanded, all expanded, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => 'Panel 2 of 2, all expanded, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'expand_all' => TRUE,
        ],

        [
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'type' => 'accordion',
          'panels' => [
            [
              'title' => 'Panel 1 of 2, expanded, Light, BG, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => 'Panel 1 of 2, Dark, BG, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
        [
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'type' => 'accordion',
          'panels' => [
            [
              'title' => 'Panel 1 of 2, expanded, Dark, BG, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => 'Panel 1 of 2, Dark, BG, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],

        [
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'type' => 'accordion',
          'panels' => [
            [
              'title' => 'Panel 1 of 2, expanded, Light, BG, Vertical spacing, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => 'Panel 1 of 2, Dark, BG, Vertical spacing, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
        [
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'type' => 'accordion',
          'panels' => [
            [
              'title' => 'Panel 1 of 2, expanded, Dark, BG, Vertical spacing, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => 'Panel 1 of 2, Dark, BG, Vertical spacing, ' . $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
      ],
    ],
  ];
}
