<?php

declare(strict_types=1);

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Content.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_content(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $page = [
    'components' => [
      [
        'type' => 'content',
        'content' => 'Light, ' . $helper::staticRichText(3),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'content',
        'content' => 'Dark, ' . $helper::staticRichText(3),
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'content',
        'content' => 'Light, BG, ' . $helper::staticRichText(3),
        'theme' => $helper::civicthemeThemeLight(),
        'background' => TRUE,
      ],
      [
        'type' => 'content',
        'content' => 'Dark, BG, ' . $helper::staticRichText(3),
        'theme' => $helper::civicthemeThemeDark(),
        'background' => TRUE,
      ],

      [
        'type' => 'content',
        'content' => 'Light, Vertical Spacing, ' . $helper::staticRichText(3),
        'theme' => $helper::civicthemeThemeLight(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
      [
        'type' => 'content',
        'content' => 'Dark, Vertical Spacing, ' . $helper::staticRichText(3),
        'theme' => $helper::civicthemeThemeDark(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],

      [
        'type' => 'content',
        'content' => 'Light, BG, Vertical Spacing, ' . $helper::staticRichText(3),
        'theme' => $helper::civicthemeThemeLight(),
        'background' => TRUE,
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
      [
        'type' => 'content',
        'content' => 'Dark, BG, Vertical Spacing, ' . $helper::staticRichText(3),
        'theme' => $helper::civicthemeThemeDark(),
        'background' => TRUE,
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
    ],
  ];

  return [
    $page + ['title' => 'Page. Component. Content, sidebar'],
    $page + [
      'title' => 'Page. Component. Content, no sidebar',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
    ],
  ];
}
