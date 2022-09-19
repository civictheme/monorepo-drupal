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
   * Page content type name.
   */
  public static function civicthemePageContentType() {
    return 'civictheme_page';
  }

  /**
   * Event content type name.
   */
  public static function civicthemeEventContentType() {
    return 'civictheme_event';
  }

  /**
   * Default Automated list view name.
   */
  public static function civicthemeAutomatedListType() {
    return 'civictheme_automated_list__block1';
  }

  /**
   * Limited type name.
   */
  public static function civicthemeAutomatedListLimitTypeLimited() {
    return 'limited';
  }

  /**
   * Unlimited type name.
   */
  public static function civicthemeAutomatedListLimitTypeUnlimited() {
    return 'unlimited';
  }

  /**
   * Promo card type name.
   */
  public static function civicthemePromoCardType() {
    return 'civictheme_promo_card';
  }

  /**
   * Navigation card type name.
   */
  public static function civicthemeNavigationCardType() {
    return 'civictheme_navigation_card';
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
   * Small size name.
   */
  public static function civicthemeSizeSmall() {
    return 'small';
  }

  /**
   * Large size name.
   */
  public static function civicthemeSizeLarge() {
    return 'large';
  }

  /**
   * Static Topics.
   */
  public static function civicthemeStaticTopics($count = NULL) {
    return static::staticTerms('civictheme_topics', $count);
  }

  /**
   * Static Topic.
   */
  public static function civicthemeStaticTopic() {
    $entities = static::civicthemeStaticTopics(1);

    return count($entities) > 0 ? reset($entities) : NULL;
  }

  /**
   * Expose single listing filter type.
   */
  public static function civicThemeExposeSingleFilter() {
    return ['topic'];
  }

  /**
   * Expose multiple filter types.
   */
  public static function civicThemeExposeMultipleFilters() {
    return ['topic', 'type', 'title'];
  }

  /**
   * Static Site sections.
   */
  public static function civicthemeStaticSiteSections($count = NULL) {
    return static::staticTerms('civictheme_site_sections', $count);
  }

  /**
   * Static Site section.
   */
  public static function civicthemeStaticSiteSection() {
    $entities = static::civicthemeStaticSiteSections(1);

    return count($entities) > 0 ? reset($entities) : NULL;
  }

  /**
   * Generic component attach helper.
   */
  public static function civicthemeComponentAttach($node, $field_name, $type, $options) {
    $method = 'civicthemeParagraph' . str_replace(' ', '', (ucwords(str_replace('_', ' ', $type)))) . 'Attach';

    if (!method_exists(self::class, $method)) {
      throw new \RuntimeException(sprintf('Method "%s" is not defined in "%s" class.', $method, self::class));
    }

    $defaults = [
      'content' => '',
      'theme' => static::civicthemeThemeLight(),
      'space' => static::civicthemeSpaceTypeTop(),
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

    $defaults = [
      'title' => '',
      'summary' => '',
      'attachments' => [],
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
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

    $defaults = [
      'title' => '',
      'summary' => '',
      'links' => FALSE,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_callout', $node, $field_name, $options);

    $paragraph->save();

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Iframe paragraph to a node.
   */
  public static function civicthemeParagraphIframeAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'url' => '',
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_iframe', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Map paragraph to a node.
   */
  public static function civicthemeParagraphMapAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'embed_url' => '',
      'address' => '',
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_map', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Promo paragraph to a node.
   */
  public static function civicthemeParagraphNextStepAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'summary' => '',
      'link' => FALSE,
      'title' => '',
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_next_step', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Promo paragraph to a node.
   */
  public static function civicthemeParagraphPromoAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'title' => '',
      'links' => FALSE,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_promo', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Automated list paragraph to a node.
   */
  public static function civicthemeParagraphAutomatedListAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'list_type' => static::civicthemeAutomatedListType(),
      'list_content_type' => static::civicthemePageContentType(),
      'list_item_view_as' => static::civicthemePromoCardType(),
      'list_filters_exp' => FALSE,
      'list_item_theme' => static::civicthemeThemeLight(),
      'list_limit' => 0,
      'list_limit_type' => self::civicthemeAutomatedListLimitTypeUnlimited(),
      'list_link_above' => NULL,
      'list_link_below' => NULL,
      'list_show_filters' => FALSE,
      'list_site_sections' => NULL,
      'theme' => static::civicthemeThemeLight(),
      'title' => NULL,
      'list_topics' => NULL,
      'space' => static::civicthemeSpaceTypeNone(),
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    if ($options['list_limit_type'] == static::civicthemeAutomatedListLimitTypeLimited()) {
      $options['list_limit'] = $options['list_limit'] ?? rand(9, 20);
    }
    else {
      $options['list_limit'] = $options['list_limit'] ?? 0;
    }

    if ($options['list_show_filters']) {
      $options['list_filters_exp'] = $options['list_filters_exp'] ?? [];
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_automated_list', $node, $field_name, $options);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Webform paragraph to a node.
   */
  public static function civicthemeParagraphWebformAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'webform' => NULL,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_webform', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Slider paragraph to a node.
   */
  public static function civicthemeParagraphSliderAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'slides' => NULL,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    if (!empty($options['slides']) && count($options['slides']) > 0) {
      $slides = $options['slides'];
      unset($options['slides']);
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_slider', $node, $field_name, $options);

    if (empty($paragraph)) {
      return;
    }

    // Slider slide.
    if (!empty($slides)) {
      foreach ($slides as $slide_options) {
        if (!empty($slide_options['type'])) {
          $type = $slide_options['type'];
          unset($slide_options['type']);
          $slide = self::civicthemeParagraphAttach($type, $paragraph, 'field_c_p_slides', $slide_options, TRUE);
          if (!empty($slide)) {
            $paragraph->field_c_p_slides->appendItem($slide);
          }
        }
      }
    }

    $paragraph->save();
    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Card container paragraph to a node.
   */
  public static function civicthemeParagraphManualListAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'column_count' => NULL,
      'theme' => self::civicthemeThemeLight(),
      'field_c_p_list_item_view_as' => 'civictheme_promo_card',
      'field_c_p_list_item_theme' => self::civicthemeThemeLight(),
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    if (!empty($options['cards']) && count($options['cards']) > 0) {
      $cards = $options['cards'];
      unset($options['cards']);
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_manual_list', $node, $field_name, $options);

    if (empty($paragraph)) {
      return;
    }

    // Slider slide.
    if (!empty($cards)) {
      foreach ($cards as $card_options) {
        if (!empty($card_options['type'])) {
          $type = $card_options['type'];
          unset($card_options['type']);
          $card = self::civicthemeParagraphAttach($type, $paragraph, 'field_c_p_list_items', $card_options, TRUE);
          if (!empty($card)) {
            $paragraph->field_c_p_list_items->appendItem($card);
          }
        }
      }
    }

    $paragraph->save();
    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Search paragraph to a node.
   */
  public static function civicthemeParagraphSearchAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'theme' => NULL,
      'title' => '',
      'button_text' => '',
      'search_url' => '',
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_search', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

}
