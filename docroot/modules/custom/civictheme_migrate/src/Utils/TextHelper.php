<?php

namespace Drupal\civictheme_migrate\Utils;

use Drupal\Component\Utility\Html;
use Drupal\file\Entity\File;

/**
 * Text Support helper class.
 *
 * @package Drupal\civictheme_migrate\Utils
 */
class TextHelper {

  /**
   * Reduce whitespaces from a string.
   *
   * @param string $string
   *   The string.
   *
   * @return string
   *   The clean string.
   */
  public static function reduceWhitespaces(string $string): string {
    $string = preg_replace("/[\n\t\r]/", '', $string);
    $string = preg_replace('/ {2,}/', ' ', $string);
    $string = preg_replace('/[\x{200B}-\x{200D}]/u', '', $string);

    return trim($string);
  }

  /**
   * Convert <img> tags into Drupal embedded media entities.
   *
   * @param string $text
   *   The inout text.
   *
   * @return string
   *   The text.
   */
  public static function convertEmbeddedImagesWithMedia(string $text): string {
    $current_path = '/';
    // Find all the <img> tags.
    $img_pattern = "<img\s[^>]*src=[\'\"](.*)[\'\"][^>]*>";
    $text = preg_replace_callback("/{$img_pattern}/siU", function (array $matches) use ($current_path) {
      $src = $matches[1];
      $src = html_entity_decode($src);
      $alias = AliasHelper::extractAliasFromUrl($src);

      // Get the scheme from the file URI.
      $uriParts = explode('://', $alias);
      $scheme = $uriParts[0] ?? '';

      // Check if the scheme is valid.
      $streamWrapperManager = \Drupal::service('stream_wrapper_manager');
      $isValidScheme = $streamWrapperManager->isValidScheme($scheme);

      // Prepend with current_path if this is a relative URL.
      if (strpos($alias, '/') !== 0 && !$isValidScheme) {
        $alias = dirname($current_path) . '/' . $alias;
      }

      $context['base_url'] = strpos($src, "http") === 0 ? AliasHelper::extractDomainFromUrl($src) : '';

      // Lookup the media.
      $media_uuid = MediaHelper::lookupMediaUuidFromUrl($alias, $context, TRUE);
      if ($media_uuid) {
        $alt_pattern = "<img\s[^>]*alt=[\'\"](.*)[\'\"][^>]*>";
        $alt = preg_match("/{$alt_pattern}/siU", $matches[0], $alt_match) ? $alt_match[1] : '';

        $title_pattern = "<img\s[^>]*title=[\'\"](.*)[\'\"][^>]*>";
        $title = preg_match("/{$title_pattern}/siU", $matches[0], $title_match) ? $title_match[1] : '';

        return MediaHelper::getEmbeddedMediaCode($media_uuid, $alt, $title);
      }

      // Otherwise just return the original img tag.
      return $matches[0];
    }, $text);

    return $text;
  }

  /**
   * Convert Internal Link Entities from a html dom.
   *
   * @param string $string
   *   The string.
   *
   * @return string
   *   The processed string.
   */
  public static function convertInternalLinkEntities(string $string): ?string {
    $dom = Html::load($string);
    if ($dom) {
      $anchors = $dom->getElementsByTagName('a');
      if ($anchors) {
        foreach ($anchors as $a) {
          if ($a->hasAttribute('href')) {
            $url = $a->getAttribute('href');
            if (str_contains($url, '/sites/default/files')) {
              $alias = parse_url($url, PHP_URL_PATH);
              $context['base_url'] = strpos($url, 'http') === 0 ? AliasHelper::extractDomainFromUrl($url) : '';
              $media = MediaHelper::lookupMediaFromUrl($alias, $context, TRUE);
              if ($media) {
                $fid = $media->getSource()->getSourceFieldValue($media);
                if ($fid) {
                  $file = File::load($fid);
                  if ($file) {
                    $a->setAttribute('data-entity-type', 'file');
                    $a->setAttribute('data-entity-uuid', $media->uuid());
                    $a->setAttribute('data-entity-substitution', 'file');
                    $a->setAttribute('href', $file->createFileUrl(TRUE));
                  }
                }
              }
            }
            elseif (AliasHelper::isInternalUri($url)) {
              $url = AliasHelper::getAliasFromInternalUri($url);
              $node = NodeHelper::lookupNodeFromAlias($url);
              if ($node) {
                $a->setAttribute('data-entity-type', 'node');
                $a->setAttribute('data-entity-uuid', $node->uuid());
                $a->setAttribute('data-entity-substitution', 'canonical');
                $a->setAttribute('href', 'entity:/node/' . $node->id());
              }
            }
          }
        }
      }
      $string = Html::serialize($dom);
    }

    return $string;
  }

  /**
   * Convert inline references (img, links, etc.) into embedded entities.
   *
   * @param string $text
   *   The inout text.
   *
   * @return string
   *   The text.
   */
  public static function convertInlineReferencesToEmbeddedEntities(string $text): string {
    $text = static::convertEmbeddedImagesWithMedia($text);
    $text = static::convertInternalLinkEntities($text);

    return $text;
  }

}
