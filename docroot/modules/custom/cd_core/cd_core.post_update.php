<?php

/**
 * @file
 * Post update hooks for core.
 */

use Drupal\block\BlockInterface;
use Drupal\cd_core\Helper;
use Drupal\cd_core\PostConfigImportUpdateHelper;
use Drupal\Core\Config\Entity\ConfigEntityUpdater;
use Drupal\Core\Utility\UpdateException;
use Drupal\user\Entity\User;

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

/**
 * Provisions links in Primary and Secondary navigation menus.
 */
function cd_core_post_update_provision_primary_secondary_navigation_links() {
  $added = PostConfigImportUpdateHelper::registerPostConfigImportUpdate();
  if ($added) {
    return TRUE;
  }

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
function cd_core_post_update_provision_footer_links($sandbox) {
  $added = PostConfigImportUpdateHelper::registerPostConfigImportUpdate();
  if ($added) {
    return TRUE;
  }

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

/**
 * Creates administrator users.
 */
function cd_core_post_update_provision_users() {
  $emails = [
    'alan@salsadigital.com.au',
    'akhil.bhandari@salsadigital.com.au',
    'alex.skrypnyk@salsadigital.com.au',
    'chris.darke@salsadigital.com.au',
    'danielle.sheffler@salsadigital.com.au',
    'govind@salsadigital.com.au',
    'kate.swayne@salsadigital.com.au',
    'lokender.singh@salsadigital.com.au',
    'richard.gaunt@salsadigital.com.au',
    'satyajit.das@salsadigital.com.au',
    'arpita.jain@salsadigital.com.au',
    'john.cloys@salsadigital.com.au',
  ];

  foreach ($emails as $email) {
    $user = User::create();
    $user->setUsername($email);
    $user->setEmail($email);
    $user->addRole('civic_site_administrator');
    $user->activate();
    $user->enforceIsNew();
    $user->save();
  }
}
