<?php

/**
 * @file
 * Create demo page nodes.
 *
 * @see https://www.drupal.org/project/coder/issues/2877475
 * phpcs:disable DrupalPractice.CodeAnalysis.VariableAnalysis.UndefinedVariable
 */

use Drupal\cs_demo\CsDemoHelper;
use Drupal\cs_demo\CsDemoRandom;
use Drupal\cs_demo\CsDemoStaticContent;

require_once 'helper.inc';

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_demo_create_node_civictheme_page_weight() {
  return 35;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_demo_create_node_civictheme_page_tracking() {
  return TRUE;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE().
 */
function cs_demo_create_node_civictheme_page() {
  $total_nodes = 20;

  $nodes = [];

  /** @var Drupal\cs_demo\CsDemoHelper $helper */
  $helper = CsDemoHelper::getInstance();

  for ($i = 0; $i < $total_nodes; $i++) {
    $types = CsDemoHelper::randomFieldAllowedBundles('node', 'civictheme_page', 'field_c_n_components', 3);

    if (is_array($types) && count($types) == 3) {
      $types = array_combine(array_values($types), [
        rand(1, 2),
        rand(1, 3),
        rand(1, 3),
      ]);
    }
    else {
      $types = [
        'content' => rand(1, 2),
      ];
    }

    $variation = CsDemoHelper::variationRandom([
      'status' => NULL,
      'summary' => NULL,
      'thumbnail' => NULL,
      'space' => 'both',
      'toc' => NULL,
      'topics' => rand(0, 5),
      'banner_type' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeBannerTypes()),
      'banner_theme' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeThemes()),
      'banner_background' => NULL,
      'banner_featured_image' => NULL,
      'banner_components' => rand(0, 2),
      'banner_components_bottom' => rand(0, 2),
    ] + $types);

    $variation_info = CsDemoHelper::variationFormatInfo($variation);
    $title = sprintf('Demo Page %s %s (%s)', count($nodes) + 1, CsDemoRandom::sentence(1, 1), $variation_info);

    /** @var \Drupal\node\Entity\Node $node */
    $node = _cs_demo_create_node_from_variation('civictheme_page', $variation);

    _cs_demo_create_node_from_variation_process_civictheme_page($node, $variation);

    $node->setTitle($title);
    $node->revision_log = $variation_info;

    if (isset($node->path->pathauto)) {
      $node->path->pathauto = 0;
    }

    $node->save();
    $pathTitle = substr(strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title))),0,60);
    $pathTitle = filter_var($pathTitle, FILTER_SANITIZE_URL);
    $path_alias = \Drupal::entityTypeManager()->getStorage('path_alias')->create([
      'path' => "/node/" . $node->id(),
      'alias' => "/demo/" . $node->bundle() . "/" . $pathTitle,
    ]);
    $path_alias->save();

    $helper::log(
      'Created "%s" node "%s" (id: %s)',
      $node->bundle(),
      $node->toLink()->toString(),
      $node->id()
    );

    $nodes[] = $node;
  }

  return $nodes;
}

/**
 * Process node variation for Civic Page.
 */
