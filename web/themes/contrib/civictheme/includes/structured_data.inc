<?php

/**
 * @file
 * Schema.org JSON-LD structured data.
 *
 * Emits a single <script type="application/ld+json"> element containing an.
 * @graph on canonical, published node pages of the mapped bundles.
 *
 * The feature is disabled by default and is enabled per-site in the theme
 * settings, where each node bundle is mapped to a Schema.org type.
 *
 * Site-specific additions (extra properties, additional graph nodes, custom
 * types) are made from a sub-theme or a module via
 * hook_civictheme_structured_data_alter() and
 * hook_civictheme_structured_data_types_alter().
 *
 * @see civictheme.api.php
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\media\MediaInterface;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Attaches the JSON-LD structured data to the page head.
 *
 * Called from civictheme_page_attachments_alter().
 *
 * @param array<string, mixed> $attachments
 *   Page attachments as provided by hook_page_attachments_alter().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_page_attachments_alter__structured_data(array &$attachments): void {
  if (!civictheme_get_theme_config_manager()->load('structured_data.enabled', FALSE)) {
    return;
  }

  /** @var \Drupal\Core\Routing\RouteMatchInterface $route_match */
  $route_match = \Drupal::service('current_route_match');
  if ($route_match->getRouteName() !== 'entity.node.canonical') {
    return;
  }

  $node = $route_match->getParameter('node');
  if (!$node instanceof NodeInterface || !$node->isPublished()) {
    return;
  }

  // Use the translation that is shown on the page so that the emitted values
  // match the rendered content.
  $node = \Drupal::service('entity.repository')->getTranslationFromContext($node);
  if (!$node instanceof NodeInterface) {
    return;
  }

  // Seed a render array for the civictheme_* field helpers to attach cacheable
  // metadata to (referenced media/terms). A non-empty array also avoids their
  // deprecated no-$build code path.
  $build = ['#cache' => []];
  $graph = _civictheme_structured_data_build($node, $route_match, $build);
  if (empty($graph)) {
    return;
  }

  $json = json_encode([
    '@context' => 'https://schema.org',
    '@graph' => array_values($graph),
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS);

  if ($json === FALSE) {
    \Drupal::logger('civictheme')->error('Unable to encode structured data for node @nid.', [
      '@nid' => $node->id(),
    ]);

    return;
  }

  $attachments['#attached']['html_head'][] = [
    [
      '#type' => 'html_tag',
      '#tag' => 'script',
      '#attributes' => ['type' => 'application/ld+json'],
      '#value' => $json,
    ],
    'civictheme_structured_data',
  ];

  // Merge cacheability: existing attachments + the node + the referenced
  // media/terms and the breadcrumb collected on $build (so the markup
  // invalidates when any of them changes, including an image or its alt text)
  // + the theme settings that drive the output.
  $cache = CacheableMetadata::createFromRenderArray($attachments);
  $cache->addCacheableDependency($node);
  // merge() returns a new instance rather than mutating, so capture it.
  $cache = $cache->merge(CacheableMetadata::createFromRenderArray($build));
  $cache->addCacheableDependency(\Drupal::config(\Drupal::theme()->getActiveTheme()->getName() . '.settings'));
  $cache->applyTo($attachments);
}

/**
 * Builds the JSON-LD @graph for a node.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node to build the graph for.
 * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
 *   The current route match, used to build the breadcrumb.
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 *
 * @return array<int, array<string, mixed>>
 *   The graph nodes or an empty array if the bundle is not mapped.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_structured_data_build(NodeInterface $node, RouteMatchInterface $route_match, array &$build): array {
  $definition = _civictheme_structured_data_node_type_definition($node->bundle());
  if (!$definition) {
    return [];
  }

  $graph = [
    _civictheme_structured_data_organization(),
    _civictheme_structured_data_website(),
  ];

  $breadcrumb = _civictheme_structured_data_breadcrumb($node, $route_match, $build);

  $entity = $definition['builder']($node, $definition, $build);
  if ($breadcrumb) {
    $entity['breadcrumb'] = ['@id' => $breadcrumb['@id']];
  }
  $graph[] = $entity;

  if ($breadcrumb) {
    $graph[] = $breadcrumb;
  }

  $context = [
    'node' => $node,
    'route_match' => $route_match,
    'definition' => $definition,
  ];
  \Drupal::moduleHandler()->alter('civictheme_structured_data', $graph, $context, $build);
  \Drupal::service('theme.manager')->alter('civictheme_structured_data', $graph, $context, $build);

  return $graph;
}

/**
 * Definitions of the Schema.org types available for bundle mapping.
 *
 * The array key is the value stored in the theme settings.
 *
 * @return array<string, array<string, mixed>>
 *   Type definitions keyed by the setting value. Each definition has keys:
 *   - label: (string) Human-readable label used in the settings form.
 *   - types: (array) Schema.org types to emit as '@type'.
 *   - fragment: (string) Fragment appended to the node URL to form the '@id'.
 *   - builder: (callable) Function building the graph node.
 */
