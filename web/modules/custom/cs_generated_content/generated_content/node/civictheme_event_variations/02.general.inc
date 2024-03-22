<?php

/**
 * @file
 * Variations for CivicTheme Event nodes.
 */

declare(strict_types=1);

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Event, General.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _cs_generated_content_create_node_civictheme_event__variations__general(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Event. Default values',
    ],
    [
      'title' => 'Event. Summary',
      'summary' => $helper::staticPlainParagraph(),
    ],
    [
      'title' => 'Event. Summary, Body',
      'summary' => $helper::staticPlainParagraph(),
      'body' => $helper::staticRichTextFieldValue(3),
    ],
    [
      'title' => 'Event. Summary, Both spaces, Body',
      'summary' => $helper::staticPlainParagraph(),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichTextFieldValue(3),
    ],
    [
      'title' => 'Event. Summary, Topics (1), Both spaces, Body',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichTextFieldValue(3),
    ],
    [
      'title' => 'Event. Summary, Topics (3), Both spaces, Body',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 3),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichTextFieldValue(3),
    ],
    [
      'title' => 'Event. Summary, Topics (1), Thumbnail, Both spaces, Body',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichTextFieldValue(3),
    ],
    [
      'title' => 'Event. Summary, Topics (1), Thumbnail, Both spaces, Body, Site section',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichTextFieldValue(3),
      'site_section' => $helper::staticTerms('civictheme_site_sections', 1),
    ],
    [
      'title' => 'Event. Summary, Topics (1), Thumbnail, Both spaces, Body, Event Date Range',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'body' => $helper::staticRichTextFieldValue(3),
      'date_range' => [
        'value' => $helper::dateToUtc('2022-07-01T09:45:00'),
        'end_value' => $helper::dateToUtc('2022-08-14T15:30:00'),
      ],
    ],
    [
      'title' => 'Event. Summary, Topics (1), Thumbnail, Both spaces, Body, Show Last Updated Date, Not Custom Date',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'show_last_updated' => TRUE,
      'custom_updated_date' => '2022-07-01',
      'body' => $helper::staticRichTextFieldValue(3),
    ],
    [
      'title' => 'Event. Summary, Topics (1), Thumbnail, Both spaces, Body, Show Last Updated Date',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'show_last_updated' => TRUE,
      'body' => $helper::staticRichTextFieldValue(3),
    ],
    [
      'title' => 'Event. Summary, Topics (1), Thumbnail, Both spaces, Body, Uncheck Last Updated Date, Shows Custom Date',
      'summary' => $helper::staticPlainParagraph(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'show_last_updated' => FALSE,
      'custom_updated_date' => '2022-07-01',
      'body' => $helper::staticRichTextFieldValue(3),
    ],
  ];
}
