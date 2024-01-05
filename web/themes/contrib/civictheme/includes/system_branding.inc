<?php

/**
 * @file
 * System branding block theme alterations.
 */

declare(strict_types=1);

use Drupal\block\Entity\Block;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_block__HOOK().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function civictheme_preprocess_block__system_branding_block(array &$variables): void {
  $variables['show_site_logo'] = !empty($variables['elements']['#configuration']['use_site_logo']);
  $variables['show_site_name'] = !empty($variables['elements']['#configuration']['use_site_name']);
  $variables['show_site_slogan'] = !empty($variables['elements']['#configuration']['use_site_slogan']);

  $is_footer_region = !empty($variables['elements']['#id']) && str_starts_with(Block::load($variables['elements']['#id'])->getRegion(), 'footer');

  $theme = $is_footer_region
    ? civictheme_get_theme_config_manager()->load('components.footer.theme', CivicthemeConstants::FOOTER_THEME_DEFAULT)
    : civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);

  $variables['theme'] = $theme;

  if ($variables['show_site_logo']) {
    $variables['url'] = Url::fromRoute('<front>')->setAbsolute()->toString();
    $variables['title'] = Xss::filterAdmin($variables['elements']['content']['site_slogan']['#markup'] ?? '');

    $logo_type = $is_footer_region ? civictheme_get_theme_config_manager()->load('components.footer.logo_type') : civictheme_get_theme_config_manager()->load('components.header.logo_type');
    $variables['type'] = str_replace('_', '-', $logo_type ?? CivicthemeConstants::LOGO_TYPE_DEFAULT);
    $variables['modifier_class'] = $is_footer_region ? 'ct-footer__logo' : 'ct-header__logo';
    $logo_alt = civictheme_get_theme_config_manager()->load('components.logo.image_alt') ?? '';

    foreach (['primary', 'secondary'] as $type) {
      foreach (['desktop', 'mobile'] as $breakpoint) {
        $logo_image = civictheme_get_theme_config_manager()->load(sprintf('components.logo.%s.%s.%s.path', $type, $theme, $breakpoint), '');
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
