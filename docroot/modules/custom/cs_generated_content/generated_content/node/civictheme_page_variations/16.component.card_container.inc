<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Card container - Content.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_card_container() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Card container component, Task card',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_task_card',
              'icon' => $helper->staticMediaItem('civictheme_icon'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::randomUrl(),
                'title' => $helper::staticSentence(2),
              ],
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Navigation card, Light',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_navigation_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::randomUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Navigation card, Dark',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_navigation_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::randomUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Service card, Dark, Link',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_service_card',
              'title' => $helper::staticSentence(3),
              'links' => [
                'uri' => $helper::randomUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Service card, Light, 2 Links',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_service_card',
              'title' => $helper::staticSentence(3),
              'links' => [
                [
                  'uri' => $helper::randomUrl(),
                  'title' => $helper::staticSentence(2),
                ],
                [
                  'uri' => $helper::randomUrl(),
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
      'title' => 'Demo Page, Card container component, Reference promo card, Page, Light',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_promo_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Reference promo card, Event, Light',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_promo_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemeEventContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Event reference card, Dark',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_event_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemeEventContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Event card, Dark',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_event_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::randomUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
              'location' => $helper::staticSentence(3),
              'topic' => $helper::randomRealTerm('civictheme_topics')->id(),
              'date' => $helper::randomDate(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Navigation reference card, Event, Dark',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_navigation_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemeEventContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Navigation reference card, Page, Light',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_navigation_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Promo card, Dark',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_promo_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::randomUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'date' => $helper::randomDate(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Subject card, Dark',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_subject_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'link' => [
                'uri' => $helper::randomUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Subject reference card, Page, Dark',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_subject_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
              'topic' => $helper::randomRealTerm('civictheme_topics')->id(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Subject reference card, Event, Light',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_subject_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemeEventContentType())->id(),
              'theme' => $helper::civicthemeThemeLight(),
              'topic' => $helper::randomRealTerm('civictheme_topics')->id(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Publication card, Image, Light, Small',
      'components' => [
        [
          'type' => 'card_container',
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
      'title' => 'Demo Page, Card container component, Publication card, No Image, Dark, Large',
      'components' => [
        [
          'type' => 'card_container',
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
      'title' => 'Demo Page, Card container component, Full width',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'fill_width' => TRUE,
          'cards' => [
            [
              'type' => 'civictheme_subject_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
              'topic' => $helper::randomRealTerm('civictheme_topics')->id(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Column count 2',
      'components' => [
        [
          'type' => 'card_container',
          'column_count' => 2,
          'cards' => [
            [
              'type' => 'civictheme_promo_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::randomUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'date' => $helper::randomDate(),
            ],
            [
              'type' => 'civictheme_subject_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeDark(),
              'topic' => $helper::randomRealTerm('civictheme_topics')->id(),
            ],
            [
              'type' => 'civictheme_promo_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::randomUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
              'topics' => $helper::staticTerms('civictheme_topics', 3),
              'date' => $helper::randomDate(),
            ],
          ],
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Card container component, Dark, Footer link, Header link',
      'components' => [
        [
          'type' => 'card_container',
          'cards' => [
            [
              'type' => 'civictheme_event_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::randomUrl(),
                'title' => $helper::staticSentence(2),
              ],
              'theme' => $helper::civicthemeThemeDark(),
              'location' => $helper::staticSentence(3),
              'topic' => $helper::randomRealTerm('civictheme_topics')->id(),
              'date' => $helper::randomDate(),
            ],
          ],
          'column_count' => rand(1, 4),
          'theme' => $helper::civicthemeThemeDark(),
          'footer_link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'header_link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Card container component, Light, BG, Title, Description',
      'components' => [
        [
          'type' => 'card_container',
          'cards' => [
            [
              'type' => 'civictheme_service_card',
              'title' => $helper::staticSentence(3),
              'links' => [
                [
                  'uri' => $helper::randomUrl(),
                  'title' => $helper::staticSentence(2),
                ],
                [
                  'uri' => $helper::randomUrl(),
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
      'title' => 'Demo Page, Card container component, Dark, BG, Title, Description',
      'components' => [
        [
          'type' => 'card_container',
          'cards' => [
            [
              'type' => 'civictheme_navigation_card',
              'image' => $helper->staticMediaItem('civictheme_image'),
              'title' => $helper::staticSentence(3),
              'summary' => $helper::staticSentence(8),
              'link' => [
                'uri' => $helper::randomUrl(),
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
