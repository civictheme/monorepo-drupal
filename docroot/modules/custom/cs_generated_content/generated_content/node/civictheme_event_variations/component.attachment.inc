<?php

/**
 * @file
 * Variations for CivicTheme Event nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Event, Attachments - Attachment.
 */
function _cs_generated_content_create_node_civictheme_event__variations__component_attachment() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Attachment',
      'summary' => $helper::staticSentence(8),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'body' => $helper::staticRichText(3),
      'attachments' => [
        [
          'type' => 'attachment',
          'title' => '1 attachment, ' . $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
        ],
        [
          'type' => 'attachment',
          'title' => '3 attachments,' . $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => [
            $helper->staticMediaItem('civictheme_document'),
            $helper->staticMediaItem('civictheme_document'),
            $helper->staticMediaItem('civictheme_document'),
          ],
        ],

        [
          'type' => 'attachment',
          'title' => 'Light, Vertical spacing, ' . $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
        [
          'type' => 'attachment',
          'title' => 'Dark, Vertical spacing, ' . $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],

        [
          'type' => 'attachment',
          'title' => 'Light, BG, Vertical spacing, ' . $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
        [
          'type' => 'attachment',
          'title' => 'Dark, BG, Vertical spacing, ' . $helper::staticSentence(3),
          'summary' => $helper::staticSentence(8),
          'attachments' => $helper->staticMediaItem('civictheme_document'),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
  ];
}
