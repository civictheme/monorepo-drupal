<?php

/**
 * @file
 * CivicTheme Campaign paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_campaign(&$variables) {
  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__image($variables);
  _civictheme_preprocess_paragraph__field__image_position($variables);
  _civictheme_preprocess_paragraph__field__topics($variables);
  _civictheme_preprocess_paragraph__field__date($variables);
  _civictheme_preprocess_paragraph__field__content($variables);
  _civictheme_preprocess_paragraph__field__links($variables);
  _civictheme_preprocess_paragraph__field__theme($variables);
  _civictheme_preprocess_paragraph__field__vertical_spacing($variables);
}
