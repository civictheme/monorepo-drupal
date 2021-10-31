<?php

/**
 * @file
 * Listing paragraph component.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\views\Views;

/**
 * Defines limited limit type.
 */
define('CIVIC_LISTINGS_LIMIT_TYPE_LIMITED', 'limited');

/**
 * Defines unlimited limit type.
 */
define('CIVIC_LISTINGS_LIMIT_TYPE_UNLIMITED', 'unlimited');

/**
 * Defines fallback limit of Full listings items if pager is disabled.
 */
define('CIVIC_LISTINGS_FULL_FALLBACK_LIMIT', 100);

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_listing(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  // Get the parent bundle.
  $variables['view_as'] = $paragraph->get('field_c_p_view_as')->getString();
  $variables['limit'] = $paragraph->field_c_p_listing_limit->value;
  $variables['uniqId'] = Html::getUniqueId('listing-' . $paragraph->id());

  $variables['listing'] = _civic_render_listing_paragraph($paragraph);

  _civic_render_listing_paragraph_add_read_more($paragraph, $variables);
}

/**
 * Render Listing paragraph.
 *
 * Please, do not optimise conditions in this function - the code is organised
 * this way on purpose to ease readability and debugging.
 *
 * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
 *   The paragraph to render.
 *
 * @return null|string
 *   The rendered paragraph markup or NULL.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_render_listing_paragraph(Paragraph $paragraph) {
  $markup = [];

  $view_name = 'civic_listing';

  // Call modules that implement the hook, and let them change the view name.
  \Drupal::moduleHandler()->alter('civic_listing_view_name', $view_name);

  $default_view_display = $view_display = 'civic_listing_block';
  $fallback_limit = CIVIC_LISTINGS_FULL_FALLBACK_LIMIT;

  $title = $paragraph->field_c_p_title->value;
  $content_type = $paragraph->field_c_p_content_type->value;
  $topics = _civic_get_entity_field_values($paragraph, 'field_c_p_topics', 'target_id');
  $view_mode = $paragraph->field_c_p_view_as->value;
  $limit_type = $paragraph->field_c_p_limit_type->value;
  $limit = (int) $paragraph->field_c_p_listing_limit->value;
  $items_per_page = (int) $paragraph->field_c_p_listing_limit->value;
  $show_pager = !empty($paragraph->field_c_p_show_pager->value);
  $show_count = empty($paragraph->field_c_p_hide_count->value);
  $show_filters = (int) $paragraph->field_c_p_show_filters->value;

  $view = Views::getView($view_name);

  if ($view) {

    // Fallback to default display if requested display not loading.
    if (!$view->setDisplay($view_display)) {
      $view->setDisplay($default_view_display);
    }

    $args = [];

    // First view argument - content types.
    $args[] = !empty($content_type) ? $content_type : 'all';

    // Second view argument - Topics.
    if (!empty($topics)) {
      $args[] = implode('+', $topics);
    }
    else {
      $args[] = 'all';
    }

    // Title.
    $view->setTitle($title);
    $view->setArguments($args);

    // View mode.
    if ($view_mode) {
      $view_mode_options = $view->display_handler->getOption('row');
      $view_mode_options['options']['view_mode'] = $view_mode;
      $view->display_handler->setOption('row', $view_mode_options);
    }

    // Pager.
    $pager = NULL;
    // Do not show pager, but show exact number of items.
    if ($limit_type == CIVIC_LISTINGS_LIMIT_TYPE_LIMITED) {
      // Prevent endless lists by using a fallback limit if a limit is 0.
      $limit = $limit > 0 ? $limit : $fallback_limit;
      // Changing pager type to 'some' and setting 'items_per_page'.
      $pager = $view->display_handler->getOption('pager');
      $pager['type'] = 'some';
      $pager['options']['items_per_page'] = $limit;
      $view->display_handler->setOption('pager', $pager);
    }
    else {
      if ($show_pager && $limit_type == CIVIC_LISTINGS_LIMIT_TYPE_UNLIMITED) {
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
      else {
        // Prevent endless lists by using a fallback limit if a limit is 0.
        $items_per_page = $fallback_limit;
        // Changing pager type to 'some' and setting 'items_per_page'.
        $pager = $view->display_handler->getOption('pager');
        $pager['type'] = 'some';
        $pager['options']['items_per_page'] = $items_per_page;
        $view->display_handler->setOption('pager', $pager);
      }
    }

    // Hide header if should not show.
    if (!$show_count) {
      $view->display_handler->setOption('header', []);
    }

    $view->initHandlers();
    // Show exposed form if available for this block display.
    if ($show_filters) {
      /** @var \Drupal\views\Plugin\views\exposed_form\ExposedFormPluginInterface $exposed_form */
      $exposed_form = $view->display_handler->getPlugin('exposed_form');
      $markup['exposed_form'] = $exposed_form->renderExposedForm();
    }

    $markup['view'] = $view->render();
  }

  return $markup;
}

/**
 * Add 'read more' entry to variables array if the option was added.
 *
 * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
 *   Paragraph being rendered.
 * @param array $variables
 *   Render array variables.
 */
function _civic_render_listing_paragraph_add_read_more(Paragraph $paragraph, array &$variables) {
  // Read More link.
  if ($read_more_uri = $paragraph->field_c_p_read_more->uri) {
    $read_more_title = $paragraph->field_c_p_read_more->title;
    $read_more_uri = Url::fromUri($read_more_uri);
    if ($read_more_uri) {
      $variables['read_more'] = Link::fromTextAndUrl($read_more_title, $read_more_uri);
    }
  }
}

/**
 * Get raw multivalued field values from the entity.
 *
 * @param \Drupal\Core\Entity\EntityInterface $entity
 *   The entity to get the values from.
 * @param string $field_name
 *   The field name.
 * @param string $value_key
 *   (optional) The value key. Default to 'value'.
 *
 * @return array
 *   Array of field values. If field does not exist or incorrect $value_key
 *   provided, empty array will be returned.
 */
function _civic_get_entity_field_values(EntityInterface $entity, $field_name, $value_key = 'value') {
  $values = [];

  if ($entity->hasField($field_name)) {
    foreach ($entity->get($field_name)->getValue() as $value) {
      if (isset($value[$value_key])) {
        $values[] = $value[$value_key];
      }
    }
  }

  return $values;
}
