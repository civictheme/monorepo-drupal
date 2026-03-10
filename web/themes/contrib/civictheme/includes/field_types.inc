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
use Drupal\file\FileInterface;
use Drupal\media\MediaInterface;

/**
 * Get a CivicTheme layout builder setting from field variables.
 *
 * Reads from the formatter's third party settings set via layout builder
 * block configuration.
 *
 * @param array $variables
 *   The field template variables.
 * @param string $key
 *   The setting key (e.g. 'theme', 'vertical_spacing', 'with_background').
 *
 * @return mixed|null
 *   The setting value, or NULL if not set or not enabled.
 */
function _civictheme_field_get_layout_builder_setting(array $variables, string $key): mixed {
  $settings = $variables['element']['#third_party_settings']['civictheme'] ?? NULL;
  if (!$settings || empty($settings['enabled'])) {
    return NULL;
  }

  return $settings[$key] ?? NULL;
}

/**
 * Resolve theme from parent entity for field preprocessing.
 *
 * Checks layout builder settings first, then falls back to the parent
 * entity's theme field to determine light/dark theme.
 *
 * @param array $variables
 *   The field template variables.
 *
 * @return string
 *   The resolved theme value ('light' or 'dark').
 */
function _civictheme_field_get_theme(array $variables): string {
  $lb_theme = _civictheme_field_get_layout_builder_setting($variables, 'theme');
  if ($lb_theme) {
    return (string) $lb_theme;
  }

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
 * Resolve vertical spacing for field preprocessing.
 *
 * Checks layout builder settings first, then falls back to the parent
 * entity's vertical spacing field.
 *
 * @param array $variables
 *   The field template variables.
 *
 * @return string|null
 *   The vertical spacing value ('top', 'bottom', 'both'), or NULL.
 */
function _civictheme_field_get_vertical_spacing(array $variables): ?string {
  $lb_value = _civictheme_field_get_layout_builder_setting($variables, 'vertical_spacing');
  if ($lb_value && $lb_value !== 'none') {
    return (string) $lb_value;
  }

  $entity = $variables['element']['#object'] ?? NULL;

  if (!$entity instanceof FieldableEntityInterface) {
    return NULL;
  }

  $fields = [
    'node' => ['field_c_n_vertical_spacing'],
    'paragraph' => ['field_c_p_vertical_spacing'],
  ];

  foreach ($fields[$entity->getEntityTypeId()] ?? [] as $field_name) {
    $value = civictheme_get_field_value($entity, $field_name, TRUE);
    if (in_array($value, ['top', 'bottom', 'both'], TRUE)) {
      return (string) $value;
    }
  }

  return NULL;
}

/**
 * Resolve background setting for field preprocessing.
 *
 * Checks layout builder settings first, then falls back to the parent
 * entity's background field.
 *
 * @param array $variables
 *   The field template variables.
 *
 * @return bool
 *   TRUE if a background should be applied.
 */
function _civictheme_field_get_with_background(array $variables): bool {
  $lb_value = _civictheme_field_get_layout_builder_setting($variables, 'with_background');
  if ($lb_value !== NULL) {
    return !empty($lb_value);
  }

  $entity = $variables['element']['#object'] ?? NULL;

  if (!$entity instanceof FieldableEntityInterface) {
    return FALSE;
  }

  $fields = [
    'paragraph' => ['field_c_p_background'],
  ];

  foreach ($fields[$entity->getEntityTypeId()] ?? [] as $field_name) {
    $value = civictheme_get_field_value($entity, $field_name, TRUE);
    if ($value) {
      return TRUE;
    }
  }

  return FALSE;
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
  $vertical_spacing = _civictheme_field_get_vertical_spacing($variables);
  $with_background = _civictheme_field_get_with_background($variables);

  foreach ($variables['items'] as &$item) {
    $item['content'] = [
      '#type' => 'component',
      '#component' => 'civictheme:basic-content',
      '#props' => [
        'theme' => $theme,
        'vertical_spacing' => $vertical_spacing,
        'with_background' => $with_background,
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
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_field__wrap_tag_list(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);
  $vertical_spacing = _civictheme_field_get_vertical_spacing($variables);
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
        'vertical_spacing' => $vertical_spacing,
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
  foreach ($variables['items'] as $delta => &$item) {
    $element = $variables['element'][$delta] ?? [];
    $item['content'] = [
      '#type' => 'component',
      '#component' => 'civictheme:datetime',
      '#props' => [
        'start' => $element['#text'] ?? '',
        'start_iso' => $element['#attributes']['datetime'] ?? '',
      ],
    ];
  }
}

/**
 * Implements hook_preprocess_field__daterange().
 */
function civictheme_preprocess_field__daterange(array &$variables): void {
  foreach ($variables['items'] as $delta => &$item) {
    $element = $variables['element'][$delta] ?? [];
    $props = [
      'start' => $element['start_date']['#text'] ?? '',
      'start_iso' => $element['start_date']['#attributes']['datetime'] ?? '',
    ];
    if (!empty($element['end_date']['#text'])) {
      $props['end'] = $element['end_date']['#text'];
      $props['end_iso'] = $element['end_date']['#attributes']['datetime'] ?? '';
    }
    $item['content'] = [
      '#type' => 'component',
      '#component' => 'civictheme:datetime',
      '#props' => $props,
    ];
  }
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
 *
 * Renders link fields using civictheme:link. Multi-value fields are wrapped
 * in a civictheme:item-list component.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_field__link(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);

  $links = [];
  foreach ($variables['items'] as $delta => $item) {
    $element = $variables['element'][$delta] ?? [];

    $url = $element['#url'] ?? NULL;
    $title = $element['#title'] ?? '';
    $url_string = $url instanceof Url ? $url->toString() : '';
    $is_external = $url instanceof Url ? $url->isExternal() : FALSE;

    $links[] = [
      '#type' => 'component',
      '#component' => 'civictheme:link',
      '#props' => [
        'theme' => $theme,
        'text' => $title,
        'url' => $url_string,
        'is_external' => $is_external,
        'is_new_window' => $is_external,
      ],
    ];
  }

  // Single link: render directly without item-list wrapper.
  if (count($links) === 1) {
    $inner = $links[0];
  }
  else {
    // Multiple links: render each, then wrap in an item-list.
    $direction = _civictheme_field_get_layout_builder_setting($variables, 'direction') ?? 'vertical';
    $size = _civictheme_field_get_layout_builder_setting($variables, 'size') ?? 'regular';
    $no_gap = !empty(_civictheme_field_get_layout_builder_setting($variables, 'no_gap'));

    $rendered_links = [];
    foreach ($links as $link) {
      $rendered_links[] = \Drupal::service('renderer')->renderInIsolation($link);
    }

    $inner = [
      '#type' => 'component',
      '#component' => 'civictheme:item-list',
      '#props' => [
        'direction' => $direction,
        'size' => $size,
        'no_gap' => $no_gap,
        'items' => $rendered_links,
      ],
    ];
  }

  $vertical_spacing = _civictheme_field_get_vertical_spacing($variables);
  $with_background = _civictheme_field_get_with_background($variables);

  $variables['items'] = [[
    'content' => [
      '#type' => 'component',
      '#component' => 'civictheme:basic-content',
      '#props' => [
        'theme' => $theme,
        'vertical_spacing' => $vertical_spacing,
        'with_background' => $with_background,
      ],
      '#slots' => [
        'content' => $inner,
      ],
    ],
    'attributes' => $variables['items'][0]['attributes'] ?? [],
  ]];
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
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_field__file(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);
  $vertical_spacing = _civictheme_field_get_vertical_spacing($variables);
  $with_background = _civictheme_field_get_with_background($variables);
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
        'vertical_spacing' => $vertical_spacing,
        'with_background' => $with_background,
        'files' => $files,
      ],
    ],
    'attributes' => $variables['items'][0]['attributes'] ?? [],
  ],
  ];
}

