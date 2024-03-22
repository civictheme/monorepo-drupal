<?php

/**
 * @file
 * Post-updates for CivicTheme.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUpdateHelper;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\ParagraphInterface;

require_once __DIR__ . '/includes/utilities.inc';

/**
 * Updates vertical spacing on nodes and components where it has not been set.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function civictheme_post_update_set_vertical_spacing_empty_value(array &$sandbox): ?string {
  return \Drupal::classResolver(CivicthemeUpdateHelper::class)->update(
    $sandbox,
    'node',
    ['civictheme_page'],
    // Start callback.
    static function (CivicthemeUpdateHelper $helper): void {
      // Noop.
    },
    // Process callback.
    static function (CivicthemeUpdateHelper $helper, EntityInterface $entity): bool {
      if (!$entity instanceof Node) {
        return FALSE;
      }

      $updated = FALSE;
      // Update vertical spacing for node.
      if (is_null(civictheme_get_field_value($entity, 'field_c_n_vertical_spacing', TRUE))) {
        // @phpstan-ignore-next-line
        $entity->field_c_n_vertical_spacing = CivicthemeConstants::VERTICAL_SPACING_NONE;
        $updated = TRUE;
      }

      // Update vertical spacing for components.
      $field_names = [
        'field_c_n_components',
        'field_c_n_banner_components',
        'field_c_n_banner_components_bott',
      ];
      foreach ($field_names as $field_name) {
        $components = civictheme_get_field_value($entity, $field_name);

        if (empty($components)) {
          continue;
        }

        foreach ($components as $component) {
          if (is_null(civictheme_get_field_value($component, 'field_c_p_vertical_spacing', TRUE))) {
            $component->field_c_p_vertical_spacing = CivicthemeConstants::VERTICAL_SPACING_NONE;
            $updated = TRUE;
          }
        }
      }

      if ($updated) {
        $entity->save();
      }

      return $updated;
    },
    // Finished callback.
    static function (CivicthemeUpdateHelper $helper): TranslatableMarkup {
      return new TranslatableMarkup("Updated values for fields 'field_c_n_vertical_spacing' and 'field_c_p_vertical_spacing'.\n");
    },
  );
}

/**
 * Renames 'Column count' and 'Fill width' List fields and updates content.
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function civictheme_post_update_rename_list_fields(array &$sandbox): ?string {
  $field_mapping = [
    'field_c_p_fill_width' => 'field_c_p_list_fill_width',
    'field_c_p_column_count' => 'field_c_p_list_column_count',
  ];

  $old_field_configs = [
    'field.storage.paragraph.field_c_p_fill_width' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_fill_width' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_fill_width' => 'field_config',
    'field.storage.paragraph.field_c_p_column_count' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_column_count' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_column_count' => 'field_config',
  ];

  $new_field_configs = [
    'field.storage.paragraph.field_c_p_list_fill_width' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_list_fill_width' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_list_fill_width' => 'field_config',
    'field.storage.paragraph.field_c_p_list_column_count' => 'field_storage_config',
    'field.field.paragraph.civictheme_manual_list.field_c_p_list_column_count' => 'field_config',
    'field.field.paragraph.civictheme_automated_list.field_c_p_list_column_count' => 'field_config',
  ];

  $new_form_display_config = [
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

  $new_form_display_group_config = [
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

  return \Drupal::classResolver(CivicthemeUpdateHelper::class)->update(
    $sandbox,
    'paragraph',
    array_keys($new_form_display_config),
    // Start callback.
    static function (CivicthemeUpdateHelper $helper) use ($new_field_configs): void {
      $config_path = \Drupal::service('extension.list.theme')->getPath('civictheme') . '/config/install';
      $helper->createConfigs($new_field_configs, $config_path);
    },
    // Process callback.
    static function (CivicthemeUpdateHelper $helper, FieldableEntityInterface $entity) use ($field_mapping): bool {
      return $helper->copyFieldContent($entity, $field_mapping);
    },
    // Finished callback.
    static function (CivicthemeUpdateHelper $helper) use ($old_field_configs, $new_form_display_config, $new_form_display_group_config): TranslatableMarkup {
      $helper->deleteConfig($old_field_configs);
      foreach ($new_form_display_config as $bundle => $config) {
        $helper->updateFormDisplayConfig('paragraph', $bundle, $config, $new_form_display_group_config[$bundle]);
      }

      return new TranslatableMarkup("Content from field 'field_c_p_column_count' was moved to 'field_c_p_list_column_count'. Content from field 'field_c_p_fill_width' was moved to 'field_c_p_list_fill_width'.\nThe 'field_c_p_column_count' and 'field_c_p_fill_width' were removed from %paragraph_types paragraph types. Please re-export your site configuration.\n", [
        '%paragraph_types' => implode(', ', array_keys($new_form_display_config)),
      ]);
    }
  );
}

/**
 * Replaces 'Summary' with 'Content' field in components and updates content.
 */