function _civictheme_structured_data_type_definitions(): array {
  return [
    'WebPage' => [
      'label' => 'Web page',
      'types' => ['WebPage'],
      'fragment' => 'webpage',
      'builder' => '_civictheme_structured_data_webpage',
    ],
    'Article' => [
      'label' => 'Article',
      'types' => ['Article'],
      'fragment' => 'article',
      'builder' => '_civictheme_structured_data_creative_work',
    ],
    'NewsArticle' => [
      'label' => 'News article',
      'types' => ['NewsArticle', 'Article'],
      'fragment' => 'article',
      'builder' => '_civictheme_structured_data_creative_work',
    ],
    'BlogPosting' => [
      'label' => 'Blog posting',
      'types' => ['BlogPosting', 'Article'],
      'fragment' => 'article',
      'builder' => '_civictheme_structured_data_creative_work',
    ],
    'Report' => [
      'label' => 'Report',
      'types' => ['Report', 'Article'],
      'fragment' => 'article',
      'builder' => '_civictheme_structured_data_creative_work',
    ],
    'Event' => [
      'label' => 'Event',
      'types' => ['Event'],
      'fragment' => 'event',
      'builder' => '_civictheme_structured_data_event',
    ],
  ];
}

/**
 * Schema.org type definitions after alterations.
 *
 * @return array<string, array<string, mixed>>
 *   Type definitions keyed by the setting value.
 *
 * @see _civictheme_structured_data_type_definitions()
 * @see hook_civictheme_structured_data_types_alter()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_structured_data_types(): array {
  $types = _civictheme_structured_data_type_definitions();

  \Drupal::moduleHandler()->alter('civictheme_structured_data_types', $types);
  \Drupal::service('theme.manager')->alter('civictheme_structured_data_types', $types);

  return array_filter($types, static function (array $definition): bool {
    return !empty($definition['types']) && !empty($definition['builder']) && is_callable($definition['builder']);
  });
}

/**
 * The type definition mapped to a node bundle in the theme settings.
 *
 * @param string $bundle
 *   The node bundle.
 *
 * @return array<string, mixed>|null
 *   The type definition or NULL if the bundle is not mapped.
 */
function _civictheme_structured_data_node_type_definition(string $bundle): ?array {
  $bundles = civictheme_get_theme_config_manager()->load('structured_data.bundles', []);
  $type = is_array($bundles) ? ($bundles[$bundle] ?? NULL) : NULL;

  if (empty($type) || !is_string($type)) {
    return NULL;
  }

  $definition = _civictheme_structured_data_types()[$type] ?? NULL;

  return $definition ? $definition + ['fragment' => 'webpage'] : NULL;
}

