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
  if ($settings['content_type'] == 'civictheme_event') {
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

/**
 * Alter the Schema.org JSON-LD structured data graph before it is rendered.
 *
 * CivicTheme emits the properties that it can derive from its own fields. Use
 * this hook to add site-specific properties, to add additional graph nodes or
 * to remove the ones that are not wanted.
 *
 * The hook is only invoked when structured data is enabled in the theme
 * settings and the node bundle is mapped to a Schema.org type.
 *
 * @param array $graph
 *   The '@graph' array to alter, passed by reference. Graph nodes provided by
 *   CivicTheme are, in order: Organization, WebSite, the content entity
 *   (WebPage, Article family or Event) and, when available, BreadcrumbList.
 * @param array $context
 *   Context data with the following keys:
 *   - node: (NodeInterface) The node the graph is built for, in the language
 *     that is shown on the page.
 *   - route_match: (RouteMatchInterface) The current route match.
 *   - definition: (array) The type definition mapped to the node bundle.
 * @param array $build
 *   Render array to apply cacheability metadata to, passed by reference. Add
 *   the cacheability of any additional data used here so that the markup is
 *   invalidated with it.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function hook_civictheme_structured_data_alter(array &$graph, array $context, array &$build): void {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $context['node'];

  foreach ($graph as &$item) {
    // Only alter the node of the content entity itself.
    if (!str_ends_with((string) $item['@id'], '#article')) {
      continue;
    }

    // Keywords from the taxonomy fields of a custom content type. Passing
    // $build collects the cacheability of the referenced terms.
    $keywords = civictheme_get_referenced_entity_labels($node, 'field_program', $build);
    if ($keywords) {
      $item['keywords'] = array_values(array_unique($keywords));
    }

    // Publication status of a custom content type.
    $status = civictheme_get_referenced_entity_labels($node, 'field_status', $build);
    if ($status) {
      $item['creativeWorkStatus'] = reset($status);
    }

    // All content on this site is publicly available.
    $item['isAccessibleForFree'] = TRUE;
  }
}

/**
 * Alter the Schema.org types available for content type mapping.
 *
 * The types defined here appear in the content type mapping in the theme
 * settings and drive the markup that is emitted for the mapped bundles.
 *
 * @param array $types
 *   Type definitions keyed by the value stored in the theme settings. Each
 *   definition has the following keys:
 *   - label: (string) Human-readable label shown in the settings form.
 *   - types: (array) Schema.org types emitted as '@type'. A single type is
 *     emitted as a string, multiple types as an array.
 *   - fragment: (string) Fragment appended to the node URL to form the '@id'.
 *   - builder: (callable) Function that builds the graph node. It receives the
 *     node, the type definition and the render array to apply cacheability
 *     metadata to.
 */
function hook_civictheme_structured_data_types_alter(array &$types): void {
  // Add a type for a custom "dataset" content type, reusing the generic
  // CivicTheme builder for creative works.
  $types['Dataset'] = [
    'label' => 'Dataset',
    'types' => ['Dataset'],
    'fragment' => 'dataset',
    'builder' => '_civictheme_structured_data_creative_work',
  ];

  // Emit a bare Article instead of a Report for publications.
  $types['Report']['types'] = ['Article'];
}
