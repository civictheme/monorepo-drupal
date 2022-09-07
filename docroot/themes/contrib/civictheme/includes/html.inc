<?php

/**
 * @file
 * HTML preprocess.
 */

use Drupal\node\NodeInterface;
use Drupal\Component\Utility\Html;

/**
 * Generic HTML pre-process.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_html(&$variables) {
  /** @var \Drupal\node\Entity\Node $node */
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof NodeInterface) {
    $site_section = civictheme_get_field_value($node, 'field_c_n_site_section', TRUE) ?? FALSE;

    if ($site_section) {
      $variables['attributes']['class'][] = 'civictheme-site-section-' . Html::cleanCssIdentifier(strtolower($site_section->getName()));
    }
  }
}