function civictheme_post_update_replace_summary_field(array &$sandbox): ?string {
  $field_mapping = [
    'field_c_p_summary' => 'field_c_p_content',
  ];

  $old_field_configs = [
    'field.field.paragraph.civictheme_attachment.field_c_p_summary' => 'field_config',
    'field.field.paragraph.civictheme_callout.field_c_p_summary' => 'field_config',
    'field.field.paragraph.civictheme_next_step.field_c_p_summary' => 'field_config',
    'field.field.paragraph.civictheme_promo.field_c_p_summary' => 'field_config',
  ];

  $new_field_configs = [
    'field.storage.paragraph.field_c_p_content' => 'field_storage_config',
    'field.field.paragraph.civictheme_attachment.field_c_p_content' => 'field_config',
    'field.field.paragraph.civictheme_callout.field_c_p_content' => 'field_config',
    'field.field.paragraph.civictheme_next_step.field_c_p_content' => 'field_config',
    'field.field.paragraph.civictheme_promo.field_c_p_content' => 'field_config',
  ];

  $new_field_settings = [
    'type' => 'string_textarea',
    'weight' => 1,
    'region' => 'content',
    'settings' => [
      'rows' => 5,
      'placeholder' => '',
    ],
    'third_party_settings' => [],
  ];

  $new_form_display_config = [
    'civictheme_attachment' => [
      'field_c_p_content' => $new_field_settings + ['weight' => 2],
    ],
    'civictheme_callout' => [
      'field_c_p_content' => $new_field_settings,
    ],
    'civictheme_next_step' => [
      'field_c_p_content' => $new_field_settings,
    ],
    'civictheme_promo' => [
      'field_c_p_content' => $new_field_settings,
    ],
  ];

  return \Drupal::classResolver(CivicthemeUpdateHelper::class)->update(
    $sandbox,
    'paragraph',
    array_keys($new_form_display_config),
    // Start callback.
    static function (CivicthemeUpdateHelper $helper) use ($new_field_configs): void {
      $config_path = \Drupal::service('extension.list.theme')->getPath('civictheme') . '/config/install';
      $helper->createConfigs($new_field_configs, $config_path);
    },
    // Process callback.
    static function (CivicthemeUpdateHelper $helper, FieldableEntityInterface $entity) use ($field_mapping): bool {
      return $helper->copyFieldContent($entity, $field_mapping);
    },
    // Finished callback.
    static function (CivicthemeUpdateHelper $helper) use ($old_field_configs, $new_form_display_config): TranslatableMarkup {
      $helper->deleteConfig($old_field_configs);
      foreach ($new_form_display_config as $bundle => $config) {
        $helper->updateFormDisplayConfig('paragraph', $bundle, $config);
      }

      return new TranslatableMarkup("Content from field 'field_c_p_summary' was moved to 'field_c_p_content'. The 'field_c_p_summary' field was removed from %paragraph_types paragraph types.\nPlease re-export your site configuration.\n", [
        '%paragraph_types' => implode(', ', array_keys($new_form_display_config)),
      ]);
    }
  );
}

