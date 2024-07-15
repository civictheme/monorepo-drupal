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
 * Alter the info about the view used in the Automated list component.
 *
 * This hook allows to alter which view and display are used to power the
 * Automated list component based on the settings provided.
 * The settings are extracted from the fields provided by the CivicTheme within
 * the Automated list paragraph entity.
 * Note that for any custom fields added to the Automated list paragraph entity,
 * the settings would need to be extracted from those fields using
 * $settings['paragraph']->get('field_name')->getString() or similar methods as
 * CivicTheme cannot predict the field names used in the custom implementation.
 *
 * @param array $info
 *   View info array to alter passed by reference. Keys are:
 *   - view_name: (string) A view machine name.
 *   - display_name: (string) A view display machine name.
 * @param array $settings
 *   The Automated list component settings with the
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
 *   - paragraph: (Paragraph) The paragraph entity.
 */
function hook_civictheme_automated_list_view_info_alter(array &$info, array $settings): void {
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
 * Alter the view used in the Automated list component before rendering.
 *
 * The view instance already has settings applied from the default fields
 * provided by the CivicTheme within the Automated list paragraph entity.
 * Any additional settings can be extracted from the fields provided by the
 * Automated list paragraph entity using $view->component_settings['paragraph'].
 *
 * @param \Drupal\views\ViewExecutable $view
 *   The view object to alter.
 */
function hook_civictheme_automated_list_view_alter(ViewExecutable $view): void {
  // Example of altering the view theme, item theme and arguments.
  if ($view->id() === 'custom_view_id') {
    $view->component_settings['theme'] = CivicthemeConstants::THEME_LIGHT;
    $view->component_settings['item_theme'] = CivicthemeConstants::THEME_DARK;

    // Example of setting view arguments based on the expected contextual
    // filters of this specific view.
    // In thi example, the view has 3 contextual filters.
    $view_args = [];
    // First view argument - content types. Read from settings.
    $view_args[] = $view->component_settings['content_type'] ?? 'all';
    // Second view argument - Content ID value. Use `all` to skip it.
    $view_args[] = 'all';
    // Third view argument - Vertical Spacing value. Use a constant value.
    $view_args[] = CivicthemeConstants::VERTICAL_SPACING_TOP;
    // Set the arguments to the view.
    $view->setArguments($view_args);
  }
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
