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
    $alt = theme_get_setting('civictheme_site_logo_alt') ?? t('CivicTheme logo');

    $is_footer_region = !empty($variables['elements']['#id']) && strpos(Block::load($variables['elements']['#id'])->getRegion(), 'footer') === 0;
    $desktop_logo = $is_footer_region ? theme_get_setting('civictheme_footer_logo_desktop') : theme_get_setting('logo')['url'];
    $desktop_logo = $desktop_logo ?? '';
    $mobile_logo = $is_footer_region ? theme_get_setting('civictheme_footer_logo_mobile') : theme_get_setting('civictheme_header_logo_mobile');
    $mobile_logo = $mobile_logo ?? '';
    $variables['modifier_class'] = $is_footer_region ? 'civictheme-footer__logo' : 'civictheme-header__logo';

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
