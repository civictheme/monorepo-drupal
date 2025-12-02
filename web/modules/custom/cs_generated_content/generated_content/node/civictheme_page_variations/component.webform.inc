<?php

declare(strict_types=1);

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Webform.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_webform(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $page = [
    'components' => [
      [
        'type' => 'webform',
        'webform' => $helper::randomRealWebform(),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'webform',
        'webform' => $helper::randomRealWebform(),
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'webform',
        'webform' => $helper::randomRealWebform(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'webform',
        'webform' => $helper::randomRealWebform(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'webform',
        'webform' => $helper::randomRealWebform(),
        'background' => TRUE,
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'webform',
        'webform' => $helper::randomRealWebform(),
        'background' => TRUE,
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'webform',
        'webform' => $helper::randomRealWebform(),
        'background' => TRUE,
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'webform',
        'webform' => $helper::randomRealWebform(),
        'background' => TRUE,
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'theme' => $helper::civicthemeThemeDark(),
      ],

    ],
  ];

  return [
    $page + ['title' => 'Page. Component. Webform, sidebar'],
    $page + [
      'title' => 'Page. Component. Webform, no sidebar',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
    ],
  ];
}
