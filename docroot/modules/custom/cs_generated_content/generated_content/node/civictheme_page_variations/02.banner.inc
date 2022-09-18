<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner.
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  /**
   * Light Theme banner with breadcrumb, with background, with featured image, Content component, Accordion component below banner
   * Dark Light featured banner without breadcrumb, without background, without featured image, Card container component, Callout component below banner
   * Light Dark banner with breadcrumb, with background, with featured image, iFrame component, Card container component below banner
   * Dark Light featured banner without breadcrumb, without background, without featured image, Map component, iFrame component below banner
   * Light Dark banner with breadcrumb, with background, with featured image, Slider component, Automated list component below banner
   * Dark Light featured banner without breadcrumb, without background, without featured image, Search component, Map component below banner
   * Light Dark banner with breadcrumb, with background, with featured image, Promo component below banner
   * Dark Light featured banner without breadcrumb, without background, without featured image, Slider component below banner
   * Light Dark banner with breadcrumb, with background, with featured image, Webform component below banner
   */
  $variations = [
    // Page, Banner.
    [
      'title' => 'Light Theme banner with breadcrumb, with background, without featured image, Content component, Accordion component below banner',
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'banner_hide_breadcrumb' => TRUE,
      'content' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
      'components' => [
        [
          'type' => 'content',
          'content' => $helper::staticRichText(3),
        ],
      ],
      'banner_components_bott' => [
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
      'title' => 'Dark featured banner without breadcrumb, without background, without featured image, Card container component, Callout component below banner',
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'content' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
      'banner_hide_breadcrumb' => FALSE,
      'banner_components' => [
        [
          'type' => 'card_container',
          'column_count' => rand(1, 4),
          'cards' => [
            [
              'type' => 'civictheme_navigation_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
            [
              'type' => 'civictheme_navigation_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
            [
              'type' => 'civictheme_navigation_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
          ],
        ],
        'banner_components_bott' => [
          [
            'type' => 'callout',
            'title' => $helper::staticSentence(3),
            'summary' => $helper::staticSentence(8),
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
          ],
        ],
      ],
    ],
    [
      'title' => 'Light banner with breadcrumb, with background, with featured image, iFrame component, Card container component below banner',
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
      'banner_hide_breadcrumb' => TRUE,
      'banner_components' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'attributes' => 'referrerpolicy="no-referrer"',
        ],
      ],
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
            [
              'type' => 'civictheme_navigation_card_ref',
              'reference' => $helper::randomRealNode($helper::civicthemePageContentType())->id(),
              'theme' => $helper::civicthemeThemeLight(),
            ],
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
      'title' => 'Dark featured banner without breadcrumb, without background, without featured image, Map component, iFrame component below banner',
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'content' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
      'banner_hide_breadcrumb' => TRUE,
      'banner_components' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'share_link' => $helper::randomUrl(),
          'view_link' => $helper::randomUrl(),
        ],
      ],
      'banner_components_bott' => [
        [
          'type' => 'iframe',
          'url' => $helper::randomUrl(),
          'attributes' => 'referrerpolicy="no-referrer"',
        ],
      ],
    ],
    [
      'title' => 'Light banner with breadcrumb, with background, without featured image, Slider component, Automated list component below banner',
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
      'banner_hide_breadcrumb' => FALSE,
      'banner_components' => [
        [
          'type' => 'slider',
          'slides' => [
            [
              'type' => 'civictheme_slider_slide',
              'title' => $helper::staticSentence(2),
              'theme' => $helper::civicthemeThemeDark(),
              'image' => $helper->staticMediaItem('civictheme_image')->id(),
              'image_position' => $helper::civicthemeSliderSlideImagePositionRight(),
            ],
          ],
        ],
      ],
      'banner_components_bott' => [
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
          'space' => $helper::civicthemeSpaceTypeTop(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Dark featured banner without breadcrumb, without background, with featured image, Map component below banner',
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'banner_hide_breadcrumb' => TRUE,
      'content' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
      'banner_components_bott' => [
        [
          'type' => 'map',
          'embed_url' => $helper::randomUrl(),
          'address' => $helper::staticSentence(3),
          'share_link' => $helper::randomUrl(),
          'view_link' => $helper::randomUrl(),
        ],
      ],
    ],
    [
      'title' => 'Light banner with breadcrumb, with background, with featured image, Promo component below banner',
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
      'banner_components_bott' => [
        [
          'type' => 'promo',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
        ],
      ],
    ],
    [
      'title' => 'Dark featured banner without breadcrumb, without background, without featured image, Slider component below banner',
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
      'banner_components_bott' => [
        [
          'type' => 'slider',
          'slides' => [
            [
              'type' => 'civictheme_slider_slide',
              'title' => $helper::staticSentence(2),
              'theme' => $helper::civicthemeThemeDark(),
              'image' => $helper->staticMediaItem('civictheme_image')->id(),
              'image_position' => $helper::civicthemeSliderSlideImagePositionRight(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Light banner with breadcrumb, with background, with featured image, Webform component below banner',
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'content' => _cs_generated_content_create_node_civictheme_page__variations_components_content_default(),
      'banner_components_bott' => [
        [
          'type' => 'webform',
          'webform' => $helper::randomRealWebform(),
        ],
      ],
    ],
  ];

  return $variations;
}
