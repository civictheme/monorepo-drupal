<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner Components - Search.
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner_component_search() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Banner Search component',
      'banner_components' => [
        [
          'type' => 'search',
          'search_url' => $helper::randomUrl(),
          'button_text' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeLight(),
          'title' => $helper::staticSentence(3),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Search component, 3 on page, Help text, Placeholder text',
      'banner_components' => [
        [
          'type' => 'search',
          'search_url' => $helper::randomUrl(),
          'button_text' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeLight(),
          'title' => $helper::staticSentence(3),
          'help_text' => $helper::staticSentence(3),
          'placeholder' => $helper::staticSentence(3),
        ],
        [
          'type' => 'search',
          'search_url' => $helper::randomUrl(),
          'button_text' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeLight(),
          'title' => $helper::staticSentence(3),
          'help_text' => $helper::staticSentence(3),
          'placeholder' => $helper::staticSentence(3),
        ],
        [
          'type' => 'search',
          'search_url' => $helper::randomUrl(),
          'button_text' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeLight(),
          'title' => $helper::staticSentence(3),
          'help_text' => $helper::staticSentence(3),
          'placeholder' => $helper::staticSentence(3),
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Banner Search component, Dark',
      'banner_components' => [
        [
          'type' => 'search',
          'search_url' => $helper::randomUrl(),
          'button_text' => $helper::staticSentence(3),
          'title' => $helper::staticSentence(3),
          'help_text' => $helper::staticSentence(3),
          'placeholder' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Search component, Light',
      'banner_components' => [
        [
          'type' => 'search',
          'search_url' => $helper::randomUrl(),
          'button_text' => $helper::staticSentence(3),
          'theme' => $helper::civicthemeThemeLight(),
          'title' => $helper::staticSentence(3),
          'help_text' => $helper::staticSentence(3),
          'placeholder' => $helper::staticSentence(3),
        ],
      ],
    ],
  ];
}
