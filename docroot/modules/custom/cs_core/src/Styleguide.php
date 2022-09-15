<?php

namespace Drupal\cs_core;

/**
 * Class Styleguide.
 *
 * Styleguide module integration.
 *
 * @package Drupal\cs_core
 */
class Styleguide {

  /**
   * Alter items.
   *
   * @param array $items
   *   Array of items passed by reference.
   */
  public function alter(array &$items) {
    $this->removeUnused($items);
  }

  /**
   * Alter header link variables.
   *
   * @param array $variables
   *   Array of header link variables passed by reference.
   */
  public function preprocessLinks(array &$variables) {
    $this->wrapLinksinColumns($variables);
  }

  /**
   * Removed unused items.
   *
   * @param array $items
   *   Array of items passed by reference.
   */
  protected function removeUnused(array &$items) {
    $items = array_diff_key($items, array_flip($this->getUnused()));
  }

  /**
   * List of unused items.
   *
   * @return string[]
   *   Array of unused items.
   */
  protected function getUnused() {
    return [
      'text_format',
      'filter_tips',
      'filter_tips_long',
      'system_powered_by',
      'feed_icon',
      'menu_tree',
      'Display Suite',
      'form-vertical-tabs',
    ];
  }

  /**
   * Wrap header links to turn the into columns for readbility.
   *
   * @param array $variables
   *   Array of header link variables passed by reference.
   */
  protected function wrapLinksinColumns(array &$variables) {
    $items = [];

    foreach ($variables['items'] as $k => $item) {
      $prefix = '';
      $suffix = '';

      if ($k == 0) {
        $prefix = '<div style="display: flex; flex-wrap: wrap;">';
      }

      $items[] = [
        '#type' => 'inline_template',
        '#template' => $prefix . '<div class="column" style="width: 25%">',
      ];

      $items[] = $item;

      if ($k == count($variables['items']) - 1) {
        $suffix = '</div>';
      }

      $items[] = ['#markup' => $suffix . '</div>'];
    }

    $variables['items'] = $items;
  }

}
