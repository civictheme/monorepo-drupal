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

    if (is_array($types) && count($types) == 3){
      $types = array_combine(array_values($types), [rand(1, 2), rand(1, 3), rand(1, 3)]);
    }
    else {
      $types = [
        'content' => rand(1,2),
      ];
    }

    $variation = CsDemoHelper::variationRandom([
      'status' => NULL,
      'summary' => NULL,
      'thumbnail' => NULL,
      'space' => 'both',
      'toc' => NULL,
      'topics' => rand(0, 5),
      'banner_type' => CsDemoRandom::arrayItem(CsDemoHelper::civicBannerTypes()),
      'banner_theme' => CsDemoRandom::arrayItem(CsDemoHelper::civicThemes()),
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

    $node->save();

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
    $node->field_c_n_summary = CsDemoRandom::sentence(2, 10);
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
      CsDemoHelper::civicParagraphContentAttach($node, 'field_c_n_banner_components', [
        'content' => CsDemoRandom::richText(rand(1, 2), rand(2, 4), sprintf('Banner component %s ', $i + 1)),
        'theme' => $variation['banner_theme'] ?: CsDemoHelper::civicThemeLight(),
      ]);
    }
  }

  if (!empty($variation['banner_components_bottom']) && $node->hasField('field_c_n_banner_components_bott')) {
    for ($i = 0; $i < $variation['banner_components_bottom']; $i++) {
      CsDemoHelper::civicParagraphContentAttach($node, 'field_c_n_banner_components_bott', [
        'content' => CsDemoRandom::richText(rand(1, 2), rand(2, 4), sprintf('Banner bottom component %s ', $i + 1)),
        'theme' => CsDemoHelper::civicThemeLight(),
      ]);
    }
  }

  $themes = CsDemoHelper::civicThemes();

  foreach ($themes as $theme) {
    // Content.
    if (!empty($variation['content']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['content']; $i++) {
        CsDemoHelper::civicParagraphContentAttach($node, 'field_c_n_components', [
          'content' => CsDemoRandom::richText(rand(1, 3), rand(5, 7), sprintf('Content %s ', $i + 1)),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(66),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicSpaceTypes()),
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
            'title' => 'A H ' . ($j + 1) . ' ' . CsDemoRandom::sentence(1),
            'content' => CsDemoRandom::richText(3, 8),
            'expand' => rand(0, 1),
          ];
        }

        CsDemoHelper::civicParagraphAccordionAttach($node, 'field_c_n_components', [
          'title' => CsDemoRandom::bool(33) ? 'AG H ' . ($i + 1) . ' ' . CsDemoRandom::sentence(1) : NULL,
          'panels' => $accordion_options,
          'expand_all' => rand(0, 1),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicSpaceTypes()),
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

        CsDemoHelper::civicParagraphAttachmentAttach($node, 'field_c_n_components', [
          'title' => 'A H ' . ($i + 1) . ' ' . CsDemoRandom::sentence(1),
          'summary' => CsDemoRandom::plainParagraph(),
          'attachments' => $attachments,
          'image' => CsDemoHelper::randomImage(),
          'icon' => CsDemoHelper::randomDocument(),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicSpaceTypes()),
        ]);
      }
    }

    // Callout.
    if (!empty($variation['callout']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['callout']; $i++) {
        CsDemoHelper::civicParagraphCalloutAttach($node, 'field_c_n_components', [
          'title' => 'C H ' . ($i + 1) . ' ' . CsDemoRandom::sentence(1),
          'summary' => CsDemoRandom::plainParagraph(),
          'link' => [
            'uri' => CsDemoRandom::url(),
            'title' => CsDemoRandom::sentence(2, 5),
          ],
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicSpaceTypes()),
        ]);
      }
    }

    // Card container.
    if (!empty($variation['card_container']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['card_container']; $i++) {

        // Cards.
        $card_types = CsDemoHelper::randomFieldAllowedBundles('paragraph', 'civictheme_card_container', 'field_c_p_cards', 3);
        if (is_array($card_types) && count($card_types) == 3){
          $card_types = array_combine(array_values($card_types), [rand(2, 4), rand(2, 4), rand(2, 4)]);
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
              'options' => CsDemoHelper::civicCardsDefaultOptions($card_type),
            ];
          }
        }

        CsDemoHelper::civicParagraphCardContainerAttach($node, 'field_c_n_components', [
          'title' => CsDemoRandom::bool(33) ? 'CC H ' . ($i + 1) . ' ' . CsDemoRandom::sentence(1) : NULL,
          'description' => CsDemoRandom::sentence(3),
          'column_count' => CsDemoHelper::randomFieldAllowedValue('paragraph', 'civictheme_card_container', 'field_c_p_column_count'),
          'fill_width' => CsDemoRandom::bool(),
          'footer_link' => CsDemoHelper::randomLinkFieldValue(),
          'header_link' => CsDemoHelper::randomLinkFieldValue(),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicSpaceTypes()),
          'cards' => $card_options,
        ]);
      }
    }

    // Next steps.
    if (!empty($variation['next_step']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['next_step']; $i++) {
        CsDemoHelper::civicParagraphNextStepAttach($node, 'field_c_n_components', [
          'title' => 'NS H ' . ($i + 1) . ' ' . CsDemoRandom::sentence(1),
          'summary' => CsDemoRandom::plainParagraph(),
          'links' => [
            [
              'uri' => CsDemoRandom::url(),
              'title' => CsDemoRandom::sentence(2, 5),
            ],
            [
              'uri' => CsDemoRandom::url(),
              'title' => CsDemoRandom::sentence(2, 5),
            ],
            [
              'uri' => CsDemoRandom::url(),
              'title' => CsDemoRandom::sentence(2, 5),
            ],
          ],
          'image' => CsDemoHelper::randomImage(),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicSpaceTypes()),
        ]);
      }
    }

    // Promo.
    if (!empty($variation['promo']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['promo']; $i++) {
        CsDemoHelper::civicParagraphPromoAttach($node, 'field_c_n_components', [
          'title' => CsDemoRandom::bool(33) ? 'P H ' . ($i + 1) . ' ' . CsDemoRandom::sentence(1) : NULL,
          'summary' => CsDemoRandom::plainParagraph(),
          'link' => [
            'uri' => CsDemoRandom::url(),
            'title' => CsDemoRandom::sentence(2, 5),
          ],
          'theme' => $theme,
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicSpaceTypes()),
        ]);
      }
    }

    // Map.
    if (!empty($variation['map']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['map']; $i++) {
        CsDemoHelper::civicParagraphMapAttach($node, 'field_c_n_components', [
          'address' => CsDemoRandom::bool(33) ? 'P H ' . ($i + 1) . ' ' . CsDemoRandom::sentence(1) : NULL,
          'summary' => CsDemoRandom::plainParagraph(),
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
        CsDemoHelper::civicParagraphIframeAttach($node, 'field_c_n_components', [
          'url' => 'https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed',
          'width' => CsDemoRandom::bool(33) ? 600 : NULL,
          'height' => CsDemoRandom::bool(33) ? 400 : NULL,
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicSpaceTypes()),
        ]);
      }
    }

    // Listing.
    if (!empty($variation['listing']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['listing']; $i++) {

        $content_type = CsDemoHelper::randomListingContentType();
        $listing_options = [
          'title' => 'L H ' . ($i + 1) . ' ' . CsDemoRandom::sentence(1),
          'link' => CsDemoHelper::randomLinkFieldValue(),
          'content_type' => $content_type,
          'topics' => CsDemoHelper::randomTopics(rand(1, 5)),
          'view_as' => CsDemoHelper::randomListingViewAs(),
          'limit_type' => CsDemoHelper::randomListingLimitType(),
          'view_mode' => CsDemoHelper::randomListingViewAs(),
          'show_pager' => CsDemoRandom::bool(),
          'hide_count' => CsDemoRandom::bool(),
          'multi_select' => CsDemoRandom::bool(),
          'show_filters' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicSpaceTypes()),
          'read_more' => CsDemoHelper::randomLinkFieldValue(),
        ];

        CsDemoHelper::civicParagraphlistingAttach($node, 'field_c_n_components', $listing_options);
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
            'title' => 'S H ' . ($j + 1) . ' ' . CsDemoRandom::sentence(1),
            'content' => CsDemoRandom::richText(1, 1),
            'image' => CsDemoHelper::randomImage(),
            'image_position' => CsDemoHelper::randomSliderSlideImagePosition(),
            'link' => CsDemoHelper::randomLinkFieldValue(),
            'topic' => CsDemoHelper::randomTopics(1),
            'theme' => $theme,
          ];
        }

        // Slider.
        CsDemoHelper::civicParagraphSliderAttach($node, 'field_c_n_components', [
          'title' => CsDemoRandom::bool(33) ? 'SG H ' . ($i + 1) . ' ' . CsDemoRandom::sentence(1) : NULL,
          'content' => CsDemoRandom::richText(rand(1, 3), rand(5, 7), sprintf('Content %s ', $i + 1)),
          'theme' => $theme,
          'background' => CsDemoRandom::bool(),
          'space' => CsDemoRandom::arrayItem(CsDemoHelper::civicSpaceTypes()),
          'slides' => $slide_options,
        ]);
      }
    }

    // Webform.
    if (!empty($variation['webform']) && $node->hasField('field_c_n_components')) {
      for ($i = 0; $i < $variation['webform']; $i++) {
        CsDemoHelper::civicParagraphWebformAttach($node, 'field_c_n_components', [
          'webform' => CsDemoHelper::randomWebform(),
        ]);
      }
    }
  }

  if (!empty($variation['toc']) && $node->hasField('field_c_n_show_toc')) {
    $node->field_c_n_show_toc = TRUE;
  }
}
