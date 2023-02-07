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

  $page = [
    'components' => [
      [
        'type' => 'attachment',
        'title' => '1 attachment, ' . $helper::staticSentence(3),
        'content' => $helper::staticSentence(8),
        'attachments' => $helper->staticMediaItem('civictheme_document'),
      ],
      [
        'type' => 'attachment',
        'title' => '3 attachments,' . $helper::staticSentence(3),
        'content' => $helper::staticSentence(8),
        'attachments' => [
          $helper->staticMediaItem('civictheme_document'),
          $helper->staticMediaItem('civictheme_document'),
          $helper->staticMediaItem('civictheme_document'),
        ],
      ],

      [
        'type' => 'attachment',
        'title' => 'Light, Vertical spacing, ' . $helper::staticSentence(3),
        'content' => $helper::staticSentence(8),
        'attachments' => $helper->staticMediaItem('civictheme_document'),
        'theme' => $helper::civicthemeThemeLight(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
      [
        'type' => 'attachment',
        'title' => 'Dark, Vertical spacing, ' . $helper::staticSentence(3),
        'content' => $helper::staticSentence(8),
        'attachments' => $helper->staticMediaItem('civictheme_document'),
        'theme' => $helper::civicthemeThemeDark(),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],

      [
        'type' => 'attachment',
        'title' => 'Light, BG, Vertical spacing, ' . $helper::staticSentence(3),
        'content' => $helper::staticSentence(8),
        'attachments' => $helper->staticMediaItem('civictheme_document'),
        'theme' => $helper::civicthemeThemeLight(),
        'background' => TRUE,
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
      [
        'type' => 'attachment',
        'title' => 'Dark, BG, Vertical spacing, ' . $helper::staticSentence(3),
        'content' => $helper::staticSentence(8),
        'attachments' => $helper->staticMediaItem('civictheme_document'),
        'theme' => $helper::civicthemeThemeDark(),
        'background' => TRUE,
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      ],
    ],
  ];

  return [
    $page + ['title' => 'Page. Component. Attachment, sidebar'],
    $page + [
      'title' => 'Page. Component. Attachment, no sidebar',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
    ],
  ];
}
