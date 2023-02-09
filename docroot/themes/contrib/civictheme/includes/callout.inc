<?php

/**
 * @file
 * CivicTheme Callout paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_callout(&$variables) {
  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__content($variables);
  _civictheme_preprocess_paragraph__field__links($variables);
  _civictheme_preprocess_paragraph__field__theme($variables);
  _civictheme_preprocess_paragraph__field__vertical_spacing($variables);
}