/**
 * Renames 'Date' field in Event content type and updates content.
 */
function civictheme_post_update_rename_event_date_field(array &$sandbox): ?string {
  $field_mapping = [
    'field_c_n_date' => 'field_c_n_date_range',
  ];

  $old_field_configs = [
    'field.storage.node.field_c_n_date' => 'field_storage_config',
    'field.field.node.civictheme_event.field_c_n_date' => 'field_config',
  ];

  $new_field_configs = [
    'field.storage.node.field_c_n_date_range' => 'field_storage_config',
    'field.field.node.civictheme_event.field_c_n_date_range' => 'field_config',
  ];

  $new_form_display_config = [
    'civictheme_event' => [
      'field_c_n_date_range' => [
        'type' => 'daterange_default',
        'weight' => 13,
        'region' => 'content',
        'settings' => [],
        'third_party_settings' => [],
      ],
    ],
  ];

  $new_form_display_group_config = [
    'civictheme_event' => [
      'group_event' => [
        'field_c_n_date_range',
      ],
    ],
  ];

  return \Drupal::classResolver(CivicthemeUpdateHelper::class)->update(
    $sandbox,
    'node',
    ['civictheme_event'],
    // Start callback.
    static function (CivicthemeUpdateHelper $helper) use ($new_field_configs): void {
      $config_path = \Drupal::service('extension.list.theme')->getPath('civictheme') . '/config/install';
      $helper->createConfigs($new_field_configs, $config_path);
    },
    // Process callback.
    static function (CivicthemeUpdateHelper $helper, FieldableEntityInterface $entity) use ($field_mapping): bool {
      return $helper->copyFieldContent($entity, $field_mapping);
    },
    // Finished callback.
    static function (CivicthemeUpdateHelper $helper) use ($old_field_configs, $new_form_display_config, $new_form_display_group_config): TranslatableMarkup {
      $helper->deleteConfig($old_field_configs);
      foreach ($new_form_display_config as $bundle => $config) {
        $helper->updateFormDisplayConfig('node', $bundle, $config, $new_form_display_group_config[$bundle]);
      }

      return new TranslatableMarkup("Content from field 'field_c_n_date' was moved to 'field_c_n_date_range'. The 'field_c_n_date_range' field was removed from 'civictheme_event' node type.\nPlease re-export your site configuration.\n");
    }
  );
}

/**
 * Renames 'Banner blend mode' field in nodes and updates content.
 */
function civictheme_post_update_rename_node_banner_blend_mode(array &$sandbox): ?string {
  $field_mapping = [
    'field_c_n_blend_mode' => 'field_c_n_banner_blend_mode',
    'field_c_b_blend_mode' => 'field_c_b_banner_blend_mode',
  ];

  $old_field_configs = [
    'field.storage.node.field_c_n_blend_mode' => 'field_storage_config',
    'field.field.node.civictheme_page.field_c_n_blend_mode' => 'field_config',
  ];

  $new_field_configs = [
    'field.storage.node.field_c_n_banner_blend_mode' => 'field_storage_config',
    'field.field.node.civictheme_page.field_c_n_banner_blend_mode' => 'field_config',
  ];

  $new_form_display_config = [
    'civictheme_page' => [
      'field_c_n_banner_blend_mode' => [
        'type' => 'options_select',
        'weight' => 15,
        'region' => 'content',
        'settings' => [],
        'third_party_settings' => [],
      ],
    ],
  ];

  $new_form_display_group_config = [
    'civictheme_page' => [
      'group_banner_background' => [
        'field_c_n_banner_background',
        'field_c_n_banner_blend_mode',
      ],
    ],
  ];

  return \Drupal::classResolver(CivicthemeUpdateHelper::class)->update(
    $sandbox,
    'node',
    ['civictheme_page'],
    // Start callback.
    static function (CivicthemeUpdateHelper $helper) use ($new_field_configs): void {
      $config_path = \Drupal::service('extension.list.theme')->getPath('civictheme') . '/config/install';
      $helper->createConfigs($new_field_configs, $config_path);
    },
    // Process callback.
    static function (CivicthemeUpdateHelper $helper, FieldableEntityInterface $entity) use ($field_mapping): bool {
      return $helper->copyFieldContent($entity, $field_mapping);
    },
    // Finished callback.
    static function (CivicthemeUpdateHelper $helper) use ($old_field_configs, $new_form_display_config, $new_form_display_group_config): TranslatableMarkup {
      $helper->deleteConfig($old_field_configs);
      foreach ($new_form_display_config as $bundle => $config) {
        $helper->updateFormDisplayConfig('node', $bundle, $config, $new_form_display_group_config[$bundle]);
      }

      return new TranslatableMarkup("Content from field 'field_c_n_blend_mode' was moved to 'field_c_n_banner_blend_mode'.\nThe 'field_c_n_blend_mode' field was removed from 'civictheme_page' node type.\nPlease re-export your site configuration.\n");
    }
  );
}

