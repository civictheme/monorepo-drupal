<?php

/**
 * @file
 * Post update hooks for core.
 */

use Drupal\block\Entity\Block;
use Drupal\Core\Utility\UpdateException;
use Drupal\redirect\Entity\Redirect;
use Drupal\user\Entity\User;

/**
 * Creates administrator users.
 */
function civictheme_dev_post_update_provision_users() {
  $emails = [
    'akhil.bhandari@salsadigital.com.au',
    'alan.rako@salsadigital.com.au',
    'alan@salsadigital.com.au',
    'alex.skrypnyk@salsadigital.com.au',
    'deepali.shelar@salsadigital.com.au',
    'govind@salsadigital.com.au',
    'john.cloys@salsadigital.com.au',
    'joshua.fernandes@salsadigital.com.au',
    'nathania.sudirman@salsadigital.com.au',
    'nathania@salsadigital.com.au',
    'nick.georgiou@salsadigital.com.au',
    'nicolas.haase@salsadigital.com.au',
    'phillipa.martin@salsadigital.com.au',
    'richard.gaunt@salsadigital.com.au',
    'sonam.chaturvedi@salsadigital.com.au',
  ];

  foreach ($emails as $email) {
    $user = User::create();
    $user->setUsername($email);
    $user->setEmail($email);
    $user->addRole('civictheme_site_administrator');
    $user->activate();
    $user->enforceIsNew();
    $user->save();
  }
}

/**
 * Creates storybook redirects.
 */
function civictheme_dev_post_update_provision_storybook_redirects() {
  $map = [
    [
      'src' => '/storybook',
      'dst' => '/themes/contrib/civictheme/civictheme_library/storybook-static/index.html',
    ],
    [
      'src' => '/storybook-drupal',
      'dst' => '/themes/contrib/civictheme/storybook-static/index.html',
    ], [
      'src' => '/storybook-drupal-demo',
      'dst' => '/themes/custom/civictheme_demo/storybook-static/index.html',
    ],
  ];

  foreach ($map as $item) {
    $redirect = Redirect::create();
    $redirect->setSource($item['src']);
    $redirect->setRedirect($item['dst']);
    $redirect->setStatusCode(301);
    $redirect->save();
  }
}

/**
 * Updates Side Navigation block visibility settings.
 */
function civictheme_dev_post_update_update_side_navigation_block() {
  $entity_type_manager = \Drupal::entityTypeManager();
  $blocks = $entity_type_manager->getStorage('block')->loadByProperties([
    'region' => 'sidebar',
  ]);

  if (empty($blocks)) {
    throw new UpdateException('Unable to find Side Navigation block.');
  }

  $block = reset($blocks);

  $block->setVisibilityConfig('request_path', [
    'id' => 'request_path',
    'negate' => TRUE,
    'pages' => "/civictheme-no-sidebar/*\n\r/admin/appearance/styleguide\n\r/admin/appearance/styleguide/*",
  ]);
  $block->save();
}

/**
 * Updates Testmode module settings.
 */
function civictheme_dev_post_update_update_testmode_settings() {
  $config = \Drupal::service('config.factory')->getEditable('testmode.settings');
  $views_list = $config->get('views_node', []);
  $views_list[] = 'civictheme_automated_list_examples';
  $views_list[] = 'civictheme_automated_list_test';
  $config->set('views_node', $views_list)->save();
}

/**
 * Updates Simple Sitemap configuration to include nodes and views.
 */
function civictheme_dev_post_update_update_simplesitemap() {
  if (!\Drupal::moduleHandler()->moduleExists('simple_sitemap')) {
    \Drupal::service('module_installer')->install(['simple_sitemap']);
  }

  $settings = [
    'index' => TRUE,
    'priority' => 0.5,
    'changefreq' => 'hourly',
    'include_images' => FALSE,
  ];

  $bundles = ['civictheme_page', 'civictheme_event'];
  foreach ($bundles as $bundle) {
    \Drupal::service('simple_sitemap.generator')->entityManager()->setBundleSettings('node', $bundle, $settings);
  }

  \Drupal::service('simple_sitemap.generator')->customLinkManager()->add('/', $settings);

  /** @var \Drupal\simple_sitemap\Entity\SimpleSitemapTypeStorage $type_storage */
  $type_storage = \Drupal::entityTypeManager()->getStorage('simple_sitemap_type');
  $type = $type_storage->load('default_hreflang');

  if ($type) {
    $type->url_generators = [
      'custom',
      'entity',
      'entity_menu_link_content',
      'arbitrary',
      'views',
    ];

    $type->save();
  }
}

/**
 * Places Listing example view blocks the current theme's regions.
 */
function civictheme_dev_post_update_place_listing_example_blocks_into_regions() {
  $theme_name = \Drupal::configFactory()->get('system.theme')->get('default');
  if ($theme_name == 'civictheme') {
    return 'Skipping update for the CivicTheme as blocks already exist.';
  }

  $block_ids = [
    'civictheme_automated_list_examples_page_one_filter_single_select_exp',
    'civictheme_automated_list_examples_page_multiple_filters_exp',
    'civictheme_automated_list_examples_page_one_filter_multi_select_exp',
  ];

  foreach ($block_ids as $block_id) {
    $parent_block = Block::load($block_id);
    $new_id = str_replace('civictheme', $theme_name, $parent_block->get('id'));
    $child_block = $parent_block->createDuplicateBlock($new_id, $theme_name);
    $child_block->save();
  }
}
