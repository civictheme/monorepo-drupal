<?php

/**
 * @file
 * Site section.
 */

declare(strict_types=1);

use Drupal\Component\Utility\Html;
use Drupal\node\NodeInterface;

/**
 * HTML pre-process for a site section.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_html__site_section(array &$variables): void {
  /** @var \Drupal\node\Entity\Node $node */
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof NodeInterface) {
    $site_section = civictheme_get_field_value($node, 'field_c_n_site_section', TRUE) ?? FALSE;

    if ($site_section) {
      $variables['attributes']['class'][] = 'ct-site-section--' . Html::cleanCssIdentifier(strtolower((string) $site_section->getName()));
      $variables['attributes']['class'][] = 'ct-site-section--' . $site_section->id();
    }
  }
}
