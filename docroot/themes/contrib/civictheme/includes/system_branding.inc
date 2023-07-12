<?php

/**
 * @file
 * System branding block theme alterations.
 */

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
 * @SuppressWarnings(ElseExpression)
 */
function civictheme_preprocess_block__system_branding_block(&$variables) {
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
    $variables['type'] = str_replace('_', '-', $logo_type) ?? CivicthemeConstants::LOGO_TYPE_DEFAULT;
    $variables['modifier_class'] = $is_footer_region ? 'ct-footer__logo' : 'ct-header__logo';
    $logo_alt = civictheme_get_theme_config_manager()->load('components.logo.image_alt') ?? '';

    foreach (['primary', 'secondary'] as $type) {
      foreach (['desktop', 'mobile'] as $breakpoint) {
        $logo_image = civictheme_get_theme_config_manager()->load("components.logo.{$type}.{$theme}.{$breakpoint}.path", '');
        if (!empty($logo_image)) {
          if (is_svg_extension($logo_image)) {
            $logo_image = '/' . ltrim($logo_image, '/');
            $modifier_class = 'ct-logo__image ct-logo__image--' . $breakpoint . ' ';
            if ($breakpoint == 'mobile') {
              if ($type == 'inline' && $logo_type == 'secondary') {
                $modifier_class .= 'hide-xxs';
              }
              else {
                $modifier_class .= 'hide-l';
              }
            }
            else {
              $modifier_class .= 'hide-xxs show-l';
            }
            $svg_logo = civictheme_embed_svg($logo_image, [$modifier_class]);
          }
          $variables['logos'][$type][$breakpoint] = [
            'url' => $logo_image,
            'alt' => $logo_alt,
            'svg_logo' => $svg_logo ?? NULL,
          ];
        }
      }
    }
  }

  if ($variables['show_site_slogan']) {
    $variables['site_slogan'] = Xss::filterAdmin($variables['elements']['content']['site_slogan']['#markup'] ?? '');
  }
}