/**
 * Sitewide base URL (no trailing slash), used to namespace '@id' values.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_structured_data_base_url(): string {
  $request = \Drupal::request();

  return rtrim($request->getSchemeAndHttpHost() . $request->getBasePath(), '/');
}

/**
 * Converts a root-relative URL into an absolute one.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_structured_data_absolute_url(string $url): string {
  if (preg_match('#^(https?:)?//#', $url)) {
    return $url;
  }

  // Root-relative URLs already contain the base path, so only the scheme and
  // the host are prepended.
  return \Drupal::request()->getSchemeAndHttpHost() . '/' . ltrim($url, '/');
}

/**
 * The publishing Organization. Reused as publisher and author by '@id'.
 *
 * @return array<string, mixed>
 *   The Organization graph node.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_structured_data_organization(): array {
  $base = _civictheme_structured_data_base_url();

  $data = [
    '@type' => 'Organization',
    '@id' => $base . '/#organization',
    'name' => \Drupal::config('system.site')->get('name'),
    'url' => $base . '/',
  ];

  $logo = _civictheme_structured_data_logo();
  if ($logo) {
    $data['logo'] = $logo;
  }

  $same_as = civictheme_get_theme_config_manager()->load('structured_data.organization.same_as', []);
  if (is_array($same_as) && !empty($same_as)) {
    $data['sameAs'] = array_values($same_as);
  }

  return $data;
}

/**
 * The Organization logo as an ImageObject.
 *
 * @return array<string, mixed>|null
 *   The ImageObject or NULL if no logo path is configured.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_structured_data_logo(): ?array {
  $config = civictheme_get_theme_config_manager();

  $path = trim((string) $config->load('structured_data.organization.logo_path', ''));
  if ($path === '') {
    $path = trim((string) $config->load('components.logo.primary.light.desktop.path', ''));
  }

  if ($path === '') {
    return NULL;
  }

  $path = ltrim($path, '/');

  $logo = [
    '@type' => 'ImageObject',
    'url' => Url::fromUri('base:' . $path, ['absolute' => TRUE])->toString(),
  ];

  // Dimensions are only available for raster images (SVG is not supported by
  // image toolkits) and are optional in the Organization markup.
  $image = \Drupal::service('image.factory')->get(\Drupal::root() . '/' . $path);
  if ($image->isValid()) {
    $logo['width'] = $image->getWidth();
    $logo['height'] = $image->getHeight();
  }

  return $logo;
}

/**
 * The WebSite node, linked to the Organization as publisher.
 *
 * @return array<string, mixed>
 *   The WebSite graph node.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_structured_data_website(): array {
  $base = _civictheme_structured_data_base_url();

  return [
    '@type' => 'WebSite',
    '@id' => $base . '/#website',
    'name' => \Drupal::config('system.site')->get('name'),
    'url' => $base . '/',
    'publisher' => ['@id' => $base . '/#organization'],
  ];
}

/**
 * Properties shared by all mapped node types.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node.
 * @param array<string, mixed> $definition
 *   The type definition.
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 *
 * @return array<string, mixed>
 *   The graph node.
 */
function _civictheme_structured_data_node(NodeInterface $node, array $definition, array &$build): array {
  $base = _civictheme_structured_data_base_url();
  $url = $node->toUrl('canonical', ['absolute' => TRUE])->toString();
  $types = array_values((array) $definition['types']);
  // The created field value is a string when the node is loaded from storage.
  $published = (int) $node->getCreatedTime();

  $data = [
    '@type' => count($types) > 1 ? $types : reset($types),
    '@id' => $url . '#' . $definition['fragment'],
    'name' => trim((string) $node->label()),
    'url' => $url,
    'mainEntityOfPage' => $url,
    'inLanguage' => $node->language()->getId(),
    'datePublished' => civictheme_format_datetime_iso($published),
    'dateModified' => _civictheme_structured_data_modified($node, $published),
    'isPartOf' => ['@id' => $base . '/#website'],
    'publisher' => ['@id' => $base . '/#organization'],
  ];

  $description = _civictheme_structured_data_description($node);
  if ($description !== '') {
    $data['description'] = $description;
  }

  $about = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics', $build);
  if (!empty($about)) {
    $data['about'] = array_values($about);
  }

  return $data;
}

/**
 * WebPage mapping.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node.
 * @param array<string, mixed> $definition
 *   The type definition.
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 *
 * @return array<string, mixed>
 *   The graph node.
 */
function _civictheme_structured_data_webpage(NodeInterface $node, array $definition, array &$build): array {
  $data = _civictheme_structured_data_node($node, $definition, $build);

  $image = _civictheme_structured_data_image($node, $build);
  if ($image) {
    $data['primaryImageOfPage'] = $image;
  }

  return $data;
}

