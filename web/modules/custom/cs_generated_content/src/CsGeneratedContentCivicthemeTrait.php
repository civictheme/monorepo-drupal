<?php

declare(strict_types=1);

namespace Drupal\cs_generated_content;

use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\TermInterface;

/**
 * Trait CsGeneratedContentTrait.
 *
 * Trait for centralised CivicTheme components handling.
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
trait CsGeneratedContentCivicthemeTrait {

  /**
   * Light theme name.
   */
  public static function civicthemeThemeLight(): string {
    return 'light';
  }

  /**
   * Dark theme name.
   */
  public static function civicthemeThemeDark(): string {
    return 'dark';
  }

  /**
   * Page content type name.
   */
  public static function civicthemePageContentType(): string {
    return 'civictheme_page';
  }

  /**
   * Event content type name.
   */
  public static function civicthemeEventContentType(): string {
    return 'civictheme_event';
  }

  /**
   * Default Automated list view name.
   */
  public static function civicthemeAutomatedListType(): string {
    return 'civictheme_automated_list__block1';
  }

  /**
   * Limited type name.
   */
  public static function civicthemeAutomatedListLimitTypeLimited(): string {
    return 'limited';
  }

  /**
   * Unlimited type name.
   */
  public static function civicthemeAutomatedListLimitTypeUnlimited(): string {
    return 'unlimited';
  }

  /**
   * Promo card type name.
   */
  public static function civicthemePromoCardType(): string {
    return 'civictheme_promo_card';
  }

  /**
   * Navigation card type name.
   */
  public static function civicthemeNavigationCardType(): string {
    return 'civictheme_navigation_card';
  }

  /**
   * Snippet type name.
   */
  public static function civicthemeSnippetType(): string {
    return 'civictheme_snippet';
  }

  /**
   * Available theme names.
   *
   * @return array<int, string>
   *   Theme names.
   */
  public static function civicthemeThemes(): array {
    return [
      static::civicthemeThemeLight(),
      static::civicthemeThemeDark(),
    ];
  }

  /**
   * Spacing - none.
   */
  public static function civicthemeVerticalSpacingTypeNone(): string {
    return 'none';
  }

  /**
   * Spacing - top.
   */
  public static function civicthemeVerticalSpacingTypeTop(): string {
    return 'top';
  }

  /**
   * Spacing - bottom.
   */
  public static function civicthemeVerticalSpacingTypeBottom(): string {
    return 'bottom';
  }

  /**
   * Spacing - both.
   */
  public static function civicthemeVerticalSpacingTypeBoth(): string {
    return 'both';
  }

  /**
   * Types of spaces.
   *
   * @return array<int, string>
   *   Types of spaces.
   */
  public static function civicthemeVerticalSpacingTypes(): array {
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
  public static function civicthemeBannerTypeDefault(): string {
    return 'default';
  }

  /**
   * Banner type - Large.
   */
  public static function civicthemeBannerTypeLarge(): string {
    return 'large';
  }

  /**
   * Types of banner.
   *
   * @return array<int, string>
   *   Types of banner.
   */
  public static function civicthemeBannerTypes(): array {
    return [
      static::civicthemeBannerTypeDefault(),
      static::civicthemeBannerTypeLarge(),
    ];
  }

  /**
   * Image position - left.
   */
  public static function civicthemeImagePositionLeft(): string {
    return 'left';
  }

  /**
   * Image position - right.
   */
  public static function civicthemeImagePositionRight(): string {
    return 'right';
  }

  /**
   * Image blend-mode.
   */
  public static function civicthemeImageBlendMode(): string {
    return 'soft-light';
  }

  /**
   * Small size name.
   */
  public static function civicthemeSizeSmall(): string {
    return 'small';
  }

  /**
   * Large size name.
   */
  public static function civicthemeSizeLarge(): string {
    return 'large';
  }

  /**
   * Static Topics.
   *
   * @param int|null $count
   *   Number of topics to return.
   *
   * @return array<int, \Drupal\taxonomy\TermInterface>
   *   Array of topics.
   */
  public static function civicthemeStaticTopics(int $count = NULL): array {
    return static::staticTerms('civictheme_topics', $count);
  }

  /**
   * Static Topic.
   */
  public static function civicthemeStaticTopic(): ?TermInterface {
    $entities = static::civicthemeStaticTopics(1);

    return count($entities) > 0 ? reset($entities) : NULL;
  }

  /**
   * Expose single listing filter type.
   *
   * @return array<int, string>
   *   Filter types.
   */
  public static function civicThemeExposeSingleFilter(): array {
    return ['topic'];
  }

  /**
   * Expose multiple filter types.
   *
   * @return array<int, string>
   *   Filter types.
   */
  public static function civicThemeExposeMultipleFilters(): array {
    return ['topic', 'type', 'title'];
  }

  /**
   * Static Site sections.
   *
   * @param int|null $count
   *   Number of site sections to return.
   *
   * @return array<int, \Drupal\taxonomy\TermInterface>
   *   Array of site sections.
   */
  public static function civicthemeStaticSiteSections(int $count = NULL): array {
    return static::staticTerms('civictheme_site_sections', $count);
  }

  /**
   * Static Site section.
   */
  public static function civicthemeStaticSiteSection(): ?TermInterface {
    $entities = static::civicthemeStaticSiteSections(1);

    return count($entities) > 0 ? reset($entities) : NULL;
  }

  /**
   * Generic component attach helper.
   */
  public static function civicthemeComponentAttach(NodeInterface $node, string $field_name, string $component_type, array $component_options): void {
    $method = 'civicthemeParagraph' . str_replace(' ', '', (ucwords(str_replace('_', ' ', $component_type)))) . 'Attach';

    if (!method_exists(self::class, $method)) {
      throw new \RuntimeException(sprintf('Method "%s" is not defined in "%s" class.', $method, self::class));
    }

    $defaults = [
      'content' => '',
      'theme' => static::civicthemeThemeLight(),
      'vertical_spacing' => static::civicthemeVerticalSpacingTypeNone(),
      'background' => FALSE,
    ];

    $component_options += $defaults;

    if (empty(array_filter($component_options))) {
      return;
    }

    // Theme.
    if (!empty($component_options['theme'])) {
      $component_options['theme'] = static::civicthemeValueFromOptions(static::civicthemeThemes(), $component_options['theme']);
    }

    // Space.
    if (!empty($component_options['vertical_spacing'])) {
      $component_options['vertical_spacing'] = static::civicthemeValueFromOptions(static::civicthemeVerticalSpacingTypes(), $component_options['vertical_spacing']);
    }

    // Background.
    if (!empty($component_options['background'])) {
      $component_options['background'] = (bool) $component_options['background'];
    }

    call_user_func([self::class, $method], $node, $field_name, $component_options);
  }

  /**
   * Attach Content paragraph to a node.
   */
  public static function civicthemeParagraphContentAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

    if (empty($options['content'])) {
      return;
    }

    $options['content'] = static::civicthemeNormaliseRichTextContentValue($options['content']);

    $paragraph = self::civicthemeParagraphAttach('civictheme_content', $node, $field_name, $options, TRUE);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Accordion paragraph to a node.
   */
  public static function civicthemeParagraphAccordionAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

    $options += [
      'panels' => [],
      'expand_all' => FALSE,
    ];

    if (empty($options['panels']) && count($options['panels']) > 0) {
      return;
    }

    $panels = $options['panels'];
    unset($options['panels']);

    if (!empty($options['expand_all'])) {
      $options['expand'] = (bool) $options['expand_all'];
    }
    $paragraph = self::civicthemeParagraphAttach('civictheme_accordion', $node, $field_name, $options);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    foreach ($panels as $panel_options) {
      $panel_options['content'] = static::civicthemeNormaliseRichTextContentValue($panel_options['content'] ?? '');

      if (!empty($panel_options['expand'])) {
        $panel_options['expand'] = (bool) $panel_options['expand'];
      }

      $panel = self::civicthemeParagraphAttach('civictheme_accordion_panel', $paragraph, 'field_c_p_panels', $panel_options, TRUE);
      if ($panel instanceof Paragraph) {
        $paragraph->field_c_p_panels->appendItem($panel);
      }
    }

    $paragraph->save();

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach paragraph to entity.
   *
   * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected static function civicthemeParagraphAttach(string $type, FieldableEntityInterface $entity, string $field_name, array $options, bool $save = FALSE): ?Paragraph {
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
   *
   * @param string|array $value
   *   Field value.
   *
   * @return array<string, string>
   *   Normalised field value.
   */
  protected static function civicthemeNormaliseRichTextContentValue(string|array $value): array {
    $value = is_array($value) ? $value : ['value' => $value];

    return $value + [
      'value' => '',
      'format' => 'civictheme_rich_text',
    ];
  }

  /**
   * Check that value exists in the provided options or return the first option.
   */
  protected static function civicthemeValueFromOptions(array $options, mixed $value): mixed {
    return in_array($value, $options) ? $value : reset($options);
  }

  /**
   * Attach Attachment paragraph to a node.
   */
  public static function civicthemeParagraphAttachmentAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'title' => '',
      'content' => '',
      'attachments' => [],
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_attachment', $node, $field_name, $options);

    $paragraph->save();

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Callout paragraph to a node.
   */
  public static function civicthemeParagraphCalloutAttach(NodeInterface $node, string $field_name, array $options): void {
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
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_callout', $node, $field_name, $options);

    $paragraph->save();

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Campaign paragraph to a node.
   */
  public static function civicthemeParagraphCampaignAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

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
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_campaign', $node, $field_name, $options, TRUE);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Iframe paragraph to a node.
   */
  public static function civicthemeParagraphIframeAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'url' => '',
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_iframe', $node, $field_name, $options, TRUE);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Map paragraph to a node.
   */
  public static function civicthemeParagraphMapAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'embed_url' => '',
      'address' => '',
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_map', $node, $field_name, $options, TRUE);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Next Step paragraph to a node.
   */
  public static function civicthemeParagraphNextStepAttach(NodeInterface $node, string $field_name, array $options): void {
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
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_next_step', $node, $field_name, $options, TRUE);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Promo paragraph to a node.
   */
  public static function civicthemeParagraphPromoAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'title' => '',
      'links' => FALSE,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_promo', $node, $field_name, $options, TRUE);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Quote paragraph to a node.
   */
  public static function civicthemeParagraphQuoteAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

    if (empty($options['content'])) {
      return;
    }

    $options['content'] = static::civicthemeNormaliseRichTextContentValue($options['content']);

    $paragraph = self::civicthemeParagraphAttach('civictheme_content', $node, $field_name, $options, TRUE);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Automated list paragraph to a node.
   *
   * @SuppressWarnings(PHPMD.ElseExpression)
   */
  public static function civicthemeParagraphAutomatedListAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

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
      return;
    }

    if ($options['list_limit_type'] == static::civicthemeAutomatedListLimitTypeLimited()) {
      $options['list_limit'] = $options['list_limit'] ?? rand(9, 20);
    }
    else {
      $options['list_limit'] = $options['list_limit'] ?? 0;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_automated_list', $node, $field_name, $options);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Webform paragraph to a node.
   */
  public static function civicthemeParagraphWebformAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'webform' => NULL,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_webform', $node, $field_name, $options, TRUE);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

  /**
   * Attach Slider paragraph to a node.
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   */
  public static function civicthemeParagraphSliderAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'slides' => NULL,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return;
    }

    if (!empty($options['slides']) && count($options['slides']) > 0) {
      $slides = $options['slides'];
      unset($options['slides']);
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_slider', $node, $field_name, $options);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    // Slider slide.
    if (!empty($slides)) {
      foreach ($slides as $slide_options) {
        if (!empty($slide_options['type'])) {
          $type = $slide_options['type'];
          unset($slide_options['type']);
          $slide = self::civicthemeParagraphAttach($type, $paragraph, 'field_c_p_slides', $slide_options, TRUE);
          if ($slide instanceof Paragraph) {
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
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   */
  public static function civicthemeParagraphManualListAttach(NodeInterface $node, string $field_name, array $options): void {
    if (!$node->hasField($field_name)) {
      return;
    }

    $defaults = [
      'list_column_count' => 1,
      'list_fill_width' => NULL,
      'theme' => self::civicthemeThemeLight(),
      'list_link_above' => NULL,
      'list_link_below' => NULL,
    ];

    $options += $defaults;

    if (empty(array_filter($options))) {
      return;
    }

    if (!empty($options['list_items']) && count($options['list_items']) > 0) {
      $list_items = $options['list_items'];
      unset($options['list_items']);
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_manual_list', $node, $field_name, $options);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    // Slider slide.
    if (!empty($list_items)) {
      foreach ($list_items as $list_item_options) {
        if (!empty($list_item_options['type'])) {
          $type = $list_item_options['type'];
          unset($list_item_options['type']);
          $card = self::civicthemeParagraphAttach($type, $paragraph, 'field_c_p_list_items', $list_item_options, TRUE);
          if ($card instanceof Paragraph) {
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
  public static function civicthemeParagraphSearchAttach(NodeInterface $node, string $field_name, array $options): void {
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
      return;
    }

    $paragraph = self::civicthemeParagraphAttach('civictheme_search', $node, $field_name, $options, TRUE);

    if (!$paragraph instanceof Paragraph) {
      return;
    }

    $node->{$field_name}->appendItem($paragraph);
  }

}
