<?php

/**
 * @file
 * Local tasks related functions.
 */

/**
 * Pre-process for local tasks.
 */
function _civictheme_preprocess_menu_local_tasks(&$variables) {
  $local_task_types = ['primary', 'secondary'];
  $context_theme = theme_get_setting('components.header.theme') ?? CIVICTHEME_HEADER_THEME_DEFAULT;

  foreach ($local_task_types as $local_task_type) {
    $variables['links'][$local_task_type] = [];
    foreach ($variables[$local_task_type] as $link) {
      if (!empty($link['#access']) && $link['#access']->isAllowed()) {
        $variables['links'][$local_task_type][] = [
          'theme' => $context_theme,
          'text' => $link['#link']['title'],
          'url' => $link['#link']['url']->toString(),
          'is_new_window' => FALSE,
          'is_external' => $link['#link']['url']->isExternal(),
          'modifier_class' => $link['#active'] ? 'selected' : '',
          'weight' => $link['#weight'] ?? 0,
        ];
      }
    }

    // Sort tabs by weight.
    array_multisort(array_column($variables['links'][$local_task_type], "text"), SORT_ASC, $variables['links'][$local_task_type]);
    unset($variables[$local_task_type]);
  }
}
