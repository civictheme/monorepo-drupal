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

  $page = [
    'components' => [
      [
        'type' => 'slider',
        'title' => 'Light, 1 slide ' . $helper::staticSentence(2),
        'theme' => $helper::civicthemeThemeLight(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 1, Light, image left, ' . $helper::staticSentence(2),
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
        ],
      ],

      [
        'type' => 'slider',
        'title' => 'Light, 1 reference slide,  ' . $helper::staticSentence(2),
        'theme' => $helper::civicthemeThemeLight(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide_ref',
            'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
            'theme' => $helper::civicthemeThemeLight(),
            'image_position' => $helper::civicthemeImagePositionRight(),
            'link_text' => 'Reference slide, Image on right, ' . $helper::staticSentence(2),
          ],
        ],
      ],
      [
        'type' => 'slider',
        'title' => 'Light, 1 reference slide, no button, ' . $helper::staticSentence(2),
        'theme' => $helper::civicthemeThemeLight(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide_ref',
            'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
            'theme' => $helper::civicthemeThemeLight(),
            'image_position' => $helper::civicthemeImagePositionRight(),
          ],
        ],
      ],

      [
        'type' => 'slider',
        'title' => 'Dark, 1 reference slide,  ' . $helper::staticSentence(2),
        'theme' => $helper::civicthemeThemeDark(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide_ref',
            'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
            'theme' => $helper::civicthemeThemeDark(),
            'image_position' => $helper::civicthemeImagePositionRight(),
            'link_text' => 'Reference slide, Image on right, ' . $helper::staticSentence(2),
          ],
        ],
      ],
      [
        'type' => 'slider',
        'title' => 'Dark, 1 reference slide, no button, ' . $helper::staticSentence(2),
        'theme' => $helper::civicthemeThemeDark(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide_ref',
            'reference' => $helper::staticNode($helper::civicthemePageContentType())->id(),
            'theme' => $helper::civicthemeThemeDark(),
            'image_position' => $helper::civicthemeImagePositionRight(),
          ],
        ],
      ],

      [
        'type' => 'slider',
        'title' => 'Light, 3 slides ' . $helper::staticSentence(2),
        'theme' => $helper::civicthemeThemeLight(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 1, Light, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 2, Light, image right, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionRight(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 3, Light, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
        ],
      ],

      [
        'type' => 'slider',
        'title' => 'Light, 3 slides, BG, ' . $helper::staticSentence(2),
        'background' => TRUE,
        'theme' => $helper::civicthemeThemeLight(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 1, Light, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 2, Light, image right, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionRight(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 3, Light, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
        ],
      ],
      [
        'type' => 'slider',
        'title' => 'Dark, 3 slides, BG, ' . $helper::staticSentence(2),
        'background' => TRUE,
        'theme' => $helper::civicthemeThemeDark(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 1, Dark, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeDark(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 2, Dark, image right, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeDark(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionRight(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 3, Dark, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeDark(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
        ],
      ],

      [
        'type' => 'slider',
        'title' => 'Light, 3 slides, Vertical spacing, ' . $helper::staticSentence(2),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'theme' => $helper::civicthemeThemeLight(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 1, Light, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 2, Light, image right, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionRight(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 3, Light, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
        ],
      ],
      [
        'type' => 'slider',
        'title' => 'Dark, 3 slides, Vertical spacing, ' . $helper::staticSentence(2),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'theme' => $helper::civicthemeThemeDark(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 1, Dark, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeDark(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 2, Dark, image right, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeDark(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionRight(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 3, Dark, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeDark(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
        ],
      ],

      [
        'type' => 'slider',
        'title' => 'Light, 3 slides, BG, Vertical spacing, ' . $helper::staticSentence(2),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'background' => TRUE,
        'theme' => $helper::civicthemeThemeLight(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 1, Light, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 2, Light, image right, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionRight(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 3, Light, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeLight(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
        ],
      ],
      [
        'type' => 'slider',
        'title' => 'Dark, 3 slides, BG, Vertical spacing, ' . $helper::staticSentence(2),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'background' => TRUE,
        'theme' => $helper::civicthemeThemeDark(),
        'slides' => [
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 1, Dark, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeDark(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 2, Dark, image right, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeDark(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionRight(),
          ],
          [
            'type' => 'civictheme_slider_slide',
            'title' => 'Slide 3, Dark, image left, ' . $helper::staticSentence(2),
            'date' => '2022-12-22',
            'content' => $helper::staticSentenceFieldValue(),
            'topics' => $helper::staticTerms('civictheme_topics', 2),
            'links' => [
              $helper::staticLinkFieldValue(),
              $helper::staticLinkFieldValue(),
            ],
            'theme' => $helper::civicthemeThemeDark(),
            'image' => $helper->staticMediaItem('civictheme_image')->id(),
            'image_position' => $helper::civicthemeImagePositionLeft(),
          ],
        ],
      ],
    ],
  ];

  return [
    $page + ['title' => 'Page. Component. Slider, sidebar'],
    $page + [
      'title' => 'Page. Component. Slider, no sidebar',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
    ],
  ];
}
