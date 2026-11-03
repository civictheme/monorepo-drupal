<?php

declare(strict_types=1);

namespace Drupal\civictheme\Utility;

use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\node\NodeInterface;

/**
 * Node helpers.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
final class CivicthemeNode {

  /**
   * Load node or the node revision if the route is a node revision.
   */
  public static function loadNodeOrRevision(array $variables): ?NodeInterface {
    $node = $variables['node'] ?? NULL;

    if (is_numeric($node)) {
      // Check to see if it's a node revision route.
      /** @var \Drupal\Core\Routing\RouteMatchInterface $route_match */
      $route_match = \Drupal::service('current_route_match');
      $node_revision = $route_match->getParameter('node_revision');
      if ($node_revision instanceof NodeInterface) {
        $node = $node_revision;
      }
    }

    return $node;
  }

  /**
   * Determine last updated date for a page.
   */
  public static function getUpdatedDate(NodeInterface $node, string $format = 'civictheme_short_date'): ?string {
    $show_last_updated = CivicthemeField::getValue($node, 'field_c_n_show_last_updated');
    $custom_updated_value = CivicthemeField::getValue($node, 'field_c_n_custom_last_updated', TRUE);

    $date = NULL;

    if ($show_last_updated && !empty($custom_updated_value)) {
      $date = CivicthemeDate::format($custom_updated_value->get('value')->getDateTime(), $format);
    }
    elseif ($show_last_updated) {
      $date = CivicthemeDate::format($node->getChangedTime(), $format);
    }

    return $date;
  }

  /**
   * Get Layout Builder settings for all view modes of an entity.
   *
   * @param string $entity_type_id
   *   Entity type ID.
   * @param string $bundle
   *   Bundle name.
   * @param bool $only_enabled
   *   (optional) Whether to return settings only for when the Layout Builder
   *   is enabled for the view mode. Defaults to TRUE.
   *
   * @return array<string, mixed>
   *   An array of Layout Builder settings keyed by view mode.
   *
   * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
   */
  public static function getLayoutBuilderSettingsPerViewMode(string $entity_type_id, string $bundle, $only_enabled = TRUE): array {
    $settings_per_mode = [];

    /** @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository */
    $entity_display_repository = \Drupal::service('entity_display.repository');
    $view_modes = array_keys($entity_display_repository->getViewModes($entity_type_id));
    array_unshift($view_modes, 'default');

    foreach ($view_modes as $view_mode) {
      $view_display = EntityViewDisplay::load("$entity_type_id.$bundle.$view_mode");
      if ($view_display) {
        $lb_settings = $view_display->getThirdPartySettings('layout_builder');
        if ($only_enabled && empty($lb_settings['enabled'])) {
          continue;
        }

        $settings_per_mode[$view_mode] = $lb_settings;
      }
    }

    return $settings_per_mode;
  }

}
