<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Iframe.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_iframe(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $page = [
    'components' => [
      [
        'type' => 'iframe',
        'url' => $helper::staticUrl(),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'iframe',
        'url' => $helper::staticUrl(),
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'iframe',
        'url' => $helper::staticUrl(),
        'height' => '100%',
        'width' => '100%',
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'iframe',
        'url' => $helper::staticUrl(),
        'height' => '100%',
        'width' => '100%',
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'iframe',
        'url' => $helper::staticUrl(),
        'height' => '50%',
        'width' => '50%',
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'iframe',
        'url' => $helper::staticUrl(),
        'height' => '50%',
        'width' => '50%',
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'iframe',
        'url' => $helper::staticUrl(),
        'height' => '100%',
        'width' => '100%',
        'theme' => $helper::civicthemeThemeLight(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
      [
        'type' => 'iframe',
        'url' => $helper::staticUrl(),
        'height' => '100%',
        'width' => '100%',
        'theme' => $helper::civicthemeThemeDark(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],

      [
        'type' => 'iframe',
        'url' => $helper::staticUrl(),
        'height' => '100%',
        'width' => '100%',
        'theme' => $helper::civicthemeThemeLight(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'background' => TRUE,
      ],
      [
        'type' => 'iframe',
        'url' => $helper::staticUrl(),
        'height' => '100%',
        'width' => '100%',
        'theme' => $helper::civicthemeThemeDark(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'background' => TRUE,
      ],
    ],
  ];

  return [
    $page + ['title' => 'Page. Component. Iframe'],
    $page + [
      'title' => 'Page. Component. Iframe, no sidebar',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
    ],
  ];
}
