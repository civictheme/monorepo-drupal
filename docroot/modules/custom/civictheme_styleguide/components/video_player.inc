<?php

/**
 * @file
 * Video Player component styleguide integration.
 */

use Drupal\civictheme_styleguide\Styleguide;
use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Implements _civictheme_styleguide_component_NAME().
 */
function _civictheme_styleguide_component_video_player() {
  $items = [];

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();
  $variations = [
    [
      'title' => 'Video Player component, without Transcript',
      'components' => [
        [
          'type' => 'civictheme_video',
          'name' => $helper::staticName(6),
          'transcript_link' => $helper::randomUrl(),
          'video_file' => $helper::staticFile('mp4'),
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
