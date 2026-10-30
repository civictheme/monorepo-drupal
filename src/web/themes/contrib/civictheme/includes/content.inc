<?php

/**
 * @file
 * CivicTheme Content paragraph component.
 */

declare(strict_types=1);

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_content(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__content($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__paragraph_field__background($variables);
}