/**
 * Card view modes and their default column counts.
 *
 * @return array<string, int>
 *   Map of view mode machine name to default column count.
 */
function _civictheme_card_view_modes(): array {
  return [
    'civictheme_promo_card' => 3,
    'civictheme_navigation_card' => 3,
    'civictheme_subject_card' => 4,
    'civictheme_snippet' => 1,
  ];
}

/**
 * Detect the entity view mode from a field's render element.
 *
 * @param array $variables
 *   The field template variables.
 *
 * @return string|null
 *   The view mode, or NULL if not detectable.
 */
function _civictheme_field_get_entity_view_mode(array $variables): ?string {
  foreach ($variables['element'] as $key => $child) {
    if (!is_numeric($key) || !is_array($child)) {
      continue;
    }
    if (isset($child['#view_mode'])) {
      return $child['#view_mode'];
    }
  }

  return NULL;
}

/**
 * Check if a field's entity view mode is a known card type.
 *
 * @param array $variables
 *   The field template variables.
 *
 * @return bool
 *   TRUE if the field renders entities in a card view mode.
 */
function _civictheme_field_is_card_view_mode(array $variables): bool {
  $view_mode = _civictheme_field_get_entity_view_mode($variables);

  return $view_mode !== NULL && isset(_civictheme_card_view_modes()[$view_mode]);
}

