<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Manual list.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _cs_generated_content_create_node_civictheme_page__variations__manual_list(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $page = [
    'components' => [
      [
        'title' => 'Promo card, Light, Full width, 1 column, 1 card',
        'theme' => $helper::civicthemeThemeLight(),
        'list_column_count' => 1,
        'type' => 'manual_list',
        'list_items' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
        ],
      ],
      [
        'title' => 'Promo card, Light, Full width, 1 column, 1 card, fill width',
        'theme' => $helper::civicthemeThemeLight(),
        'list_column_count' => 1,
        'list_fill_width' => TRUE,
        'type' => 'manual_list',
        'list_items' => [
          [
            'type' => 'civictheme_promo_card',
            'title' => $helper::staticSentence(3),
          ],
        ],
      ],
      [
        'title' => 'Promo card, Light, Full width, 2 column, 3 cards',
        'theme' => $helper::civicthemeThemeLight(),
        'list_column_count' => 2,
        'type' => 'manual_list',
        'list_items' => [
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
        'title' => 'Promo card, Light, Full width, 2 column, 3 cards, fill width',
        'theme' => $helper::civicthemeThemeLight(),
        'list_column_count' => 2,
        'list_fill_width' => TRUE,
        'type' => 'manual_list',
        'list_items' => [
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
        'title' => 'Promo card, Light, Title, Content',
        'theme' => $helper::civicthemeThemeLight(),
        'content' => $helper::staticSentence(8),
        'type' => 'manual_list',
        'list_column_count' => 2,
        'list_items' => [
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
        'title' => 'Promo card, Dark, Title, Content',
        'theme' => $helper::civicthemeThemeDark(),
        'content' => $helper::staticSentenceFieldValue(8),
        'type' => 'manual_list',
        'list_column_count' => 2,
        'list_items' => [
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
        'title' => 'Promo card, Light, BG, Title, Content, Before Link, After Link',
        'theme' => $helper::civicthemeThemeLight(),
        'content' => $helper::staticSentenceFieldValue(8),
        'list_link_above' => $helper::staticLinkFieldValue(2),
        'list_link_below' => $helper::staticLinkFieldValue(2),
        'background' => TRUE,
        'type' => 'manual_list',
        'list_column_count' => 2,
        'list_items' => [
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
        'title' => 'Promo card, Dark, BG, Title, Content, Before Link, After Link',
        'theme' => $helper::civicthemeThemeDark(),
        'content' => $helper::staticSentenceFieldValue(8),
        'list_link_above' => $helper::staticLinkFieldValue(2),
        'list_link_below' => $helper::staticLinkFieldValue(2),
        'background' => TRUE,
        'type' => 'manual_list',
        'list_column_count' => 2,
        'list_items' => [
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
        'title' => 'Promo card, Light, BG, Title, Content, Before Link, After Link, Vertical Spacing',
        'theme' => $helper::civicthemeThemeLight(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'content' => $helper::staticSentenceFieldValue(8),
        'list_link_above' => $helper::staticLinkFieldValue(2),
        'list_link_below' => $helper::staticLinkFieldValue(2),
        'background' => TRUE,
        'type' => 'manual_list',
        'list_column_count' => 2,
        'list_items' => [
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
        'title' => 'Promo card, Dark, BG, Title, Content, Before Link, After Link, Vertical Spacing',
        'theme' => $helper::civicthemeThemeDark(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'content' => $helper::staticSentenceFieldValue(8),
        'list_link_above' => $helper::staticLinkFieldValue(2),
        'list_link_below' => $helper::staticLinkFieldValue(2),
        'background' => TRUE,
        'type' => 'manual_list',
        'list_column_count' => 2,
        'list_items' => [
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
        'title' => 'Snippet, Light, BG, Title, Content, Before Link, After Link, Vertical Spacing',
        'theme' => $helper::civicthemeThemeLight(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'content' => $helper::staticSentenceFieldValue(8),
        'list_link_above' => $helper::staticLinkFieldValue(2),
        'list_link_below' => $helper::staticLinkFieldValue(2),
        'background' => TRUE,
        'type' => 'manual_list',
        'list_column_count' => 1,
        'list_items' => [
          [
            'type' => 'civictheme_snippet',
            'title' => $helper::staticSentence(3),
            'summary' => $helper::staticPlainParagraph(),
          ],
          [
            'type' => 'civictheme_snippet',
            'title' => $helper::staticSentence(3),
            'summary' => $helper::staticPlainParagraph(),
          ],
          [
            'type' => 'civictheme_snippet',
            'title' => $helper::staticSentence(3),
            'summary' => $helper::staticPlainParagraph(),
          ],
        ],
      ],
      [
        'title' => 'Snippet, Dark, BG, Title, Content, Before Link, After Link, Vertical Spacing',
        'theme' => $helper::civicthemeThemeDark(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'content' => $helper::staticSentenceFieldValue(8),
        'list_link_above' => $helper::staticLinkFieldValue(2),
        'list_link_below' => $helper::staticLinkFieldValue(2),
        'background' => TRUE,
        'type' => 'manual_list',
        'list_column_count' => 1,
        'list_items' => [
          [
            'type' => 'civictheme_snippet',
            'title' => $helper::staticSentence(3),
            'summary' => $helper::staticPlainParagraph(),
            'theme' => $helper::civicthemeThemeDark(),
          ],
          [
            'type' => 'civictheme_snippet',
            'title' => $helper::staticSentence(3),
            'summary' => $helper::staticPlainParagraph(),
            'theme' => $helper::civicthemeThemeDark(),
          ],
          [
            'type' => 'civictheme_snippet',
            'title' => $helper::staticSentence(3),
            'summary' => $helper::staticPlainParagraph(),
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
