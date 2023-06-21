<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\civictheme_migrate\Component\Field\BackgroundFieldTrait;
use Drupal\civictheme_migrate\Component\Field\HelperFieldTrait;
use Drupal\civictheme_migrate\Component\Field\ThemeFieldTrait;
use Drupal\civictheme_migrate\Component\Field\VerticalSpacingFieldTrait;
use Drupal\Component\Utility\NestedArray;

/**
 * Attachment component.
 *
 * Represents an Attachment component.
 */
class Accordion extends AbstractCivicThemeComponent {

  use HelperFieldTrait;
  use BackgroundFieldTrait;
  use ThemeFieldTrait;
  use VerticalSpacingFieldTrait;

  /**
   * Array of panels.
   *
   * @var array
   */
  protected $panels = [];

  /**
   * Whether to expand all panels initially.
   *
   * @var bool
   */
  protected $expand = FALSE;

  /**
   * Get the panels.
   *
   * @return array
   *   Array of panels.
   */
  public function getPanels(): array {
    return $this->panels;
  }

  /**
   * Set the panels.
   *
   * @param array $value
   *   Array of panels.
   */
  public function setPanels(array $value): void {
    $this->panels = $value;
  }

  /**
   * Get whether to expand all panels initially.
   */
  public function getExpand(): bool {
    return $this->expand;
  }

  /**
   * Set whether to expand all panels initially.
   */
  public function setExpand($value): void {
    $this->expand = (bool) $value;
  }

  /**
   * {@inheritdoc}
   */
  public static function migrateFields(): array {
    return ['children'];
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareStub($data, array $context): array {
    if (!empty($data['children']['accordion_list']['children'])) {
      $panels = [];
      foreach ($data['children']['accordion_list']['children'] as $items) {
        $children = NestedArray::getValue($items, [
          'accordion_items',
          'children',
        ]);
        foreach ($children as $child) {
          if (empty($child['item_title']) || empty($child['item_content']['value'])) {
            continue;
          }
          $panels[] = [
            'title' => $child['item_title'],
            'content' => $this->preprocessContent($child['item_content']['value']),
          ];
        }
      }

      $data['panels'] = $panels;
    }

    if (!empty($options['expand_all'])) {
      $data['expand'] = (bool) $data['expand_all'];
    }

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  protected function build(): mixed {
    $paragraph = parent::build();

    if (!empty($this->panels)) {
      foreach ($this->panels as $panel_data) {
        if (!empty($panel_data['expand'])) {
          $panel_data['expand'] = (bool) $panel_data['expand'];
        }

        $panel = self::createParagraph('civictheme_accordion_panel', $panel_data, 'field_c_p_', TRUE);
        if (!empty($panel)) {
          $paragraph->field_c_p_panels->appendItem($panel);
        }
      }

      $paragraph->save();
    }

    return $paragraph;
  }

}
