<?php

/**
 * @file
 * Post update hooks for core.
 */

use Drupal\cd_core\Helper;
use Drupal\cd_core\PostConfigImportUpdateHelper;
use Drupal\Core\Utility\UpdateException;

/**
 * Sets homepage.
 */
function cd_core_post_update_set_homepage() {
  $added = PostConfigImportUpdateHelper::registerPostConfigImportUpdate();
  if ($added) {
    return TRUE;
  }

  $node = Helper::loadNodeByTitle('Providing visually engaging digital experiences', 'civic_page');

  if (!$node) {
    throw new UpdateException('Unable to find homepage node.');
  }

  $config = \Drupal::service('config.factory')->getEditable('system.site');
  $config->set('page.front', '/node/' . $node->id())->save();
}
