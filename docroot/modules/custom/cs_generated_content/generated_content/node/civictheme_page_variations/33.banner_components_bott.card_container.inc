<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner Bottom Components Card container - Content.
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner_components_bott_card_container() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Banner Bottom Card container component, Task card',
      'banner_components_bott' => [
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
                'title' => $helper::staticString(30),
              ],
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Card container component, Navigation card, Light',
      'banner_components_bott' => [
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
                'title' => $helper::staticString(30),
              ],
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Card container component, Navigation card, Dark',
      'banner_components_bott' => [
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
                'title' => $helper::staticString(30),
              ],
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Card container component, Service card, Dark, Link',
      'banner_components_bott' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_service_card',
              'title' => $helper::staticSentence(3),
              'links' => [
                'uri' => $helper::randomUrl(),
                'title' => $helper::staticString(30),
              ],
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Card container component, Service card, Light, 2 Links',
      'banner_components_bott' => [
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
                  'title' => $helper::staticString(30),
                ],
                [
                  'uri' => $helper::randomUrl(),
                  'title' => $helper::staticString(30),
                ],
              ],
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Card container component, Reference promo card, Page, Light',
      'banner_components_bott' => [
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
      'title' => 'Demo Page, Banner Bottom Card container component, Reference promo card, Event, Light',
      'banner_components_bott' => [
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
      'title' => 'Demo Page, Banner Bottom Card container component, Event reference card, Dark',
      'banner_components_bott' => [
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
      'title' => 'Demo Page, Banner Bottom Card container component, Event card, Dark',
      'banner_components_bott' => [
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
                'title' => $helper::staticString(30),
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
      'title' => 'Demo Page, Banner Bottom Card container component, Navigation reference card, Event, Dark',
      'banner_components_bott' => [
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
      'title' => 'Demo Page, Banner Bottom Card container component, Navigation reference card, Page, Light',
      'banner_components_bott' => [
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
      'title' => 'Demo Page, Banner Bottom Card container component, Promo card, Dark',
      'banner_components_bott' => [
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
                'title' => $helper::staticString(30),
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
      'title' => 'Demo Page, Banner Bottom Card container component, Subject card, Dark',
      'banner_components_bott' => [
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
                'title' => $helper::staticString(30),
              ],
              'theme' => $helper::civicthemeThemeDark(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Card container component, Subject reference card, Page, Dark',
      'banner_components_bott' => [
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
      'title' => 'Demo Page, Banner Bottom Card container component, Subject reference card, Event, Light',
      'banner_components_bott' => [
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
      'title' => 'Demo Page, Banner Bottom Card container component, Publication card, Image, Light, Small',
      'banner_components_bott' => [
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
      'title' => 'Demo Page, Banner Bottom Card container component, Publication card, No Image, Dark, Large',
      'banner_components_bott' => [
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
      'title' => 'Demo Page, Banner Bottom Card container component, Full width',
      'banner_components_bott' => [
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
      'title' => 'Demo Page, Banner Bottom Card container component, Column count 2',
      'banner_components_bott' => [
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
                'title' => $helper::staticString(30),
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
                'title' => $helper::staticString(30),
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
      'title' => 'Demo Page, Banner Bottom Card container component, Dark, Footer link, Header link',
      'banner_components_bott' => [
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
                'title' => $helper::staticString(30),
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
            'title' => $helper::staticString(30),
          ],
          'header_link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Card container component, Light, BG, Title, Description',
      'banner_components_bott' => [
        [
          'type' => 'card_container',
          'cards' => [
            [
              'type' => 'civictheme_service_card',
              'title' => $helper::staticSentence(3),
              'links' => [
                [
                  'uri' => $helper::randomUrl(),
                  'title' => $helper::staticString(30),
                ],
                [
                  'uri' => $helper::randomUrl(),
                  'title' => $helper::staticString(30),
                ],
              ],
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
          'column_count' => rand(1, 4),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'title' => $helper::staticString(30),
          'description' => $helper::staticSentence(8),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Bottom Card container component, Dark, BG, Title, Description',
      'banner_components_bott' => [
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
                'title' => $helper::staticString(30),
              ],
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
          'column_count' => rand(1, 4),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'title' => $helper::staticString(30),
          'description' => $helper::staticSentence(8),
        ],
      ],
    ],
  ];
}
