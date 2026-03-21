<?php

/**
 * @file
 * System main block theme alterations.
 */

declare(strict_types=1);

/**
 * Implements template_preprocess_block__HOOK().
 */
function civictheme_preprocess_block__system_main_block(array &$variables): void {
  // Add the most generic class to make text appear styled.
  $variables['attributes']['class'][] = 'ct-text-regular';
  $variables['modifier_class'] = FALSE;
}