/**
 * Article, NewsArticle, BlogPosting and Report mapping.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node.
 * @param array<string, mixed> $definition
 *   The type definition.
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 *
 * @return array<string, mixed>
 *   The graph node.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_structured_data_creative_work(NodeInterface $node, array $definition, array &$build): array {
  $data = _civictheme_structured_data_node($node, $definition, $build);

  $base = _civictheme_structured_data_base_url();
  $data['headline'] = _civictheme_structured_data_truncate((string) $node->label(), CivicthemeConstants::STRUCTURED_DATA_HEADLINE_LENGTH);
  $data['author'] = ['@id' => $base . '/#organization'];

  $image = _civictheme_structured_data_image($node, $build);
  if ($image) {
    $data['image'] = $image;
  }

  return $data;
}

/**
 * Event mapping.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node.
 * @param array<string, mixed> $definition
 *   The type definition.
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 *
 * @return array<string, mixed>
 *   The graph node.
 */
function _civictheme_structured_data_event(NodeInterface $node, array $definition, array &$build): array {
  $data = _civictheme_structured_data_node($node, $definition, $build);

  $date = civictheme_get_field_value($node, 'field_c_n_date_range', TRUE);
  if ($date) {
    $start = $date->get('value')->getDateTime();
    if ($start) {
      $data['startDate'] = civictheme_format_datetime_iso($start);
    }
    $end = $date->get('end_value')->getDateTime();
    if ($end) {
      $data['endDate'] = civictheme_format_datetime_iso($end);
    }
  }

  $location = _civictheme_structured_data_event_location($node, $build);
  if ($location) {
    $data['location'] = $location;
  }

  $image = _civictheme_structured_data_image($node, $build);
  if ($image) {
    $data['image'] = $image;
  }

  return $data;
}

/**
 * Event location as a Place, sourced from the Map location paragraph.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node.
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 *
 * @return array<string, mixed>|null
 *   The Place or NULL if no address is available.
 */
function _civictheme_structured_data_event_location(NodeInterface $node, array &$build): ?array {
  $paragraphs = civictheme_get_field_value($node, 'field_c_n_location', FALSE, [], $build);

  foreach ((array) $paragraphs as $paragraph) {
    if (!$paragraph instanceof ParagraphInterface || $paragraph->bundle() !== 'civictheme_map') {
      continue;
    }

    $address = trim((string) civictheme_get_field_value($paragraph, 'field_c_p_address', FALSE, ''));
    if ($address === '') {
      continue;
    }

    return [
      '@type' => 'Place',
      'address' => $address,
    ];
  }

  return NULL;
}

/**
 * BreadcrumbList for the current route.
 *
 * Mirrors the breadcrumb rendered by the Banner component: the links provided
 * by the breadcrumb builders, followed by the current page when it is not
 * already the last item.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node.
 * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
 *   The current route match.
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 *
 * @return array<string, mixed>|null
 *   The BreadcrumbList or NULL if the breadcrumb is unavailable or has fewer
 *   than two items.
 *
 * @see _civictheme_preprocess_block__civictheme_banner__breadcrumb()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function _civictheme_structured_data_breadcrumb(NodeInterface $node, RouteMatchInterface $route_match, array &$build): ?array {
  // The breadcrumb is not rendered on the front page.
  if (\Drupal::service('path.matcher')->isFrontPage()) {
    return NULL;
  }

  try {
    $breadcrumb = \Drupal::service('breadcrumb')->build($route_match);
  }
  catch (\Exception $exception) {
    // A failing breadcrumb builder must not break the page: the rest of the
    // graph is still valid without a BreadcrumbList.
    \Drupal::logger('civictheme')->warning('Unable to build the structured data breadcrumb: @message', [
      '@message' => $exception->getMessage(),
    ]);

    return NULL;
  }

  // Capture the breadcrumb's cacheability (e.g. route/path contexts) so the
  // rendered markup invalidates with it.
  CacheableMetadata::createFromRenderArray($build)
    ->merge(CacheableMetadata::createFromObject($breadcrumb))
    ->applyTo($build);

  $items = [];
  $last_text = '';
  foreach ($breadcrumb->getLinks() as $link) {
    $item = _civictheme_structured_data_breadcrumb_item($link, count($items) + 1);
    if ($item) {
      $items[] = $item;
      $last_text = $item['name'];
    }
  }

  // Append the current page, unless a breadcrumb builder already did so.
  $title = trim((string) $node->label());
  if ($title !== '' && $title !== $last_text) {
    $items[] = [
      '@type' => 'ListItem',
      'position' => count($items) + 1,
      'name' => $title,
    ];
  }

  if (count($items) < 2) {
    return NULL;
  }

  return [
    '@type' => 'BreadcrumbList',
    '@id' => $node->toUrl('canonical', ['absolute' => TRUE])->toString() . '#breadcrumb',
    'itemListElement' => $items,
  ];
}

/**
 * A single BreadcrumbList item built from a breadcrumb link.
 *
 * @param \Drupal\Core\Link $link
 *   The breadcrumb link.
 * @param int $position
 *   The 1-based position of the item in the list.
 *
 * @return array<string, mixed>|null
 *   The ListItem or NULL if the link has no text.
 */
