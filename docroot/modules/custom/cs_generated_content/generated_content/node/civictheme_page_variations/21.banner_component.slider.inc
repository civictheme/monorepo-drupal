<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Banner Components - Slider.
 */
function _cs_generated_content_create_node_civictheme_page__variations__banner_component_slider() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Banner Slider component, Slider slide - Light - Image - Left',
      'banner_components' => [
        [
          'type' => 'slider',
          'slides' => [
            [
              'type' => 'civictheme_slider_slide',
              'title' => $helper::staticSentence(2),
              'theme' => $helper::civicthemeThemeLight(),
              'image' => $helper->staticMediaItem('civictheme_image')->id(),
              'image_position' => $helper::civicthemeSliderSlideImagePositionLeft(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Slider component, Slider slide - Dark - Image - Right',
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
    ],
    [
      'title' => 'Demo Page, Banner Slider component, Slider slide - Dark - Image - Right - Content',
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
              'content' => $helper::staticRichText(3),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Slider component, Slider slide - Dark - Image - Right - Topic',
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
              'topic' => $helper::randomRealTerm('civictheme_topics')->id(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Slider component, Slider slide - reference - Page',
      'banner_components' => [
        [
          'type' => 'slider',
          'slides' => [
           [
             'type' => 'civictheme_slider_slide_ref',
             'reference' => $helper::randomRealNode($helper::civicthemePageContentType())->id(),
           ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Slider component, Slider slide - reference - Event',
      'banner_components' => [
        [
          'type' => 'slider',
          'slides' => [
            [
              'type' => 'civictheme_slider_slide_ref',
              'reference' => $helper::randomRealNode($helper::civicthemeEventContentType())->id(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Slider component, 3 on page, Title, Link',
      'banner_components' => [
        [
          'type' => 'slider',
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
          'slides' => [
            [
              'type' => 'civictheme_slider_slide_ref',
              'reference' => $helper::randomRealNode($helper::civicthemeEventContentType())->id(),
            ],
          ],
        ],
        [
          'type' => 'slider',
          'slides' => [
            [
              'type' => 'civictheme_slider_slide',
              'title' => $helper::staticSentence(2),
              'theme' => $helper::civicthemeThemeDark(),
              'image' => $helper->staticMediaItem('civictheme_image')->id(),
              'image_position' => $helper::civicthemeSliderSlideImagePositionRight(),
              'topic' => $helper::randomRealTerm('civictheme_topics')->id(),
            ],
          ],
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticSentence(2),
          ],
        ],
        [
          'type' => 'slider',
          'slides' => [
            [
              'type' => 'civictheme_slider_slide',
              'title' => $helper::staticSentence(2),
              'theme' => $helper::civicthemeThemeDark(),
              'image' => $helper->staticMediaItem('civictheme_image')->id(),
              'image_position' => $helper::civicthemeSliderSlideImagePositionRight(),
              'content' => $helper::staticRichText(3),
            ],
          ],
          'title' => $helper::staticSentence(3),
          'link' => [
            'uri' => $helper::randomUrl(),
          ],
        ],
      ],
    ],

    [
      'title' => 'Demo Page, Banner Slider component, Dark, Slider slide - Dark - Image - Right - Content',
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
              'content' => $helper::staticRichText(3),
            ],
          ],
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Slider component, Light, BG, Slider slide - reference - Event',
      'banner_components' => [
        [
          'type' => 'slider',
          'slides' => [
            [
              'type' => 'civictheme_slider_slide_ref',
              'reference' => $helper::randomRealNode($helper::civicthemeEventContentType())->id(),
            ],
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Banner Slider component, Dark, BG, Slider slide - Dark - Image - Right - Topic',
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
              'topic' => $helper::randomRealTerm('civictheme_topics')->id(),
            ],
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
  ];
}
