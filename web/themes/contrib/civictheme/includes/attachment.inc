<?php

/**
 * @file
 * CivicTheme Attachment paragraph component.
 */

declare(strict_types=1);

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
      if (civictheme_get_theme_config_manager()->load('components.attachment.use_media_name', TRUE)) {
        $vars['name'] = $vars['media_name'];
      }
      $variables['files'][] = $vars;
    }
  }
}
