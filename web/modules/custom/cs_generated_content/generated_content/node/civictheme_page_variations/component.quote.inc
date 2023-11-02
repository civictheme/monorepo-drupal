<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Quote.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_quote() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $page = [
    'components' => [
      [
        'type' => 'quote',
        'content' => 'Light, Content, ' . $helper::staticSentence(3),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'quote',
        'content' => 'Dark, Content, ' . $helper::staticSentence(3),
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'quote',
        'content' => 'Light, Content, Author, ' . $helper::staticSentence(3),
        'author' => $helper::staticName(),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'quote',
        'content' => 'Dark, Content, Author, ' . $helper::staticSentence(3),
        'author' => $helper::staticName(),
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'quote',
        'content' => 'Light, Content, Author, Vertical spacing, ' . $helper::staticSentence(3),
        'author' => $helper::staticName(),
        'theme' => $helper::civicthemeThemeLight(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
      [
        'type' => 'quote',
        'content' => 'Dark, Content, Author, Vertical spacing, ' . $helper::staticSentence(3),
        'author' => $helper::staticName(),
        'theme' => $helper::civicthemeThemeDark(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
    ],
  ];

  return [
    $page + ['title' => 'Page. Component. Quote, sidebar'],
    $page + [
      'title' => 'Page. Component. Quote, no sidebar',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
    ],
  ];
}
