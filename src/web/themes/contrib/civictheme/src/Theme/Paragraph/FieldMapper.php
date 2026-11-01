<?php

declare(strict_types=1);

namespace Drupal\civictheme\Theme\Paragraph;

use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\Theme\FeatureOptout;
use Drupal\civictheme\Utility\CivicthemeDateHelper;
use Drupal\civictheme\Utility\CivicthemeFieldHelper;
use Drupal\civictheme\Utility\CivicthemeMediaHelper;
use Drupal\Component\Utility\Unicode;
use Drupal\Component\Utility\Xss;
use Drupal\media\MediaInterface;
use Drupal\text\TextSummary;

/**
 * Maps paragraph and referenced-node field values onto template variables.
 *
 * Internal to CivicTheme: used as a unified place to populate variables.
 *
 * For readability, similar paragraph and node methods are grouped together
 * and sorted alphabetically.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
final class FieldMapper {

  /**
   * Pre-process for With background paragraph field.
   */
  public static function paragraphFieldBackground(array &$variables): void {
    $variables['with_background'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_background');
  }

  /**
   * Pre-process for Column count paragraph field.
   */
  public static function paragraphFieldColumnCount(array &$variables): void {
    $variables['column_count'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_list_column_count', TRUE, 1);
  }

  /**
   * Pre-process for Content paragraph field.
   */
  public static function paragraphFieldContent(array &$variables): void {
    $variables['content'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_content');
  }

  /**
   * Pre-process for Date paragraph field.
   */
  public static function paragraphFieldDate(array &$variables): void {
    $date = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_date', TRUE);
    if ($date) {
      $variables['date'] = CivicthemeDateHelper::format($date->get('value')->getDateTime(), 'civictheme_short_date');
      $variables['date_iso'] = CivicthemeDateHelper::formatIso($date->get('value')->getDateTime());
    }
  }

  /**
   * Pre-process for Date node field.
   */
  public static function nodeFieldDate(array &$variables): void {
    $node = $variables['node'] ?? CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_reference', TRUE, build: $variables);
    if ($node) {
      $variables['date'] = CivicthemeDateHelper::format($node->getChangedTime(), 'civictheme_short_date');
      $variables['date_iso'] = CivicthemeDateHelper::formatIso($node->getChangedTime());
    }
  }

  /**
   * Pre-process for Date range paragraph field.
   */
  public static function paragraphFieldDateRange(array &$variables): void {
    $date = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_date_range', TRUE);
    if ($date) {
      $variables['date'] = CivicthemeDateHelper::format($date->get('value')->getDateTime());
      $variables['date_iso'] = CivicthemeDateHelper::formatIso($date->get('value')->getDateTime());
      if ($date->get('end_value')->getDateTime()) {
        $variables['date_end'] = CivicthemeDateHelper::format($date->get('end_value')->getDateTime());
        $variables['date_end_iso'] = CivicthemeDateHelper::formatIso($date->get('end_value')->getDateTime());
      }
    }
  }

  /**
   * Pre-process for Date range node field.
   */
  public static function nodeFieldDateRange(array &$variables): void {
    $node = $variables['node'] ?? CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_reference', TRUE, build: $variables);
    if ($node) {
      $date = CivicthemeFieldHelper::getValue($node, 'field_c_n_date_range', TRUE);
      if ($date) {
        $variables['date'] = CivicthemeDateHelper::format($date->get('value')->getDateTime());
        $variables['date_iso'] = CivicthemeDateHelper::formatIso($date->get('value')->getDateTime());
        if ($date->get('end_value')->getDateTime()) {
          $variables['date_end'] = CivicthemeDateHelper::format($date->get('end_value')->getDateTime());
          $variables['date_end_iso'] = CivicthemeDateHelper::formatIso($date->get('end_value')->getDateTime());
        }
      }
    }
  }

  /**
   * Pre-process for Fill width paragraph field.
   */
  public static function paragraphFieldFillWidth(array &$variables): void {
    $variables['fill_width'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_list_fill_width', TRUE, FALSE);
  }

  /**
   * Pre-process for Image paragraph field.
   */
  public static function paragraphFieldImage(array &$variables, ?string $image_style = NULL): void {
    $media = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_image', TRUE, build: $variables);
    if ($media) {
      $variables['image'] = CivicthemeMediaHelper::getImageVariables($media, $image_style);
    }
  }

  /**
   * Pre-process for Thumbnail node field.
   */
  public static function nodeFieldImage(array &$variables, ?string $image_style = NULL): void {
    $node = $variables['node'] ?? CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_reference', TRUE, build: $variables);
    if ($node) {
      $media = CivicthemeFieldHelper::getValue($node, 'field_c_n_thumbnail', TRUE, build: $variables);
      if ($media instanceof MediaInterface && $media->getSource()->getPluginId() === 'image') {
        $variables['image'] = CivicthemeMediaHelper::getImageVariables($media, $image_style);
      }
    }
  }

  /**
   * Pre-process for Image position paragraph field.
   */
  public static function paragraphFieldImagePosition(array &$variables): void {
    $variables['image_position'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_image_position');
  }

  /**
   * Pre-process for Link paragraph field.
   */
  public static function paragraphFieldLink(array &$variables): void {
    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
    $link = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_link', TRUE);
    if ($link !== NULL) {
      $variables['link'] = [
        'text' => html_entity_decode(Xss::filter($link->get('title')->getString())),
        'url' => $link->getUrl()->toString(),
        'is_new_window' => $link->isExternal(),
        'is_external' => $link->isExternal(),
      ];
    }
  }

  /**
   * Pre-process for Link Above paragraph field.
   */
  public static function paragraphFieldLinkAbove(array &$variables): void {
    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
    $link = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_list_link_above', TRUE);
    if ($link !== NULL) {
      $variables['link_above'] = [
        'text' => $link->get('title')->getString(),
        'url' => $link->getUrl()->toString(),
        'is_new_window' => $link->isExternal(),
        'is_external' => $link->isExternal(),
      ];
    }
  }

  /**
   * Pre-process for Link Below paragraph field.
   */
  public static function paragraphFieldLinkBelow(array &$variables): void {
    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
    $link = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_list_link_below', TRUE);
    if ($link !== NULL) {
      $variables['link_below'] = [
        'text' => $link->get('title')->getString(),
        'url' => $link->getUrl()->toString(),
        'is_new_window' => $link->isExternal(),
        'is_external' => $link->isExternal(),
      ];
    }
  }

  /**
   * Pre-process for Link node field.
   */
  public static function nodeFieldLink(array &$variables): void {
    $node = $variables['node'] ?? CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_reference', TRUE, build: $variables);
    if ($node) {
      $variables['link'] = [
        'text' => $node->getTitle(),
        'url' => $node->toUrl()->toString(),
        'is_new_window' => FALSE,
        'is_external' => FALSE,
      ];
    }
  }

  /**
   * Pre-process for Links paragraph field.
   */
  public static function paragraphFieldLinks(array &$variables): void {
    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem[]|null $links */
    $links = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_links');
    if ($links) {
      foreach ($links as $link) {
        $variables['links'][] = [
          'text' => Xss::filter($link->get('title')->getString()),
          'url' => $link->getUrl()->toString(),
          'is_new_window' => $link->isExternal(),
          'is_external' => $link->isExternal(),
        ];
      }
    }
  }

  /**
   * Pre-process for Location node field.
   */
  public static function nodeFieldLocation(array &$variables): void {
    // Location is a field on the paragraph attached to an Event node through
    // 'field_c_n_location' field. We are only using a first location.
    $node = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_reference', TRUE, build: $variables);
    if ($node) {
      /** @var \Drupal\paragraphs\Entity\Paragraph[] $locations */
      $locations = CivicthemeFieldHelper::getValue($node, 'field_c_n_location', build: $variables);
      if (!empty($locations)) {
        foreach ($locations as $location) {
          $address = CivicthemeFieldHelper::getValue($location, 'field_c_p_address', TRUE);
          if (!empty($address)) {
            $variables['location'] = $address;

            return;
          }
        }
      }
    }
  }

  /**
   * Pre-process for Summary paragraph field.
   */
  public static function paragraphFieldSummary(array &$variables): void {
    $summary = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_summary', TRUE);
    if (!empty($summary)) {
      $variables['summary'] = static::trimSummary($summary, $variables['paragraph']->bundle());
    }
  }

  /**
   * Pre-process for Summary node field.
   */
  public static function nodeFieldSummary(array &$variables, ?string $bundle = NULL, string $field_name = 'field_c_n_summary'): void {
    $node = $variables['node'] ?? CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_reference', TRUE, build: $variables);
    if ($node) {
      $summary = CivicthemeFieldHelper::getValue($node, $field_name, TRUE);
      if (!empty($summary)) {
        $variables['summary'] = static::trimSummary($summary, $bundle ?? (empty($variables['paragraph']) ? 'none' : $variables['paragraph']->bundle()));
      }
    }
  }

  /**
   * Pre-process for Theme paragraph field.
   */
  public static function paragraphFieldTheme(array &$variables): void {
    $variables['theme'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_theme');
  }

  /**
   * Pre-process for Title paragraph field.
   */
  public static function paragraphFieldTitle(array &$variables): void {
    $variables['title'] = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_title');
  }

  /**
   * Pre-process for Title node field.
   */
  public static function nodeFieldTitle(array &$variables): void {
    $node = $variables['node'] ?? CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_reference', TRUE, build: $variables);
    if ($node) {
      $variables['title'] = $node->getTitle();
    }
  }

  /**
   * Pre-process for Topics paragraph field.
   */
  public static function paragraphFieldTopics(array &$variables): void {
    $labels = CivicthemeFieldHelper::getReferencedEntityLabels($variables['paragraph'], 'field_c_p_topics', build: $variables);
    $variables['tags'] = array_map(static fn($label) => ['content' => (string) $label], $labels);
  }

  /**
   * Pre-process for Topics node field.
   */
  public static function nodeFieldTopics(array &$variables): void {
    $node = $variables['node'] ?? CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_reference', TRUE, build: $variables);
    if ($node) {
      $labels = CivicthemeFieldHelper::getReferencedEntityLabels($node, 'field_c_n_topics', $variables);
      $variables['tags'] = array_map(static fn($label) => ['content' => (string) $label], $labels);
    }
  }

  /**
   * Pre-process for Vertical spacing paragraph field.
   */
  public static function paragraphFieldVerticalSpacing(array &$variables): void {
    $value = CivicthemeFieldHelper::getValue($variables['paragraph'], 'field_c_p_vertical_spacing');
    if (in_array($value, ['top', 'bottom', 'both'], TRUE)) {
      $variables['vertical_spacing'] = $value;
    }
  }

  /**
   * Trim a summary value to the component-configured length.
   *
   * @param mixed $summary
   *   The raw summary value.
   * @param string $bundle
   *   The bundle used to resolve the configurable component name.
   *
   * @return string
   *   The trimmed summary.
   */
  protected static function trimSummary(mixed $summary, string $bundle): string {
    $length = CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH;

    $component_name = static::getConfigurableComponentNameFromBundle($bundle);
    if ($component_name) {
      /** @var \Drupal\civictheme\CivicthemeConfigManager $theme_config_manager */
      $theme_config_manager = \Drupal::classResolver(CivicthemeConfigManager::class);
      $length = $theme_config_manager->loadForComponent($component_name, 'summary_length', CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH);
    }

    $default_summary_length = (int) \Drupal::config('text.settings')->get('default_summary_length');
    $summary_trimmed = (string) \Drupal::service(TextSummary::class)->generate($summary, NULL, $default_summary_length);

    if (!FeatureOptout::isOptedOut('process', CivicthemeConstants::OPTOUT_SUMMARY_HIDE_ELLIPSIS)) {
      $summary_trimmed = Unicode::truncate($summary_trimmed, $length, TRUE, TRUE);
    }

    return $summary_trimmed;
  }

  /**
   * Extract a configurable component name from a paragraph bundle.
   */
  protected static function getConfigurableComponentNameFromBundle(string $bundle): ?string {
    // Accommodate for existing and additional components that could be added
    // in the sub-themes.
    if (preg_match('/civictheme_(.*_card|.*_?snippet).*/', $bundle, $matches)) {
      return $matches[1];
    }

    return NULL;
  }

}
