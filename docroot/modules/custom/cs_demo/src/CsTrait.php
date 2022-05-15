<?php

namespace Drupal\cs_demo;

use Drupal\paragraphs\Entity\Paragraph;

/**
 * Trait CivicDemoTrait.
 *
 * Trait for centralised Civic components handling.
 */
trait CivicDemoTrait {

  /**
   * Light theme name.
   */
  public static function civicThemeLight() {
    return 'light';
  }

  /**
   * Dark theme name.
   */
  public static function civicThemeDark() {
    return 'dark';
  }

  /**
   * Available theme names.
   */
  public static function civicThemes() {
    return [
      static::civicThemeLight(),
      static::civicThemeDark(),
    ];
  }

  /**
   * Types of spaces.
   */
  public static function civicSpaceTypes() {
    return [
      '',
      'top',
      'bottom',
      'both',
    ];
  }

  /**
   * Types of banner.
   */
  public static function civicBannerTypes() {
    return ['default', 'large'];
  }

  /**
   * Attach Content paragraph to a node.
   */
  public static function civicParagraphContentAttach($node, $field_name, $options, $save = FALSE) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'content' => '',
      'theme' => 'light',
      'space' => 'top',
      'background' => FALSE,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = Paragraph::create([
      'type' => 'civic_content',
    ]);

    if (!empty($options['content'])) {
      $paragraph->field_c_p_content = [
        'value' => $options['content'],
        'format' => 'civic_rich_text',
      ];
    }

    if (!empty($options['theme'])) {
      $paragraph->field_c_p_theme = [
        'value' => static::civicValueFromOptions(static::civicThemes(), $options['theme']),
      ];
    }

    if (!empty($options['space'])) {
      $paragraph->field_c_p_space = [
        'value' => static::civicValueFromOptions(static::civicSpaceTypes(), $options['space']),
      ];
    }

    if (!empty($options['background'])) {
      $paragraph->field_c_p_background = [
        'value' => (bool) $options['background'],
      ];
    }

    if ($save) {
      $paragraph->save();
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Check that value exists in the provided options or return the first option.
   */
  protected static function civicValueFromOptions(array $options, $value) {
    return in_array($value, $options) ? $value : reset($options);
  }

}
