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
  $show_site_logo = !empty($variables['elements']['#configuration']['use_site_logo']);
  $show_site_name = !empty($variables['elements']['#configuration']['use_site_name']);
  $show_site_slogan = !empty($variables['elements']['#configuration']['use_site_slogan']);

  $is_footer_region = !empty($variables['elements']['#id']) && str_starts_with(Block::load($variables['elements']['#id'])->getRegion(), 'footer');

  $theme = $is_footer_region
    ? civictheme_get_theme_config_manager()->load('components.footer.theme', CivicthemeConstants::FOOTER_THEME_DEFAULT)
    : civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);

  $modifier_class = civictheme_extract_modifier_class($variables);

  $variables['content'] = [];

  if ($show_site_logo) {
    $url = Url::fromRoute('<front>')->setAbsolute()->toString();
    $title = Xss::filterAdmin($variables['elements']['content']['site_slogan']['#markup'] ?? '');
    $logo_type = $is_footer_region ? civictheme_get_theme_config_manager()->load('components.footer.logo_type') : civictheme_get_theme_config_manager()->load('components.header.logo_type');
    $type = str_replace('_', '-', $logo_type ?? CivicthemeConstants::LOGO_TYPE_DEFAULT);
    $logo_modifier_class = trim(($is_footer_region ? 'ct-footer__logo' : 'ct-header__logo') . ' ' . ($modifier_class ?? ''));

    $logos = [];
    foreach (['primary', 'secondary'] as $logo_kind) {
      $logo_alt = civictheme_get_theme_config_manager()->load(sprintf('components.logo.%s.image_alt', $logo_kind)) ?? '';
      foreach (['desktop', 'mobile'] as $breakpoint) {
        $logo_image = civictheme_get_theme_config_manager()->load(sprintf('components.logo.%s.%s.%s.path', $logo_kind, $theme, $breakpoint), '');
        if (!empty($logo_image)) {
          $logos[$logo_kind][$breakpoint] = [
            'url' => '/' . ltrim((string) $logo_image, '/'),
            'alt' => $logo_alt,
          ];
        }
      }
    }

    $variables['content']['logo'] = [
      '#type' => 'component',
      '#component' => 'civictheme:logo',
      '#props' => array_filter([
        'logos' => $logos ?: NULL,
        'url' => $url,
        'title' => $title,
        'theme' => $theme,
        'type' => $type,
        'modifier_class' => $logo_modifier_class,
      ], fn ($v) => $v !== NULL),
    ];
  }

  $site_name = $variables['elements']['content']['site_name']['#markup'] ?? '';
  if ($show_site_name && $site_name) {
    $variables['content']['site_name'] = [
      '#type' => 'component',
      '#component' => 'civictheme:link',
      '#props' => array_filter([
        'theme' => $theme,
        'text' => $site_name,
        'url' => Url::fromRoute('<front>')->setAbsolute()->toString(),
      ], fn ($v) => $v !== NULL),
    ];
  }

  if ($show_site_slogan) {
    $site_slogan = Xss::filterAdmin($variables['elements']['content']['site_slogan']['#markup'] ?? '');
    if ($site_slogan) {
      $variables['content']['site_slogan'] = [
        '#type' => 'component',
        '#component' => 'civictheme:paragraph',
        '#props' => array_filter([
          'theme' => $theme,
          'content' => $site_slogan,
        ], fn ($v) => $v !== NULL),
      ];
    }
  }
}
