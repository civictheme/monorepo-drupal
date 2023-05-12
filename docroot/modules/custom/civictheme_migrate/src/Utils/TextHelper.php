<?php

namespace Drupal\civictheme_migrate\Utils;

use Drupal\Component\Utility\Html;

/**
 * Text Support helper class.
 *
 * @package Drupal\civictheme_migrate\Utils
 */
class TextHelper {

  /**
   * Remove whitespaces from a string.
   *
   * @param string $value
   *   The string.
   *
   * @return string
   *   The clean string.
   */
  public static function trimWhitespaces(string $value) : string {
    $value = preg_replace("/(\n|\t|\r)/", '', $value);
    $value = preg_replace('/ {2,}/', ' ', $value);
    $value = preg_replace('/[\x{200B}-\x{200D}]/u', '', $value);
    return trim($value);
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
  public static function convertEmbeddedImagesWithMedia(string $text) : string {
    $current_path = '/';
    // Find all the <img> tags.
    $img_pattern = "<img\s[^>]*src=[\'\"](.*)[\'\"][^>]*>";
    $text = preg_replace_callback("/{$img_pattern}/siU", function (array $matches) use ($current_path) {
      $src = $matches[1];
      $alias = AliasHelper::extractAliasFromUrl(html_entity_decode($src));
      $context['base_url'] = AliasHelper::extractDomainFromUrl(html_entity_decode($src));
      // Prepend with current_path if this is a relative URL.
      if (strpos($alias, '/') !== 0) {
        $alias = dirname($current_path) . '/' . $alias;
      }

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
  public static function convertInternalLinkEntities(string $string) : ?string {
    $dom = Html::load($string);
    if ($dom) {
      $anchors = $dom->getElementsByTagName('a');
      if ($anchors) {
        foreach ($anchors as $a) {
          if (strpos($url, '/-/sites/default/files/') === 0) {
            $alias = parse_url($url, PHP_URL_PATH);
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
          elseif ($a->hasAttribute('href')) {
            $url = $a->getAttribute('href');
            if (AliasHelper::isInternalUri($url)) {
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
  public static function convertInlineReferencesToEmbeddedEntities(string $text) : string {
    $text = static::convertEmbeddedImagesWithMedia($text);
    $text = static::convertInternalLinkEntities($text);
    return $text;
  }

}
