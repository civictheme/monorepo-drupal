<?php

/**
 * @file
 * Civic card task paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_card_task(&$variables) {
  $variables['size'] = 'small';

  // @todo Add icon support in backend as per the component.
  if (isset($variables['image'])) {
    unset($variables['image']);
  }
}
