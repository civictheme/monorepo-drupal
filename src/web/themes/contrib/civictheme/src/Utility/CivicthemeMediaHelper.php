<?php

declare(strict_types=1);

namespace Drupal\civictheme\Utility;

use Drupal\Core\File\Exception\InvalidStreamWrapperException;
use Drupal\Core\StringTranslation\ByteSizeMarkup;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\media\MediaInterface;
use Drupal\media\Plugin\media\Source\OEmbedInterface;

/**
 * Media and file helpers.
 *
 * The procedural civictheme_media_*() functions delegate here.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class CivicthemeMediaHelper {

  /**
   * Get variables from media.
   *
   * @param \Drupal\media\MediaInterface $media
   *   Host Media entity for image.
   *
   * @return array<string, mixed>|null
   *   Variables array or NULL if not available.
   */
  public static function getVariables(MediaInterface $media): ?array {
    $label = $media->label();
    $variables = [
      'media_name' => t('@name', ['@name' => $label]),
      'created' => CivicthemeDateHelper::format((int) $media->getCreatedTime(), 'civictheme_short_date'),
      'changed' => CivicthemeDateHelper::format($media->getChangedTime(), 'civictheme_short_date'),
    ];
    $source = $media->getSource();
    if ($source instanceof OEmbedInterface) {
      $variables['name'] = t('@name', ['@name' => $label]);
      $url = $source->getMetadata($media, 'url');
      if (!empty($url)) {
        $variables['url'] = $url;
      }
      return $variables;
    }

    $fid = $media->getSource()->getSourceFieldValue($media);
    /** @var \Drupal\file\FileInterface|null $file */
    $file = $fid ? File::load($fid) : NULL;

    if (empty($file)) {
      return NULL;
    }

    $variables += [
      'name' => t('@name', ['@name' => $file->label()]),
      'ext' => pathinfo((string) $file->getFileUri(), PATHINFO_EXTENSION) ?: '',
      'url' => static::getUrl($media),
      'size' => ByteSizeMarkup::create($file->getSize()),
      'icon' => static::getIconFromFile($file),
    ];

    return $variables;
  }

  /**
   * Get variables from Image media.
   *
   * @param \Drupal\media\MediaInterface $media
   *   Host Media entity for image.
   * @param string|null $image_style
   *   Name of the image style to apply.
   *
   * @return array<string, mixed>|null
   *   Image variables array or NULL if not available.
   */
  public static function getImageVariables(MediaInterface $media, ?string $image_style = NULL): ?array {
    if (!in_array($media->bundle(), ['civictheme_image', 'civictheme_icon'])) {
      return NULL;
    }

    $variables = static::getVariables($media);

    if ($variables['ext'] == 'svg') {
      $image_style = NULL;
    }

    $url = static::getImageUrl($media, $image_style);
    $image_url = \Drupal::service('file_url_generator')->generateString($url);
    $variables['url'] = \Drupal::service('file_url_generator')->transformRelative($image_url);

    $image = $media
      ->get($media->getSource()->getConfiguration()['source_field'])
      ->first()
      ->getValue();

    $variables['alt'] = $image['alt'] ?? '';
    $variables['title'] = $image['title'] ?? '';

    return $variables;
  }

  /**
   * Get the media source file URL.
   *
   * @param \Drupal\media\MediaInterface $media
   *   The media entity to derive the URL from.
   * @param bool $is_relative
   *   (optional) Whether the URL should be root-relative, defaults to TRUE.
   *
   * @return string|null
   *   The relative URL for the source file or NULL if the file was not found.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   *
   * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
   */
  public static function getUrl(MediaInterface $media, bool $is_relative = TRUE): ?string {
    $file = File::load($media->getSource()->getSourceFieldValue($media));
    try {
      return $file !== NULL ? $file->createFileUrl($is_relative) : NULL;
    }
    catch (InvalidStreamWrapperException $e) {
      return NULL;
    }
  }

  /**
   * Get Image media file URL, optionally using an image style.
   *
   * @param \Drupal\media\MediaInterface $media
   *   The media entity to derive the URL from.
   * @param string|null $image_style
   *   (optional) Image style name.
   * @param bool $is_relative
   *   (optional) Whether the URL should be root-relative, defaults to TRUE.
   *
   * @return string|null
   *   The relative URL for the source file or NULL if the file was not found.
   *
   * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
   * @SuppressWarnings(PHPMD.ElseExpression)
   */
  public static function getImageUrl(MediaInterface $media, ?string $image_style = NULL, bool $is_relative = TRUE): ?string {
    $src = NULL;

    $file = File::load($media->getSource()->getSourceFieldValue($media));

    if ($file !== NULL) {
      if ($image_style) {
        $style = ImageStyle::load($image_style);
        $src = $style !== NULL ? $style->buildUrl($file->get('uri')->getString()) : NULL;
      }
      else {
        $src = $file->createFileUrl($is_relative);
      }
    }

    return $src;
  }

  /**
   * File icon symbol provider.
   *
   * @param \Drupal\file\FileInterface $file
   *   File object.
   *
   * @return string
   *   Return Symbol.
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   */
  public static function getIconFromFile(FileInterface $file): string {
    $mime_type = $file->getMimeType();

    return match ($mime_type) {
      'application/msword', 'application/vnd.ms-word.document.macroEnabled.12', 'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.text-template', 'application/vnd.oasis.opendocument.text-master', 'application/vnd.oasis.opendocument.text-web', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.stardivision.writer', 'application/vnd.sun.xml.writer', 'application/vnd.sun.xml.writer.template', 'application/vnd.sun.xml.writer.global', 'application/vnd.wordperfect', 'application/x-abiword', 'application/x-applix-word', 'application/x-kword', 'application/x-kword-crypt' => 'word-file',
      'application/vnd.ms-excel', 'application/vnd.ms-excel.sheet.macroEnabled.12', 'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.spreadsheet-template', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.stardivision.calc', 'application/vnd.sun.xml.calc', 'application/vnd.sun.xml.calc.template', 'application/vnd.lotus-1-2-3', 'application/x-applix-spreadsheet', 'application/x-gnumeric', 'application/x-kspread', 'application/x-kspread-crypt' => 'download-file',
      'application/vnd.ms-powerpoint', 'application/vnd.ms-powerpoint.presentation.macroEnabled.12', 'application/vnd.oasis.opendocument.presentation', 'application/vnd.oasis.opendocument.presentation-template', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.stardivision.impress', 'application/vnd.sun.xml.impress', 'application/vnd.sun.xml.impress.template', 'application/x-kpresenter' => 'download-file',
      'application/pdf', 'application/x-pdf', 'applications/vnd.pdf', 'text/pdf', 'text/x-pdf' => 'pdf-file',
      default => 'download-file',
    };
  }

  /**
   * Embed SVG from provided URL.
   *
   * @param string $url
   *   Local URL or local path to retrieve SVG from.
   * @param array $css_classes
   *   Array of CSS classes to add.
   *
   * @return string|null
   *   Loaded SVG or NULL if unable to load SVG.
   */
  public static function embedSvg(string $url, array $css_classes = []): ?string {
    $svg_path = DRUPAL_ROOT . (str_starts_with($url, 'http') ? parse_url(str_replace('.png', '.svg', $url), PHP_URL_PATH) : str_replace('.png', '.svg', $url));
    if (!file_exists($svg_path)) {
      return NULL;
    }

    $content = (string) file_get_contents($svg_path);

    if (!empty($css_classes)) {
      $content = str_replace('<svg ', '<svg class="' . implode(' ', $css_classes) . '" ', $content);
    }

    // Tag list taken from
    // https://developer.mozilla.org/en-US/docs/Web/SVG/Reference/Element.
    // Excludes <a>, <script> and <style>.
    $supported_svg_tags = implode('', [
      '<animate>',
      '<animateMotion>',
      '<animateTransform>',
      '<circle>',
      '<clipPath>',
      '<defs>',
      '<desc>',
      '<ellipse>',
      '<feBlend>',
      '<feColorMatrix>',
      '<feComponentTransfer>',
      '<feComposite>',
      '<feConvolveMatrix>',
      '<feDiffuseLighting>',
      '<feDisplacementMap>',
      '<feDistantLight>',
      '<feDropShadow>',
      '<feFlood>',
      '<feFuncA>',
      '<feFuncB>',
      '<feFuncG>',
      '<feFuncR>',
      '<feGaussianBlur>',
      '<feImage>',
      '<feMerge>',
      '<feMergeNode>',
      '<feMorphology>',
      '<feOffset>',
      '<fePointLight>',
      '<feSpecularLighting>',
      '<feSpotLight>',
      '<feTile>',
      '<feTurbulence>',
      '<filter>',
      '<foreignObject>',
      '<g>',
      '<image>',
      '<line>',
      '<linearGradient>',
      '<marker>',
      '<mask>',
      '<metadata>',
      '<mpath>',
      '<path>',
      '<pattern>',
      '<polygon>',
      '<polyline>',
      '<radialGradient>',
      '<rect>',
      '<set>',
      '<stop>',
      '<svg>',
      '<switch>',
      '<symbol>',
      '<text>',
      '<textPath>',
      '<title>',
      '<tspan>',
      '<use>',
      '<view>',
    ]);

    return strip_tags($content, $supported_svg_tags);
  }

}
