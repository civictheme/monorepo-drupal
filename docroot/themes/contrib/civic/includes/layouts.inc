<?php

/**
 * @file
 * Layout preprocessing.
 */

/**
 * Implements template_preprocess_layout().
 */
function civic_preprocess_layout(&$variables) {
  $variables['contained'] = $variables['layout']->id() === 'civic_one_column_contained';
  $variables['modifier_class'] = $variables['contained'] ? 'col-m-12' : '';
}
