<?php

/**
 * @file
 * Figure component styleguide integration.
 */

use Drupal\civictheme_styleguide\Styleguide;
use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Implements _civictheme_styleguide_component_NAME().
 */
function _civictheme_styleguide_component_figure() {
  $items = [];

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [
    [
      'title' => 'Figure component',
      'components' => [
        [
          'type' => 'civictheme_image',
          'name' => $helper::staticName(6),
          'caption' => $helper::staticSentence(20),
          'image' => $helper::createFile('jpg'),
        ],
      ],
    ],
    [
      'title' => 'Figure component, without Caption',
      'components' => [
        [
          'type' => 'civictheme_image',
          'name' => $helper::staticName(8),
          'image' => $helper::createFile('png'),
        ],
      ],
    ],
  ];

  foreach ($variations as $variation) {
    $entity = Styleguide::entityFromOptions('media', $variation['components'][0]['type'], $variation['components'][0]);

    $items[] = [
      'title' => $variation['title'],
      'content' => Styleguide::renderEntity('media', $entity, TRUE),
      'options' => [
        'edge-to-edge' => FALSE,
      ],
    ];
  }

  return $items;
}
