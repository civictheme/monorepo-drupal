<?php

/**
 * @file
 * Post update hooks for CivicTheme Higher Education Content.
 */

use Drupal\civictheme_content\Helper;
use Drupal\Core\Utility\UpdateException;

/**
 * Common updates.
 */
function civictheme_content_highereducation_post_update_common() {
  \Drupal::moduleHandler()->loadInclude('civictheme_content', 'install');
  civictheme_content_post_update_common();
}

/**
 * Sets homepage.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_content_highereducation_post_update_set_homepage() {
  try {
    Helper::setHomepageFromNode('Home');
  }
  catch (\Exception $e) {
    throw new UpdateException($e->getMessage());
  }
}
