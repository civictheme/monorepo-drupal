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
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $files = [];
  $attachments = civictheme_get_field_value($paragraph, 'field_c_p_attachments', build: $variables) ?? [];
  foreach ($attachments as $attachment) {
    $vars = civictheme_media_get_variables($attachment);
    if ($vars) {
      if (civictheme_get_theme_config_manager()->load('components.attachment.use_media_name', TRUE)) {
        $vars['name'] = $vars['media_name'];
      }
      $files[] = $vars;
    }
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:attachment',
    '#props' => array_filter([
      'theme' => $theme,
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title'),
      'content' => civictheme_get_wysiwyg_field_value($paragraph, 'field_c_p_content', TRUE, $theme ?? 'light'),
      'files' => $files ?: NULL,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing'),
      'with_background' => (bool) civictheme_get_field_value($paragraph, 'field_c_p_background'),
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
  ];
}
