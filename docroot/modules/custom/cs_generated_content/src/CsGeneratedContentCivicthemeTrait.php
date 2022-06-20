<?php

namespace Drupal\cs_generated_content;

use Drupal\paragraphs\Entity\Paragraph;

/**
 * Trait CsGeneratedContentTrait.
 *
 * Trait for centralised CivicTheme components handling.
 */
trait CsGeneratedContentCivicthemeTrait {

  /**
   * Light theme name.
   */
  public static function civicthemeThemeLight() {
    return 'light';
  }

  /**
   * Dark theme name.
   */
  public static function civicthemeThemeDark() {
    return 'dark';
  }

  /**
   * Available theme names.
   */
  public static function civicthemeThemes() {
    return [
      static::civicthemeThemeLight(),
      static::civicthemeThemeDark(),
    ];
  }

  /**
   * Spacing - none.
   */
  public static function civicthemeSpaceTypeNone() {
    return '';
  }

  /**
   * Spacing - top.
   */
  public static function civicthemeSpaceTypeTop() {
    return 'top';
  }

  /**
   * Spacing - bottom.
   */
  public static function civicthemeSpaceTypeBottom() {
    return 'bottom';
  }

  /**
   * Spacing - both.
   */
  public static function civicthemeSpaceTypeBoth() {
    return 'both';
  }

  /**
   * Types of spaces.
   */
  public static function civicthemeSpaceTypes() {
    return [
      static:: civicthemeSpaceTypeNone(),
      static:: civicthemeSpaceTypeTop(),
      static:: civicthemeSpaceTypeBottom(),
      static:: civicthemeSpaceTypeBoth(),
    ];
  }

  /**
   * Banner type - Default.
   */
  public static function civicthemeBannerTypeDefault() {
    return 'default';
  }

  /**
   * Banner type - Large.
   */
  public static function civicthemeBannerTypeLarge() {
    return 'large';
  }

  /**
   * Types of banner.
   */
  public static function civicthemeBannerTypes() {
    return [
      static::civicthemeBannerTypeDefault(),
      static::civicthemeBannerTypeLarge(),
    ];
  }

  /**
   * Slider slide image position - left.
   */
  public static function civicthemeSliderSlideImagePositionLeft() {
    return 'left';
  }

  /**
   * Slider slide image position - right.
   */
  public static function civicthemeSliderSlideImagePositionRight() {
    return 'right';
  }

  /**
   * Slider slide image position.
   */
  public static function civicthemeSliderSlideImagePositions() {
    return [
      static:: civicthemeSliderSlideImagePositionLeft(),
      static:: civicthemeSliderSlideImagePositionRight(),
    ];
  }

  /**
   * Generic component attach helper.
   */
  public static function civicthemeComponentAttach($node, $field_name, $type, $options) {
    $method = 'civicthemeParagraph' . ucfirst($type) . 'Attach';

    if (!method_exists(self::class, $method)) {
      throw new \RuntimeException(sprintf('Method "%s" is not defined in "%s" class.', $method, self::class));
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

    // Theme.
    if (!empty($options['theme'])) {
      $options['theme'] = static::civicthemeValueFromOptions(static::civicthemeThemes(), $options['theme']);
    }

    // Space.
    if (!empty($options['space'])) {
      $options['space'] = static::civicthemeValueFromOptions(static::civicthemeSpaceTypes(), $options['space']);
    }

    // Background.
    if (!empty($options['background'])) {
      $options['background'] = (bool) $options['background'];
    }

    call_user_func([self::class, $method], $node, $field_name, $options);
  }

  /**
   * Attach Content paragraph to a node.
   */
  public static function civicthemeParagraphContentAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    if (empty($options['content'])) {
      return;
    }

    $options['content'] = static::civicthemeNormaliseRichTextContentValue($options['content']);

    $paragraph = self::civicthemeParagraphAttach('civictheme_content', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Accordion paragraph to a node.
   */
  public static function civicthemeParagraphAccordionAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $options += [
      'panels' => [],
      'expand_all' => FALSE,
    ];

    if (!empty($options['panels']) && count($options['panels']) > 0) {
      $panels = $options['panels'];
      unset($options['panels']);
    }
    else {
      // Only create if panels were provided.
      return;
    }

    if (!empty($options['expand_all'])) {
      $options['expand'] = (bool) $options['expand_all'];
    }
    $paragraph = self::civicthemeParagraphAttach('civictheme_accordion', $node, $field_name, $options);

    if (empty($paragraph)) {
      return;
    }

    // Accordion panels.
    if (!empty($panels)) {
      foreach ($panels as $panel_options) {
        $panel_options['content'] = static::civicthemeNormaliseRichTextContentValue($panel_options['content'] ?? '');

        if (!empty($panel_options['expand'])) {
          $panel_options['expand'] = (bool) $panel_options['expand'];
        }

        $panel = self::civicthemeParagraphAttach('civictheme_accordion_panel', $paragraph, 'field_c_p_panels', $panel_options, TRUE);
        if (!empty($panel)) {
          $paragraph->field_c_p_panels->appendItem($panel);
        }
      }
    }

    $paragraph->save();

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach paragraph to entity.
   */
  protected static function civicthemeParagraphAttach($type, $entity, $field_name, $options, $save = FALSE) {
    if (!$entity->hasField($field_name)) {
      return NULL;
    }

    $paragraph = Paragraph::create([
      'type' => $type,
    ]);

    // Attaching all fields to paragraph.
    foreach ($options as $field_name => $value) {
      $field_name = 'field_c_p_' . $field_name;
      if ($paragraph->hasField($field_name)) {
        $paragraph->{$field_name} = $value;
      }
    }

    if ($save) {
      $paragraph->save();
    }

    return $paragraph;
  }

  /**
   * Normalise rich text content field.
   */
  protected static function civicthemeNormaliseRichTextContentValue($value) {
    $value = is_array($value) ? $value : ['value' => $value];
    $value += [
      'value' => '',
      'format' => 'civictheme_rich_text',
    ];

    return $value;
  }

  /**
   * Check that value exists in the provided options or return the first option.
   */
  protected static function civicthemeValueFromOptions(array $options, $value) {
    return in_array($value, $options) ? $value : reset($options);
  }

  /**
   * Attach Attachment paragraph to a node.
   */
  public static function civicthemeParagraphAttachmentAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    if (empty($options['title']) || empty($options['summary']) || empty($options['attachments'])) {
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_attachment', $node, $field_name, $options);

    $paragraph->save();

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Callout paragraph to a node.
   */
  public static function civicthemeParagraphCalloutAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    if (empty($options['title']) || empty($options['summary']) || empty($options['links'])) {
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_callout', $node, $field_name, $options);

    $paragraph->save();

    $node->{$field_name}->appendItem($paragraph);
  }

}
