<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Automated List.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_automated_list() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Automated List component, Dark single exposed filter, light navigation cards, no title top link with bottom space, with pagination',
      'components' => [
        [
          'type' => 'automated_list',
          'list_type' => $helper::civicthemeAutomatedListType(),
          // Are we just testing civic page or do we want to event cards.
          // or leave to another test.
          'list_content_type' => $helper::civicthemePageContentType(),
          'theme' => $helper::civicthemeThemeDark(),
          'title' => NULL,
          'list_show_filters' => TRUE,
          'list_filters_exp' => $helper::civicThemeExposeSingleFilter(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'list_view_as' => $helper::civicthemeNavigationCardType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_link_above' => [
            'uri' => $helper::randomUrl() . '/above',
            'title' => 'Above ' . $helper::staticSentence(2),
          ],
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBottom(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Automated List component, Light single filter light navigation cards, no title, content, top link with top space, with pagination',
      'components' => [
        [
          'type' => 'automated_list',
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'theme' => $helper::civicthemeThemeLight(),
          'title' => NULL,
          'content' => $helper::staticHtmlParagraph(),
          'list_show_filters' => TRUE,
          'list_filters_exp' => $helper::civicThemeExposeSingleFilter(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'list_view_as' => $helper::civicthemeNavigationCardType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_link_above' => [
            'uri' => $helper::randomUrl() . '/above',
            'title' => 'Above ' . $helper::staticSentence(2),
          ],
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeTop(),
        ],
      ],
    ],
    // Light no filter dark promo, title, bottom link with no space listing multiple filters, without pagination
    [
      'title' => 'Demo Page, Automated List component, Light no filter dark promo, title, bottom link with no space listing without pagination',
      'components' => [
        [
          'type' => 'automated_list',
          'theme' => $helper::civicthemeThemeLight(),
          'title' => 'Automated List component, Light no filter dark promo, title, bottom link with no space listing without pagination',
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_show_filters' => FALSE,
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_view_as' => $helper::civicthemePromoCardType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeLimited(),
          'content' => $helper::staticHtmlParagraph(),
          'list_item_theme' => $helper::civicthemeThemeDark(),
          'list_link_below' => [
            'uri' => $helper::randomUrl() . '/below',
            'title' => 'Below ' . $helper::staticSentence(2),
          ],
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeNone(),
        ],
      ],
    ],
    // Dark no filter light navigation cards, no title top link with bottom space, without pagination.
    [
      'title' => 'Demo Page, Automated List component, Dark no filter light navigation cards, no title top link with bottom space, without pagination.',
      'components' => [
        [
          'type' => 'automated_list',
          'theme' => $helper::civicthemeThemeDark(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_show_filters' => FALSE,
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_view_as' => $helper::civicthemePromoCardType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeLimited(),
          'list_item_theme' => $helper::civicthemeThemeLight(),
          'content' => $helper::staticHtmlParagraph(),
          'list_link_top' => [
            'uri' => $helper::randomUrl() . '/below',
            'title' => 'Below ' . $helper::staticSentence(2),
          ],
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBottom(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Automated List component, Light multi-filter light navigation, title and top link with both space, with pagination.',
      'components' => [
        [
          'type' => 'automated_list',
          'theme' => $helper::civicthemeThemeLight(),
          'title' => 'Light multi-filter light navigation, title and top link with both space, with pagination',
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_show_filters' => TRUE,
          'list_filters_exp' => $helper::civicThemeExposeMultipleFilters(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_view_as' => $helper::civicthemeNavigationCardType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_item_theme' => $helper::civicthemeThemeDark(),
          'list_link_above' => [
            'uri' => $helper::randomUrl() . '/above',
            'title' => 'Above ' . $helper::staticSentence(2),
          ],
          'list_link_below' => [
            'uri' => $helper::randomUrl() . '/below',
            'title' => 'Below ' . $helper::staticSentence(2),
          ],
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Automated List component, Dark multi-filter dark promo cards, no title, bottom link with top space, with pagination',
      'components' => [
        [
          'type' => 'automated_list',
          'theme' => $helper::civicthemeThemeDark(),
          'list_type' => $helper::civicthemeAutomatedListType(),
          'list_show_filters' => TRUE,
          'list_filters_exp' => $helper::civicThemeExposeMultipleFilters(),
          'list_content_type' => $helper::civicthemePageContentType(),
          'list_view_as' => $helper::civicthemePromoCardType(),
          'list_limit_type' => $helper::civicthemeAutomatedListLimitTypeUnlimited(),
          'list_item_theme' => $helper::civicthemeThemeDark(),
          'list_link_below' => [
            'uri' => $helper::randomUrl() . '/below',
            'title' => 'Below ' . $helper::staticSentence(2),
          ],
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeTop(),
          'background' => TRUE,
        ],
      ],
    ],
  ];
}
