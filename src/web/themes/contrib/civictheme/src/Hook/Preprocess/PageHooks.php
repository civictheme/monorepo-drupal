<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook\Preprocess;

use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;
use Drupal\civictheme\Utility\CivicthemeField;
use Drupal\civictheme\Utility\CivicthemeNode;
use Drupal\Component\Utility\Html;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;

/**
 * Html and page preprocess hook implementations for CivicTheme.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class PageHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_preprocess_HOOK() for html.
   */
  #[Hook('preprocess_html')]
  public function preprocessHtml(array &$variables): void {
    $this->preprocessHtmlSkipLink($variables);
    $this->preprocessHtmlSiteSection($variables);
    $this->preprocessHtmlSearchHeadTitle($variables);

    // Disable modifier_class as this template is provided by Drupal.
    $variables['modifier_class'] = FALSE;
  }

  /**
   * Implements hook_preprocess_HOOK() for page.
   */
  #[Hook('preprocess_page')]
  public function preprocessPage(array &$variables): void {
    // All pages.
    $this->preprocessPageGeneric($variables);

    // Node pages.
    $this->preprocessPageNode($variables);

    // Preprocessing for Alerts functionality.
    $this->preprocessPageAlert($variables);

    // Add all page variables to the root variables. This is required so that
    // child themes could add page-level variables without have to extend
    // page template (the variables there are not scoped as an exception).
    //
    // IMPORTANT! Do not add anything below this line or components will not
    // be able to discover variables.
    $page_variables = array_filter($variables['page'], static function ($key): bool {
      return !str_starts_with($key, '#');
    }, ARRAY_FILTER_USE_KEY);
    $variables += $page_variables;
  }

  /**
   * HTML pre-process for a Skip Link.
   */
  protected function preprocessHtmlSkipLink(array &$variables): void {
    /** @var \Drupal\civictheme\CivicthemeConfigManager $theme_config_manager */
    $theme_config_manager = \Drupal::classResolver(CivicthemeConfigManager::class);

    $variables['skip_link_theme'] = $theme_config_manager->load('components.skip_link.theme');
  }

  /**
   * HTML pre-process for a site section.
   */
  protected function preprocessHtmlSiteSection(array &$variables): void {
    /** @var \Drupal\node\NodeInterface|null $node */
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof NodeInterface) {
      $site_section = CivicthemeField::getValue($node, 'field_c_n_site_section', TRUE, build: $variables) ?? FALSE;

      if ($site_section) {
        $variables['attributes']['class'][] = 'ct-site-section--' . Html::cleanCssIdentifier(strtolower((string) $site_section->getName()));
        $variables['attributes']['class'][] = 'ct-site-section--' . $site_section->id();
      }
    }
  }

  /**
   * HTML pre-process for search keywords in the head title.
   */
  protected function preprocessHtmlSearchHeadTitle(array &$variables): void {
    /** @var \Drupal\civictheme\CivicthemeConfigManager $theme_config_manager */
    $theme_config_manager = \Drupal::classResolver(CivicthemeConfigManager::class);

    // Load search fields from theme settings.
    $setting_keyword_fields = $theme_config_manager->load('components.search.keyword_fields') ?? '';
    if (empty($setting_keyword_fields)) {
      return;
    }

    // Add search keywords to head title.
    $keywords = NULL;
    $search_fields = CivicthemeUtility::multilineToArray($setting_keyword_fields);
    foreach ($search_fields as $field) {
      $query_field = \Drupal::request()->query->get($field);
      if (!empty($query_field)) {
        $keywords = $query_field;
        break;
      }
    }
    if (empty($keywords)) {
      return;
    }
    $head_title = (string) ($variables['head_title']['title'] ?? '');
    if (empty($head_title)) {
      return;
    }
    $variables['head_title']['title'] = $this->t("@title - Searching for '@keywords'", [
      '@title' => $head_title,
      '@keywords' => $keywords,
    ]);
  }

  /**
   * Generic page pre-processing.
   */
  protected function preprocessPageGeneric(array &$variables): void {
    /** @var \Drupal\civictheme\CivicthemeConfigManager $theme_config_manager */
    $theme_config_manager = \Drupal::classResolver(CivicthemeConfigManager::class);

    // Set header and footer theme from theme settings.
    $variables['page']['header_theme'] = $theme_config_manager->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
    $variables['page']['footer_theme'] = $theme_config_manager->load('components.footer.theme', CivicthemeConstants::FOOTER_THEME_DEFAULT);
    $variables['page']['footer_background_image'] = $theme_config_manager->load('components.footer.background_image.path');

    // Always contain non-node page content (unless provided from elsewhere).
    // For nodes this is set to false via preprocessPageNode() as layout is
    // controlled via layout templates.
    $variables['page']['content_contained'] = $variables['page']['content_contained'] ?? TRUE;

    // Set layout defaults for non-node pages (forms, search, homepage etc).
    // Node pages override these in preprocessPageNode().
    $variables['hide_sidebar_left'] = $variables['hide_sidebar_left'] ?? FALSE;
    $variables['hide_sidebar_right'] = $variables['hide_sidebar_right'] ?? FALSE;
    $variables['vertical_spacing'] = $variables['vertical_spacing'] ?? 'none';
  }

  /**
   * Generic page pre-process for all view modes of a node.
   */
  protected function preprocessPageNode(array &$variables): void {
    /** @var \Drupal\node\Entity\Node $node */
    $node = CivicthemeNode::loadNodeOrRevision($variables);

    // @phpstan-ignore-next-line
    if (!$node) {
      return;
    }

    $vertical_spacing = CivicthemeField::getValue($node, 'field_c_n_vertical_spacing');
    $variables['vertical_spacing'] = in_array($vertical_spacing, ['top', 'bottom', 'both'], TRUE) ? $vertical_spacing : 'none';

    // @todo Add support for hiding of the right sidebar through a node field.
    $variables['hide_sidebar_left'] = (bool) (CivicthemeField::getValue($node, 'field_c_n_hide_sidebar') ?? FALSE);
    $variables['hide_sidebar_right'] = FALSE;

    $layout_builder_settings_per_view_mode = CivicthemeNode::getLayoutBuilderSettingsPerViewMode($node->getEntityTypeId(), $node->bundle());
    if (!empty($layout_builder_settings_per_view_mode)) {
      // Layout builder determines whether a node is contained and what it's
      // layout is, so we set as not contained to allow layout builder to
      // span edge-to-edge.
      $variables['page']['content_contained'] = FALSE;

      $variables['#cache']['contexts'] = Cache::mergeContexts(
        $variables['#cache']['contexts'] ?? [],
        $node->getCacheContexts(),
        [
          'url.query_args',
          'url.path',
        ]
      );

      $context = [
        'node' => $node,
        'layout_builder_settings_per_view_mode' => $layout_builder_settings_per_view_mode,
      ];

      // Allow to suppress sidebars. Note that implementors should ensure
      // that the cacheability metadata is set correctly.
      \Drupal::moduleHandler()->alter('civictheme_layout_suppress_page_regions', $variables, $context);
      \Drupal::service('theme.manager')->alter('civictheme_layout_suppress_page_regions', $variables, $context);
    }
  }

  /**
   * Page pre-process to support showing of alerts.
   */
  protected function preprocessPageAlert(array &$variables): void {
    $variables['attributes']['data-component-name'] = 'ct-alerts';
    $variables['attributes']['data-alert-endpoint'] = '/api/civictheme-alerts?_format=json';
  }

}
