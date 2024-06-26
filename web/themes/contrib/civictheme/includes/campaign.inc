<?php

/**
 * @file
 * CivicTheme Campaign paragraph component.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_campaign(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__image($variables, CivicthemeConstants::CAMPAIGN_IMAGE_STYLE);
  _civictheme_preprocess_paragraph__paragraph_field__image_position($variables);
  _civictheme_preprocess_paragraph__paragraph_field__topics($variables);
  _civictheme_preprocess_paragraph__paragraph_field__date($variables);
  _civictheme_preprocess_paragraph__paragraph_field__content($variables);
  _civictheme_preprocess_paragraph__paragraph_field__links($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);
}
