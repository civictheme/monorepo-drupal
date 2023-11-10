<?php

/**
 * @file
 * CivicTheme Attachment paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_attachment(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__content($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__paragraph_field__background($variables);

  $attachments = civictheme_get_field_value($variables['paragraph'], 'field_c_p_attachments') ?? [];
  foreach ($attachments as $attachment) {
    $vars = civictheme_media_get_variables($attachment);
    if ($vars) {
      $variables['files'][] = $vars;
    }
  }
}
