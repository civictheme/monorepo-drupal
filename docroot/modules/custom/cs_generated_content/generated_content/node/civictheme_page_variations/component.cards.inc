<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Cards.
 */
function _cs_generated_content_create_node_civictheme_page__variations__cards() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Navigation card',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_navigation_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => $helper::staticLinkFieldValue(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
        [
          'title' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_navigation_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => $helper::staticLinkFieldValue(),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Navigation reference card',
      'components' => [
        [
          'theme' => $helper::civicthemeThemeLight(),
          'title' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_navigation_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
        [
          'theme' => $helper::civicthemeThemeDark(),
          'title' => $helper::civicthemeThemeDark(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_navigation_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],

    [
      'title' => 'Promo card',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_promo_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'subtitle' => $helper::staticSentence(2),
              'summary' => $helper::staticSentence(8),
              'link' => $helper::staticLinkFieldValue(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeLight(),
            ],
            [
              'type' => 'civictheme_promo_card',
              'title' => 'No image, ' . $helper::staticSentence(3),
              'subtitle' => $helper::staticSentence(2),
              'summary' => $helper::staticSentence(8),
              'link' => $helper::staticLinkFieldValue(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeLight(),
            ],
            [
              'type' => 'civictheme_promo_card',
              'title' => 'No image, no link, ' . $helper::staticSentence(3),
              'subtitle' => $helper::staticSentence(2),
              'summary' => $helper::staticSentence(8),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
        [
          'title' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_promo_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'subtitle' => $helper::staticSentence(2),
              'summary' => $helper::staticSentence(8),
              'link' => $helper::staticLinkFieldValue(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeDark(),
            ],
            [
              'type' => 'civictheme_promo_card',
              'title' => 'No image, ' . $helper::staticSentence(3),
              'subtitle' => $helper::staticSentence(2),
              'summary' => $helper::staticSentence(8),
              'link' => $helper::staticLinkFieldValue(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeDark(),
            ],
            [
              'type' => 'civictheme_promo_card',
              'title' => 'No image, no link, ' . $helper::staticSentence(3),
              'subtitle' => $helper::staticSentence(2),
              'summary' => $helper::staticSentence(8),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Promo reference card',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_promo_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
        [
          'title' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'type' => 'manual_list',
          'column_count' => 2,
          'background' => TRUE,
          'cards' => [
            [
              'type' => 'civictheme_promo_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],

    [
      'title' => 'Publication card',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_publication_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'document' => $helper->staticMediaItem('civictheme_document'),
              'theme' => $helper::civicthemeThemeLight(),
            ],
            [
              'type' => 'civictheme_publication_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => 'No image, ' . $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'document' => $helper->staticMediaItem('civictheme_document'),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
        [
          'theme' => $helper::civicthemeThemeDark(),
          'title' => $helper::civicthemeThemeDark(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_publication_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'document' => $helper->staticMediaItem('civictheme_document'),
              'theme' => $helper::civicthemeThemeDark(),
            ],
            [
              'type' => 'civictheme_publication_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => 'No image, ' . $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'document' => $helper->staticMediaItem('civictheme_document'),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],

    [
      'title' => 'Service card',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_service_card',
              'title' => $helper::staticSentence(3),
              'links' => [
                $helper::staticLinkFieldValue(),
                $helper::staticLinkFieldValue(),
                $helper::staticLinkFieldValue(),
              ],
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
        [
          'title' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_service_card',
              'title' => $helper::staticSentence(3),
              'links' => [
                $helper::staticLinkFieldValue(),
                $helper::staticLinkFieldValue(),
                $helper::staticLinkFieldValue(),
              ],
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],

    [
      'title' => 'Subject card',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_subject_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'link' => $helper::staticLinkFieldValue(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
            [
              'type' => 'civictheme_subject_card',
              'title' => 'No image, ' . $helper::staticSentence(3),
              'link' => $helper::staticLinkFieldValue(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
        [
          'title' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_subject_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'link' => $helper::staticLinkFieldValue(),
              'theme' => $helper::civicthemeThemeDark(),
            ],
            [
              'type' => 'civictheme_subject_card',
              'title' => 'No image, ' . $helper::staticSentence(3),
              'link' => $helper::staticLinkFieldValue(),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Subject reference card',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_subject_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'topic' => $helper::staticTerm('civictheme_topics')->id(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
        [
          'title' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'cards' => [
            [
              'type' => 'civictheme_subject_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'topic' => $helper::staticTerm('civictheme_topics')->id(),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
  ];
}
