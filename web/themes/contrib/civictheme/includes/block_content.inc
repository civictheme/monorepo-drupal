<?php

/**
 * @file
 * Block content related functions.
 */

declare(strict_types=1);

/**
 * Implements template_preprocess_block__HOOK().
 */
function civictheme_preprocess_block__block_content(array &$variables): void {
  if ($variables['elements']['#configuration']['label'] === 'Copyright') {
    $variables['attributes']['class'][] = 'copyright';
    $variables['attributes']['class'][] = 'ct-text-regular';
  }
}
