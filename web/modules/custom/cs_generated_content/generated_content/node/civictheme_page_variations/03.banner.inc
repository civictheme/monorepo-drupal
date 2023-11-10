<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [
    [
      'title' => 'Page. Banner default, Light, Background, Content',
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'blend_mode' => $helper::civicthemeImageBlendMode(),
      'banner_components' => [
        [
          'type' => 'content',
          'theme' => $helper::civicthemeThemeLight(),
          'content' => $helper::staticRichText(1),
        ],
      ],
    ],
    [
      'title' => 'Page. Banner default, Light, Background, Content',
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'blend_mode' => $helper::civicthemeImageBlendMode(),
      'banner_components' => [
        [
          'type' => 'content',
          'theme' => $helper::civicthemeThemeDark(),
          'content' => $helper::staticRichText(1),
        ],
      ],
    ],

    [
      'title' => 'Page. Banner large, Light, Background, Content',
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'blend_mode' => $helper::civicthemeImageBlendMode(),
      'banner_components' => [
        [
          'type' => 'content',
          'theme' => $helper::civicthemeThemeLight(),
          'content' => $helper::staticRichText(1),
        ],
      ],
    ],
    [
      'title' => 'Page. Banner large, Light, Background, Content',
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'blend_mode' => $helper::civicthemeImageBlendMode(),
      'banner_components' => [
        [
          'type' => 'content',
          'theme' => $helper::civicthemeThemeDark(),
          'content' => $helper::staticRichText(1),
        ],
      ],
    ],

    [
      'title' => 'Page. Banner default, Light, All fields, Content, Accordion below',
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'blend_mode' => $helper::civicthemeImageBlendMode(),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'banner_components' => [
        [
          'type' => 'content',
          'theme' => $helper::civicthemeThemeLight(),
          'content' => $helper::staticRichText(1),
        ],
      ],
      'banner_components_bott' => [
        [
          'type' => 'accordion',
          'theme' => $helper::civicthemeThemeLight(),
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
      'title' => 'Page. Banner default, Dark, All fields, Content, Accordion below',
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'blend_mode' => $helper::civicthemeImageBlendMode(),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'banner_components' => [
        [
          'type' => 'content',
          'theme' => $helper::civicthemeThemeDark(),
          'content' => $helper::staticRichText(1),
        ],
      ],
      'banner_components_bott' => [
        [
          'type' => 'accordion',
          'theme' => $helper::civicthemeThemeDark(),
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
      'title' => 'Page. Banner large, Light, All fields, Content, Accordion below',
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'blend_mode' => $helper::civicthemeImageBlendMode(),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'banner_components' => [
        [
          'type' => 'content',
          'theme' => $helper::civicthemeThemeLight(),
          'content' => $helper::staticRichText(1),
        ],
      ],
      'banner_components_bott' => [
        [
          'type' => 'accordion',
          'theme' => $helper::civicthemeThemeLight(),
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
      'title' => 'Page. Banner large, Dark, All fields, Content, Accordion below',
      'banner_theme' => $helper::civicthemeThemeDark(),
      'banner_type' => $helper::civicthemeBannerTypeLarge(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'blend_mode' => $helper::civicthemeImageBlendMode(),
      'banner_featured_image' => $helper->staticMediaItem('civictheme_image'),
      'banner_components' => [
        [
          'type' => 'content',
          'theme' => $helper::civicthemeThemeDark(),
          'content' => $helper::staticRichText(1),
        ],
      ],
      'banner_components_bott' => [
        [
          'type' => 'accordion',
          'theme' => $helper::civicthemeThemeDark(),
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
      'title' => 'Page. Banner default, Light, Background, Content, No breadcrumb',
      'banner_theme' => $helper::civicthemeThemeLight(),
      'banner_type' => $helper::civicthemeBannerTypeDefault(),
      'banner_background' => $helper->staticMediaItem('civictheme_image'),
      'blend_mode' => $helper::civicthemeImageBlendMode(),
      'banner_hide_breadcrumb' => TRUE,
      'banner_components' => [
        [
          'type' => 'content',
          'theme' => $helper::civicthemeThemeLight(),
          'content' => $helper::staticRichText(1),
        ],
      ],
    ],
  ];

  return $variations;
}
