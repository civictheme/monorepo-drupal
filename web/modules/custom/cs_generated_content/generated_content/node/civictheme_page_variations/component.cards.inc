<?php

declare(strict_types=1);

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Cards.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _cs_generated_content_create_node_civictheme_page__variations__cards(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Page. Component. Navigation card',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'list_items' => [
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
          'list_items' => [
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
      'title' => 'Page. Component. Navigation reference card',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
      'components' => [
        [
          'theme' => $helper::civicthemeThemeLight(),
          'title' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'list_items' => [
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
          'list_items' => [
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
      'title' => 'Page. Component. Promo card',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'list_items' => [
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
          'list_items' => [
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
      'title' => 'Page. Component. Promo reference card',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'list_items' => [
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
          'list_items' => [
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
      'title' => 'Page. Component. Event card',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'list_items' => [
            [
              'type' => 'civictheme_event_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => 'Summary ' . $helper::staticSentence(8),
              'date_range' => [
                'value' => '2021-04-30T12:15:00',
                'end_value' => '2026-05-30T14:30:00',
              ],
              'location' => 'Location ' . $helper::staticSentence(2),
              'link' => $helper::staticLinkFieldValue(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeLight(),
            ],
            [
              'type' => 'civictheme_event_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => 'Same day ' . $helper::staticSentence(3),
              'summary' => 'Summary ' . $helper::staticSentence(8),
              'date_range' => [
                'value' => '2021-04-30T12:15:00',
                'end_value' => '2021-04-30T14:30:00',
              ],
              'location' => 'Location ' . $helper::staticSentence(2),
              'link' => $helper::staticLinkFieldValue(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeLight(),
            ],
            [
              'type' => 'civictheme_event_card',
              'title' => 'No image ' . $helper::staticSentence(3),
              'summary' => 'Summary ' . $helper::staticSentence(8),
              'date_range' => [
                'value' => '2021-04-30T12:15:00',
                'end_value' => '2026-05-30T14:30:00',
              ],
              'location' => 'Location ' . $helper::staticSentence(2),
              'link' => $helper::staticLinkFieldValue(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeLight(),
            ],
            [
              'type' => 'civictheme_event_card',
              'title' => 'No image, no link ' . $helper::staticSentence(3),
              'summary' => 'Summary ' . $helper::staticSentence(8),
              'date_range' => [
                'value' => '2021-04-30T12:15:00',
                'end_value' => '2026-05-30T14:30:00',
              ],
              'location' => 'Location ' . $helper::staticSentence(2),
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
          'list_items' => [
            [
              'type' => 'civictheme_event_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => 'Summary ' . $helper::staticSentence(8),
              'date_range' => [
                'value' => $helper::dateToUtc('2021-04-30T12:15:00'),
                'end_value' => $helper::dateToUtc('2026-05-30T14:30:00'),
              ],
              'location' => 'Location ' . $helper::staticSentence(2),
              'link' => $helper::staticLinkFieldValue(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeDark(),
            ],
            [
              'type' => 'civictheme_event_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => 'Same day ' . $helper::staticSentence(3),
              'summary' => 'Summary ' . $helper::staticSentence(8),
              'date_range' => [
                'value' => $helper::dateToUtc('2021-04-30T12:15:00'),
                'end_value' => $helper::dateToUtc('2021-04-30T14:30:00'),
              ],
              'location' => 'Location ' . $helper::staticSentence(2),
              'link' => $helper::staticLinkFieldValue(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeDark(),
            ],
            [
              'type' => 'civictheme_event_card',
              'title' => 'No image ' . $helper::staticSentence(3),
              'summary' => 'Summary ' . $helper::staticSentence(8),
              'date_range' => [
                'value' => $helper::dateToUtc('2021-04-30T12:15:00'),
                'end_value' => $helper::dateToUtc('2026-05-30T14:30:00'),
              ],
              'location' => 'Location ' . $helper::staticSentence(2),
              'link' => $helper::staticLinkFieldValue(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeDark(),
            ],
            [
              'type' => 'civictheme_event_card',
              'title' => 'No image, no link ' . $helper::staticSentence(3),
              'summary' => 'Summary ' . $helper::staticSentence(8),
              'date_range' => [
                'value' => $helper::dateToUtc('2021-04-30T12:15:00'),
                'end_value' => $helper::dateToUtc('2026-05-30T14:30:00'),
              ],
              'location' => 'Location ' . $helper::staticSentence(2),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Page. Component. Event reference card',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'list_items' => [
            [
              'type' => 'civictheme_event_card_ref',
              'reference' => $helper::staticNode($helper::civicthemeEventContentType())->id(),
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
          'list_items' => [
            [
              'type' => 'civictheme_event_card_ref',
              'reference' => $helper::staticNode($helper::civicthemeEventContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],

    [
      'title' => 'Page. Component. Publication card',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'list_items' => [
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
          'list_items' => [
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
      'title' => 'Page. Component. Service card',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'list_items' => [
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
          'list_items' => [
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
      'title' => 'Page. Component. Subject card',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'list_items' => [
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
          'list_items' => [
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
      'title' => 'Page. Component. Subject reference card',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
      'components' => [
        [
          'title' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'column_count' => 2,
          'background' => TRUE,
          'type' => 'manual_list',
          'list_items' => [
            [
              'type' => 'civictheme_subject_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
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
          'list_items' => [
            [
              'type' => 'civictheme_subject_card_ref',
              'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
  ];
}
