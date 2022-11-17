<?php

/**
 * @file
 * Post update hooks for CivicTheme Default Corporate Content.
 */

use Drupal\civictheme_content\Helper;
use Drupal\Core\Utility\UpdateException;

/**
 * Common updates.
 */
function civictheme_content_corporate_post_update_common() {
  civictheme_content_post_update_common();
}

/**
 * Sets homepage.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_content_corporate_post_update_set_homepage() {
  try {
    Helper::setHomepageFromNode('Protecting the environment');
  }
  catch (\Exception $e) {
    throw new UpdateException($e->getMessage());
  }
}
