<?php

namespace Drupal\cs_demo;

use Drupal\paragraphs\Entity\Paragraph;

/**
 * Trait CsDemoTrait.
 *
 * Trait for centralised Civic theme components handling.
 */
trait CsDemoTrait {

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
   * Slider slide image position.
   */
  public static function civicSliderSlideImagePositions() {
    return ['left', 'right'];
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

    // Theme.
    if (!empty($options['theme'])) {
      $options['theme'] = static::civicValueFromOptions(static::civicThemes(), $options['theme']);
    }

    // Space.
    if (!empty($options['space'])) {
      $options['space'] = static::civicValueFromOptions(static::civicSpaceTypes(), $options['space']);
    }

    // Background.
    if (!empty($options['background'])) {
      $options['background'] = (bool) $options['background'];
    }

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
   * Attach Content paragraph to a node.
   */
  public static function civicParagraphContentAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $paragraph = self::civicParagraphAttach('civictheme_content', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Accordion paragraph to a node.
   */
  public static function civicParagraphAccordionAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'panels' => NULL,
      'expand_all' => FALSE,
    ];

    $options += $defaults;

    // Expand all.
    if (!empty($options['expand_all'])) {
      $options['expand_all'] = (bool) $options['expand_all'];
    }
    $paragraph = self::civicParagraphAttach('civictheme_accordion', $node, $field_name, $options);

    if (empty($paragraph)) {
      return;
    }

    // Accordion panels.
    if (!empty($options['panels'])) {
      for ($i = 0; $i < $options['panels']; $i++) {
        $panel_options = $options['panels'];
        if (!empty($options['panels']['expand'])) {
          $panel_options['expand'] = (bool) $options['panels']['expand'];
        }
        $panel = self::civicParagraphAttach('civictheme_accordion_panel', $paragraph, 'field_c_p_panels', $panel_options);
        if (!empty($panel)) {
          $paragraph->field_c_p_panels->appendItem($panel);
        }
      }
    }

    $paragraph->save();

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Accordion paragraph to a node.
   */
  public static function civicParagraphCalloutAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'summary' => '',
      'links' => FALSE,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicParagraphAttach('civictheme_callout', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Accordion paragraph to a node.
   */
  public static function civicParagraphSliderAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      slides => [],

    ];

    $paragraph = self::civicParagraphAttach('civictheme_slider', $node, $field_name, $options, FALSE);

    if (empty($paragraph)) {
      return;
    }

    // Links.
    if (!empty($options['link'])) {
      $paragraph->field_c_p_link = [
        'uri' => $options['link']['value'],
        'title' => $options['link']['title'],
      ];
    }

    // Accordion panels.
    if (!empty($options['slides'])) {
      for ($i = 0; $i < $options['slides']; $i++) {
        $panel_options = $options['slides'][$i];
        $panel = self::civicParagraphAttach('civictheme_slider_slide', $paragraph, 'field_c_p_slides', [
          'title' => $panel_options['title'],
          'content' => $panel_options['content'],
        ]);
        $panel->field_c_p_expand = [
          'value' => $panel_options['expand'],
        ];
        $paragraph->field_c_p_panels->appendItem($panel);
      }
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Promo paragraph to a node.
   */
  public static function civicParagraphPromoAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'summary' => '',
      'links' => FALSE,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicParagraphAttach('civictheme_promo', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Promo paragraph to a node.
   */
  public static function civicParagraphNextStepAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'summary' => '',
      'links' => FALSE,
      'image' => NULL,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicParagraphAttach('civictheme_next_step', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Map paragraph to a node.
   */
  public static function civicParagraphMapAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'embed_url' => NULL,
      'share_link' => NULL,
      'view_link' => NULL,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicParagraphAttach('civictheme_map', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Iframe paragraph to a node.
   */
  public static function civicParagraphIframeAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'attributes' => '',
      'width' => '',
      'height' => '',
      'url' => '',
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicParagraphAttach('civictheme_iframe', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Iframe paragraph to a node.
   */
  public static function civicParagraphWebformAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $paragraph = self::civicParagraphAttach('civictheme_webform', $node, $field_name, $options, TRUE);

    if (!empty($paragraph)) {
      $node->{$field_name}->appendItem($paragraph);
    }
  }

  /**
   * Attach Listing paragraph to a node.
   */
  public static function civicParagraplistingAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'title' => 'Demo listing',
      'filter_exposed' =>  0,
      'hide_count' => 0,
      'limit_type' => 'unlimited',
      'listing_limit' => '9',
      'content_types' => [],
      'listing_multi_select' => 1,
      'show_filters' => 0,
      'show_pager' => 1,
      'view_as' => 'teaser',
      'read_more_title' => '',
      'read_more_uri' => NULL,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicParagraphAttach('civictheme_listing', $node, $field_name, $options);

    if (empty($paragraph)) {
      return;
    }

    if ($options['read_more_title']) {
      $paragraph->field_c_p_read_more->title = $options['read_more_title'];
    }

    if ($options['read_more_uri']) {
      $paragraph->field_c_p_read_more->uri = $options['read_more_uri'];
    }

    $paragraph->save();

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Check that value exists in the provided options or return the first option.
   */
  protected static function civicValueFromOptions(array $options, $value) {
    return in_array($value, $options) ? $value : reset($options);
  }

}
