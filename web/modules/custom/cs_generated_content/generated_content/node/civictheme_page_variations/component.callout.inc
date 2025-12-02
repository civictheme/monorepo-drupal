<?php

declare(strict_types=1);

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Callout.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_callout(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Page. Component. Callout',
      'components' => [
        [
          'type' => 'callout',
          'title' => 'Light, 1 link, ' . $helper::staticSentence(3),
          'content' => $helper::staticSentence(8),
          'links' => [
            $helper::staticLinkFieldValue(),
          ],
        ],
        [
          'type' => 'callout',
          'title' => 'Light, 2 links, ' . $helper::staticSentence(3),
          'content' => $helper::staticSentence(8),
          'links' => [
            $helper::staticLinkFieldValue(),
            $helper::staticLinkFieldValue(),
          ],
        ],
        [
          'type' => 'callout',
          'title' => 'Dark, 1 link, ' . $helper::staticSentence(3),
          'content' => $helper::staticSentence(8),
          'links' => [
            $helper::staticLinkFieldValue(),
          ],
          'theme' => $helper::civicthemeThemeDark(),
        ],

        [
          'type' => 'callout',
          'title' => 'Light, 1 link, Vertical spacing' . $helper::staticSentence(3),
          'content' => $helper::staticSentence(8),
          'links' => [
            $helper::staticLinkFieldValue(),
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
        [
          'type' => 'callout',
          'title' => 'Dark, 1 link, Vertical spacing' . $helper::staticSentence(3),
          'content' => $helper::staticSentence(8),
          'links' => [
            $helper::staticLinkFieldValue(),
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        ],
      ],
    ],
  ];
}
