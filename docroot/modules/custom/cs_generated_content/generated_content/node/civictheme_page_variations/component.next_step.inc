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
      'title' => 'Page. Component. Next Step',
      'components' => [
        [
          'type' => 'next_step',
          'title' => 'Light, ' . $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeLight(),
        ],
        [
          'type' => 'next_step',
          'title' => 'Dark, ' . $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeDark(),
        ],

        [
          'type' => 'next_step',
          'title' => 'Light, Summary, Link, ' . $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::staticUrl(),
          ],
          'theme' => $helper::civicthemeThemeLight(),
        ],
        [
          'type' => 'next_step',
          'title' => 'Dark, Summary, Link, ' . $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::staticUrl(),
          ],
          'theme' => $helper::civicthemeThemeLight(),
        ],

        [
          'type' => 'next_step',
          'title' => 'Light, Summary, Link, Vertical Space, ' . $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::staticUrl(),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
        [
          'type' => 'next_step',
          'title' => 'Dark, Summary, Link, Vertical Space, ' . $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'link' => [
            'uri' => $helper::staticUrl(),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
  ];
}
