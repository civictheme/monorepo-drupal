<?php

/**
 * @file
 * Field type preprocessing for CivicTheme components.
 *
 * Maps Drupal field types to CivicTheme SDC components via per-type preprocess
 * functions. Uses Drupal core's field type theme suggestions from
 * system_theme_suggestions_field() which adds 'field__<field_type>'
 * suggestions,
 * enabling these preprocess functions automatically.
 *
 * Sub-themes can override any preprocess function or drop in a
 * field--<type>.html.twig template to customise rendering per field type.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\StringTranslation\ByteSizeMarkup;
use Drupal\Core\Url;

/**
 * Resolve theme from parent entity for field preprocessing.
 *
 * Checks the parent entity's theme field to determine light/dark theme.
 *
 * @param array $variables
 *   The field template variables.
 *
 * @return string
 *   The resolved theme value ('light' or 'dark').
 */
function _civictheme_field_get_theme(array $variables): string {
  $entity = $variables['element']['#object'] ?? NULL;

  if (!$entity instanceof FieldableEntityInterface) {
    return CivicthemeConstants::THEME_LIGHT;
  }

  $theme_fields = [
    'node' => ['field_c_n_theme', 'field_c_n_banner_theme'],
    'paragraph' => ['field_c_p_theme'],
    'block_content' => ['field_c_b_theme'],
    'taxonomy_term' => ['field_c_t_theme'],
  ];

  foreach ($theme_fields[$entity->getEntityTypeId()] ?? [] as $field_name) {
    $value = civictheme_get_field_value($entity, $field_name, TRUE);
    if ($value) {
      return (string) $value;
    }
  }

  return CivicthemeConstants::THEME_LIGHT;
}

/**
 * Wrap field items in civictheme:paragraph component.
 */
function _civictheme_preprocess_field__wrap_paragraph(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);

  foreach ($variables['items'] as &$item) {
    $item['content'] = [
      '#type' => 'component',
      '#component' => 'civictheme:paragraph',
      '#props' => [
        'theme' => $theme,
      ],
      '#slots' => [
        'content' => $item['content'],
      ],
    ];
  }
}

/**
 * Wrap field items in civictheme:basic-content component.
 */
function _civictheme_preprocess_field__wrap_basic_content(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);

  foreach ($variables['items'] as &$item) {
    $item['content'] = [
      '#type' => 'component',
      '#component' => 'civictheme:basic-content',
      '#props' => [
        'theme' => $theme,
      ],
      '#slots' => [
        'content' => $item['content'],
      ],
    ];
  }
}

/**
 * Collect field items into a single civictheme:tag-list component.
 *
 * Drupal's list formatter already resolves keys to human-readable labels.
 */
function _civictheme_preprocess_field__wrap_tag_list(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);
  $tags = [];

  foreach ($variables['items'] as $item) {
    $rendered = \Drupal::service('renderer')->renderInIsolation($item['content']);
    $tags[] = ['content' => trim((string) $rendered)];
  }

  $variables['items'] = [[
    'content' => [
      '#type' => 'component',
      '#component' => 'civictheme:tag-list',
      '#props' => [
        'theme' => $theme,
        'tags' => $tags,
      ],
    ],
    'attributes' => $variables['items'][0]['attributes'] ?? [],
  ],
  ];
}

/**
 * Implements hook_preprocess_field__string().
 */
function civictheme_preprocess_field__string(array &$variables): void {
  _civictheme_preprocess_field__wrap_paragraph($variables);
}

/**
 * Implements hook_preprocess_field__string_long().
 */
function civictheme_preprocess_field__string_long(array &$variables): void {
  _civictheme_preprocess_field__wrap_paragraph($variables);
}

/**
 * Implements hook_preprocess_field__boolean().
 */
function civictheme_preprocess_field__boolean(array &$variables): void {
  _civictheme_preprocess_field__wrap_paragraph($variables);
}

/**
 * Implements hook_preprocess_field__integer().
 */
function civictheme_preprocess_field__integer(array &$variables): void {
  _civictheme_preprocess_field__wrap_paragraph($variables);
}

/**
 * Implements hook_preprocess_field__datetime().
 */
function civictheme_preprocess_field__datetime(array &$variables): void {
  _civictheme_preprocess_field__wrap_paragraph($variables);
}

/**
 * Implements hook_preprocess_field__daterange().
 */
function civictheme_preprocess_field__daterange(array &$variables): void {
  _civictheme_preprocess_field__wrap_paragraph($variables);
}

/**
 * Implements hook_preprocess_field__text_long().
 */
function civictheme_preprocess_field__text_long(array &$variables): void {
  _civictheme_preprocess_field__wrap_basic_content($variables);
}

/**
 * Implements hook_preprocess_field__text_with_summary().
 */
function civictheme_preprocess_field__text_with_summary(array &$variables): void {
  _civictheme_preprocess_field__wrap_basic_content($variables);
}

/**
 * Implements hook_preprocess_field__list_string().
 */
function civictheme_preprocess_field__list_string(array &$variables): void {
  _civictheme_preprocess_field__wrap_tag_list($variables);
}

/**
 * Implements hook_preprocess_field__list_integer().
 */
function civictheme_preprocess_field__list_integer(array &$variables): void {
  _civictheme_preprocess_field__wrap_tag_list($variables);
}

/**
 * Implements hook_preprocess_field__link().
 */
function civictheme_preprocess_field__link(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);

  foreach ($variables['items'] as $delta => &$item) {
    $element = $variables['element'][$delta] ?? [];

    $url = $element['#url'] ?? NULL;
    $title = $element['#title'] ?? '';
    $url_string = $url instanceof Url ? $url->toString() : '';
    $is_external = $url instanceof Url ? $url->isExternal() : FALSE;

    $item['content'] = [
      '#type' => 'component',
      '#component' => 'civictheme:content-link',
      '#props' => [
        'theme' => $theme,
        'text' => $title,
        'url' => $url_string,
        'is_external' => $is_external,
        'is_new_window' => $is_external,
      ],
    ];
  }
}

/**
 * Implements hook_preprocess_field__email().
 */
function civictheme_preprocess_field__email(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);

  foreach ($variables['items'] as $delta => &$item) {
    $email = $variables['element'][$delta]['#title']
      ?? $variables['element']['#items'][$delta]->value
      ?? '';

    $item['content'] = [
      '#type' => 'component',
      '#component' => 'civictheme:link',
      '#props' => [
        'theme' => $theme,
        'text' => $email,
        'url' => 'mailto:' . $email,
      ],
    ];
  }
}

/**
 * Implements hook_preprocess_field__file().
 */
function civictheme_preprocess_field__file(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);
  $files = [];
  $file_url_generator = \Drupal::service('file_url_generator');
  $date_formatter = \Drupal::service('date.formatter');

  foreach ($variables['element']['#items'] as $item) {
    $file = $item->entity;
    if (!$file) {
      continue;
    }

    $files[] = [
      'name' => $file->getFilename(),
      'ext' => pathinfo($file->getFilename(), PATHINFO_EXTENSION),
      'url' => $file_url_generator->generateAbsoluteString($file->getFileUri()),
      'size' => ByteSizeMarkup::create($file->getSize()),
      'changed' => $date_formatter->format($file->getChangedTime(), 'civictheme_short_date'),
    ];
  }

  $variables['items'] = [[
    'content' => [
      '#type' => 'component',
      '#component' => 'civictheme:attachment',
      '#props' => [
        'theme' => $theme,
        'files' => $files,
      ],
    ],
    'attributes' => $variables['items'][0]['attributes'] ?? [],
  ],
  ];
}
