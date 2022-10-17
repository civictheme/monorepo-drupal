<?php

/**
 * @file
 * Breadcrumb-related functions.
 */

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Link;

/**
 * Pre-process for breadcrumb.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_civictheme_breadcrumb(&$variables) {
  // Enforce active to never be a link.
  $variables['theme'] = $variables['display_theme'] ?? CivicthemeConstants::THEME_LIGHT;

  // Append the current page title to the breadcrumb, unless it is already
  // added.
  $current_route_match = \Drupal::routeMatch();

  if (!empty($current_route_match->getRouteObject())) {
    $title = \Drupal::service('title_resolver')->getTitle(\Drupal::request(), $current_route_match->getRouteObject());
    if (!empty($title)) {
      $link = Link::createFromRoute($title, '<none>');
      $last_link = end($variables['links']);
      if ($last_link instanceof Link && ($last_link->getText() != $link->getText() || $last_link->getUrl() != $last_link->getUrl())) {
        $variables['links'][] = $link;
        $variables['active_is_link'] = FALSE;
      }
    }
  }
}
