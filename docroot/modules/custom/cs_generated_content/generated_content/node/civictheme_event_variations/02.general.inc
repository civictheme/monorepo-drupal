<?php

/**
 * @file
 * Variations for CivicTheme Event nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Event, General.
 */
function _cs_generated_content_create_node_civictheme_event__variations__general() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [
    [
      'title' => 'Default values',
    ],
    [
      'title' => 'Summary',
      'summary' => $helper::staticPlainParagraph(),
    ],
    [
      'title' => 'Summary, Body',
      'summary' => $helper::staticPlainParagraph(),
      'body' => $helper::staticRichText(3),
    ],
    [
      'title' => 'Summary, Both spaces, Body',
      'summary' => $helper::staticPlainParagraph(),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichText(3),
    ],
    [
      'title' => 'Summary, Topics (1), Both spaces, Body',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichText(3),
    ],
    [
      'title' => 'Summary, Topics (3), Both spaces, Body',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 3),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichText(3),
    ],
    [
      'title' => 'Summary, Topics (1), Thumbnail, Both spaces, Body',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichText(3),
    ],
    [
      'title' => 'Summary, Topics (1), Thumbnail, Both spaces, Body, Event Date',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichText(3),
      'date' => [
        'value' => '2022-07-01T12:12:12',
        'end_value' => '2022-07-01T12:12:12',
      ],
    ],
    [
      'title' => 'Summary, Topics (1), Thumbnail, Both spaces, Body, Event Date Range',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichText(3),
      'date' => [
        'value' => '2022-07-01T12:12:12',
        'end_value' => '2023-07-01T12:12:12',
      ],
    ],
    [
      'title' => 'Summary, Topics (1), Thumbnail, Both spaces, Body, Show Last Updated Date, Not Custom Date',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'show_last_updated' => TRUE,
      'custom_updated_date' => '2022-07-01',
      'body' => $helper::staticRichText(3),
    ],
    [
      'title' => 'Summary, Topics (1), Thumbnail, Both spaces, Body, Show Last Updated Date',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'show_last_updated' => TRUE,
      'body' => $helper::staticRichText(3),
    ],
    [
      'title' => 'Summary, Topics (1), Thumbnail, Both spaces, Body, Uncheck Last Updated Date, Shows Custom Date',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'show_last_updated' => FALSE,
      'custom_updated_date' => '2022-07-01',
      'body' => $helper::staticRichText(3),
    ],
  ];

  return $variations;
}