function _civictheme_structured_data_breadcrumb_item(Link $link, int $position): ?array {
  $text = _civictheme_structured_data_link_text($link->getText());
  if ($text === '') {
    return NULL;
  }

  $item = [
    '@type' => 'ListItem',
    'position' => $position,
    'name' => $text,
  ];

  // Omit 'item' on routeless links (e.g. the current page) - an empty string is
  // invalid, while a name-only last entry is allowed.
  try {
    $url = $link->getUrl()->setAbsolute()->toString();
    if ($url !== '') {
      $item['item'] = $url;
    }
  }
  catch (\Exception) {
    // Routeless link - name only.
  }

  return $item;
}

/**
 * Plain text of a breadcrumb link.
 *
 * @param mixed $link_text
 *   The link text, which may be a string, a markup object or a render array.
 *
 * @return string
 *   The plain text, or an empty string when there is none.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_structured_data_link_text(mixed $link_text): string {
  if (is_array($link_text)) {
    // Rendering in isolation keeps any cacheable metadata of the render array
    // from leaking into the page head.
    $link_text = \Drupal::service('renderer')->renderInIsolation($link_text);
  }

  return trim(strip_tags(html_entity_decode((string) $link_text)));
}

/**
 * Featured image as an ImageObject.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node.
 * @param array<string, mixed> $build
 *   Render array to apply cacheability metadata to.
 *
 * @return array<string, mixed>|null
 *   The ImageObject or NULL if the node has no usable image.
 */
function _civictheme_structured_data_image(NodeInterface $node, array &$build): ?array {
  $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE, NULL, $build);
  if (!$media instanceof MediaInterface) {
    return NULL;
  }

  $image_style = trim((string) civictheme_get_theme_config_manager()->load('structured_data.image_style', CivicthemeConstants::SOCIAL_SHARE_IMAGE_STYLE));

  $variables = civictheme_media_image_get_variables($media, $image_style !== '' ? $image_style : NULL);
  if (!is_array($variables) || empty($variables['url'])) {
    return NULL;
  }

  $image = [
    '@type' => 'ImageObject',
    'url' => _civictheme_structured_data_absolute_url((string) $variables['url']),
  ];

  // Image styles are not applied to SVG images.
  if (($variables['ext'] ?? '') === 'svg') {
    $image_style = '';
  }

  return $image + _civictheme_structured_data_image_dimensions($media, $image_style);
}

/**
 * Dimensions of the media image after the image style is applied.
 *
 * The dimensions are derived from the stored source dimensions rather than from
 * the derivative file, so no derivative generation is triggered.
 *
 * @param \Drupal\media\MediaInterface $media
 *   The image media entity.
 * @param string $image_style
 *   The image style name or an empty string for the original image.
 *
 * @return array<string, int>
 *   Array with 'width' and 'height' keys, or an empty array if the dimensions
 *   are not available.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function _civictheme_structured_data_image_dimensions(MediaInterface $media, string $image_style): array {
  $values = _civictheme_structured_data_image_values($media);

  $dimensions = _civictheme_structured_data_source_dimensions($values);
  if ($dimensions === []) {
    return [];
  }

  $style = $image_style === '' ? NULL : ImageStyle::load($image_style);
  if ($style) {
    $style->transformDimensions($dimensions, _civictheme_structured_data_file_uri($values));
  }

  // Some image effects cannot predict the resulting dimensions and set them to
  // NULL - such values are omitted rather than emitted as zeroes.
  return array_filter($dimensions);
}

/**
 * The stored dimensions of an image field item.
 *
 * @param array<string, mixed> $values
 *   The image field item values.
 *
 * @return array<string, int>
 *   Array with 'width' and 'height' keys, or an empty array when either of the
 *   dimensions is missing.
 */
