<?php

declare(strict_types=1);

namespace Drupal\civictheme;

/**
 * CivicTheme constants.
 */
final class CivicthemeConstants {

  /**
   * Defines a value 'inherit'.
   */
  const INHERIT = 'inherit';

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

  const VERTICAL_SPACING_NONE = 'none';

  const VERTICAL_SPACING_TOP = 'top';

  const VERTICAL_SPACING_BOTTOM = 'bottom';

  const VERTICAL_SPACING_BOTH = 'both';

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
   * Defines default banner theme.
   */
  const BANNER_THEME_DEFAULT = self::THEME_LIGHT;

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
   * Defines a limit of filter options on the Automated list.
   *
   * If there are more options than the limit - the filter will render as
   * standard fields.
   *
   * @todo Move to configuration.
   */
  const AUTOMATED_LIST_SINGLE_FILTER_LIMIT = 50;

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

  /**
   * Defines component summary length.
   */
  const COMPONENT_SUMMARY_DEFAULT_LENGTH = 160;

  /**
   * Defines card summary length.
   *
   * @deprecated in civictheme:1.6.2 and is removed from civictheme:1.8.0.
   * Replaced by COMPONENT_SUMMARY_DEFAULT_LENGTH.
   * @see https://www.drupal.org/project/civictheme/issues/3409009
   */
  const CARD_SUMMARY_DEFAULT_LENGTH = 160;

  /**
   * Defines an optout string for views exposed filters.
   */
  const OPTOUT_VIEWS_EXPOSED_FILTER = 'CivicThemeOptoutViewsExposedFilter';

  /**
   * Defines an optout string for views table style.
   */
  const OPTOUT_VIEWS_STYLE_TABLE = 'CivicThemeOptoutViewsStyleTable';

}
