<?php

/**
 * @file
 * System branding block theme alterations.
 */

use Drupal\block\Entity\Block;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_block__HOOK().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_block__system_branding_block(&$variables) {
  $variables['show_site_logo'] = !empty($variables['elements']['#configuration']['use_site_logo']);
  $variables['show_site_name'] = !empty($variables['elements']['#configuration']['use_site_name']);
  $variables['show_site_slogan'] = !empty($variables['elements']['#configuration']['use_site_slogan']);

  if ($variables['show_site_logo']) {
    $variables['url'] = Url::fromRoute('<front>')->setAbsolute()->toString();
    $variables['title'] = Xss::filterAdmin($variables['elements']['content']['site_slogan']['#markup'] ?? '');

    // Make logo switch based on which region it is used in.
    $is_footer_region = !empty($variables['elements']['#id']) && str_starts_with(Block::load($variables['elements']['#id'])->getRegion(), 'footer');

    $context_theme = $is_footer_region ? theme_get_setting('components.footer.theme') : theme_get_setting('components.header.theme');
    $context_theme = $context_theme ?: CIVICTHEME_THEME_LIGHT;

    // Primary logos.
    $primary_desktop_logo = theme_get_setting("components.logo.image_primary_{$context_theme}_desktop");
    $primary_desktop_logo = $primary_desktop_logo ?? '';
    $primary_mobile_logo = theme_get_setting("components.logo.image_primary_{$context_theme}_mobile");
    $primary_mobile_logo = $primary_mobile_logo ?? '';

    // Secondary logos.
    $secondary_desktop_logo = theme_get_setting("components.logo.image_secondary_{$context_theme}_desktop");
    $secondary_desktop_logo = $secondary_desktop_logo ?? '';
    $secondary_mobile_logo = theme_get_setting("components.logo.image_secondary_{$context_theme}_mobile");
    $secondary_mobile_logo = $secondary_mobile_logo ?? '';

    // Logo type.
    $logo_type = $is_footer_region ? theme_get_setting("components.footer.type") : theme_get_setting("components.header.type");
    $variables['type'] = $logo_type ?? 'default';

    $variables['modifier_class'] = $is_footer_region ? 'civictheme-footer__logo' : 'civictheme-header__logo';

    $alt = theme_get_setting('components.logo.image_alt') ?? t('CivicTheme logo');

    if (!empty($primary_desktop_logo)) {
      $variables['logos']['primary']['desktop'] = [
        'src' => '/' . ltrim($primary_desktop_logo, '/'),
        'alt' => $alt,
      ];
    }

    if (!empty($primary_mobile_logo)) {
      $variables['logos']['primary']['mobile'] = [
        'src' => '/' . ltrim($primary_mobile_logo, '/'),
        'alt' => $alt,
      ];
    }

    if (!empty($secondary_desktop_logo)) {
      $variables['logos']['secondary']['desktop'] = [
        'src' => '/' . ltrim($secondary_desktop_logo, '/'),
        'alt' => $alt,
      ];
    }

    if (!empty($secondary_mobile_logo)) {
      $variables['logos']['secondary']['mobile'] = [
        'src' => '/' . ltrim($secondary_mobile_logo, '/'),
        'alt' => $alt,
      ];
    }
  }
}
