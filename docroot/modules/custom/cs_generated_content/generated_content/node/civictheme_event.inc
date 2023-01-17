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
  return 36;
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

  for ($i = 0; $i < 20; $i++) {
    $variations[] = [
      'status' => 1,
      'title' => sprintf('%s Event %s', ($i + 1), $helper::staticSentence(3)),
      'thumbnail' => $helper::staticMediaItem('civictheme_image'),
      'summary' => 'Summary ' . $helper::staticSentence(20),
      'body' => $helper::staticSentenceFieldValue(),
      'topics' => $helper::staticTerms('civictheme_topics', 1),
      'site_section' => $helper::staticTerms('civictheme_site_sections', 1),
      'field_c_n_show_toc' => TRUE,
      'attachments' => $helper::staticMediaItems('civictheme_document', 3),
      'vertical_spacing' => $helper::civicthemeVerticalSpacingTypeBoth(),
      'show_last_updated' => TRUE,
    ];
  }

  foreach ($variations as $i => $variation) {
    /** @var \Drupal\node\Entity\Node $node */
    $node = $helper::variationCreateNode('civictheme_event', $variation, $i, '_cs_generated_content_create_node_civictheme_event__variation_to_fields');

    $variation_info = $helper::variationFormatInfo($variation);
    $node->revision_log = $variation_info;

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

  if (!empty($variation['site_section']) && $node->hasField('field_c_n_site_section')) {
    $node->field_c_n_site_section = $variation['site_section'];
  }

  if (!empty($variation['attachments']) && $node->hasField('field_c_n_attachments')) {
    $node->field_c_n_attachments = $variation['attachments '];
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
}
