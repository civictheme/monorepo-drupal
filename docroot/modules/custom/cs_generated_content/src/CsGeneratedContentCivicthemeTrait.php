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
   * Attach paragraph to entity.
   */
  public static function civicthemeParagraphAttach($type, $entity, $field_name, $options, $save = FALSE) {
    if (!$entity->hasField($field_name)) {
      return NULL;
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
  public static function civicthemeParagraphContentAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_content', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Attachment paragraph to a node.
   */
  public static function civicthemeParagraphAttachmentAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'summary' => '',
      'attachments' => [],
      'image' => NULL,
      'icon' => NULL,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return NULL;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_attachment', $node, $field_name, $options, TRUE);

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

    $defaults = [
      'panels' => NULL,
      'expand_all' => FALSE,
    ];

    $options += $defaults;

    if (!empty($options['panels']) && count($options['panels']) > 0) {
      $panels = $options['panels'];
      unset($options['panels']);
    }

    // Expand all.
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
   * Attach Accordion paragraph to a node.
   */
  public static function civicthemeParagraphCalloutAttach($node, $field_name, $options) {
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

    $paragraph = self::civicthemeParagraphAttach('civictheme_callout', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Slider paragraph to a node.
   */
  public static function civicthemeParagraphCardContainerAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    if (!empty($options['cards']) && count($options['cards']) > 0) {
      $cards = $options['cards'];
      unset($options['cards']);
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_card_container', $node, $field_name, $options);

    if (empty($paragraph)) {
      return;
    }

    // Cards.
    if (!empty($cards)) {
      foreach ($cards as $card_options) {
        if (!empty($card_options['type']) && !empty($card_options['options'])) {
          $card = self::civicthemeParagraphAttach($card_options['type'], $paragraph, 'field_c_p_cards', $card_options['options'], TRUE);
          if (!empty($card)) {
            $paragraph->field_c_p_cards->appendItem($card);
          }
        }
      }
    }

    $paragraph->save();
    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Slider paragraph to a node.
   */
  public static function civicthemeParagraphSliderAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
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
        $slide = self::civicthemeParagraphAttach('civictheme_slider_slide', $paragraph, 'field_c_p_slides', $slide_options, TRUE);
        if (!empty($slide)) {
          $paragraph->field_c_p_slides->appendItem($slide);
        }
      }
    }

    $paragraph->save();
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
      'summary' => '',
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
   * Attach Promo paragraph to a node.
   */
  public static function civicthemeParagraphNextStepAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'summary' => '',
      'link' => FALSE,
      'image' => NULL,
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
   * Attach Map paragraph to a node.
   */
  public static function civicthemeParagraphMapAttach($node, $field_name, $options) {
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

    $paragraph = self::civicthemeParagraphAttach('civictheme_map', $node, $field_name, $options, TRUE);

    if (empty($paragraph)) {
      return;
    }

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
      'attributes' => '',
      'width' => '',
      'height' => '',
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
   * Attach Iframe paragraph to a node.
   */
  public static function civicthemeParagraphWebformAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_webform', $node, $field_name, $options, TRUE);

    if (!empty($paragraph)) {
      $node->{$field_name}->appendItem($paragraph);
    }
  }

  /**
   * Attach Listing paragraph to a node.
   */
  public static function civicthemeParagraphListingAttach($node, $field_name, $options) {
    if (!$node->hasField($field_name)) {
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_listing', $node, $field_name, $options);

    if (empty($paragraph)) {
      return;
    }

    if ($options['show_filters']) {
      $paragraph->field_c_p_listing_f_exposed = CsGeneratedContentHelper::randomListingFilters();
    }

    // Default to show all.
    $paragraph->field_c_p_listing_limit = 0;
    if ($options['limit_type'] && $options['limit_type'] == 'limited') {
      $paragraph->field_c_p_listing_limit = rand(9, 20);
    }

    $paragraph->save();

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Get Cards default options.
   */
  public static function civicthemeCardsDefaultOptions($type) {
    $options = [
      'task_card' => [
        'icon' => CsGeneratedContentHelper::randomIcon(),
        'link' => CsGeneratedContentHelper::randomLinkFieldValue(),
        'summary' => CsGeneratedContentHelper::plainParagraph(),
        'title' => 'TC H ' . CsGeneratedContentHelper::sentence(1),
      ],
      'navigation_card' => [
        'image' => CsGeneratedContentHelper::randomImage(),
        'link' => CsGeneratedContentHelper::randomLinkFieldValue(),
        'summary' => CsGeneratedContentHelper::plainParagraph(),
        'title' => 'NC H ' . CsGeneratedContentHelper::sentence(1),
      ],
      'service_card' => [
        'links' => [
          CsGeneratedContentHelper::randomLinkFieldValue(),
          CsGeneratedContentHelper::randomLinkFieldValue(),
          CsGeneratedContentHelper::randomLinkFieldValue(),
        ],
        'title' => 'SC H ' . CsGeneratedContentHelper::sentence(1),
      ],
      'promo_card_ref' => [
        'reference' => CsGeneratedContentHelper::randomNodes(1, [
          'civictheme_event',
          'civictheme_page',
        ]),
      ],
      'event_card_ref' => [
        'reference' => CsGeneratedContentHelper::randomNode('civictheme_event'),
      ],
      'navigation_card_ref' => [
        'reference' => CsGeneratedContentHelper::randomNodes(1, [
          'civictheme_event',
          'civictheme_page',
        ]),
      ],
      'event_card' => [
        'date' => CsGeneratedContentHelper::date(),
        'image' => CsGeneratedContentHelper::randomImage(),
        'link' => CsGeneratedContentHelper::randomLinkFieldValue(),
        'location' => 'E L ' . CsGeneratedContentHelper::sentence(1),
        'summary' => CsGeneratedContentHelper::plainParagraph(),
        'title' => 'EC H ' . CsGeneratedContentHelper::sentence(1),
        'topic' => CsGeneratedContentHelper::randomTopics(1),
      ],
      'promo_card' => [
        'date' => CsGeneratedContentHelper::date(),
        'image' => CsGeneratedContentHelper::randomImage(),
        'link' => CsGeneratedContentHelper::randomLinkFieldValue(),
        'summary' => CsGeneratedContentHelper::plainParagraph(),
        'title' => 'PC H ' . CsGeneratedContentHelper::sentence(1),
        'topics' => CsGeneratedContentHelper::randomTopics(),
      ],
      'subject_card_ref' => [
        'reference' => CsGeneratedContentHelper::randomNodes(1, [
          'civictheme_event',
          'civictheme_page',
        ]),
        'topic' => CsGeneratedContentHelper::randomTopics(1),
      ],
      'subject_card' => [
        'image' => CsGeneratedContentHelper::randomImage(),
        'link' => CsGeneratedContentHelper::randomLinkFieldValue(),
        'title' => 'TC H ' . CsGeneratedContentHelper::sentence(1),
      ],
      'publication_card' => [
        'document' => CsGeneratedContentHelper::randomDocument(),
        'image' => CsGeneratedContentHelper::randomImage(),
        'size' => CsGeneratedContentHelper::randomFieldAllowedValue('paragraph', 'civictheme_publication_card', 'field_c_p_size'),
        'summary' => CsGeneratedContentHelper::plainParagraph(),
        'title' => 'EC H ' . CsGeneratedContentHelper::sentence(1),
      ],
    ];

    if (!empty($type) && isset($options[$type])) {
      return $options[$type];
    }

    return [];
  }

  /**
   * Check that value exists in the provided options or return the first option.
   */
  protected static function civicthemeValueFromOptions(array $options, $value) {
    return in_array($value, $options) ? $value : reset($options);
  }

}
