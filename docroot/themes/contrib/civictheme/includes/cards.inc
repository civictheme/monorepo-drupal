<?php

/**
 * @file
 * Card theme alterations.
 */

function civictheme_preprocess_paragraph__civictheme_promo_card(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'image',
    'title',
    'summary',
    'link',
    'topics',
    'theme',
  ]);

  $variables['subtitle'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_subtitle', TRUE);
}

