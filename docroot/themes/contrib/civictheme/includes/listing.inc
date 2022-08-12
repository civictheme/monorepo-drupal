<?php

/**
 * @file
 * Listing paragraph component.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Element;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\views\Views;
use Drupal\Core\Template\Attribute;

/**
 * Defines limited limit type.
 */
define('CIVICTHEME_LISTINGS_LIMIT_TYPE_LIMITED', 'limited');

/**
 * Defines unlimited limit type.
 */
define('CIVICTHEME_LISTINGS_LIMIT_TYPE_UNLIMITED', 'unlimited');

/**
 * Defines fallback limit of Full listings items if pager is disabled.
 */
define('CIVICTHEME_LISTINGS_FULL_FALLBACK_LIMIT', 12);

/**
 * Defines fallback view name.
 */
define('CIVICTHEME_LISTINGS_DEFAULT_VIEW', 'civictheme_listing__block1');

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_listing(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  // Get the parent bundle.
  $variables['view_as'] = civictheme_get_field_value($paragraph, 'field_c_p_view_as');
  $variables['limit'] = civictheme_get_field_value($paragraph, 'field_c_p_listing_limit');
  $variables['attributes']['id'] = Html::getUniqueId('listing-' . $paragraph->id());
  if (is_array($variables['attributes'])) {
    $variables['modifier_class'] = implode(' ', $variables['attributes']['class']);
    unset($variables['attributes']['class']);
    $variables['attributes'] = new Attribute($variables['attributes']);
  }
  $variables['listing'] = _civictheme_listing_element($paragraph, $variables);
  $form_elements = Element::children($variables['listing']['view']['#view']->exposed_widgets);
  foreach ($form_elements as $form_element) {
    $variables['listing']['view']['#view']->exposed_widgets[$form_element]['#civictheme_theme'] = $variables['theme'];
  }
}

/**
 * Render Listing paragraph.
 *
 * Please, do not optimise conditions in this function - the code is organised
 * this way on purpose to ease readability and debugging.
 *
 * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
 *   The paragraph containing view conditions.
 *
 * @return array
 *   The render array for view and exposed form.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_listing_element(Paragraph $paragraph, &$variables) {
  // Setting up default view name.
  [$view_name, $view_display] = explode('__', CIVICTHEME_LISTINGS_DEFAULT_VIEW);
  _civictheme_listing_get_view_name($view_name, $view_display, $paragraph);

  $view = Views::getView($view_name);
  $element = NULL;
  if ($view) {
    $view->setDisplay($view_display);

    if ($variables['attributes'] instanceof Attribute) {
      $variables['attributes']->setAttribute('data-view', $view->id());
    }

    _civictheme_listing_alter_view($paragraph, $view);
    $view->civictheme_listing_entity = $paragraph;

    $element['view'] = $view->executeDisplay($view_display);
  }

  return $element;
}

/**
 * Get the civictheme listing view name.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_listing_get_view_name(&$view_name, &$display_id, $paragraph) {
  if ($paragraph && $paragraph->hasField('field_c_p_listing_type') && !$paragraph->get('field_c_p_listing_type')->isEmpty()) {
    [$view_name, $display_id] = explode('__', $paragraph->get('field_c_p_listing_type')->getString());
  }
  // Call modules/theme that implement the hook, and let them change the view.
  \Drupal::moduleHandler()->alter('civictheme_listing_view_name', $view_name, $display_id, $paragraph);
  \Drupal::service('theme.manager')->alter('civictheme_listing_view_name', $view_name, $display_id, $paragraph);
}

/**
 * Loads and alters view for civictheme listing.
 */
function _civictheme_listing_alter_view($paragraph, &$view) {
  $title = civictheme_get_field_value($paragraph, 'field_c_p_title');
  $content_type = civictheme_get_field_value($paragraph, 'field_c_p_content_type');
  $topics = civictheme_get_entity_field_key_values($paragraph, 'field_c_p_topics', 'target_id');
  $view_mode = civictheme_get_field_value($paragraph, 'field_c_p_view_as');

  if ($view) {
    $args = [];
    // First view argument - content types.
    $args[] = !empty($content_type) ? $content_type : 'all';
    // Second view argument - Topics.
    $args[] = !empty($topics) ? implode('+', $topics) : $args[] = 'all';

    // Title.
    $view->setTitle($title);
    $view->setArguments($args);

    // View mode.
    if ($view_mode) {
      $view_mode_options = $view->display_handler->getOption('row');
      $view_mode_options['options']['view_mode'] = $view_mode;
      $view->display_handler->setOption('row', $view_mode_options);
    }

    _civictheme_listing_view_pager_alter($paragraph, $view);
  }
}

/**
 * Sets pager for CivicTheme listing.
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_listing_view_pager_alter($paragraph, &$view) {
  $header = $view->display_handler->getOption('header');
  $fallback_limit = CIVICTHEME_LISTINGS_FULL_FALLBACK_LIMIT;
  $limit_type = civictheme_get_field_value($paragraph, 'field_c_p_limit_type');
  $limit = civictheme_get_field_value($paragraph, 'field_c_p_listing_limit');
  $items_per_page = civictheme_get_field_value($paragraph, 'field_c_p_listing_limit');
  $show_filters = civictheme_get_field_value($paragraph, 'field_c_p_show_filters');
  // Do not show pager, but show exact number of items.
  if ($limit_type == CIVICTHEME_LISTINGS_LIMIT_TYPE_LIMITED) {
    // Prevent endless lists by using a fallback limit if a limit is 0.
    $limit = $limit > 0 ? $limit : $fallback_limit;
    // Changing pager type to 'some' and setting 'items_per_page'.
    $pager = $view->display_handler->getOption('pager');
    $pager['type'] = 'some';
    $pager['options']['items_per_page'] = $limit;
    $view->display_handler->setOption('pager', $pager);

    if ($show_filters && isset($header['result']['content'])) {
      $header['result']['content'] = 'Showing @end of @total';
      $view->display_handler->setOption('header', $header);
    }
    else {
      $view->display_handler->setOption('header', []);
    }
  }
  elseif ($limit_type == CIVICTHEME_LISTINGS_LIMIT_TYPE_UNLIMITED) {
    if ($items_per_page == 0) {
      // Show pager and default number of items.
      // This is default view display state, so no change is required.
      // Do not optimise - left empty for clarity.
    }
    else {
      // Show pager with specified number of items per page.
      // Re-using existing pager.
      $view->setItemsPerPage($items_per_page);
    }
  }
}