/**
 * Wrap entity reference items in a card list when rendered as cards.
 *
 * When the formatter view mode is a known card type, collects rendered items
 * into $variables['rows'] and sets grid properties. The template
 * field--civictheme-card-list.html.twig renders these in a civictheme:list
 * with a civictheme:grid.
 *
 * @see field--civictheme-card-list.html.twig
 */
function _civictheme_preprocess_field__wrap_entity_reference(array &$variables): void {
  $view_mode = _civictheme_field_get_entity_view_mode($variables);
  if (!$view_mode || !isset(_civictheme_card_view_modes()[$view_mode])) {
    return;
  }

  $variables['theme'] = _civictheme_field_get_theme($variables);
  $variables['vertical_spacing'] = _civictheme_field_get_vertical_spacing($variables);
  $variables['with_background'] = _civictheme_field_get_with_background($variables);

  // Column count and fill width from layout builder settings, falling back
  // to defaults per card view mode.
  $lb_column_count = _civictheme_field_get_layout_builder_setting($variables, 'column_count');
  $variables['column_count'] = $lb_column_count ? (int) $lb_column_count : _civictheme_card_view_modes()[$view_mode];
  $lb_fill_width = _civictheme_field_get_layout_builder_setting($variables, 'fill_width');
  $variables['fill_width'] = $lb_fill_width !== NULL ? !empty($lb_fill_width) : FALSE;

  $items = [];
  foreach ($variables['items'] as $item) {
    $items[] = $item['content'];
  }
  $variables['items'] = $items;
}

