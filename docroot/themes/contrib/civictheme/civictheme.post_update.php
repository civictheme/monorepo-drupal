<?php

/**
 * @file
 * Post-updates for CivicTheme.
 */

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

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

/**
 * Update List components field config.
 */
function civictheme_post_update_list_component(&$sandbox) {
  // Import required config first.
  _civictheme_list_component_fields_config();

  $batch_size = 10;
  $paragraph_types = ['civictheme_manual_list', 'civictheme_automated_list'];

  // If the sandbox is empty, initialize it.
  if (!isset($sandbox['progress'])) {
    $sandbox['batch'] = 0;

    $sandbox['progress'] = 0;
    $sandbox['current_paragraph'] = 0;
    // Query to fetch all the manual_list paragraph ids.
    $query = \Drupal::entityQuery('paragraph')
      ->accessCheck(FALSE)
      ->condition('type', $paragraph_types, 'in');
    $sandbox['paragraph_ids'] = $query->execute();
    $sandbox['max'] = $query->count()->execute();

    $sandbox['results']['processed'] = [];
    $sandbox['results']['updated'] = [];
    $sandbox['results']['skipped'] = [];
  }

  $sandbox['batch']++;

  $pids = array_slice($sandbox['paragraph_ids'], $sandbox['progress'], $batch_size);

  foreach ($pids as $id) {
    $sandbox['results']['processed'][] = $id;
    $paragraph = Paragraph::load($id);
    if (!$paragraph) {
      $sandbox['results']['skipped'][] = $id;
      continue;
    }

    $updated = _civictheme_update_list_fields($paragraph);

    if ($updated) {
      $sandbox['results']['updated'][] = $id;
    }
    else {
      $sandbox['results']['skipped'][] = $id;
    }

    $sandbox['progress']++;
    $sandbox['current_paragraph'] = $id;
  }

  $sandbox['#finished'] = $sandbox['max'] > 0 ? $sandbox['progress'] / $sandbox['max'] : 1;

  if ($sandbox['#finished'] >= 1) {
    // Run config delete.
    _civictheme_list_component_fields_config_delete();

    return sprintf(
      "Content from field 'field_c_p_column_count' was moved to 'field_c_p_list_column_count'.\n
      Content from field 'field_c_p_fill_width' was moved to 'field_c_p_list_fill_width'.\n
      The 'field_c_p_column_count' and 'field_c_p_fill_width' were removed from %s paragraph types.\n
      Please re-export your site configuration. \n
      Update results ran in %s batch(es):\n   Processed: %s %s\n   Updated: %s %s\n   Skipped: %s %s\n",
      implode(', ', $paragraph_types),
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
 * Updated required field configs.
 */
function _civictheme_list_component_fields_config() {
  // Obtain configuration from yaml files.
  $config_path = \Drupal::service('extension.list.theme')->getPath('civictheme') . '/config/install';
  $source = new FileStorage($config_path);
  /** @var Drupal\Core\Entity\EntityTypeManager */
  $entity_type_manager_service = \Drupal::service('entity_type.manager');

  $configs = [
    'field.storage.paragraph.field_c_p_list_fill_width' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_list_fill_width' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_list_fill_width' => 'field_config',
    'field.storage.paragraph.field_c_p_list_column_count' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_list_column_count' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_list_column_count' => 'field_config',
  ];
  // Check if field already exported to config/sync.
  foreach ($configs as $config => $type) {
    $storage = $entity_type_manager_service->getStorage($type);
    $config_read = $source->read($config);
    $id = substr($config, strpos($config, '.', 6) + 1);
    if ($storage->load($id) == NULL) {
      $config_entity = $storage->createFromStorageRecord($config_read);
      $config_entity->save();
    }
  }

  $update_field_config = [
    'civictheme_manual_list' => [
      'field_c_p_list_column_count' => [
        'type' => 'options_select',
        'weight' => 5,
        'region' => 'content',
        'settings' => [],
        'third_party_settings' => [],
      ],
      'field_c_p_list_fill_width' => [
        'type' => 'boolean_checkbox',
        'weight' => 6,
        'region' => 'content',
        'settings' => [
          'display_label' => TRUE,
        ],
        'third_party_settings' => [],
      ],
    ],
    'civictheme_automated_list' => [
      'field_c_p_list_column_count' => [
        'type' => 'options_select',
        'weight' => 5,
        'region' => 'content',
        'settings' => [],
        'third_party_settings' => [],
      ],
      'field_c_p_list_fill_width' => [
        'type' => 'options_select',
        'weight' => 5,
        'region' => 'content',
        'settings' => [
          'display_label' => TRUE,
        ],
        'third_party_settings' => [],
      ],
    ],
  ];

  $group_name = 'group_columns';

  foreach ($update_field_config as $type => $fields) {
    /** @var \Drupal\Core\Entity\Display\EntityFormDisplayInterface $form_display */
    $entity_form_display = EntityFormDisplay::load('paragraph.' . $type . '.default');

    if ($entity_form_display) {
      foreach ($fields as $field => $replacements) {
        $component = $entity_form_display->getComponent($field);
        if ($component) {
          $component = array_replace_recursive($component, $replacements);
          $entity_form_display->setComponent($field, $component);
        }
        // Update the field groups.
        $field_group = $entity_form_display->getThirdPartySettings('field_group');
        if (!empty($field_group[$group_name]['children'])) {
          if (!in_array($field, $field_group[$group_name]['children'])) {
            $field_group[$group_name]['children'][] = $field;
            $entity_form_display->setThirdPartySetting('field_group', $group_name, $field_group[$group_name]);
          }
        }
      }
    }
  }
}

/**
 * Delete field configs after content update.
 */
function _civictheme_list_component_fields_config_delete() {
  /** @var Drupal\Core\Entity\EntityTypeManager */
  $entity_type_manager_service = \Drupal::service('entity_type.manager');

  $configs = [
    'field.storage.paragraph.field_c_p_fill_width' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_fill_width' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_fill_width' => 'field_config',
    'field.storage.paragraph.field_c_p_column_count' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_column_count' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_column_count' => 'field_config',
  ];
  // Check if field already exported to config/sync.
  foreach ($configs as $config => $type) {
    $storage = $entity_type_manager_service->getStorage($type);
    $id = substr($config, strpos($config, '.', 6) + 1);
    $config_read = $storage->load($id);
    if ($config_read != NULL) {
      $config_read->delete();
    }
  }
}

/**
 * Update field data for a given Paragraph.
 */
function _civictheme_update_list_fields(Paragraph $paragraph) {
  $changed = FALSE;

  // Update fill width field value.
  if ($paragraph->hasField('field_c_p_list_fill_width') && !is_null(civictheme_get_field_value($paragraph, 'field_c_p_fill_width', TRUE))) {
    $paragraph->field_c_p_list_fill_width = civictheme_get_field_value($paragraph, 'field_c_p_fill_width', TRUE);
    $changed = TRUE;
  }

  // Update column count field value.
  if ($paragraph->hasField('field_c_p_list_column_count') && !is_null(civictheme_get_field_value($paragraph, 'field_c_p_column_count', TRUE))) {
    $paragraph->field_c_p_list_column_count = civictheme_get_field_value($paragraph, 'field_c_p_column_count', TRUE);
    $changed = TRUE;
  }

  if ($changed) {
    $paragraph->save();
  }

  return $changed;
}
