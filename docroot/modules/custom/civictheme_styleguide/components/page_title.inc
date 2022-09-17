<?php

/**
 * @file
 * Page title styleguide integration.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Implements _civictheme_styleguide_component_NAME().
 */
function _civictheme_styleguide_component_page_title() {
  $items = [];

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $variations = [
    [
      'title' => 'Page Title component',
      'components' => [
        [
          'type' => 'page_title_block',
          'title' => $helper::staticSentence(8),
        ],
      ],
    ],
  ];

  foreach ($variations as $variation) {
    $block_manager = \Drupal::service('plugin.manager.block');
    $plugin_block = $block_manager->createInstance($variation['components'][0]['type'], []);
    $plugin_block->setTitle($variation['components'][0]['title']);
    $render = $plugin_block->build();
    \Drupal::service('renderer')->addCacheableDependency($render, $plugin_block);

    $items[] = [
      'title' => $variation['title'],
      'content' => $render,
      'options' => [
        'edge-to-edge' => FALSE,
      ],
    ];
  }

  return $items;
}
