<?php

namespace Drupal\civictheme_migrate\Component;

/**
 * Class ManualList.
 *
 * Represents a ManualList component.
 */
class ManualList extends AbstractCivicThemeComponent {

  /**
   * {@inheritdoc}
   */
  public static function getSrcFields(): array {
    return ['children'];
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareData($data, array $context): array {
    $data = $data['children'] ?? $data;

    if (!empty($data['cards']['children'])) {
      $cards = [];
      foreach ($data['children']['cards']['children'] as $children) {
        foreach ($children as $card_type => $card_children) {
          $card_info = $card_children['children'];
          $summary = $card_info['item_content']['value'] ?? '';
          $card = [
            'type' => 'civictheme_' . $card_type,
            'title' => $card_info['item_title'],
            'summary' => strip_tags($summary),
            'links' => $card_info['item_links'] ?? [],
            'location' => $card_info['item_location'] ?? '',
          ];

          // Link process.
          if (!empty($card_info['item_link'][0])) {
            $card['link'] = array_combine(['uri', 'title'], $card_info['item_link'][0]);
          }

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

}
