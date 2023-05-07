<?php

/**
 * @file
 * Post-updates for CivicTheme.
 */

use Drupal\civictheme\CivicthemeConstants;
use Drupal\node\Entity\Node;

require_once 'includes/utilities.inc';

/**
 * Updates vertical spacing on components where it has not been set.
 */
function civictheme_post_update_vertical_spacing(&$sandbox) {
  $batch_size = 50;

  // If the sandbox is empty, initialize it.
  if (!isset($sandbox['progress'])) {
    $sandbox['progress'] = 0;
    $sandbox['current_node'] = 0;
    // Query to fetch all the civictheme_page node ids.
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'civictheme_page');
    $sandbox['node_ids'] = $query->execute();
    $sandbox['max'] = $query->count()->execute();
  }

  $nids = array_slice($sandbox['node_ids'], $sandbox['progress'], $batch_size);

  foreach ($nids as $nid) {
    $node = Node::load($nid);
    _civictheme_update_vertical_spacing($node);
    $node->save();

    // Update the sandbox progress.
    $sandbox['progress']++;
    $sandbox['current_node'] = $nid;
  }

  $sandbox['#finished'] = $sandbox['progress'] / $sandbox['max'];
}

/**
 * Update vertical spacing for a given node.
 */
function _civictheme_update_vertical_spacing(Node $node) {
  if (is_null(civictheme_get_field_value($node, 'field_c_n_vertical_spacing', TRUE))) {
    $node->field_c_n_vertical_spacing = CivicthemeConstants::VERTICAL_SPACING_NONE;

    $field_names = [
      'field_c_n_components',
      'field_c_n_banner_components',
      'field_c_n_banner_components_bott',
    ];

    foreach ($field_names as $field_name) {
      $components = civictheme_get_field_value($node, $field_name);

      if (empty($components)) {
        continue;
      }

      foreach ($components as $component) {
        if (is_null(civictheme_get_field_value($component, 'field_c_p_vertical_spacing', TRUE))) {
          $component->field_c_p_vertical_spacing = CivicthemeConstants::VERTICAL_SPACING_NONE;
        }
      }
    }

    $node->save();
  }
}
