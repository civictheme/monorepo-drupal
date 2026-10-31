<?php

/**
 * @file
 * Variations for CivicTheme Event nodes.
 */

declare(strict_types=1);

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Event, Location - Map.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _cs_generated_content_create_node_civictheme_event__variations__component_map(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Event. Component. Map',
      'summary' => $helper::staticSentence(8),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'body' => $helper::staticRichTextFieldValue(3),
      'location' => [
        [
          'type' => 'map',
          'embed_url' => $helper::staticUrl(),
          'theme' => $helper::civicthemeThemeLight(),
        ],
        [
          'type' => 'map',
          'embed_url' => $helper::staticUrl(),
          'theme' => $helper::civicthemeThemeDark(),
        ],

        [
          'type' => 'map',
          'embed_url' => $helper::staticUrl(),
          'address' => $helper::staticSentence(3),
          'view_link' => $helper::staticLinkFieldValue(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
        [
          'type' => 'map',
          'embed_url' => $helper::staticUrl(),
          'address' => $helper::staticSentence(3),
          'view_link' => $helper::staticLinkFieldValue(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],

        [
          'type' => 'map',
          'embed_url' => $helper::staticUrl(),
          'address' => $helper::staticSentence(3),
          'view_link' => $helper::staticLinkFieldValue(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
        [
          'type' => 'map',
          'embed_url' => $helper::staticUrl(),
          'address' => $helper::staticSentence(3),
          'view_link' => $helper::staticLinkFieldValue(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
  ];
}
