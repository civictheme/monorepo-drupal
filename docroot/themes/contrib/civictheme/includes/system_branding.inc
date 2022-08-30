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

    $desktop_logo = theme_get_setting("components.logo.image_{$context_theme}_desktop");
    $desktop_logo = $desktop_logo ?? '';
    $mobile_logo = theme_get_setting("components.logo.image_{$context_theme}_mobile");
    $mobile_logo = $mobile_logo ?? '';

    $variables['modifier_class'] = $is_footer_region ? 'civictheme-footer__logo' : 'civictheme-header__logo';

    $alt = theme_get_setting('components.logo.image_alt') ?? t('CivicTheme logo');

    if (!empty($desktop_logo)) {
      $variables['logos']['desktop'] = [
        'src' => '/' . ltrim($desktop_logo, '/'),
        'alt' => $alt,
      ];
    }

    if (!empty($mobile_logo)) {
      $variables['logos']['mobile'] = [
        'src' => '/' . ltrim($mobile_logo, '/'),
        'alt' => $alt,
      ];
    }
  }
}
