<?php

namespace Drupal\civictheme_migrate\Utils;

use Drupal\paragraphs\Entity\Paragraph;

/**
 * Trait CsGeneratedContentTrait.
 *
 * Trait for centralised CivicTheme components handling.
 */
trait CivicthemeTrait {

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
  public static function civicthemeVerticalSpacingTypeNone() {
    return 'none';
  }

  /**
   * Spacing - top.
   */
  public static function civicthemeVerticalSpacingTypeTop() {
    return 'top';
  }

  /**
   * Spacing - bottom.
   */
  public static function civicthemeVerticalSpacingTypeBottom() {
    return 'bottom';
  }

  /**
   * Spacing - both.
   */
  public static function civicthemeVerticalSpacingTypeBoth() {
    return 'both';
  }

  /**
   * Types of spaces.
   */
  public static function civicthemeVerticalSpacingTypes() {
    return [
      static:: civicthemeVerticalSpacingTypeNone(),
      static:: civicthemeVerticalSpacingTypeTop(),
      static:: civicthemeVerticalSpacingTypeBottom(),
      static:: civicthemeVerticalSpacingTypeBoth(),
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
   * Image position - left.
   */
  public static function civicthemeImagePositionLeft() {
    return 'left';
  }

  /**
   * Image position - right.
   */
  public static function civicthemeImagePositionRight() {
    return 'right';
  }

  /**
   * Image blend-mode.
   */
  public static function civicthemeImageBlendMode() {
    return 'soft-light';
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
   * Generic component create helper.
   */
  public static function civicthemeComponentCreate($type, $options) {
    $method = 'civicthemeParagraph' . str_replace(' ', '', (ucwords(str_replace('_', ' ', $type)))) . 'Create';

    if (!method_exists(self::class, $method)) {
      throw new \RuntimeException(sprintf('Method "%s" is not defined in "%s" class.', $method, self::class));
    }

    $defaults = [
      'content' => '',
      'theme' => static::civicthemeThemeLight(),
      'vertical_spacing' => static::civicthemeVerticalSpacingTypeNone(),
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
    if (!empty($options['vertical_spacing'])) {
      $options['vertical_spacing'] = static::civicthemeValueFromOptions(static::civicthemeVerticalSpacingTypes(), $options['vertical_spacing']);
    }

    // Background.
    if (!empty($options['background'])) {
      $options['background'] = (bool) $options['background'];
    }

    return call_user_func([self::class, $method], $options);
  }

  /**
   * Create Content paragraph.
   */
  public static function civicthemeParagraphContentCreate($options) {
    if (empty($options['content'])) {
      return;
    }

    $options['content'] = static::civicthemeNormaliseRichTextContentValue($options['content']);

    $paragraph = self::civicthemeParagraphCreate('civictheme_content', $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    return $paragraph;
  }

  /**
   * Create Accordion paragraph.
   */
  public static function civicthemeParagraphAccordionCreate($options) {
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
    $paragraph = self::civicthemeParagraphCreate('civictheme_accordion', $options);

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

        $panel = self::civicthemeParagraphCreate('civictheme_accordion_panel', $panel_options, TRUE);
        if (!empty($panel)) {
          $paragraph->field_c_p_panels->appendItem($panel);
        }
      }
    }

    $paragraph->save();

    return $paragraph;
  }

  /**
   * Create paragraph to entity.
   */
  protected static function civicthemeParagraphCreate($type, $options, $save = FALSE) {
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
   * Create Attachment paragraph.
   */
  public static function civicthemeParagraphAttachmentCreate($options) {
    $defaults = [
      'title' => '',
      'content' => '',
      'attachments' => [],
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $options['content'] = static::civicthemeNormaliseRichTextContentValue($options['content'] ?? '');
    $paragraph = self::civicthemeParagraphCreate('civictheme_attachment', $options);

    $paragraph->save();

    return $paragraph;
  }

  /**
   * Create Callout paragraph.
   */
  public static function civicthemeParagraphCalloutCreate($options) {
    $defaults = [
      'title' => '',
      'summary' => '',
      'links' => FALSE,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphCreate('civictheme_callout', $options);

    $paragraph->save();

    return $paragraph;
  }

  /**
   * Create Campaign paragraph.
   */
  public static function civicthemeParagraphCampaignCreate($options) {
    $defaults = [
      'image' => NULL,
      'image_position' => self::civicthemeImagePositionLeft(),
      'title' => '',
      'date' => '',
      'summary' => '',
      'links' => FALSE,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphCreate('civictheme_campaign', $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    return $paragraph;
  }

  /**
   * Create Iframe paragraph.
   */
  public static function civicthemeParagraphIframeCreate($options) {
    $defaults = [
      'url' => '',
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphCreate('civictheme_iframe', $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    return $paragraph;
  }

  /**
   * Create Map paragraph.
   */
  public static function civicthemeParagraphMapCreate($options) {
    $defaults = [
      'embed_url' => '',
      'address' => '',
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphCreate('civictheme_map', $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    return $paragraph;
  }

  /**
   * Create Next Step paragraph.
   */
  public static function civicthemeParagraphNextStepCreate($options) {
    $defaults = [
      'summary' => '',
      'link' => FALSE,
      'title' => '',
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphCreate('civictheme_next_step', $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    return $paragraph;
  }

  /**
   * Create Promo paragraph.
   */
  public static function civicthemeParagraphPromoCreate($options) {
    $defaults = [
      'title' => '',
      'links' => FALSE,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphCreate('civictheme_promo', $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    return $paragraph;
  }

  /**
   * Create Quote paragraph.
   */
  public static function civicthemeParagraphQuoteCreate($options) {
    $defaults = [
      'content' => '',
      'author' => '',
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphCreate('civictheme_quote', $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    return $paragraph;
  }

  /**
   * Create Automated list paragraph.
   */
  public static function civicthemeParagraphAutomatedListCreate($options) {
    $defaults = [
      'list_type' => static::civicthemeAutomatedListType(),
      'list_content_type' => static::civicthemePageContentType(),
      'list_item_view_as' => static::civicthemePromoCardType(),
      'list_filters_exp' => [],
      'list_item_theme' => static::civicthemeThemeLight(),
      'list_limit' => 0,
      'list_limit_type' => self::civicthemeAutomatedListLimitTypeUnlimited(),
      'list_link_above' => NULL,
      'list_link_below' => NULL,
      'list_site_sections' => NULL,
      'list_topics' => NULL,
      'list_column_count' => 1,
      'list_fill_width' => NULL,
      'title' => NULL,
      'theme' => static::civicthemeThemeLight(),
      'vertical_spacing' => static::civicthemeVerticalSpacingTypeNone(),
      'background' => FALSE,
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

    $paragraph = self::civicthemeParagraphCreate('civictheme_automated_list', $options);

    if (empty($paragraph)) {
      return;
    }

    return $paragraph;
  }

  /**
   * Create Webform paragraph.
   */
  public static function civicthemeParagraphWebformCreate($options) {
    $defaults = [
      'webform' => NULL,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphCreate('civictheme_webform', $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    return $paragraph;
  }

  /**
   * Create Slider paragraph.
   */
  public static function civicthemeParagraphSliderCreate($options) {
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

    $paragraph = self::civicthemeParagraphCreate('civictheme_slider', $options);

    if (empty($paragraph)) {
      return;
    }

    // Slider slide.
    if (!empty($slides)) {
      foreach ($slides as $slide_options) {
        if (!empty($slide_options['type'])) {
          $type = $slide_options['type'];
          unset($slide_options['type']);
          $slide = self::civicthemeParagraphCreate($type, $paragraph, 'field_c_p_slides', $slide_options, TRUE);
          if (!empty($slide)) {
            $paragraph->field_c_p_slides->appendItem($slide);
          }
        }
      }
    }

    $paragraph->save();
    return $paragraph;
  }

  /**
   * Create Card container paragraph.
   */
  public static function civicthemeParagraphManualListCreate($options) {
    $defaults = [
      'list_column_count' => 3,
      'list_fill_width' => NULL,
      'theme' => self::civicthemeThemeLight(),
      'list_link_above' => NULL,
      'list_link_below' => NULL,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $options['content'] = static::civicthemeNormaliseRichTextContentValue($options['content']);

    if (!empty($options['cards']) && count($options['cards']) > 0) {
      $cards = $options['cards'];
      unset($options['cards']);
    }

    $paragraph = self::civicthemeParagraphCreate('civictheme_manual_list', $options);

    if (empty($paragraph)) {
      return;
    }

    // Cards.
    if (!empty($cards)) {
      foreach ($cards as $card_options) {
        if (!empty($card_options['type'])) {
          $type = $card_options['type'];
          unset($card_options['type']);
          $card = self::civicthemeParagraphCreate($type, $card_options, TRUE);
          if (!empty($card)) {
            $paragraph->field_c_p_list_items->appendItem($card);
          }
        }
      }
    }

    $paragraph->save();
    return $paragraph;
  }

  /**
   * Create Search paragraph.
   */
  public static function civicthemeParagraphSearchCreate($options) {
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

    $paragraph = self::civicthemeParagraphCreate('civictheme_search', $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    return $paragraph;
  }

}
