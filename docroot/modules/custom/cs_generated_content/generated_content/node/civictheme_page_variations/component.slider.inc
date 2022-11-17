<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Slider.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_slider() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Slider component, Slider slide - Light - Image - Left',
      'components' => [
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
      'title' => 'Slider component, Slider slide - Dark - Image - Right',
      'components' => [
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
      'title' => 'Slider component, Slider slide - Dark - Image - Right - Content',
      'components' => [
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
      'title' => 'Slider component, Slider slide - Dark - Image - Right - Topic',
      'components' => [
        [
          'type' => 'slider',
          'slides' => [
            [
              'type' => 'civictheme_slider_slide',
              'title' => $helper::staticSentence(2),
              'theme' => $helper::civicthemeThemeDark(),
              'image' => $helper->staticMediaItem('civictheme_image')->id(),
              'image_position' => $helper::civicthemeSliderSlideImagePositionRight(),
              'topic' => $helper::staticTerm('civictheme_topics')->id(),
            ],
          ],
        ],
      ],
    ],
    [
      'title' => 'Slider component, Dark, Slider slide - Dark - Image - Right - Content',
      'components' => [
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
      'title' => 'Slider component, Dark, BG, Slider slide - Dark - Image - Right - Topic',
      'components' => [
        [
          'type' => 'slider',
          'slides' => [
            [
              'type' => 'civictheme_slider_slide',
              'title' => $helper::staticSentence(2),
              'theme' => $helper::civicthemeThemeDark(),
              'image' => $helper->staticMediaItem('civictheme_image')->id(),
              'image_position' => $helper::civicthemeSliderSlideImagePositionRight(),
              'topic' => $helper::staticTerm('civictheme_topics')->id(),
            ],
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
  ];
}