function _cs_demo_create_node_from_variation_process_civictheme_page($node, $variation) {
  // General.
  if (!empty($variation['space']) && $node->hasField('field_c_n_space')) {
    $node->field_c_n_space = $variation['space'];
  }

  if (!empty($variation['summary']) && $node->hasField('field_c_n_summary')) {
    $node->field_c_n_summary = CsDemoStaticContent::sentence(15);
  }

  if (empty($variation['thumbnail']) && $node->hasField('field_c_n_thumbnail')) {
    $node->field_c_n_thumbnail = CsDemoHelper::randomImage();
  }

  if (!empty($variation['topics']) && $node->hasField('field_c_n_topics')) {
    $node->field_c_n_topics = CsDemoHelper::randomTopics($variation['topics']);
  }

  // Banner.
  if (!empty($variation['banner_type']) && $node->hasField('field_c_n_banner_type')) {
    $node->field_c_n_banner_type = $variation['banner_type'];
  }

  if (!empty($variation['banner_theme']) && $node->hasField('field_c_n_banner_theme')) {
    $node->field_c_n_banner_theme = $variation['banner_theme'];
  }

  if (!empty($variation['banner_background']) && $node->hasField('field_c_n_banner_background')) {
    $node->field_c_n_banner_background = CsDemoHelper::randomImage();
  }

  if (!empty($variation['banner_featured_image']) && $node->hasField('field_c_n_banner_featured_image')) {
    $node->field_c_n_banner_featured_image = CsDemoHelper::randomImage();
  }

  if (!empty($variation['banner_components']) && $node->hasField('field_c_n_banner_components')) {
    for ($i = 0; $i < $variation['banner_components']; $i++) {
      CsDemoHelper::civicthemeParagraphContentAttach($node, 'field_c_n_banner_components', [
        'content' => CsDemoStaticContent::richText(2, sprintf('Banner component %s ', $i + 1)),
        'theme' => $variation['banner_theme'] ?: CsDemoHelper::civicthemeThemeLight(),
      ]);
    }
  }

  if (!empty($variation['banner_components_bottom']) && $node->hasField('field_c_n_banner_components_bott')) {
    for ($i = 0; $i < $variation['banner_components_bottom']; $i++) {
      CsDemoHelper::civicthemeParagraphContentAttach($node, 'field_c_n_banner_components_bott', [
        'content' => CsDemoStaticContent::richText(2, sprintf('Banner bottom component %s ', $i + 1)),
        'theme' => CsDemoHelper::civicthemeThemeLight(),
      ]);
    }
  }

  $themes = CsDemoHelper::civicthemeThemes();

  foreach ($themes as $theme) {
    // Content.
    if (!empty($variation['content']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['content']; $i++) {
        CsDemoHelper::civicthemeParagraphContentAttach($node, 'field_c_n_components', [
          'content' => CsDemoStaticContent::richText(5, sprintf('Content %s ', $i + 1)),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(66),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeSpaceTypes()),
        ]);
      }
    }

    // Accordion.
    if (!empty($variation['accordion']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['accordion']; $i++) {
        // Panels.
        $count_per_group = rand(4, 8);
        $accordion_options = [];
        for ($j = 0; $j < $count_per_group; $j++) {
          $accordion_options[] = [
            'title' => 'A H ' . ($j + 1) . ' ' . CsDemoStaticContent::sentence(5),
            'content' => CsDemoStaticContent::richText(5),
            'expand' => rand(0, 1),
          ];
        }

        CsDemoHelper::civicthemeParagraphAccordionAttach($node, 'field_c_n_components', [
          'panels' => $accordion_options,
          'expand_all' => rand(0, 1),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeSpaceTypes()),
        ]);
      }
    }

    // Attachments.
    if (!empty($variation['attachment']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['attachment']; $i++) {
        $attachments = [];
        $attachments_count = rand(3, 8);

        for ($j = 0; $j < $attachments_count; $j++) {
          $attachments[] = CsDemoHelper::randomDocument();
        }

        CsDemoHelper::civicthemeParagraphAttachmentAttach($node, 'field_c_n_components', [
          'title' => 'A H ' . ($i + 1) . ' ' . CsDemoStaticContent::sentence(5),
          'summary' => CsDemoStaticContent::plainParagraph(),
          'attachments' => $attachments,
          'image' => CsDemoHelper::randomImage(),
          'icon' => CsDemoHelper::randomDocument(),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeSpaceTypes()),
        ]);
      }
    }

    // Callout.
    if (!empty($variation['callout']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['callout']; $i++) {
        CsDemoHelper::civicthemeParagraphCalloutAttach($node, 'field_c_n_components', [
          'title' => 'C H ' . ($i + 1) . ' ' . CsDemoStaticContent::sentence(5),
          'summary' => CsDemoStaticContent::plainParagraph(),
          'link' => [
            'uri' => CsDemoRandom::url(),
            'title' => CsDemoStaticContent::sentence(3),
          ],
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeSpaceTypes()),
        ]);
      }
    }

    // Card container.
    if (!empty($variation['card_container']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['card_container']; $i++) {
        // Cards.
        $card_types = CsDemoHelper::randomFieldAllowedBundles('paragraph', 'civictheme_card_container', 'field_c_p_cards', 3);
        if (is_array($card_types) && count($card_types) == 3) {
          $card_types = array_combine(array_values($card_types), [
            rand(2, 4),
            rand(2, 4),
            rand(2, 4),
          ]);
        }
        else {
          $card_types = [
            'task_card' => rand(1, 5),
          ];
        }

        $card_options = [];
        foreach ($card_types as $card_type => $card_count) {
          for ($k = 0; $k < $card_count; $k++) {
            $card_options[] = [
              'type' => 'civictheme_' . $card_type,
              'options' => CsDemoHelper::civicthemeCardsDefaultOptions($card_type),
            ];
          }
        }

        CsDemoHelper::civicthemeParagraphCardContainerAttach($node, 'field_c_n_components', [
          'title' => CsDemoRandom::bool(33) ? 'CC H ' . ($i + 1) . ' ' . CsDemoStaticContent::sentence(5) : NULL,
          'description' => CsDemoStaticContent::sentence(7),
          'column_count' => CsDemoHelper::randomFieldAllowedValue('paragraph', 'civictheme_card_container', 'field_c_p_column_count'),
          'fill_width' => CsDemoRandom::bool(),
          'footer_link' => CsDemoHelper::randomLinkFieldValue(),
          'header_link' => CsDemoHelper::randomLinkFieldValue(),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeSpaceTypes()),
          'cards' => $card_options,
        ]);
      }
    }

    // Next steps.
    if (!empty($variation['next_step']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['next_step']; $i++) {
        CsDemoHelper::civicthemeParagraphNextStepAttach($node, 'field_c_n_components', [
          'title' => 'NS H ' . ($i + 1) . ' ' . CsDemoStaticContent::sentence(5),
          'summary' => CsDemoStaticContent::plainParagraph(),
          'links' => [
            [
              'uri' => CsDemoRandom::url(),
              'title' => CsDemoStaticContent::sentence(3),
            ],
            [
              'uri' => CsDemoRandom::url(),
              'title' => CsDemoStaticContent::sentence(2),
            ],
            [
              'uri' => CsDemoRandom::url(),
              'title' => CsDemoStaticContent::sentence(4),
            ],
          ],
          'image' => CsDemoHelper::randomImage(),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeSpaceTypes()),
        ]);
      }
    }

    // Promo.
    if (!empty($variation['promo']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['promo']; $i++) {
        CsDemoHelper::civicthemeParagraphPromoAttach($node, 'field_c_n_components', [
          'title' => CsDemoRandom::bool(33) ? 'P H ' . ($i + 1) . ' ' . CsDemoStaticContent::sentence(5) : NULL,
          'summary' => CsDemoStaticContent::plainParagraph(),
          'link' => [
            'uri' => CsDemoRandom::url(),
            'title' => CsDemoStaticContent::sentence(2),
          ],
          'theme' => $theme,
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeSpaceTypes()),
        ]);
      }
    }

    // Map.
    if (!empty($variation['map']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['map']; $i++) {
        CsDemoHelper::civicthemeParagraphMapAttach($node, 'field_c_n_components', [
          'address' => CsDemoRandom::bool(33) ? 'P H ' . ($i + 1) . ' ' . CsDemoStaticContent::sentence(1) : NULL,
          'summary' => CsDemoStaticContent::plainParagraph(),
          'embed_url' => 'https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed',
          'share_link' => 'https://goo.gl/maps/XsNx2XM21y41UQ7P7',
          'view_link' => 'https://maps.google.com/maps?ll=-25.853564,133.545713&z=3&t=m&hl=en-US&gl=US&mapclient=embed&q=Australia',
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
        ]);
      }
    }

    // Iframe.
    if (!empty($variation['iframe']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['iframe']; $i++) {
        CsDemoHelper::civicthemeParagraphIframeAttach($node, 'field_c_n_components', [
          'url' => 'https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed',
          'width' => CsDemoRandom::bool(33) ? 600 : NULL,
          'height' => CsDemoRandom::bool(33) ? 400 : NULL,
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeSpaceTypes()),
        ]);
      }
    }

    // Listing.
    if (!empty($variation['listing']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['listing']; $i++) {
        $content_type = CsDemoHelper::randomListingContentType();
        $listing_options = [
          'title' => 'L H ' . ($i + 1) . ' ' . CsDemoStaticContent::sentence(1),
          'link' => CsDemoHelper::randomLinkFieldValue(),
          'content_type' => $content_type,
          'topics' => CsDemoHelper::randomTopics(rand(1, 5)),
          'view_as' => CsDemoHelper::randomListingViewAs(),
          'limit_type' => CsDemoHelper::randomListingLimitType(),
          'view_mode' => CsDemoHelper::randomListingViewAs(),
          'multi_select' => CsDemoRandom::bool(),
          'show_filters' => CsDemoRandom::bool(),
          'theme' => $theme,
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeSpaceTypes()),
          'read_more' => CsDemoHelper::randomLinkFieldValue(),
        ];

        CsDemoHelper::civicthemeParagraphListingAttach($node, 'field_c_n_components', $listing_options);
      }
    }

    // Slider.
    if (!empty($variation['slider']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['slider']; $i++) {
        // Slides.
        $count_per_group = rand(3, 6);
        $slide_options = [];
        for ($j = 0; $j < $count_per_group; $j++) {
          $slide_options[] = [
            'title' => 'S H ' . ($j + 1) . ' ' . CsDemoStaticContent::sentence(1),
            'content' => CsDemoStaticContent::richText(1),
            'image' => CsDemoHelper::randomImage(),
            'image_position' => CsDemoHelper::randomSliderSlideImagePosition(),
            'link' => CsDemoHelper::randomLinkFieldValue(),
            'topic' => CsDemoHelper::randomTopics(1),
            'theme' => $theme,
          ];
        }

        // Slider.
        CsDemoHelper::civicthemeParagraphSliderAttach($node, 'field_c_n_components', [
          'title' => CsDemoRandom::bool(33) ? 'SG H ' . ($i + 1) . ' ' . CsDemoStaticContent::sentence(1) : NULL,
          'content' => CsDemoStaticContent::richText(1, sprintf('Content %s ', $i + 1)),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicthemeSpaceTypes()),
          'slides' => $slide_options,
        ]);
      }
    }

    // Webform.
    if (!empty($variation['webform']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['webform']; $i++) {
        CsDemoHelper::civicthemeParagraphWebformAttach($node, 'field_c_n_components', [
          'webform' => CsDemoHelper::randomWebform(),
        ]);
      }
    }
  }

  if (!empty($variation['toc']) && $node->hasField('field_c_n_show_toc')) {
    $node->field_c_n_show_toc = TRUE;
  }
}
