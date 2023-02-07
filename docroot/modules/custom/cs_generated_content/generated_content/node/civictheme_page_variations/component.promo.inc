<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Promo.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_promo() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $page = [
    'components' => [
      [
        'type' => 'promo',
        'title' => 'Light, ' . $helper::staticSentence(3),
        'link' => $helper::staticLinkFieldValue(),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'promo',
        'title' => 'Dark, ' . $helper::staticSentence(3),
        'link' => $helper::staticLinkFieldValue(),
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'promo',
        'title' => 'Light, Content, Vertical spacing, ' . $helper::staticSentence(3),
        'content' => $helper::staticSentence(8),
        'link' => $helper::staticLinkFieldValue(),
        'theme' => $helper::civicthemeThemeLight(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
      [
        'type' => 'promo',
        'title' => 'Dark, Content, Vertical spacing, ' . $helper::staticSentence(3),
        'content' => $helper::staticSentence(8),
        'link' => $helper::staticLinkFieldValue(),
        'theme' => $helper::civicthemeThemeDark(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
    ],
  ];

  return [
    $page + ['title' => 'Page. Component. Promo, sidebar'],
    $page + [
      'title' => 'Page. Component. Promo, no sidebar',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
    ],
  ];
}
