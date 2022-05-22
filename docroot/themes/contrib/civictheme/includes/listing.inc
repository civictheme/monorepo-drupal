<?php

/**
 * @file
 * Listing paragraph component.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Element;
use Drupal\Core\Url;
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
define('CIVICTHEME_LISTINGS_FULL_FALLBACK_LIMIT', 100);

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_listing(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  // Get the parent bundle.
  $variables['view_as'] = $paragraph->get('field_c_p_view_as')->getString();
  $variables['limit'] = $paragraph->field_c_p_listing_limit->value;
  $variables['attributes']['id'] = Html::getUniqueId('listing-' . $paragraph->id());
  if (is_array($variables['attributes'])) {
    $variables['modifier_class'] = implode(' ', $variables['attributes']['class']);
    unset($variables['attributes']['class']);
    $variables['attributes'] = new Attribute($variables['attributes']);
  }
  $variables['listing'] = _civictheme_listing_element($paragraph, $variables);
  $form_elements = Element::children($variables['listing']['view']['#view']->exposed_widgets);
  foreach ($form_elements as $form_element) {
    $variables['listing']['view']['#view']->exposed_widgets[$form_element]['#civic_theme'] = $variables['theme'];
  }
  _civictheme_render_listing_paragraph_add_read_more($paragraph, $variables);
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
 */
function _civictheme_listing_element(Paragraph $paragraph) {
  $view_name = 'civictheme_listing';

  // Call modules/theme that implement the hook, and let them change the view.
  \Drupal::moduleHandler()->alter('civictheme_listing_view_name', $view_name);
  \Drupal::service('theme.manager')->alter('civictheme_listing_view_name', $view_name);

  $view_display = 'civictheme_listing_block';
  $show_filters = (int) $paragraph->field_c_p_show_filters->value;

  $view = Views::getView($view_name);
  if ($view) {
    $view->setDisplay($view_display);

    _civictheme_listing_alter_view($paragraph, $view);
    _civictheme_listing_preprocess_view($paragraph, $view);

    $view->initHandlers();
    $element = [];
    // Show exposed form if available for this block display.
    if ($show_filters) {
      /** @var \Drupal\views\Plugin\views\exposed_form\ExposedFormPluginInterface $exposed_form */
      $exposed_form = $view->display_handler->getPlugin('exposed_form');
      $view->exposed_widgets = $exposed_form->renderExposedForm();
      // Fixes drupal views bug where if there is a page display within a view
      // even if you are wanting to render the block view form the action path
      // of the exposed form points to the page display view.
      // @see https://www.drupal.org/project/drupal/issues/2844823
      $view->exposed_widgets['#action'] = Url::fromRoute('<current>')->toString();
      $view->display_handler->displaysExposed();
    }
  }
  $element['view'] = $view->render();

  return $element;
}

/**
 * Adds preprocessing data to view to provide to listing child components.
 */
function _civictheme_listing_preprocess_view($paragraph, &$view) {
  $link = $paragraph->hasField('field_c_p_link') && !$paragraph->get('field_c_p_link')->isEmpty() ? $paragraph->get('field_c_p_link')->first() : NULL;

  $view->civictheme_listing = [
    'theme' => _civictheme_paragraph_get_theme($paragraph) !== FALSE ? _civictheme_paragraph_get_theme($paragraph) : 'light',
    'link' => [
      'text' => $link->get('title')->getString(),
      'is_external' => $link->isExternal(),
      'is_new_window' => $link->isExternal(),
      'url' => $link->getUrl()->toString(),
    ],
    'title' => _civictheme_paragraph_field_has_value($paragraph, 'field_c_p_title') ? $paragraph->get('field_c_p_title')->getString() : '',
    'with_background' => _civictheme_paragraph_field_has_value($paragraph, 'field_c_p_background') ? $paragraph->get('field_c_p_background')->getString() : '',
    'vertical_space' => _civictheme_paragraph_field_has_value($paragraph, 'field_c_p_space') ? $paragraph->get('field_c_p_space')->getString() : '',
  ];
}

/**
 * Loads and alters view for civictheme listing.
 */
function _civictheme_listing_alter_view($paragraph, &$view) {
  $fallback_limit = CIVICTHEME_LISTINGS_FULL_FALLBACK_LIMIT;
  $title = $paragraph->field_c_p_title->value;
  $content_type = $paragraph->field_c_p_content_type->value;
  $topics = _civictheme_get_entity_field_values($paragraph, 'field_c_p_topics', 'target_id');
  $view_mode = $paragraph->field_c_p_view_as->value;
  $limit_type = $paragraph->field_c_p_limit_type->value;
  $limit = (int) $paragraph->field_c_p_listing_limit->value;
  $items_per_page = (int) $paragraph->field_c_p_listing_limit->value;
  $show_pager = !empty($paragraph->field_c_p_show_pager->value);
  $show_count = empty($paragraph->field_c_p_hide_count->value);

  if ($view) {
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
    if ($limit_type == CIVICTHEME_LISTINGS_LIMIT_TYPE_LIMITED) {
      // Prevent endless lists by using a fallback limit if a limit is 0.
      $limit = $limit > 0 ? $limit : $fallback_limit;
      // Changing pager type to 'some' and setting 'items_per_page'.
      $pager = $view->display_handler->getOption('pager');
      $pager['type'] = 'some';
      $pager['options']['items_per_page'] = $limit;
      $view->display_handler->setOption('pager', $pager);
    }
    else {
      if ($show_pager && $limit_type == CIVICTHEME_LISTINGS_LIMIT_TYPE_UNLIMITED) {
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
  }

}

/**
 * Add 'read more' entry to variables array if the option was added.
 *
 * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
 *   Paragraph being rendered.
 * @param array $variables
 *   Render array variables.
 */
function _civictheme_render_listing_paragraph_add_read_more(Paragraph $paragraph, array &$variables) {
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
function _civictheme_get_entity_field_values(EntityInterface $entity, $field_name, $value_key = 'value') {
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