/**
 * Renames 'Banner blend mode' field in blocks and updates content.
 */
function civictheme_post_update_rename_block_banner_blend_mode(array &$sandbox): ?string {
  $field_mapping = [
    'field_c_b_blend_mode' => 'field_c_b_banner_blend_mode',
  ];

  $old_field_configs = [
    'field.storage.block_content.field_c_b_blend_mode' => 'field_storage_config',
    'field.field.block_content.civictheme_banner.field_c_b_blend_mode' => 'field_config',
  ];

  $new_field_configs = [
    'field.storage.block_content.field_c_b_banner_blend_mode' => 'field_storage_config',
    'field.field.block_content.civictheme_banner.field_c_b_banner_blend_mode' => 'field_config',
  ];

  $new_form_display_config = [
    'civictheme_banner' => [
      'field_c_b_banner_blend_mode' => [
        'type' => 'options_select',
        'weight' => 4,
        'region' => 'content',
        'settings' => [],
        'third_party_settings' => [],
      ],
    ],
  ];

  return \Drupal::classResolver(CivicthemeUpdateHelper::class)->update(
    $sandbox,
    'block_content',
    ['civictheme_banner'],
    // Start callback.
    static function (CivicthemeUpdateHelper $helper) use ($new_field_configs): void {
      $config_path = \Drupal::service('extension.list.theme')->getPath('civictheme') . '/config/install';
      $helper->createConfigs($new_field_configs, $config_path);
    },
    // Process callback.
    static function (CivicthemeUpdateHelper $helper, FieldableEntityInterface $entity) use ($field_mapping): bool {
      return $helper->copyFieldContent($entity, $field_mapping);
    },
    // Finished callback.
    static function (CivicthemeUpdateHelper $helper) use ($old_field_configs, $new_form_display_config): TranslatableMarkup {
      $helper->deleteConfig($old_field_configs);
      foreach ($new_form_display_config as $bundle => $config) {
        $helper->updateFormDisplayConfig('block_content', $bundle, $config);
      }

      return new TranslatableMarkup("Content from field 'field_c_b_blend_mode' was moved to 'field_c_b_banner_blend_mode'.\nThe 'field_c_b_blend_mode' field was removed from 'civictheme_banner' block type.\nPlease re-export your site configuration.\n");
    }
  );
}

/**
 * Disable the default moderated_content view.
 */
function civictheme_post_update_disable_moderated_content_view(): void {
  \Drupal::configFactory()
    ->getEditable('views.view.moderated_content')
    ->set('status', FALSE)
    ->save();
}

