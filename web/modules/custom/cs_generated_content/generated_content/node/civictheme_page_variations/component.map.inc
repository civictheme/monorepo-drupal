<?php

declare(strict_types=1);

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Map.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_map(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $page = [
    'components' => [
      [
        'type' => 'map',
        'embed_url' => $helper::staticUrl(),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'map',
        'embed_url' => $helper::staticUrl(),
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'map',
        'embed_url' => $helper::staticUrl(),
        'address' => $helper::staticSentence(3),
        'view_link' => $helper::staticLinkFieldValue(),
        'theme' => $helper::civicthemeThemeLight(),
        'background' => TRUE,
      ],
      [
        'type' => 'map',
        'embed_url' => $helper::staticUrl(),
        'address' => $helper::staticSentence(3),
        'view_link' => $helper::staticLinkFieldValue(),
        'theme' => $helper::civicthemeThemeDark(),
        'background' => TRUE,
      ],

      [
        'type' => 'map',
        'embed_url' => $helper::staticUrl(),
        'address' => $helper::staticSentence(3),
        'view_link' => $helper::staticLinkFieldValue(),
        'theme' => $helper::civicthemeThemeLight(),
        'background' => TRUE,
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
      [
        'type' => 'map',
        'embed_url' => $helper::staticUrl(),
        'address' => $helper::staticSentence(3),
        'view_link' => $helper::staticLinkFieldValue(),
        'theme' => $helper::civicthemeThemeDark(),
        'background' => TRUE,
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
    ],
  ];

  return [
    $page + ['title' => 'Page. Component. Map, sidebar'],
    $page + [
      'title' => 'Page. Component. Map, no sidebar',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
    ],
  ];
}
