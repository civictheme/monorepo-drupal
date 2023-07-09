<?php

/**
 * @file
 * Post-updates for CivicTheme.
 */

use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUpdateHelper;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
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

/**
 * Update List components field config.
 */
function civictheme_post_update_rename_list_fileds(&$sandbox) {
  // New field configs.
  $new_field_configs = [
    'field.storage.paragraph.field_c_p_list_fill_width' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_list_fill_width' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_list_fill_width' => 'field_config',
    'field.storage.paragraph.field_c_p_list_column_count' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_list_column_count' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_list_column_count' => 'field_config',
  ];

  // Old field configs to remove.
  $old_field_configs = [
    'field.storage.paragraph.field_c_p_fill_width' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_fill_width' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_fill_width' => 'field_config',
    'field.storage.paragraph.field_c_p_column_count' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_column_count' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_column_count' => 'field_config',
  ];

  $form_display_config = [
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

  $form_display_group_config = [
    'civictheme_manual_list' => [
      'group_columns' => [
        'field_c_p_list_column_count',
        'field_c_p_list_fill_width',
      ],
    ],
    'civictheme_automated_list' => [
      'group_columns' => [
        'field_c_p_list_column_count',
        'field_c_p_list_fill_width',
      ],
    ],
  ];

  // Obtain configuration from yaml files.
  $config_path = \Drupal::service('extension.list.theme')->getPath('civictheme') . '/config/install';
  $bundles = ['civictheme_manual_list', 'civictheme_automated_list'];

  $field_mapping = [
    'field_c_p_fill_width' => 'field_c_p_list_fill_width',
    'field_c_p_column_count' => 'field_c_p_list_column_count',
  ];

  \Drupal::classResolver(CivicthemeUpdateHelper::class)->update(
    $sandbox,
    'paragraph',
    $bundles,
    // Start callback.
    function (CivicthemeUpdateHelper $helper) use (&$sandbox, $config_path, $new_field_configs) {
      $helper->createConfigs($sandbox, $new_field_configs, $config_path);
    },
    // Process callback.
    function (CivicthemeUpdateHelper $helper, EntityInterface $entity) use (&$sandbox, $field_mapping) {
      $helper->updateFieldContent($sandbox, $entity, $field_mapping);
    },
    // Finished callback.
    function (CivicthemeUpdateHelper $helper) use (&$sandbox, $old_field_configs, $form_display_config, $form_display_group_config) {
      $helper->deleteConfig($sandbox, $old_field_configs);
      // Updated form display setting.
      foreach ($form_display_config as $bundle => $config) {
        $helper->updateFormDisplay('paragraph', $bundle, $config, $form_display_group_config[$bundle]);
      }

      if ($sandbox['#finished']) {
        $paragraph_types = array_keys($form_display_config);
        $log = new TranslatableMarkup("Content from field 'field_c_p_column_count' was moved to 'field_c_p_list_column_count'.
          Content from field 'field_c_p_fill_width' was moved to 'field_c_p_list_fill_width'.
          The 'field_c_p_column_count' and 'field_c_p_fill_width' were removed from %paragraph_types paragraph types.
          Please re-export your site configuration. \n", [
            '%paragraph_types' => implode(', ', $paragraph_types),
          ]);
        \Drupal::logger('update')->notice($log);
      }
    },
  );

}
