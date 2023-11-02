<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Automated list.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_automated_list() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $pages = [
    // All. Promo card.
    [
      'title' => 'Page. Component. Automated list, All, View as Promo card, Unlimited, Title, Content, Above link, Below link, Both space, Background',
      'no_sidebar' => TRUE,
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title Light',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 4,
          'list_item_view_as' => $helper::civicthemePromoCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
        [
          'type' => 'automated_list',
          'title' => 'Automated list title Dark',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 4,
          'list_item_view_as' => $helper::civicthemePromoCardType(),
          'list_item_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
      ],
    ],

    // All. Navigation card.
    [
      'title' => 'Page. Component. Automated list, All, View as Navigation card, Unlimited, Title, Content, Above link, Below link, Both space, Background',
      'no_sidebar' => TRUE,
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title Light',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 2,
          'list_item_view_as' => $helper::civicthemeNavigationCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
        [
          'type' => 'automated_list',
          'title' => 'Automated list title Dark',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 2,
          'list_item_view_as' => $helper::civicthemeNavigationCardType(),
          'list_item_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
      ],
    ],

    // All. Snippet.
    [
      'title' => 'Page. Component. Automated list, All, View as Snippet, Unlimited, Title, Content, Above link, Below link, Both space, Background',
      'no_sidebar' => TRUE,
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title Light',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 1,
          'list_item_view_as' => $helper::civicthemeSnippetType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
        [
          'type' => 'automated_list',
          'title' => 'Automated list title Dark',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 1,
          'list_item_view_as' => $helper::civicthemeSnippetType(),
          'list_item_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
      ],
    ],

    // Page. Promo card.
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Promo card, Light card, Unlimited, Title, Content, Above link, Below link, Both space, Background',
      'no_sidebar' => TRUE,
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 4,
          'list_item_view_as' => $helper::civicthemePromoCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
      ],
    ],
    // Page. Navigation card.
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Navigation card, Light card, Unlimited, Title, Content, Above link, Below link, Both space, Background',
      'no_sidebar' => TRUE,
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 2,
          'list_item_view_as' => $helper::civicthemeNavigationCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
      ],
    ],
    // Page. Snippet.
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Snippet, Light card, Unlimited, Title, Content, Above link, Below link, Both space, Background',
      'no_sidebar' => TRUE,
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 1,
          'list_item_view_as' => $helper::civicthemeSnippetType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
      ],
    ],

    // Event. Promo card.
    [
      'title' => 'Page. Component. Automated list, Light, Events, View as Promo card, Light card, Unlimited, Title, Content, Above link, Below link, Both space, Background',
      'no_sidebar' => TRUE,
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_content_type' => $helper::civicthemeEventContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 4,
          'list_item_view_as' => $helper::civicthemePromoCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
      ],
    ],
    // Event. Navigation card.
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Navigation card, Light card, Unlimited, Title, Content, Above link, Below link, Both space, Background',
      'no_sidebar' => TRUE,
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_content_type' => $helper::civicthemeEventContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 2,
          'list_item_view_as' => $helper::civicthemeNavigationCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
      ],
    ],
    // Event. Snippet.
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Snippet, Light card, Unlimited, Title, Content, Above link, Below link, Both space, Background',
      'no_sidebar' => TRUE,
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'content' => $helper::staticSentenceFieldValue(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_link_above' => $helper::staticLinkFieldValue(),
          'list_link_below' => $helper::staticLinkFieldValue(),
          'list_content_type' => $helper::civicthemeEventContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 1,
          'list_item_view_as' => $helper::civicthemeSnippetType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
      ],
    ],

    // Limit.
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Promo card, Light card, Unlimited 6, Title',
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 6,
          'list_column_count' => 4,
          'list_item_view_as' => $helper::civicthemePromoCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Promo card, Light card, Limited 6, Title',
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeLimited(),
          'list_limit' => 6,
          'list_column_count' => 4,
          'list_item_view_as' => $helper::civicthemePromoCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],

    // Topics and Site sections.
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Promo card, Light card, Unlimited, Title, Topics',
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 4,
          'list_item_view_as' => $helper::civicthemePromoCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'list_topics' => $helper::civicthemeStaticTopic(),
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Promo card, Light card, Unlimited, Title, Site sections',
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 4,
          'list_item_view_as' => $helper::civicthemePromoCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'list_site_sections' => $helper::civicthemeStaticSiteSection(),
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Promo card, Light card, Unlimited, Title, Topics, Site sections',
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 4,
          'list_item_view_as' => $helper::civicthemePromoCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'list_topics' => $helper::civicthemeStaticTopic(),
          'list_site_sections' => $helper::civicthemeStaticSiteSection(),
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],

    // Exposed filters.
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Promo card, Light card, Unlimited, Title, Single exposed filter',
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 4,
          'list_item_view_as' => $helper::civicthemePromoCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'list_filters_exp' => $helper::civicThemeExposeSingleFilter(),
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Page. Component. Automated list, Light, Pages, View as Promo card, Light card, Unlimited, Title, Multi exposed filter',
      'components' => [
        [
          'type' => 'automated_list',
          'title' => 'Automated list title',
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_limit' => 0,
          'list_column_count' => 4,
          'list_item_view_as' => $helper::civicthemePromoCardType(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'list_filters_exp' => $helper::civicThemeExposeMultipleFilters(),
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
  ];

  $all_pages = [];
  foreach ($pages as $page) {
    $set = [$page];
    if (!empty($page['no_sidebar'])) {
      // @formatter:off
      $set[] = [
        'title' => str_replace('Page. Component. Automated list', 'Page. Component. Automated list, no sidebar', $page['title']),
        'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
      ] + $page;
      // @formatter:on
      unset($page['no_sidebar']);
    }

    $all_pages = array_merge($all_pages, $set);
  }

  return $all_pages;
}
