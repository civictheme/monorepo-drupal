<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook\Preprocess;

use Drupal\block\Entity\Block;
use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\Theme\Block\BannerBuilder;
use Drupal\civictheme\Theme\Block\MobileNavigationBuilder;
use Drupal\civictheme\Theme\Navigation\MenuTreeMapper;
use Drupal\civictheme\Utility\CivicthemeAttributeHelper;
use Drupal\civictheme\Utility\CivicthemeFieldHelper;
use Drupal\civictheme\Utility\CivicthemeMediaHelper;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Render\Markup;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Url;
use Drupal\link\Plugin\Field\FieldType\LinkItem;
use Drupal\media\MediaInterface;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Block preprocess hook implementations for CivicTheme.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class BlockHooks {

  /**
   * Implements hook_preprocess_HOOK() for block.
   */
  #[Hook('preprocess_block')]
  public function preprocessBlock(array &$variables): void {
    $this->preprocessBlockDrupal($variables);
    $this->preprocessBlockSystemMainBlock($variables);
    BannerBuilder::preprocess($variables);
    $this->preprocessBlockNavigation($variables);
    $this->preprocessBlockContent($variables);
    MobileNavigationBuilder::preprocess($variables);
    $this->preprocessBlockSocialLinks($variables);
    $this->preprocessBlockFooter($variables);
  }

  /**
   * Implements hook_preprocess_HOOK() for block__civictheme_search.
   */
  #[Hook('preprocess_block__civictheme_search')]
  public function preprocessBlockCivicthemeSearch(array &$variables): void {
    if ($variables['base_plugin_id'] != 'block_content') {
      return;
    }

    $entity = $variables['elements']['content']['#block_content'];
    if ($entity->bundle() != 'civictheme_search' || ($entity->hasField('field_c_b_link') && $entity->field_c_b_link->isEmpty())) {
      return;
    }

    $variables['content'] = NULL;
    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
    $link = CivicthemeFieldHelper::getValue($entity, 'field_c_b_link', TRUE);
    if ($link !== NULL) {
      $variables['url'] = $link->getUrl()->toString();
      $variables['text'] = $link->get('title')->getString();
      if (CivicthemeFieldHelper::getValue($entity, 'field_c_b_link_in_mobile_menu')) {
        // Hide search block on mobile view.
        $variables['modifier_class'] = 'hide-xxs show-m-flex';
      }
    }

    $variables['theme'] = $this->configManager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
  }

  /**
   * Implements hook_preprocess_HOOK() for block__system_branding_block.
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   * @SuppressWarnings(PHPMD.NPathComplexity)
   */
  #[Hook('preprocess_block__system_branding_block')]
  public function preprocessBlockSystemBrandingBlock(array &$variables): void {
    $variables['show_site_logo'] = !empty($variables['elements']['#configuration']['use_site_logo']);
    $variables['show_site_name'] = !empty($variables['elements']['#configuration']['use_site_name']);
    $variables['show_site_slogan'] = !empty($variables['elements']['#configuration']['use_site_slogan']);

    $is_footer_region = !empty($variables['elements']['#id']) && str_starts_with(Block::load($variables['elements']['#id'])->getRegion(), 'footer');

    $theme = $is_footer_region
      ? $this->configManager()->load('components.footer.theme', CivicthemeConstants::FOOTER_THEME_DEFAULT)
      : $this->configManager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);

    $variables['theme'] = $theme;

    if ($variables['show_site_logo']) {
      $variables['url'] = Url::fromRoute('<front>')->setAbsolute()->toString();
      $variables['title'] = Xss::filterAdmin($variables['elements']['content']['site_slogan']['#markup'] ?? '');

      $logo_type = $is_footer_region ? $this->configManager()->load('components.footer.logo_type') : $this->configManager()->load('components.header.logo_type');
      $variables['type'] = str_replace('_', '-', $logo_type ?? CivicthemeConstants::LOGO_TYPE_DEFAULT);
      CivicthemeAttributeHelper::addModifierClass($variables, $is_footer_region ? 'ct-footer__logo' : 'ct-header__logo');

      foreach (['primary', 'secondary'] as $type) {
        $logo_alt = $this->configManager()->load(sprintf('components.logo.%s.image_alt', $type)) ?? '';
        foreach (['desktop', 'mobile'] as $breakpoint) {
          $logo_image = $this->configManager()->load(sprintf('components.logo.%s.%s.%s.path', $type, $theme, $breakpoint), '');
          if (!empty($logo_image)) {
            $variables['logos'][$type][$breakpoint] = [
              'url' => '/' . ltrim((string) $logo_image, '/'),
              'alt' => $logo_alt,
            ];
          }
        }
      }
    }

    if ($variables['show_site_slogan']) {
      $variables['site_slogan'] = Xss::filterAdmin($variables['elements']['content']['site_slogan']['#markup'] ?? '');
    }
  }

  /**
   * Implements preprocess for block__menu_block__civictheme_primary_navigation.
   */
  #[Hook('preprocess_block__menu_block__civictheme_primary_navigation')]
  public function preprocessBlockPrimaryNavigation(array &$variables): void {
    $variables['theme'] = $this->configManager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
    $variables['items'] = $variables['content']['#items'] ?? [];
    $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';
    CivicthemeAttributeHelper::addModifierClass($variables, 'ct-flex-justify-content-end');
    MenuTreeMapper::processMenuItems($variables['items'], FALSE);
  }

  /**
   * Implements preprocess for the secondary navigation menu block.
   */
  #[Hook('preprocess_block__menu_block__civictheme_secondary_navigation')]
  public function preprocessBlockSecondaryNavigation(array &$variables): void {
    $variables['theme'] = $this->configManager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
    $variables['items'] = $variables['content']['#items'] ?? [];
    $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';
    CivicthemeAttributeHelper::addModifierClass($variables, 'ct-flex-justify-content-end');
    MenuTreeMapper::processMenuItems($variables['items'], FALSE);
  }

  /**
   * Implements preprocess for the sidebar navigation menu block.
   */
  #[Hook('preprocess_block__menu_block__civictheme_sidebar_navigation')]
  public function preprocessBlockSidebarNavigation(array &$variables): void {
    $variables['theme'] = $this->configManager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
    $variables['items'] = $variables['content']['#items'] ?? [];
    $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';
    $variables['label'] = '';
    $variables['in_sidebar'] = TRUE;
    $expand_all_items = $variables['configuration']['expand_all_items'] ?? FALSE;
    MenuTreeMapper::processMenuItems($variables['items'], $expand_all_items);
  }

  /**
   * Implements hook_preprocess_HOOK() for block__layout_builder.
   */
  #[Hook('preprocess_block__layout_builder')]
  public function preprocessBlockLayoutBuilder(array &$variables): void {
    $variables['modifier_class'] = FALSE;
  }

  /**
   * Preprocess block to add drupal_attributes.
   *
   * Creates a copy of attributes as drupal_attributes for use in templates
   * where Drupal's default block classes need to be preserved separately.
   */
  protected function preprocessBlockDrupal(array &$variables): void {
    $attributes = $variables['attributes'] ?? [];

    // Convert to array if it's already an Attribute object.
    if ($attributes instanceof Attribute) {
      $attributes = $attributes->toArray();
    }

    $variables['drupal_attributes'] = new Attribute($attributes);
  }

  /**
   * Preprocess for system_main_block block.
   */
  protected function preprocessBlockSystemMainBlock(array &$variables): void {
    if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'system_main_block') {
      return;
    }

    // Add the most generic class to make text appear styled.
    $variables['attributes']['class'][] = 'ct-text-regular';
    $variables['modifier_class'] = FALSE;
  }

  /**
   * Preprocess for navigation menu blocks.
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   */
  protected function preprocessBlockNavigation(array &$variables): void {
    if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'menu_block') {
      return;
    }

    $menu_name = strtr($variables['elements']['#derivative_plugin_id'], '-', '_');

    $menu_theme_list = [
      'civictheme_primary_navigation',
      'civictheme_secondary_navigation',
      'civictheme_sidebar_navigation',
      'civictheme_footer',
    ];

    if (!empty($variables['elements']['#configuration']['suggestion']) && in_array($variables['elements']['#configuration']['suggestion'], $menu_theme_list)) {
      $menu_name = $variables['elements']['#configuration']['suggestion'];
    }

    if ($menu_name === 'civictheme_primary_navigation' || $menu_name === 'civictheme_secondary_navigation') {
      $key = substr($menu_name, strlen('civictheme_'));
      // Set menu theme as per footer theme.
      $variables['theme'] = $this->configManager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
      // Set name for aria-label of <nav> element.
      $variables['name'] = strtr($key, '_navigation', '');
      $variables['items'] = $variables['content']['#items'] ?? [];
      $variables['dropdown'] = $this->configManager()->load(sprintf('components.navigation.%s.dropdown', $key), $menu_name === 'civictheme_primary_navigation' ? CivicthemeConstants::NAVIGATION_DROPDOWN_DRAWER : CivicthemeConstants::NAVIGATION_DROPDOWN_DROPDOWN);
      $variables['dropdown_columns'] = (int) $this->configManager()->load(sprintf('components.navigation.%s.dropdown_columns', $key), 4);
      $variables['dropdown_columns_fill'] = (boolean) $this->configManager()->load(sprintf('components.navigation.%s.dropdown_columns_fill', $key), FALSE);
      $variables['is_animated'] = (boolean) $this->configManager()->load(sprintf('components.navigation.%s.is_animated', $key), FALSE);
      $variables['variant'] = $menu_name === 'civictheme_primary_navigation' ? 'primary' : 'secondary';
      $expand_all_items = $variables['elements']['#configuration']['expand_all_items'] ?? FALSE;
      MenuTreeMapper::processMenuItems($variables['items'], $expand_all_items);
    }

    if ($menu_name === 'civictheme_footer') {
      // Set menu theme as per footer theme.
      $variables['theme'] = $this->configManager()->load('components.footer.theme', CivicthemeConstants::FOOTER_THEME_DEFAULT);
      $variables['items'] = $variables['content']['#items'] ?? [];
      $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';

      MenuTreeMapper::processMenuItems($variables['items']);
    }
  }

  /**
   * Preprocess for block_content block.
   */
  protected function preprocessBlockContent(array &$variables): void {
    if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'block_content') {
      return;
    }

    if ($variables['elements']['#configuration']['label'] === 'Copyright') {
      $variables['attributes']['class'][] = 'copyright';
      $variables['attributes']['class'][] = 'ct-text-regular';
    }
  }

  /**
   * Pre-process for Social Links block.
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   */
  protected function preprocessBlockSocialLinks(array &$variables): void {
    if ($variables['base_plugin_id'] != 'block_content') {
      return;
    }

    $entity = $variables['elements']['content']['#block_content'];
    if ($entity->bundle() != 'civictheme_social_links' || !CivicthemeFieldHelper::hasValue($entity, 'field_c_b_social_icons')) {
      return;
    }

    $items = [];

    $paragraphs = CivicthemeFieldHelper::getReferencedEntities($entity, 'field_c_b_social_icons', $variables);
    foreach ($paragraphs as $paragraph) {
      if (!$paragraph instanceof Paragraph) {
        continue;
      }

      $item = [];

      $link = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_link', TRUE);
      if ($link instanceof LinkItem) {
        $item['url'] = $link->getUrl()->toString();
        $item['title'] = $link->get('title')->getString();
      }

      $media = CivicthemeFieldHelper::getValue($paragraph, 'field_c_p_icon', TRUE, build: $variables);
      if ($media instanceof MediaInterface) {
        $image = CivicthemeMediaHelper::getImageVariables($media);
        if ($image) {
          $svg = CivicthemeMediaHelper::embedSvg($image['url'], ['ct-icon']);
          $item['icon_html'] = $svg !== NULL ? Markup::create($svg) : '';
        }
      }

      $items[] = $item;
    }

    $variables['items'] = $items;

    $variables['theme'] = CivicthemeFieldHelper::getThemeValue($entity);
    $variables['with_border'] = CivicthemeFieldHelper::getValue($entity, 'field_c_b_with_border') ?? FALSE;
  }

  /**
   * Preprocess for footer menu block.
   */
  protected function preprocessBlockFooter(array &$variables): void {
    if (($variables['base_plugin_id'] ?? '') !== 'menu_block') {
      return;
    }

    $menu_name = strtr((string) ($variables['elements']['#derivative_plugin_id'] ?? ''), '-', '_');

    // The footer template is selected by the block's suggestion, so it takes
    // precedence over the menu name.
    $suggestion = $variables['elements']['#configuration']['suggestion'] ?? '';
    if ($suggestion === 'civictheme_footer') {
      $menu_name = $suggestion;
    }

    // Remove the block heading from footer menu blocks: the heading is
    // rendered by the navigation component from the 'title' variable set in
    // preprocessBlockNavigation().
    if ($menu_name === 'civictheme_footer') {
      $variables['label'] = '';
    }
  }

  /**
   * Get the theme config manager.
   */
  protected function configManager(): CivicthemeConfigManager {
    /** @var \Drupal\civictheme\CivicthemeConfigManager $theme_config_manager */
    $theme_config_manager = \Drupal::classResolver(CivicthemeConfigManager::class);

    return $theme_config_manager;
  }

}
