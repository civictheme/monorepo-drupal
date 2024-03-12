<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Content Quote.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_quote(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $page = [
    'components' => [
      [
        'type' => 'content',
        'content' => '<blockquote>Light, Content, ' . $helper::staticSentence(3) . '</blockquote>',
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'content',
        'content' => '<blockquote>Dark, Content, ' . $helper::staticSentence(3) . '</blockquote>',
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'content',
        'content' => '<blockquote>Light, Content, Author, ' . $helper::staticSentence(3) . '<cite>' . $helper::staticName() . '</cite></blockquote>',
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'content',
        'content' => '<blockquote>Dark, Content, Author, ' . $helper::staticSentence(3) . '<cite>' . $helper::staticName() . '</cite></blockquote>',
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'content',
        'content' => '<blockquote>Light, Content, Author, Vertical spacing, ' . $helper::staticSentence(3) . '<cite>' . $helper::staticName() . '</cite></blockquote>',
        'theme' => $helper::civicthemeThemeLight(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
      [
        'type' => 'content',
        'content' => '<blockquote>Dark, Content, Author, Vertical spacing, ' . $helper::staticSentence(3) . '<cite>' . $helper::staticName() . '</cite></blockquote>',
        'theme' => $helper::civicthemeThemeDark(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
    ],
  ];

  return [
    $page + ['title' => 'Page. Component. Content, Quote, sidebar'],
    $page + [
      'title' => 'Page. Component. Content, Quote, no sidebar',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
    ],
  ];
}
