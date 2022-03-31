<?php

/**
 * @file
 * Post update hooks for Civic Default Demo Content.
 */

use Drupal\block\BlockInterface;
use Drupal\civic_default_content\Helper;
use Drupal\Core\Config\Entity\ConfigEntityUpdater;
use Drupal\Core\Utility\UpdateException;

/**
 * Sets homepage.
 */
function civic_default_content_demo_post_update_set_homepage() {
  try {
    Helper::setHomepageFromNode('Your department/agency tagline');
  }
  catch (\Exception $e) {
    throw new UpdateException($e->getMessage());
  }
}

/**
 * Provisions links in footer and updates menu block configurations.
 */
function civic_default_content_demo_post_update_provision_footer_links() {
  $theme = \Drupal::theme()->getActiveTheme()->getName();

  $map = [
    $theme . '_footer_menu_1' => Helper::findMenuItemByTitle('civic-footer', 'General'),
    $theme . '_footer_menu_2' => Helper::findMenuItemByTitle('civic-footer', 'About us'),
    $theme . '_footer_menu_3' => Helper::findMenuItemByTitle('civic-footer', 'Help'),
    $theme . '_footer_menu_4' => Helper::findMenuItemByTitle('civic-footer', 'Services'),
  ];

  $sandbox = [];
  \Drupal::classResolver(ConfigEntityUpdater::class)
    ->update($sandbox, 'block', function (BlockInterface $block) use ($map) {
      if (strpos($block->getPluginId(), 'menu_block:') === 0) {
        if (!empty($map[$block->id()])) {
          $block_settings = $block->get('settings');
          $block_settings['parent'] = 'civic-footer:menu_link_content:' . $map[$block->id()]->uuid();
          $block->set('settings', $block_settings);
          return TRUE;
        }
      }
      return FALSE;
    });
}
