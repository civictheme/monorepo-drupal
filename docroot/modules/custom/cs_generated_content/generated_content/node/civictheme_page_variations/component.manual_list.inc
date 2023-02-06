<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Manual list.
 */
function _cs_generated_content_create_node_civictheme_page__variations__manual_list() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $page = [
    'components' => [
      [
        'title' => 'Light, Full width, 1 column, 1 card',
        'theme' => $helper::civicthemeThemeLight(),
        'column_count' => 1,
        'type' => 'manual_list',
        'cards' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
        ],
      ],
      [
        'title' => 'Light, Full width, 1 column, 1 card, fill width',
        'theme' => $helper::civicthemeThemeLight(),
        'column_count' => 1,
        'fill_width' => TRUE,
        'type' => 'manual_list',
        'cards' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
        ],
      ],
      [
        'title' => 'Light, Full width, 2 column, 3 cards',
        'theme' => $helper::civicthemeThemeLight(),
        'column_count' => 2,
        'type' => 'manual_list',
        'cards' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
        ],
      ],
      [
        'title' => 'Light, Full width, 2 column, 3 cards, fill width',
        'theme' => $helper::civicthemeThemeLight(),
        'column_count' => 2,
        'fill_width' => TRUE,
        'type' => 'manual_list',
        'cards' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
        ],
      ],
      [
        'title' => 'Light, Title, Content',
        'theme' => $helper::civicthemeThemeLight(),
        'content' => $helper::staticSentence(8),
        'type' => 'manual_list',
        'column_count' => 2,
        'cards' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
        ],
      ],
      [
        'title' => 'Dark, Title, Content',
        'theme' => $helper::civicthemeThemeDark(),
        'content' => $helper::staticSentenceFieldValue(8),
        'type' => 'manual_list',
        'column_count' => 2,
        'cards' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
        ],
      ],
      [
        'title' => 'Light, BG, Title, Content, Before Link, After Link',
        'theme' => $helper::civicthemeThemeLight(),
        'content' => $helper::staticSentenceFieldValue(8),
        'list_link_above' => $helper::staticLinkFieldValue(2),
        'list_link_below' => $helper::staticLinkFieldValue(2),
        'background' => TRUE,
        'type' => 'manual_list',
        'column_count' => 2,
        'cards' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
        ],
      ],
      [
        'title' => 'Dark, BG, Title, Content, Before Link, After Link',
        'theme' => $helper::civicthemeThemeDark(),
        'content' => $helper::staticSentenceFieldValue(8),
        'list_link_above' => $helper::staticLinkFieldValue(2),
        'list_link_below' => $helper::staticLinkFieldValue(2),
        'background' => TRUE,
        'type' => 'manual_list',
        'column_count' => 2,
        'cards' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
            'theme' => $helper::civicthemeThemeDark(),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
            'theme' => $helper::civicthemeThemeDark(),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
            'theme' => $helper::civicthemeThemeDark(),
          ],
        ],
      ],

      [
        'title' => 'Light, BG, Title, Content, Before Link, After Link, Vertical Spacing',
        'theme' => $helper::civicthemeThemeLight(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'content' => $helper::staticSentenceFieldValue(8),
        'list_link_above' => $helper::staticLinkFieldValue(2),
        'list_link_below' => $helper::staticLinkFieldValue(2),
        'background' => TRUE,
        'type' => 'manual_list',
        'column_count' => 2,
        'cards' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
        ],
      ],
      [
        'title' => 'Dark, BG, Title, Content, Before Link, After Link, Vertical Spacing',
        'theme' => $helper::civicthemeThemeDark(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'content' => $helper::staticSentenceFieldValue(8),
        'list_link_above' => $helper::staticLinkFieldValue(2),
        'list_link_below' => $helper::staticLinkFieldValue(2),
        'background' => TRUE,
        'type' => 'manual_list',
        'column_count' => 2,
        'cards' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
            'theme' => $helper::civicthemeThemeDark(),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
            'theme' => $helper::civicthemeThemeDark(),
          ],
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
            'theme' => $helper::civicthemeThemeDark(),
          ],
        ],
      ],
    ],
  ];

  return [
    $page + ['title' => 'Page. Component. Manual list, sidebar'],
    $page + [
      'title' => 'Page. Component. Manual list, no sidebar',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
    ],
  ];
}
