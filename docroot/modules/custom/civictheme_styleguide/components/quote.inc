<?php

/**
 * @file
 * CivicTheme Quote component styleguide integration.
 */

use Drupal\civictheme_styleguide\Styleguide;
use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Slider component styleguide.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civictheme_styleguide_component_quote() {
  $items = [];

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [
    [
      'title' => 'Quote component',
      'components' => [
        [
          'type' => 'civictheme_quote',
          'content' => [
            "value"  => $helper::staticHtmlParagraph(),
            "format" => "civictheme_rich_text",
          ],
          'author' => $helper::staticName(3),
        ],
      ],
    ],
    [
      'title' => 'Quote component, Light',
      'components' => [
        [
          'type' => 'civictheme_quote',
          'content' => [
            "value"  => $helper::staticHtmlParagraph(),
            "format" => "civictheme_rich_text",
          ],
          'author' => $helper::staticName(),
          'theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Quote component, Dark',
      'components' => [
        [
          'type' => 'civictheme_quote',
          'content' => [
            "value"  => $helper::staticHtmlParagraph(),
            "format" => "civictheme_rich_text",
          ],
          'author' => $helper::staticName(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Quote component, Light, No Author, Both spaces',
      'components' => [
        [
          'type' => 'civictheme_quote',
          'content' => [
            "value"  => $helper::staticHtmlParagraph(),
            "format" => "civictheme_rich_text",
          ],
          'theme' => $helper::civicthemeThemeLight(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Quote component, Dark, No Author, Both spaces',
      'components' => [
        [
          'type' => 'civictheme_quote',
          'content' => [
            "value"  => $helper::staticHtmlParagraph(),
            "format" => "civictheme_rich_text",
          ],
          'theme' => $helper::civicthemeThemeDark(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Quote component, Light, Both spaces',
      'components' => [
        [
          'type' => 'civictheme_quote',
          'content' => [
            "value"  => $helper::staticHtmlParagraph(),
            "format" => "civictheme_rich_text",
          ],
          'author' => $helper::staticName(),
          'theme' => $helper::civicthemeThemeLight(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Quote component, Dark, Both spaces',
      'components' => [
        [
          'type' => 'civictheme_quote',
          'content' => [
            "value"  => $helper::staticHtmlParagraph(),
            "format" => "civictheme_rich_text",
          ],
          'author' => $helper::staticName(),
          'theme' => $helper::civicthemeThemeDark(),
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
  ];

  foreach ($variations as $variation) {
    $para = Styleguide::paragraphFromOptions($variation['components'][0]['type'], $variation['components'][0]);
    $items[] = [
      'title' => $variation['title'],
      'content' => Styleguide::renderParagraph($para, TRUE),
      'options' => [
        'edge-to-edge' => FALSE,
      ],
    ];
  }

  return $items;
}
