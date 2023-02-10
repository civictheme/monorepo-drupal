<?php

/**
 * @file
 * Create generated Event nodes.
 */

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_node_civictheme_event_weight() {
  return 31;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_node_civictheme_event_tracking() {
  return TRUE;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE().
 */
function cs_generated_content_generated_content_create_node_civictheme_event() {
  $nodes = [];

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  // Some components require to reference existing nodes. Since variation
  // evaluation happens before such nodes created, we need to pre-create them
  // in a preceding pass.
  for ($pass = 0; $pass < 2; $pass++) {
    // First pass - provide variations from the generated content pages.
    if ($pass === 0) {
      require_once __DIR__ . DIRECTORY_SEPARATOR . 'civictheme_event_variations/01.helpers.inc';
      for ($i = 0; $i < 20; $i++) {
        $variations[] = [
          'title' => sprintf('Event. %s %s', str_pad($i + 1, 2, '0', STR_PAD_LEFT), $helper::staticSentence(3)),
          'thumbnail' => $helper::staticMediaItem('civictheme_image'),
          'summary' => 'Summary ' . $helper::staticSentence(20),
          'topics' => $helper::staticTerms('civictheme_topics', 1),
          'body' => $helper::staticRichText(3),
          'field_c_n_show_toc' => TRUE,
          'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
          'show_last_updated' => TRUE,
          'date_range' => [
            'value' => $helper::dateToUtc('2022-07-01T09:45:00'),
            'end_value' => $helper::dateToUtc('2022-08-14T15:30:00'),
          ],
          'location' => [
            [
              'type' => 'map',
              'embed_url' => $helper::staticUrl(),
              'address' => $helper::staticSentence(3),
              'view_link' => $helper::staticLinkFieldValue(),
              'theme' => $helper::civicthemeThemeLight(),
              'background' => TRUE,
              'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
            ],
          ],
        ];
      }
      $variations = _cs_generated_content_create_node_civictheme_event__variations__post_process($variations);
    }
    else {
      // Second pass - provide variations from the variation callbacks.
      $variations = $helper::variationFetchAll(
        '_cs_generated_content_create_node_civictheme_event__variations__',
        __DIR__ . DIRECTORY_SEPARATOR . 'civictheme_event_variations',
      );
    }

    foreach ($variations as $i => $variation) {
      /** @var \Drupal\node\Entity\Node $node */
      $node = $helper::variationCreateNode('civictheme_event', $variation, $i, '_cs_generated_content_create_node_civictheme_event__variation_to_fields');

      $variation_info = $helper::variationFormatInfo($variation);
      $node->revision_log = 'Generated content. ' . $variation_info;

      $node->save();

      $helper::log(
        'Created "%s" node "%s" [ID: %s] %s',
        $node->bundle(),
        $node->toLink()->toString(),
        $node->id(),
        Link::createFromRoute('Edit', 'entity.node.edit_form', ['node' => $node->id()])->toString()
      );

      $nodes[] = $node;
    }

    // Add created nodes to the repository to make them available within the
    // next pass.
    $helper::addToRepository($nodes);
  }

  return $nodes;
}

/**
 * Processor to convert variation values to 'civictheme_event' node fields.
 */
function _cs_generated_content_create_node_civictheme_event__variation_to_fields($node, $variation) {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  if (!empty($variation['title'])) {
    $node->setTitle($variation['title']);
  }

  if (!empty($variation['alias'])) {
    // Replace tokens in an alias.
    $variation['alias'] = $helper::replaceTokens($variation['alias'], $variation, function ($value) {
      return is_string($value) ? \Drupal::service('pathauto.alias_cleaner')->cleanString($value) : $value;
    });

    $node->set('path', [
      'pathauto' => FALSE,
      'alias' => $variation['alias'],
    ]);
  }

  if (!empty($variation['summary']) && $node->hasField('field_c_n_summary')) {
    $node->field_c_n_summary = $variation['summary'];
  }

  if (!empty($variation['body']) && $node->hasField('field_c_n_body')) {
    $node->field_c_n_body = $variation['body'];
  }

  if (!empty($variation['thumbnail']) && $node->hasField('field_c_n_thumbnail')) {
    $node->field_c_n_thumbnail = $variation['thumbnail'];
  }

  if (!empty($variation['topics']) && $node->hasField('field_c_n_topics')) {
    $node->field_c_n_topics = $variation['topics'];
  }

  if (!empty($variation['date_range']) && $node->hasField('field_c_n_date_range')) {
    $node->field_c_n_date_range = $variation['date_range'];
  }

  if (!empty($variation['site_section']) && $node->hasField('field_c_n_site_section')) {
    $node->field_c_n_site_section = $variation['site_section'];
  }

  if (isset($variation['vertical_spacing']) && $node->hasField('field_c_n_vertical_spacing')) {
    $node->field_c_n_vertical_spacing = $variation['vertical_spacing'];
  }

  if (isset($variation['show_last_updated']) && $node->hasField('field_c_n_show_last_updated')) {
    $node->field_c_n_show_last_updated = $variation['show_last_updated'];
  }

  if (isset($variation['custom_updated_date']) && $node->hasField('field_c_n_custom_last_updated')) {
    $node->field_c_n_custom_last_updated = $variation['custom_updated_date'];
  }

  // Locations.
  if (!empty($variation['location']) && $node->hasField('field_c_n_location')) {
    for ($i = 0; $i < count($variation['location']); $i++) {
      // Extract component type.
      $component_type = $variation['location'][$i]['type'];
      // Remove component type and passthrough properties.
      $component_options = array_diff_key($variation['location'][$i], ['type' => TRUE]);
      $helper::civicthemeComponentAttach($node, 'field_c_n_location', $component_type, $component_options);
    }
  }

  // Attachments.
  if (!empty($variation['attachments']) && $node->hasField('field_c_n_attachments')) {
    for ($i = 0; $i < count($variation['attachments']); $i++) {
      // Extract component type.
      $component_type = $variation['attachments'][$i]['type'];
      // Remove component type and passthrough properties.
      $component_options = array_diff_key($variation['attachments'][$i], ['type' => TRUE]);
      $helper::civicthemeComponentAttach($node, 'field_c_n_attachments', $component_type, $component_options);
    }
  }
}
