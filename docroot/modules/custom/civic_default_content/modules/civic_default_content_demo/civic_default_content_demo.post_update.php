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
    Helper::setHomepageFromNode('Providing visually engaging digital experiences');
  }
  catch (\Exception $e) {
    throw new UpdateException($e->getMessage());
  }
}

/**
 * Provisions links in Primary and Secondary navigation menus.
 */
function civic_default_content_demo_post_update_provision_primary_secondary_navigation_links() {
  Helper::saveMenuTree('civic-primary-navigation', [
    'General' => [
      'link' => '/<front>',
      'children' => [
        'For individuals' => [
          'link' => '/node/36',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
        'For businesses' => [
          'link' => '/civic/pages',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
        'For government' => [
          'link' => '/node/31',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
        'News & events' => [
          'link' => '/node/9',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
      ],
    ],
    'About us' => [
      'link' => '/<front>',
      'children' => [
        'For individuals' => [
          'link' => '/node/36',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
        'For businesses' => [
          'link' => '/civic/pages',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
          ],
        ],
        'For government' => [
          'link' => '/node/31',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
            'Menu item on one or two lines item on one or two lines 5' => '/',
          ],
        ],
        'News & events' => [
          'link' => '/node/9',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
      ],
    ],
    'Help' => [
      'link' => '/<front>',
      'children' => [
        'For individuals' => [
          'link' => '/node/36',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
        'For businesses' => [
          'link' => '/civic/pages',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
        'For government' => [
          'link' => '/node/31',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
        'News & events' => [
          'link' => '/node/9',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
      ],
    ],
    'Services' => [
      'link' => '/<front>',
      'children' => [
        'For individuals' => [
          'link' => '/node/36',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
        'For businesses' => [
          'link' => '/civic/pages',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
        'For government' => [
          'link' => '/node/31',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
        'News & events' => [
          'link' => '/node/9',
          'children' => [
            'Menu item on one or two lines item on one or two lines 1' => '/',
            'Menu item on one or two lines item on one or two lines 2' => '/',
            'Menu item on one or two lines item on one or two lines 3' => '/',
            'Menu item on one or two lines item on one or two lines 4' => '/',
          ],
        ],
      ],
    ],
  ]);

  Helper::saveMenuTree('civic-secondary-navigation', [
    'General' => [
      'link' => '/<front>',
    ],
    'About us' => [
      'link' => '/<front>',
    ],
    'Help' => [
      'link' => '/<front>',
    ],
    'Services' => [
      'link' => '/<front>',
    ],
  ]);
}

/**
 * Provisions links in footer and updates menu block configurations.
 */
function civic_default_content_demo_post_update_provision_footer_links() {
  Helper::saveMenuTree('civic-footer', [
    'General' => [
      'link' => '/<front>',
      'children' => [
        'For individuals' => '/node/36',
        'For businesses' => '/civic/pages',
        'For government' => '/node/31',
        'News & events' => '/node/9',
      ],
    ],
    'About us' => [
      'link' => '/<front>',
      'children' => [
        'News & events' => '/node/9',
        'For individuals' => '/node/36',
        'For businesses' => '/civic/pages',
        'For government' => '/node/31',
      ],
    ],
    'Help' => [
      'link' => '/<front>',
      'children' => [
        'For government' => '/node/31',
        'For individuals' => '/node/36',
        'For businesses' => '/civic/pages',
        'News & events' => '/node/9',
      ],
    ],
    'Services' => [
      'link' => '/<front>',
      'children' => [
        'Services' => '/node/40',
        'For individuals' => '/node/36',
        'For businesses' => '/civic/pages',
        'For government' => '/node/31',
      ],
    ],
  ]);

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
