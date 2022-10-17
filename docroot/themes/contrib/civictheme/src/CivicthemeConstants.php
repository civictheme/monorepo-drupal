<?php

namespace Drupal\civictheme;

/**
 * CivicTheme constants.
 */
final class CivicthemeConstants {

  /**
   * Defines 'light' theme machine name.
   */
  const THEME_LIGHT = 'light';

  /**
   * Defines 'light' theme label.
   */
  const THEME_LIGHT_LABEL = 'Light';

  /**
   * Defines 'dark' theme machine name.
   */
  const THEME_DARK = 'dark';

  /**
   * Defines 'dark' theme label.
   */
  const THEME_DARK_LABEL = 'Dark';

  // For per-component 'theme' default values we are relying on the defaults set
  // in the components' templates.
  // Only define per-component theme constants when other components rely on
  // them *contextually* (i.e., need to decide current component's theme based
  // on the value of the parent component's theme that may not be set in
  // config).
  // For other cases - if 'theme' value is missing - NULL will be passed into
  // a component template, which will then use component's default 'theme'
  // value.
  /**
   * Defines header default theme.
   */
  const HEADER_THEME_DEFAULT = self::THEME_LIGHT;

  /**
   * Defines footer default theme.
   */
  const FOOTER_THEME_DEFAULT = self::THEME_DARK;

  /**
   * Defines logo default type.
   */
  const LOGO_TYPE_DEFAULT = 'default';

  /**
   * Defines logo default type.
   */
  const LOGO_TYPE_INLINE = 'inline';

  /**
   * Defines logo default type.
   */
  const LOGO_TYPE_STACKED = 'stacked';

  /**
   * Defines footer default type.
   */
  const FOOTER_TYPE_DEFAULT = 'default';

  /**
   * Defines 'default' banner style.
   */
  const BANNER_TYPE_DEFAULT = 'default';

  /**
   * Defines 'large' banner style.
   */
  const BANNER_TYPE_LARGE = 'large';

  /**
   * Defines Automated list default view name.
   */
  const AUTOMATED_LIST_DEFAULT_VIEW_NAME = 'automated_list';

  /**
   * Defines Automated list default view display name.
   */
  const AUTOMATED_LIST_DEFAULT_VIEW_DISPLAY_NAME = 'block1';

  /**
   * Defines Automated list "limited" limit type.
   */
  const AUTOMATED_LIST_LIMIT_TYPE_LIMITED = 'limited';

  /**
   * Defines Automated list "unlimited" limit type.
   */
  const AUTOMATED_LIST_LIMIT_TYPE_UNLIMITED = 'unlimited';

  /**
   * Defines Automated list limit fallback.
   */
  const AUTOMATED_LIST_LIMIT_FALLBACK = 12;

  /**
   * Defines Automated list default theme.
   */
  const AUTOMATED_LIST_DEFAULT_THEME = self::THEME_LIGHT;

  /**
   * Defines Navigation "none" dropdown type.
   */
  const NAVIGATION_DROPDOWN_NONE = 'none';

  /**
   * Defines Navigation "dropdown" dropdown type.
   */
  const NAVIGATION_DROPDOWN_DROPDOWN = 'dropdown';

  /**
   * Defines Navigation "drawer" dropdown type.
   */
  const NAVIGATION_DROPDOWN_DRAWER = 'drawer';

}
