<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\civictheme_migrate\Component\Field\BackgroundFieldTrait;
use Drupal\civictheme_migrate\Component\Field\ContentFieldTrait;
use Drupal\civictheme_migrate\Component\Field\ThemeFieldTrait;
use Drupal\civictheme_migrate\Component\Field\TitleFieldTrait;
use Drupal\civictheme_migrate\Component\Field\VerticalSpacingFieldTrait;

/**
 * Class ManualList.
 *
 * Represents a ManualList component.
 */
class ManualList extends AbstractCivicThemeComponent {

  use BackgroundFieldTrait;
  use ContentFieldTrait;
  use ThemeFieldTrait;
  use TitleFieldTrait;
  use VerticalSpacingFieldTrait;

  /**
   * The number of columns to use for the list.
   *
   * @var int
   */
  protected $listColumnCount = 3;

  /**
   * Whether to fill the width of the list.
   *
   * @var bool
   */
  protected $listFillWidth = FALSE;

  /**
   * A link above the list.
   *
   * @var mixed
   */
  protected $listLinkAbove;

  /**
   * A link below the list.
   *
   * @var mixed
   */
  protected $listLinkBelow;

  /**
   * A list of cards.
   *
   * @var array
   */
  protected $cards = [];

  /**
   * Get the list column count.
   */
  public function getListColumnCount(): int {
    return $this->listColumnCount;
  }

  /**
   * Set the list column count.
   */
  public function setListColumnCount(int $value): void {
    $this->listColumnCount = $value;
  }

  /**
   * Get the flag for whether to fill the width of the list.
   */
  public function getListFillWidth(): bool {
    return $this->listFillWidth;
  }

  /**
   * Set the flag for whether to fill the width of the list.
   */
  public function setListFillWidth($value): void {
    $this->listFillWidth = $value;
  }

  /**
   * Get the link above the list.
   */
  public function getListLinkAbove(): mixed {
    return $this->listLinkAbove;
  }

  /**
   * Set the link above the list.
   */
  public function setListLinkAbove(mixed $value): void {
    $this->listLinkAbove = $value;
  }

  /**
   * Get the link below the list.
   */
  public function getListLinkBelow():mixed {
    return $this->listLinkBelow;
  }

  /**
   * Set the link below the list.
   */
  public function setListLinkBelow(mixed $value): void {
    $this->listLinkBelow = $value;
  }

  /**
   * Get a list of cards.
   */
  public function getCards():array {
    return $this->cards;
  }

  /**
   * Set a list of cards.
   */
  public function setCards($values): void {
    $this->cards = $values;
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
    if (!empty($data['children']['cards']['children'])) {
      $cards = [];
      foreach ($data['children']['cards']['children'] as $children) {
        foreach ($children as $card_type => $card_info) {
          $summary = $card_info['item_content']['value'] ?? '';
          $card = [
            'type' => 'civictheme_' . $card_type,
            'title' => $card_info['item_title'],
            'summary' => strip_tags($summary),
            'links' => $card_info['item_links'] ?? [],
            'location' => $card_info['item_location'] ?? '',
          ];

          // Document for publication card.
          if (!empty($card_info['item_document'][0])) {
            $lookup_result = $this->migrateLookup->lookup('media_civictheme_document', [$card_info['item_document'][0]]);
            if (!empty($lookup_result)) {
              $card['document'] = $lookup_result[0]['mid'];
            }
          }

          // Image lookup.
          if (!empty($card_info['item_image'][0])) {
            $lookup_result = $this->migrateLookup->lookup('media_civictheme_image', [$card_info['item_image'][0]]);
            if (!empty($lookup_result)) {
              $card['image'] = $lookup_result[0]['mid'];
            }
          }

          // Topics.
          if (!empty($card_info['item_topics'])) {
            $topics = $card_info['item_topics'];
            if (count($topics) > 0) {
              foreach ($topics as $topic) {
                $props = [
                  'name' => $topic,
                  'vid' => 'civictheme_topics',
                ];
                $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadByProperties($props);
                if (!empty($terms)) {
                  $term = reset($terms);
                  $card['topics'][] = $term->id();
                }
                else {
                  $card['topics'][] = $this->entityTypeManager->getStorage('taxonomy_term')->create([
                    'name' => $topic,
                    'vid' => 'civictheme_topics',
                  ])->save();
                }
              }
            }
          }

          $cards[] = $card;
        }
      }

      $data['cards'] = $cards;
    }

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  protected function build(): mixed {
    $paragraph = parent::build();

    if (!empty($this->cards)) {
      foreach ($this->cards as $card_options) {
        if (!empty($card_options['type'])) {
          $type = $card_options['type'];
          unset($card_options['type']);
          $card = self::createParagraph($type, $card_options, 'field_c_p_', TRUE);
          if (!empty($card)) {
            $paragraph->field_c_p_list_items->appendItem($card);
          }
        }
      }
    }

    $paragraph->save();

    return $paragraph;
  }

}
