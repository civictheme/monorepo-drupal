<?php

/**
 * @file
 * Post update hooks for CivicTheme Government Content.
 */

declare(strict_types=1);

use Drupal\civictheme_content\Helper;
use Drupal\Core\Utility\UpdateException;

/**
 * Common updates.
 */
function civictheme_content_government_post_update_common(): void {
  \Drupal::moduleHandler()->loadInclude('civictheme_content', 'install');
  civictheme_content_post_update_common();
}

/**
 * Sets homepage.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_content_government_post_update_set_homepage(): void {
  try {
    Helper::setHomepageFromNode('Home');
  }
  catch (\Exception $exception) {
    throw new UpdateException($exception->getMessage());
  }
}
