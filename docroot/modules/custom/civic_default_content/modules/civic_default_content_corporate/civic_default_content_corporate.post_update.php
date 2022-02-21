<?php

/**
 * @file
 * Post update hooks for Civic Default Corporate Content.
 */

use Drupal\civic_default_content\Helper;
use Drupal\Core\Utility\UpdateException;

/**
 * Sets homepage.
 */
function civic_default_content_corporate_post_update_set_homepage() {
  try {
    Helper::setHomepageFromNode('Protecting the environment');
  }
  catch (\Exception $e) {
    throw new UpdateException($e->getMessage());
  }
}
