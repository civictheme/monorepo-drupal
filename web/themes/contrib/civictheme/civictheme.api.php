<?php

/**
 * @file
 * Hooks related to the CivicTheme theme.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Cache\Cache;
use Drupal\views\ViewExecutable;

/**
 * Alter the view info used in the Automated list component.
 *
 * @param array $info
 *   View info array to alter passed by reference. Keys are:
 *   - view_name: (string) A view machine name.
 *   - display_name: (string) A view display machine name.
 * @param array $settings
 *   The Automated list component settings passed by reference with the
 *   following keys:
 *   - title: (string) List title.
 *   - type: (string) List type (view name that powers Automated list).
 *   - content_type: (string) Content type to filter by.
 *   - limit: (int) Results limit.
 *   - limit_type: (string) Results limit type: 'limited' or 'unlimited'.
 *   - filters_exp: (array) Array of exposed filter names.
 *   - view_as: (string) The name of the view mode of the result item.
 *   - theme: (string) The view theme: light or dark.
 *   - item_theme: (string) The name of the theme for an item: light or dark.
 *   - topics: (array) Array of Topic entities.
 *   - site_sections: (array) Array of Site section entities.
 *   - cache_tags: (array) Array of the cache tags.
 */
function hook_civictheme_automated_list_view_info_alter(array &$info, array &$settings): void {
  // Change the view name and block based on the conditions set in the
  // Automated list settings.
  if ($settings['content_type'] == 'event') {
    // Use a custom display name for 'Event' content type.
    $info['display_name'] = 'my_custom_block_1';
  }
  elseif ($settings['content_type'] == 'profile') {
    // Use a custom view for a custom content type 'profile' (which is not a
    // part of CivicTheme) with a 'default' display name (implicitly).
    $info['view_name'] = 'my_custom_view';
  }
}

/**
 * Alter the CivicTheme view preprocess settings.
 *
 * @param array $variables
 *   Array of preprocess variables of the Automated list view.
 */
function hook_civictheme_automated_list_preprocess_view_alter(array &$variables, ViewExecutable &$view): void {
  if ($view->id() == 'civictheme_view_examples') {
    $variables['theme'] = CivicthemeConstants::THEME_DARK;
    $variables['with_background'] = TRUE;
    $variables['vertical_spacing'] = 'both';
  }

  /**
   * Allow to suppress page regions for pages with Layout Builder enabled.
   *
   * @param array $variables
   *   Array of variables passed to the page template.
   * @param array $context
   *   Array of context data.
   *   - node: The node object.
   *   - layout_builder_settings_per_view_mode: An array of the layout builder
   *     settings keyed by view mode.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  function hook_civictheme_layout_suppress_page_regions_alter(array &$variables, array $context): void {
    /** @var \Drupal\node\NodeInterface $node */
    $node = $variables['node'];
    if ($node->bundle() == 'civictheme_page' && $context['layout_builder_settings_per_view_mode']['full']['enabled']) {
      $variables['page']['sidebar_top_left'] = [];
      $variables['page']['sidebar_bottom_left'] = [];
      $variables['page']['sidebar_top_right'] = [];
      $variables['page']['sidebar_bottom_right'] = [];

      // Do not forget to merge the cache contexts.
      $variables['#cache']['contexts'] = Cache::mergeContexts(
        $variables['#cache']['contexts'] ?? [],
        [
          'user.roles:authenticated',
        ]
      );
    }
  }

}
