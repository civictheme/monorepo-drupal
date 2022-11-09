<?php

/**
 * @file
 * Post update hooks for CivicTheme Default Higher education Content.
 */

use Drupal\block\BlockInterface;
use Drupal\civictheme_content\Helper;
use Drupal\Core\Config\Entity\ConfigEntityUpdater;
use Drupal\Core\Utility\UpdateException;

/**
 * Sets homepage.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_content_highereducation_post_update_set_homepage() {
  try {
    Helper::setHomepageFromNode('My University. Helping you make a difference');
  }
  catch (\Exception $e) {
    throw new UpdateException($e->getMessage());
  }
}

/**
 * Provisions links in footer and updates menu block configurations.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_content_highereducation_post_update_provision_footer_links() {
  $theme = \Drupal::theme()->getActiveTheme()->getName();

  $map = [
    $theme . '_footer_menu_1' => Helper::findMenuItemByTitle('civictheme-footer', 'My University'),
    $theme . '_footer_menu_2' => Helper::findMenuItemByTitle('civictheme-footer', 'Courses'),
    $theme . '_footer_menu_3' => Helper::findMenuItemByTitle('civictheme-footer', 'Help'),
    $theme . '_footer_menu_4' => Helper::findMenuItemByTitle('civictheme-footer', 'Resources'),
  ];

  $sandbox = [];
  \Drupal::classResolver(ConfigEntityUpdater::class)
    ->update($sandbox, 'block', function (BlockInterface $block) use ($map) {
      if (strpos($block->getPluginId(), 'menu_block:') === 0) {
        if (!empty($map[$block->id()])) {
          $block_settings = $block->get('settings');
          $block_settings['label_display'] = TRUE;
          $block_settings['label_type'] = 'fixed';
          $block_settings['parent'] = 'civictheme-footer:menu_link_content:' . $map[$block->id()]->uuid();
          $block->set('settings', $block_settings);
          return TRUE;
        }
      }
      return FALSE;
    });
}