function _civictheme_structured_data_source_dimensions(array $values): array {
  $width = (int) ($values['width'] ?? 0);
  $height = (int) ($values['height'] ?? 0);

  return $width > 0 && $height > 0 ? ['width' => $width, 'height' => $height] : [];
}

/**
 * Values of the source field item of an image media entity.
 *
 * @param \Drupal\media\MediaInterface $media
 *   The image media entity.
 *
 * @return array<string, mixed>
 *   The field item values or an empty array if there is no image.
 */
function _civictheme_structured_data_image_values(MediaInterface $media): array {
  $source_field = $media->getSource()->getConfiguration()['source_field'] ?? NULL;
  if (!$source_field || !civictheme_field_has_value($media, $source_field)) {
    return [];
  }

  $item = $media->get($source_field)->first();

  return $item ? $item->getValue() : [];
}

/**
 * The file URI referenced by the image field item values.
 *
 * @param array<string, mixed> $values
 *   The image field item values.
 *
 * @return string
 *   The file URI or an empty string if the file is not available.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_structured_data_file_uri(array $values): string {
  $file = empty($values['target_id']) ? NULL : File::load($values['target_id']);

  return $file instanceof FileInterface ? (string) $file->getFileUri() : '';
}

/**
 * The 'dateModified' value, clamped to never precede the published date.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node.
 * @param int $published
 *   The published timestamp.
 *
 * @return string
 *   The date as an ISO 8601 date or date and time string.
 */
function _civictheme_structured_data_modified(NodeInterface $node, int $published): string {
  $last_updated = NULL;
  if (civictheme_field_has_value($node, 'field_c_n_custom_last_updated')) {
    $item = civictheme_get_field_value($node, 'field_c_n_custom_last_updated', TRUE);
    $last_updated = $item?->value;
  }

  $value = _civictheme_structured_data_modified_value(
    is_string($last_updated) ? $last_updated : NULL,
    (int) $node->getChangedTime(),
    $published
  );

  return is_int($value) ? civictheme_format_datetime_iso($value) : $value;
}

/**
 * Resolves the 'dateModified' value from the available dates.
 *
 * The "last updated" field is date-only, so on the day a node is first
 * published its midnight value would sort before the timestamped
 * 'datePublished', which search engines flag as an error. When that happens the
 * published date is used instead, so that modified equals published.
 *
 * @param string|null $last_updated
 *   The value of the custom "last updated" field, if any.
 * @param int $changed
 *   The node changed timestamp.
 * @param int $published
 *   The published timestamp.
 *
 * @return int|string
 *   A timestamp to format, or the "last updated" value as-is when it is a valid
 *   ISO 8601 date that does not precede the published date.
 */
function _civictheme_structured_data_modified_value(?string $last_updated, int $changed, int $published): int|string {
  if ($last_updated !== NULL && trim($last_updated) !== '') {
    $timestamp = strtotime($last_updated);

    return $timestamp !== FALSE && $timestamp >= $published ? $last_updated : $published;
  }

  return max($changed, $published);
}

/**
 * Plain-text description sourced from the summary field.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node.
 *
 * @return string
 *   The description or an empty string if the node has no summary.
 */
function _civictheme_structured_data_description(NodeInterface $node): string {
  // The summary is a 'string_long' field, so civictheme_get_field_value()
  // returns the value with tags already stripped.
  $summary = trim((string) civictheme_get_field_value($node, 'field_c_n_summary', FALSE, ''));
  if ($summary === '') {
    return '';
  }

  $length = (int) civictheme_get_theme_config_manager()->load('structured_data.description_length', CivicthemeConstants::STRUCTURED_DATA_DESCRIPTION_LENGTH);

  return _civictheme_structured_data_truncate($summary, $length);
}

/**
 * Truncates text to the given length, appending an ellipsis when trimmed.
 *
 * @param string $text
 *   The text to truncate.
 * @param int $length
 *   The maximum length. Values below 1 disable truncation.
 *
 * @return string
 *   The truncated text.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_structured_data_truncate(string $text, int $length): string {
  $text = trim($text);

  if ($length < 1 || mb_strlen($text) <= $length) {
    return $text;
  }

  return Unicode::truncate($text, $length, TRUE, TRUE);
}
