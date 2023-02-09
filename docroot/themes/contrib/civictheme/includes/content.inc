<?php

/**
 * @file
 * CivicTheme Content paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_content(&$variables) {
  _civictheme_preprocess_paragraph__field__content($variables);
  _civictheme_preprocess_paragraph__field__theme($variables);
  _civictheme_preprocess_paragraph__field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__field__background($variables);
}
