<?php

/**
 * @file
 * CivicTheme Promo paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_promo(&$variables) {
  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__content($variables);
  _civictheme_preprocess_paragraph__field__link($variables);
  _civictheme_preprocess_paragraph__field__theme($variables);
  _civictheme_preprocess_paragraph__field__vertical_spacing($variables);
}
