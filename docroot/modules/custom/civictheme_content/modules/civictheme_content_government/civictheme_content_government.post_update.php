<?php

/**
 * @file
 * Post update hooks for CivicTheme Government Content.
 */

use Drupal\block\BlockInterface;
use Drupal\civictheme_content\Helper;
use Drupal\Core\Config\Entity\ConfigEntityUpdater;
use Drupal\Core\Utility\UpdateException;

/**
 * Common updates.
 */
function civictheme_content_government_post_update_common() {
  civictheme_content_post_update_common();
}

/**
 * Sets homepage.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_content_government_post_update_set_homepage() {
  try {
    Helper::setHomepageFromNode('Department of Citizens. Making Australia a safe place to live.');
  }
  catch (\Exception $e) {
    throw new UpdateException($e->getMessage());
  }
}
