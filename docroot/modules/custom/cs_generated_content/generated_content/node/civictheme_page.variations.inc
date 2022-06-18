<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Variations of pages used for listings.
 */
function _cs_generated_content_create_node_civictheme_page__for_listings__variations() {
  $total_nodes = 30;

  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [];

  for ($i = 0; $i < $total_nodes; $i++) {
    $variation['title'] = sprintf('Demo Page for listing %s %s', $i + 1, $helper::staticSentence(1));
    // Set every 4th page to be unpublished.
    $variation['status'] = $i % 4 != 0;
    $variation['summary'] = $helper::staticPlainParagraph($i);
    $variation['content'] = $helper::staticRichText(3);
    $variation['alias'] = '/demo/civictheme_page/{title}';
    $variations[] = $variation;
  }

  return $variations;
}

/**
 * Variations of pages used for components.
 */
function _cs_generated_content_create_node_civictheme_page__components__variations() {
  $variations = [];

  $variations = array_merge($variations, _cs_generated_content_create_node_civictheme_page__components__variations__page_general());
  $variations = array_merge($variations, _cs_generated_content_create_node_civictheme_page__components__variations__page_banner());
  $variations = array_merge($variations, _cs_generated_content_create_node_civictheme_page__components__variations__content());
  $variations = array_merge($variations, _cs_generated_content_create_node_civictheme_page__components__variations__accordion());

  // Add common variation properties to all variations.
  foreach ($variations as &$variation) {
    $variation['status'] = TRUE;
    $variation['alias'] = '/generated-content/civictheme_page/component-{title}';
  }

  return $variations;
}

/**
 * Page, General.
 */
function _cs_generated_content_create_node_civictheme_page__components__variations__page_general() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [
    [
      'title' => 'Demo Page, default values',
    ],
    [
      'title' => 'Demo Page, Summary',
      'summary' => $helper::staticPlainParagraph(),
    ],
    [
      'title' => 'Demo Page, Summary, Content',
      'summary' => $helper::staticPlainParagraph(),
      'components' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(),
    ],
    [
      'title' => 'Demo Page, Summary, Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'components' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(),
    ],
    [
      'title' => 'Demo Page, Summary, Topics (1), Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'components' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(),
    ],
    [
      'title' => 'Demo Page, Summary, Topics (3), Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 3),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'components' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(),
    ],
    [
      'title' => 'Demo Page, Summary, Topics (1), Thumbnail, Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'components' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(),
    ],
  ];

  return $variations;
}

/**
 * Page, Banner.
 */
function _cs_generated_content_create_node_civictheme_page__components__variations__page_banner() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [
    // Page, Banner.
    [
      'title' => 'Demo Page, Banner',
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(6),
    ],
    [
      'title' => 'Demo Page, Banner, Default, Light',
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_theme' => $helper::civicthemeThemeLight(),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(7),
    ],
    [
      'title' => 'Demo Page, Banner, Default, Dark',
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_theme' => $helper::civicthemeThemeDark(),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(7),
    ],
    [
      'title' => 'Demo Page, Banner, Large, Light',
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_theme' => $helper::civicthemeThemeLight(),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(8),
    ],
    [
      'title' => 'Demo Page, Banner, Large, Dark',
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_theme' => $helper::civicthemeThemeDark(),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(9),
    ],

    [
      'title' => 'Demo Page, Banner, Default, Light, Background',
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(7),
    ],
    [
      'title' => 'Demo Page, Banner, Default, Dark, Background',
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(7),
    ],
    [
      'title' => 'Demo Page, Banner, Large, Light, Background',
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(8),
    ],
    [
      'title' => 'Demo Page, Banner, Large, Dark, Background',
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(9),
    ],

    [
      'title' => 'Demo Page, Banner, Default, Light, Background, Image',
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(10),
    ],
    [
      'title' => 'Demo Page, Banner, Default, Dark, Background, Image',
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(11),
    ],
    [
      'title' => 'Demo Page, Banner, Large, Light, Background, Image',
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(12),
    ],
    [
      'title' => 'Demo Page, Banner, Large, Dark, Background, Image',
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(13),
    ],
  ];

  return $variations;
}

/**
 * Page, Components - Content.
 */
function _cs_generated_content_create_node_civictheme_page__components__variations__content() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Content component',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, 3 on page',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
        ],
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
        ],
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Content component, Dark',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Light, BG',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Dark, BG',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
  ];
}

/**
 * Page, Components - Accordion.
 */
function _cs_generated_content_create_node_civictheme_page__components__variations__accordion() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Accordion component',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, 2 panels',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, 2 panels, all expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'expand_all' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, 2 panels, first and all expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'expand_all' => TRUE,
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Accordion component, Light, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Light, BG, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Light, BG, Both spaces, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Light, No BG, Both spaces, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Accordion component, Dark, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Dark, BG, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Dark, BG, Both spaces, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Accordion component, Dark, No BG, Both spaces, 2 panels, only first expanded',
      'components' => [
        [
          'type' => 'accordion',
          'panels' => [
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
              'expand' => TRUE,
            ],
            [
              'title' => $helper::staticSentence(3),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
  ];
}

/**
 * Default values for Content component to use in variations.
 */
function _cs_generated_content_create_node_civictheme_page__components__variants__content_default() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'type' => 'content',
      'content' => $helper::staticRichText(3),
      'space' => $helper::civicthemeSpaceTypeNone(),
    ],
  ];
}
