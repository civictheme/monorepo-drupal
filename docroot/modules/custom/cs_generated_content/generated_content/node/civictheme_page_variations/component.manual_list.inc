<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Card container - Content.
 */
function _cs_generated_content_create_node_civictheme_page__variations__manual_list() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Manual list component, Navigation card, Light',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_navigation_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::staticUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Navigation card, Dark',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_navigation_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::staticUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Service card, Dark, Link',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_service_card',
              'title' => $helper::staticSentence(3),
              'links' => [
                'uri' => $helper::staticUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Service card, Light, 2 Links',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_service_card',
              'title' => $helper::staticSentence(3),
              'links' => [
                [
                  'uri' => $helper::staticUrl(),
                  'title' => $helper::staticSentence(2),
                ],
                [
                  'uri' => $helper::staticUrl(),
                  'title' => $helper::staticSentence(2),
                ],
              ],
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Reference promo card, Page, Light',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_promo_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Navigation reference card, Page, Light',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_navigation_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Promo card, Dark',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_promo_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::staticUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'date' => '2022-11-14',
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Subject card, Dark',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_subject_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'link' => [
                'uri' => $helper::staticUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Subject reference card, Page, Dark',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_subject_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
              'topic' => $helper::staticTerm('civictheme_topics')->id(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Publication card, Image, Light, Small',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_publication_card',
              'theme' => $helper::civicthemeThemeLight(),
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'document' => $helper->staticMediaItem('civictheme_document'),
              'size' => $helper::civicthemeSizeSmall(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Publication card, No Image, Dark, Large',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_publication_card',
              'theme' => $helper::civicthemeThemeDark(),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'document' => $helper->staticMediaItem('civictheme_document'),
              'size' => $helper::civicthemeSizeLarge(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Full width',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => rand(1, 4),
          'fill_width' => TRUE,
          'cards' => [
            [
              'type' => 'civictheme_subject_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
              'topic' => $helper::staticTerm('civictheme_topics')->id(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Column count 2',
      'components' => [
        [
          'type' => 'manual_list',
          'column_count' => 2,
          'cards' => [
            [
              'type' => 'civictheme_promo_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::staticUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'date' => '2022-11-14',
            ],
            [
              'type' => 'civictheme_subject_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
              'topic' => $helper::staticTerm('civictheme_topics')->id(),
            ],
            [
              'type' => 'civictheme_promo_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::staticUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'date' => '2022-11-14',
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Light, BG, Title, Description',
      'components' => [
        [
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_service_card',
              'title' => $helper::staticSentence(3),
              'links' => [
                [
                  'uri' => $helper::staticUrl(),
                  'title' => $helper::staticSentence(2),
                ],
                [
                  'uri' => $helper::staticUrl(),
                  'title' => $helper::staticSentence(2),
                ],
              ],
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
          'column_count' => rand(1, 4),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'title' => $helper::staticSentence(2),
          'description' => $helper::staticSentence(8),
        ],
      ],
    ],
    [
      'title' => 'Manual list component, Dark, BG, Title, Description',
      'components' => [
        [
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_navigation_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::staticUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
          'column_count' => rand(1, 4),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'title' => $helper::staticSentence(2),
          'description' => $helper::staticSentence(8),
        ],
      ],
    ],
  ];
}
