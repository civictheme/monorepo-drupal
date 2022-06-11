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
    // Set every 4th page to be unpublished.
    $variation['title'] = sprintf('Demo Page for listing %s %s', $i + 1, $helper::staticSentence(1, $i));
    $variation['status'] = $i % 4 != 0;
    $variation['summary'] = $helper::staticPlainParagraph($i);
    $variation['content'] = $helper::staticRichText(3, '', $i);
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
      'summary' => $helper::staticPlainParagraph(0),
    ],
    [
      'title' => 'Demo Page, Summary, Content',
      'summary' => $helper::staticPlainParagraph(1),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(1),
    ],
    [
      'title' => 'Demo Page, Summary, Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(2),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(2),
    ],
    [
      'title' => 'Demo Page, Summary, Topics (1), Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(3),
      'topics' => $helper::staticTerms('civictheme_topics', 1, 0),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(3),
    ],
    [
      'title' => 'Demo Page, Summary, Topics (3), Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(4),
      'topics' => $helper::staticTerms('civictheme_topics', 3, 1),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(4),
    ],

    [
      'title' => 'Demo Page, Summary, Topics (1), Thumbnail, Both spaces, Content',
      'summary' => $helper::staticPlainParagraph(5),
      'topics' => $helper::staticTerms('civictheme_topics', 1, 2),
      'thumbnail' => $helper->staticMedia('civictheme_image', 1, 0),
      'space' => $helper::civicthemeSpaceTypeBoth(),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(5),
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
      'banner_background' => $helper->staticMedia('civictheme_image', 1, 1),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(7),
    ],
    [
      'title' => 'Demo Page, Banner, Default, Dark, Background',
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_background' => $helper->staticMedia('civictheme_image', 1, 2),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(7),
    ],
    [
      'title' => 'Demo Page, Banner, Large, Light, Background',
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_background' => $helper->staticMedia('civictheme_image', 1, 3),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(8),
    ],
    [
      'title' => 'Demo Page, Banner, Large, Dark, Background',
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_background' => $helper->staticMedia('civictheme_image', 1, 4),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(9),
    ],

    [
      'title' => 'Demo Page, Banner, Default, Light, Background, Image',
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_background' => $helper->staticMedia('civictheme_image', 1, 5),
      'banner_featured_image' => $helper->staticMedia('civictheme_image', 1, 6),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(10),
    ],
    [
      'title' => 'Demo Page, Banner, Default, Dark, Background, Image',
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_background' => $helper->staticMedia('civictheme_image', 1, 7),
      'banner_featured_image' => $helper->staticMedia('civictheme_image', 1, 8),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(11),
    ],
    [
      'title' => 'Demo Page, Banner, Large, Light, Background, Image',
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_background' => $helper->staticMedia('civictheme_image', 1, 9),
      'banner_featured_image' => $helper->staticMedia('civictheme_image', 1, 10),
      'content' => _cs_generated_content_create_node_civictheme_page__components__variants__content_default(12),
    ],
    [
      'title' => 'Demo Page, Banner, Large, Dark, Background, Image',
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_background' => $helper->staticMedia('civictheme_image', 1, 11),
      'banner_featured_image' => $helper->staticMedia('civictheme_image', 1, 12),
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
      'content' => [
        [
          'content' => $helper::staticRichText(3, '', 14),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Dark',
      'content' => [
        [
          'content' => $helper::staticRichText(3, '', 15),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Light, BG',
      'content' => [
        [
          'content' => $helper::staticRichText(3, '', 16),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Dark, BG',
      'content' => [
        [
          'content' => $helper::staticRichText(3, '', 17),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Light, BG, Both spaces',
      'content' => [
        [
          'content' => $helper::staticRichText(3, '', 18),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Dark, No BG, Both spaces',
      'content' => [
        [
          'content' => $helper::staticRichText(3, '', 19),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Light, BG, Both spaces',
      'content' => [
        [
          'content' => $helper::staticRichText(3, '', 20),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content component, Dark, BG, Both spaces',
      'content' => [
        [
          'content' => $helper::staticRichText(3, '', 21),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
  ];
}

/**
 * Default values for Content component to ue in variations.
 */
function _cs_generated_content_create_node_civictheme_page__components__variants__content_default($idx) {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'content' => $helper::staticRichText(3, '', $idx),
      'space' => $helper::civicthemeSpaceTypeNone(),
    ],
  ];
}