/**
 * Implements hook_preprocess_field__entity_reference().
 *
 * Renders media entity references using CivicTheme SDC components when
 * the formatter view mode is 'component'.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_field__entity_reference(array &$variables): void {
  $view_mode = _civictheme_field_get_entity_view_mode($variables);
  if ($view_mode !== 'component') {
    return;
  }

  $items = $variables['element']['#items'] ?? NULL;
  if (!$items) {
    return;
  }

  $entity = $items->first()?->entity ?? NULL;
  if (!$entity instanceof MediaInterface) {
    return;
  }

  $bundle = $entity->bundle();
  $handlers = [
    'civictheme_image' => '_civictheme_preprocess_field__media_image',
    'civictheme_video' => '_civictheme_preprocess_field__media_video',
    'civictheme_remote_video' => '_civictheme_preprocess_field__media_remote_video',
  ];

  if (isset($handlers[$bundle])) {
    $handlers[$bundle]($variables);
  }
}

/**
 * Preprocess media image fields into civictheme:figure components.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_field__media_image(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);
  $vertical_spacing = _civictheme_field_get_vertical_spacing($variables);
  $with_background = _civictheme_field_get_with_background($variables);
  $image_factory = \Drupal::service('image.factory');
  $file_url_generator = \Drupal::service('file_url_generator');

  $figures = [];
  foreach ($variables['element']['#items'] as $item) {
    $media = $item->entity;
    if (!$media instanceof MediaInterface) {
      continue;
    }

    $image_file = civictheme_get_field_referenced_entity($media, 'field_c_m_image');
    if (!$image_file instanceof FileInterface) {
      continue;
    }

    $image = $image_factory->get($image_file->getFileUri());
    if (!$image->isValid()) {
      continue;
    }

    $image_url = $file_url_generator->generateString($image_file->getFileUri());
    $caption = $media->get('field_c_m_caption')->getString();

    $figure = [
      '#type' => 'component',
      '#component' => 'civictheme:figure',
      '#props' => [
        'theme' => $theme,
        'url' => $file_url_generator->transformRelative($image_url),
        'alt' => $media->get('field_c_m_image')->first()->get('alt')->getString(),
        'width' => (string) $image->getWidth(),
        'height' => (string) $image->getHeight(),
      ],
    ];

    if (!empty($caption)) {
      $figure['#slots']['caption'] = ['#markup' => $caption];
    }

    $figures[] = $figure;
  }

  if (empty($figures)) {
    return;
  }

  $variables['items'] = [[
    'content' => [
      '#type' => 'component',
      '#component' => 'civictheme:basic-content',
      '#props' => [
        'theme' => $theme,
        'vertical_spacing' => $vertical_spacing,
        'with_background' => $with_background,
      ],
      '#slots' => [
        'content' => $figures,
      ],
    ],
    'attributes' => $variables['items'][0]['attributes'] ?? [],
  ]];
}

/**
 * Preprocess media video fields into civictheme:video-player components.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_field__media_video(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);
  $vertical_spacing = _civictheme_field_get_vertical_spacing($variables);
  $with_background = _civictheme_field_get_with_background($variables);

  $players = [];
  foreach ($variables['element']['#items'] as $item) {
    $media = $item->entity;
    if (!$media instanceof MediaInterface) {
      continue;
    }

    $sources = [];
    foreach ($media->get('field_c_m_video_file')->referencedEntities() as $video_file) {
      if (!$video_file instanceof FileInterface) {
        continue;
      }

      $sources[] = [
        'url' => $video_file->createFileUrl(),
        'type' => $video_file->getMimeType(),
      ];
    }

    if (empty($sources)) {
      continue;
    }

    $players[] = [
      '#type' => 'component',
      '#component' => 'civictheme:video-player',
      '#props' => [
        'theme' => $theme,
        'sources' => $sources,
      ],
    ];
  }

  if (empty($players)) {
    return;
  }

  $variables['items'] = [[
    'content' => [
      '#type' => 'component',
      '#component' => 'civictheme:basic-content',
      '#props' => [
        'theme' => $theme,
        'vertical_spacing' => $vertical_spacing,
        'with_background' => $with_background,
      ],
      '#slots' => [
        'content' => $players,
      ],
    ],
    'attributes' => $variables['items'][0]['attributes'] ?? [],
  ]];
}

/**
 * Preprocess media remote video fields into civictheme:video-player components.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function _civictheme_preprocess_field__media_remote_video(array &$variables): void {
  $theme = _civictheme_field_get_theme($variables);
  $vertical_spacing = _civictheme_field_get_vertical_spacing($variables);
  $with_background = _civictheme_field_get_with_background($variables);

  $players = [];
  foreach ($variables['element']['#items'] as $item) {
    $media = $item->entity;
    if (!$media instanceof MediaInterface) {
      continue;
    }

    try {
      $oembed_field = $media->get('field_c_m_oembed_video')->first();
      if (!$oembed_field) {
        continue;
      }

      $resource_url = \Drupal::service('media.oembed.url_resolver')
        ->getResourceUrl($oembed_field->getValue()['value']);
      $resource = \Drupal::service('media.oembed.resource_fetcher')
        ->fetchResource($resource_url);

      $html = $resource->getHtml();

      // Set 'title' attribute on the oEmbed iframe element.
      $doc = _civictheme_get_dom_document($html);
      $iframe_elements = $doc->getElementsByTagName('iframe');
      if (!empty($iframe_elements[0])) {
        $iframe_elements[0]->setAttribute('title', $media->getName());
        $html = $doc->saveHTML($iframe_elements[0]);
      }

      $players[] = [
        '#type' => 'component',
        '#component' => 'civictheme:video-player',
        '#props' => [
          'theme' => $theme,
          'raw_source' => $html,
        ],
      ];
    }
    catch (\Exception) {
      // Skip media items that fail oEmbed resolution.
    }
  }

  if (empty($players)) {
    return;
  }

  $variables['items'] = [[
    'content' => [
      '#type' => 'component',
      '#component' => 'civictheme:basic-content',
      '#props' => [
        'theme' => $theme,
        'vertical_spacing' => $vertical_spacing,
        'with_background' => $with_background,
      ],
      '#slots' => [
        'content' => $players,
      ],
    ],
    'attributes' => $variables['items'][0]['attributes'] ?? [],
  ]];
}
