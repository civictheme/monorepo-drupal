<?php

/**
 * @file
 * Create generated Page nodes.
 */

declare(strict_types=1);

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
use Drupal\node\NodeInterface;

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_node_civictheme_page_weight(): int {
  return 32;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_node_civictheme_page_tracking(): bool {
  return TRUE;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE().
 *
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function cs_generated_content_generated_content_create_node_civictheme_page(): array {
  $nodes = [];

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  // Some components require to reference existing nodes. Since variation
  // evaluation happens before such nodes created, we need to pre-create them
  // in a preceding pass.
  for ($pass = 0; $pass < 2; $pass++) {
    // First pass - provide variations from the generated content pages.
    if ($pass === 0) {
      require_once __DIR__ . DIRECTORY_SEPARATOR . 'civictheme_page_variations/01.helpers.inc';
      for ($i = 0; $i < 20; $i++) {
        $variations[] = [
          'title' => sprintf('Page %s. %s', str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT), $helper::staticSentence(3)),
          'thumbnail' => $helper::staticMediaItem('civictheme_image'),
          'summary' => 'Summary ' . $helper::staticSentence(100),
          'topics' => $helper::staticTerms('civictheme_topics', 1),
          'components' => [
            [
              'type' => 'content',
              'content' => $helper::staticRichText(3),
            ],
          ],
        ];
      }
      $variations = _cs_generated_content_create_node_civictheme_page__variations__post_process($variations);
    }
    else {
      // Second pass - provide variations from the variation callbacks.
      $variations = $helper::variationFetchAll(
        '_cs_generated_content_create_node_civictheme_page__variations__',
        __DIR__ . DIRECTORY_SEPARATOR . 'civictheme_page_variations',
      );
    }

    foreach ($variations as $i => $variation) {
      /** @var \Drupal\node\Entity\Node $node */
      $node = $helper::variationCreateNode('civictheme_page', $variation, $i, '_cs_generated_content_create_node_civictheme_page__variation_to_fields');

      $variation_info = $helper::variationFormatInfo($variation);
      // @phpstan-ignore-next-line
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
 * Processor to convert variation values to 'civictheme_page' node fields.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function _cs_generated_content_create_node_civictheme_page__variation_to_fields(NodeInterface $node, array $variation): void {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  if (!empty($variation['title'])) {
    $node->setTitle($variation['title']);
  }

  if (!empty($variation['alias'])) {
    // Replace tokens in an alias.
    $variation['alias'] = $helper::replaceTokens($variation['alias'], $variation, static function ($value) {
      return is_string($value) ? \Drupal::service('pathauto.alias_cleaner')->cleanString($value) : $value;
    });

    $node->set('path', [
      'pathauto' => FALSE,
      'alias' => $variation['alias'],
    ]);
  }

  // General.
  if (!empty($variation['summary']) && $node->hasField('field_c_n_summary')) {
    $node->set('field_c_n_summary', $variation['summary']);
  }

  if (!empty($variation['thumbnail']) && $node->hasField('field_c_n_thumbnail')) {
    $node->set('field_c_n_thumbnail', $variation['thumbnail']);
  }

  if (!empty($variation['topics']) && $node->hasField('field_c_n_topics')) {
    $node->set('field_c_n_topics', $variation['topics']);
  }

  if (!empty($variation['site_section']) && $node->hasField('field_c_n_site_section')) {
    $node->set('field_c_n_site_section', $variation['site_section']);
  }

  if (isset($variation['vertical_spacing']) && $node->hasField('field_c_n_vertical_spacing')) {
    $node->set('field_c_n_vertical_spacing', $variation['vertical_spacing']);
  }

  if (isset($variation['show_last_updated']) && $node->hasField('field_c_n_show_last_updated')) {
    $node->set('field_c_n_show_last_updated', $variation['show_last_updated']);
  }

  if (isset($variation['custom_updated_date']) && $node->hasField('field_c_n_custom_last_updated')) {
    $node->set('field_c_n_custom_last_updated', $variation['custom_updated_date']);
  }

  // Banner.
  if (!empty($variation['banner_type']) && $node->hasField('field_c_n_banner_type')) {
    $node->set('field_c_n_banner_type', $variation['banner_type']);
  }

  if (!empty($variation['banner_theme']) && $node->hasField('field_c_n_banner_theme')) {
    $node->set('field_c_n_banner_theme', $variation['banner_theme']);
  }

  if (!empty($variation['banner_background']) && $node->hasField('field_c_n_banner_background')) {
    $node->set('field_c_n_banner_background', $variation['banner_background']);
  }

  if (!empty($variation['blend_mode']) && $node->hasField('field_c_n_banner_blend_mode')) {
    $node->set('field_c_n_banner_blend_mode', $variation['blend_mode']);
  }

  if (!empty($variation['banner_featured_image']) && $node->hasField('field_c_n_banner_featured_image')) {
    $node->set('field_c_n_banner_featured_image', $variation['banner_featured_image']);
  }

  if (!empty($variation['banner_hide_breadcrumb']) && $node->hasField('field_c_n_banner_hide_breadcrumb')) {
    $node->set('field_c_n_banner_hide_breadcrumb', $variation['banner_hide_breadcrumb']);
  }

  if (!empty($variation['banner_title']) && $node->hasField('field_c_n_banner_title')) {
    $node->set('field_c_n_banner_title', $variation['banner_title']);
  }

  // Components.
  if (!empty($variation['components']) && $node->hasField('field_c_n_components')) {
    $counter = count($variation['components']);
    for ($i = 0; $i < $counter; $i++) {
      // Extract component type.
      $component_type = $variation['components'][$i]['type'];
      // Remove component type and passthrough properties.
      $component_options = array_diff_key($variation['components'][$i], ['type' => TRUE]);
      $helper::civicthemeComponentAttach($node, 'field_c_n_components', $component_type, $component_options);
    }
  }

  // Banner Components.
  if (!empty($variation['banner_components']) && $node->hasField('field_c_n_banner_components')) {
    $counter = count($variation['banner_components']);
    for ($i = 0; $i < $counter; $i++) {
      // Extract component type.
      $component_type = $variation['banner_components'][$i]['type'];
      // Remove component type and passthrough properties.
      $component_options = array_diff_key($variation['banner_components'][$i], ['type' => TRUE]);
      $helper::civicthemeComponentAttach($node, 'field_c_n_banner_components', $component_type, $component_options);
    }
  }

  // Banner Components Bottom.
  if (!empty($variation['banner_components_bott']) && $node->hasField('field_c_n_banner_components_bott')) {
    $counter = count($variation['banner_components_bott']);
    for ($i = 0; $i < $counter; $i++) {
      // Extract component type.
      $component_type = $variation['banner_components_bott'][$i]['type'];
      // Remove component type and passthrough properties.
      $component_options = array_diff_key($variation['banner_components_bott'][$i], ['type' => TRUE]);
      $helper::civicthemeComponentAttach($node, 'field_c_n_banner_components_bott', $component_type, $component_options);
    }
  }
}
