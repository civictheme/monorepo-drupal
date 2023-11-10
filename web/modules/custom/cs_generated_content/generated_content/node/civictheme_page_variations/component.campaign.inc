<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Campaign.
 *
 * @return array<int, array<string, mixed>>
 *   Variations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_campaign(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $page = [
    'components' => [
      [
        'type' => 'campaign',
        'title' => 'Light, Image left, ' . $helper::staticSentence(3),
        'image' => $helper::staticMediaItem('civictheme_image'),
        'image_position' => $helper::civicthemeImagePositionLeft(),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'campaign',
        'title' => 'Light, Image right, ' . $helper::staticSentence(3),
        'image' => $helper::staticMediaItem('civictheme_image'),
        'image_position' => $helper::civicthemeImagePositionRight(),
        'theme' => $helper::civicthemeThemeLight(),
      ],

      [
        'type' => 'campaign',
        'title' => 'Light, Image left, Date, Content, 2 links, 2 Topics, ' . $helper::staticSentence(3),
        'image' => $helper::staticMediaItem('civictheme_image'),
        'image_position' => $helper::civicthemeImagePositionLeft(),
        'date' => '2022-12-22',
        'content' => $helper::staticSentence(8),
        'links' => [
          $helper::staticLinkFieldValue(),
          $helper::staticLinkFieldValue(),
        ],
        'topics' => $helper::staticTerms('civictheme_topics', 2),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'campaign',
        'title' => 'Dark, Image left, Date, Content, 2 links, 2 Topics, ' . $helper::staticSentence(3),
        'image' => $helper::staticMediaItem('civictheme_image'),
        'image_position' => $helper::civicthemeImagePositionLeft(),
        'date' => '2022-12-22',
        'content' => $helper::staticSentence(8),
        'links' => [
          $helper::staticLinkFieldValue(),
          $helper::staticLinkFieldValue(),
        ],
        'topics' => $helper::staticTerms('civictheme_topics', 2),
        'theme' => $helper::civicthemeThemeDark(),
      ],

      [
        'type' => 'campaign',
        'title' => 'Light, Image left, Date, Content, 2 links, 2 Topics, Vertical spacing, ' . $helper::staticSentence(3),
        'image' => $helper::staticMediaItem('civictheme_image'),
        'image_position' => $helper::civicthemeImagePositionLeft(),
        'date' => '2022-12-22',
        'content' => $helper::staticSentence(8),
        'links' => [
          $helper::staticLinkFieldValue(),
          $helper::staticLinkFieldValue(),
        ],
        'topics' => $helper::staticTerms('civictheme_topics', 2),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'theme' => $helper::civicthemeThemeLight(),
      ],
      [
        'type' => 'campaign',
        'title' => 'Dark, Image left, Date, Content, 2 links, 2 Topics, Vertical spacing, ' . $helper::staticSentence(3),
        'image' => $helper::staticMediaItem('civictheme_image'),
        'image_position' => $helper::civicthemeImagePositionLeft(),
        'date' => '2022-12-22',
        'content' => $helper::staticSentence(8),
        'links' => [
          $helper::staticLinkFieldValue(),
          $helper::staticLinkFieldValue(),
        ],
        'topics' => $helper::staticTerms('civictheme_topics', 2),
        'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
        'theme' => $helper::civicthemeThemeDark(),
      ],
    ],
  ];

  return [
    $page + ['title' => 'Page. Component. Campaign, sidebar'],
    $page + [
      'title' => 'Page. Component. Campaign, no sidebar',
      'alias' => '/generated-content/civictheme_page/civictheme-no-sidebar/{title}',
    ],
  ];
}
