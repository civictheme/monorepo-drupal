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
  $batch_size = 10;

  // If the sandbox is empty, initialize it.
  if (!isset($sandbox['progress'])) {
    $sandbox['batch'] = 0;

    $sandbox['progress'] = 0;
    $sandbox['current_node'] = 0;
    // Query to fetch all the civictheme_page node ids.
    $query = \Drupal::entityQuery('node')
      ->accessCheck(FALSE)
      ->condition('type', 'civictheme_page');
    $sandbox['node_ids'] = $query->execute();
    $sandbox['max'] = $query->count()->execute();

    $sandbox['results']['processed'] = [];
    $sandbox['results']['updated'] = [];
    $sandbox['results']['skipped'] = [];
  }

  $sandbox['batch']++;

  $nids = array_slice($sandbox['node_ids'], $sandbox['progress'], $batch_size);

  foreach ($nids as $nid) {
    $sandbox['results']['processed'][] = $nid;
    $node = Node::load($nid);
    if (!$node) {
      $sandbox['results']['skipped'][] = $nid;
      continue;
    }

    $updated = _civictheme_update_vertical_spacing($node);

    if ($updated) {
      $sandbox['results']['updated'][] = $nid;
    }
    else {
      $sandbox['results']['skipped'][] = $nid;
    }

    $sandbox['progress']++;
    $sandbox['current_node'] = $nid;
  }

  $sandbox['#finished'] = $sandbox['max'] > 0 ? $sandbox['progress'] / $sandbox['max'] : 1;

  if ($sandbox['#finished'] >= 1) {
    return sprintf("Update results ran in %s batch(es):\n   Processed: %s %s\n   Updated: %s %s\n   Skipped: %s %s\n",
      $sandbox['batch'],
      count($sandbox['results']['processed']),
      count($sandbox['results']['processed']) ? '(' . implode(', ', $sandbox['results']['processed']) . ')' : '',
      count($sandbox['results']['updated']),
      count($sandbox['results']['updated']) ? '(' . implode(', ', $sandbox['results']['updated']) . ')' : '',
      count($sandbox['results']['skipped']),
      count($sandbox['results']['skipped']) ? '(' . implode(', ', $sandbox['results']['skipped']) . ')' : '',
    );
  }
}

/**
 * Update vertical spacing for a given node.
 */
function _civictheme_update_vertical_spacing(Node $node) {
  $changed = FALSE;

  if (is_null(civictheme_get_field_value($node, 'field_c_n_vertical_spacing', TRUE))) {
    $node->field_c_n_vertical_spacing = CivicthemeConstants::VERTICAL_SPACING_NONE;
    $changed = TRUE;
  }

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
        $changed = TRUE;
      }
    }
  }

  if ($changed) {
    $node->save();
  }

  return $changed;
}
