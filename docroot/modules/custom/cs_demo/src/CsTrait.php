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
   * Attach paragraph to entity.
   */
  public static function civicParagraphAttach($type, $enity, $field_name, $options, $save = FALSE) {
    if (!$enity->hasField($field_name)) {
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
      'type' => $type,
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

    return $paragraph;
  }

  /**
   * Attach Content paragraph to a node.
   */
  public static function civicParagraphContentAttach($node, $field_name, $options, $save = FALSE) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $paragraph = self::civicParagraphAttach('civictheme_content', $node, $field_name, $options, $save);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Accordion paragraph to a node.
   */
  public static function civicParagraphAccordionAttach($node, $field_name, $options, $save = FALSE) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $paragraph = self::civicParagraphAttach('civictheme_accordion', $node, $field_name, $options, $save);

    if (empty($paragraph)) {
      return;
    }

    // Expand all.
    if (!empty($options['expand_all'])) {
      $paragraph->field_c_p_expand = [
        'value' => (bool) $options['expand_all'],
      ];
    }

    // Accordion panels.
    if (!empty($options['panels'])) {
      for ($i = 0; $i < $options['panels']; $i++) {
        $panel = self::civicParagraphAttach('civictheme_accordion_panel', $paragraph, 'field_c_p_panels', [
          'title' => self::string(),
          'content' => self::richText(rand(1, 3), rand(5, 7), sprintf('Content %s ', $i + 1)),
        ]);
        $panel->field_c_p_expand = [
          'value' => (bool) rand(0, 1),
        ];
        $paragraph->field_c_p_panels->appendItem($panel);
      }
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Accordion paragraph to a node.
   */
  public static function civicParagraphCalloutAttach($node, $field_name, $options, $save = FALSE) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $paragraph = self::civicParagraphAttach('civictheme_accordion', $node, $field_name, $options, $save);

    if (empty($paragraph)) {
      return;
    }

    // Summary.
    if (!empty($options['summary'])) {
      $paragraph->field_c_p_summary = [
        'value' => self::plainParagraph(),
      ];
    }

    // Links.
    if (!empty($options['links'])) {
      $paragraph->field_c_p_links = $options['links'];
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
