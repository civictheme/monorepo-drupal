<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Attachment.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_attachment() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Content attachment',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachments(3)',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => [
            $helper->staticMediaItem('civictheme_document'),
            $helper->staticMediaItem('civictheme_document'),
            $helper->staticMediaItem('civictheme_document'),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, 3 on page',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
        ],
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
        ],
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, Image, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Light, BG, Image, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, BG, Image, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Light, No BG, Both spaces, Image, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, No BG, Both spaces, Image, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Light, BG, Both spaces, Image, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, BG, Both spaces, Image, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, Image',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Light, BG, Image',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, BG, Image',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Light, BG, Both spaces, Image',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, No BG, Both spaces, Image',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Light, BG, Both spaces, Image',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, BG, Both spaces, Image',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'image' => $helper->staticMediaItem('civictheme_image'),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Light, BG, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, BG, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Light, No BG, Both spaces, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, No BG, Both spaces, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Light, BG, Both spaces, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Content attachment, Dark, BG, Both spaces, Icon',
      'components' => [
        [
          'type' => 'attachment',
          'title' => $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'icon' => $helper->staticMediaItem('civictheme_icon'),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
  ];
}