/**
 * Convert a quote component to a content component with markup.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function civictheme_post_update_convert_quote_to_content_component(array &$sandbox): ?string {
  $old_field_configs = [
    'field.storage.paragraph.field_c_p_author' => 'field_storage_config',
    'field.field.paragraph.civictheme_quote.field_c_p_author' => 'field_config',
    'field.field.paragraph.civictheme_quote.field_c_p_content' => 'field_config',
    'field.field.paragraph.civictheme_quote.field_c_p_theme' => 'field_config',
    'field.field.paragraph.civictheme_quote.field_c_p_vertical_spacing' => 'field_config',
    'core.entity_form_display.paragraph.civictheme_quote.default' => 'entity_form_display',
    'core.entity_view_display.paragraph.civictheme_quote.default' => 'entity_view_display',
    'paragraphs.paragraphs_type.civictheme_quote' => 'paragraphs_type',
  ];

  return \Drupal::classResolver(CivicthemeUpdateHelper::class)->update(
    $sandbox,
    'node',
    ['civictheme_page'],
    // Start callback.
    static function (CivicthemeUpdateHelper $helper): void {
      // Noop.
    },
    // Process callback.
    static function (CivicthemeUpdateHelper $helper, EntityInterface $entity): bool {
      if (!$entity instanceof Node) {
        return FALSE;
      }

      $updated = FALSE;

      // Update quote component to a content component.
      $field_name = 'field_c_n_components';
      $components = civictheme_get_field_value($entity, $field_name);

      if (!empty($components)) {
        foreach ($components as $key => $component) {
          if ($component->bundle() == 'civictheme_quote') {
            $content = civictheme_get_field_value($component, 'field_c_p_content', TRUE);
            $author = civictheme_get_field_value($component, 'field_c_p_author', TRUE);
            $paragraph_values = [
              'type' => 'civictheme_content',
              'field_c_p_content' => [
                'value' => '<blockquote>' . $content . (empty($author) ? '' : '<cite> ' . $author . ' </cite>') . '</blockquote>',
                'format' => 'civictheme_rich_text',
              ],
              'field_c_p_theme' => civictheme_get_field_theme_value($component),
              'field_c_p_vertical_spacing' => civictheme_get_field_value($component, 'field_c_p_vertical_spacing', TRUE, CivicthemeConstants::VERTICAL_SPACING_NONE),
            ];
            $paragraph = $helper->createContentParagraph($paragraph_values);
            if ($paragraph instanceof ParagraphInterface) {
              $components[$key] = $paragraph;
              $updated = TRUE;
            }
          }
        }
      }

      if ($updated) {
        $entity->set('field_c_n_components', $components);
        $entity->save();
      }

      return $updated;
    },
    // Finished callback.
    static function (CivicthemeUpdateHelper $helper) use ($old_field_configs): TranslatableMarkup {
      $helper->deleteConfig($old_field_configs);
      return new TranslatableMarkup("Updated quote component to a content component.\n");
    },
  );
}

/**
 * Add Background field to Promo Component.
 */
function civictheme_post_update_add_background_promo_component(array &$sandbox): ?string {
  $new_field_configs = [
    'field.field.paragraph.civictheme_promo.field_c_p_background' => 'field_config',
  ];

  $new_form_display_config = [
    'civictheme_promo' => [
      'field_c_p_background' => [
        'type' => 'boolean_checkbox',
        'weight' => 6,
        'region' => 'content',
        'settings' => [
          'display_label' => TRUE,
        ],
        'third_party_settings' => [],
      ],
    ],
  ];

  return \Drupal::classResolver(CivicthemeUpdateHelper::class)->update(
    $sandbox,
    'paragraph',
    ['civictheme_promo'],
    // Start callback.
    static function (CivicthemeUpdateHelper $helper) use ($new_field_configs): void {
      $config_path = \Drupal::service('extension.list.theme')->getPath('civictheme') . '/config/install';
      $helper->createConfigs($new_field_configs, $config_path);
    },
    // Process callback.
    static function (CivicthemeUpdateHelper $helper): void {
      // Noop.
    },
    // Finished callback.
    static function (CivicthemeUpdateHelper $helper) use ($new_form_display_config): TranslatableMarkup {
      foreach ($new_form_display_config as $bundle => $config) {
        $helper->updateFormDisplayConfig('paragraph', $bundle, $config);
      }

      return new TranslatableMarkup("Added Background field to Promo Component.\n");
    }
  );
}
