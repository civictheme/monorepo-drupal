<?php

/**
 * @file
 * CivicTheme constants.
 *
 * phpcs:disable DrupalPractice.Commenting.CommentEmptyLine.SpacingAfter
 */

/**
 * Defines 'light' theme machine name.
 */
define('CIVICTHEME_THEME_LIGHT', 'light');

/**
 * Defines 'light' theme label.
 */
define('CIVICTHEME_THEME_LIGHT_LABEL', 'Light');

/**
 * Defines 'dark' theme machine name.
 */
define('CIVICTHEME_THEME_DARK', 'dark');

/**
 * Defines 'dark' theme label.
 */
define('CIVICTHEME_THEME_DARK_LABEL', 'Dark');

// For per-component 'theme' default values we are relying on the defaults set
// in the components' templates.
// Only define per-component theme constants when other components rely on
// them *contextually* (i.e., need to decide current component's theme based on
// the value of the parent component's theme that may not be set in config).
// For other cases - if 'theme' value is missing - NULL will be passed into
// a component template, which will then use component's default 'theme' value.

/**
 * Defines header default theme.
 */
define('CIVICTHEME_HEADER_THEME_DEFAULT', CIVICTHEME_THEME_LIGHT);

/**
 * Defines header default type.
 */
define('CIVICTHEME_HEADER_TYPE_DEFAULT', 'default');

/**
 * Defines footer default theme.
 */
define('CIVICTHEME_FOOTER_THEME_DEFAULT', CIVICTHEME_THEME_DARK);

/**
 * Defines footer default type.
 */
define('CIVICTHEME_FOOTER_TYPE_DEFAULT', 'default');

/**
 * Defines 'default' banner style.
 */
define('CIVICTHEME_BANNER_TYPE_DEFAULT', 'default');

/**
 * Defines 'large' banner style.
 */
define('CIVICTHEME_BANNER_TYPE_LARGE', 'large');

/**
 * Defines Automated list default view name.
 */
define('CIVICTHEME_AUTOMATED_LIST_DEFAULT_VIEW_NAME', 'civictheme_automated_list');

/**
 * Defines Automated list default view display name.
 */
define('CIVICTHEME_AUTOMATED_LIST_DEFAULT_VIEW_DISPLAY_NAME', 'block1');

/**
 * Defines Automated list "limited" limit type.
 */
define('CIVICTHEME_AUTOMATED_LIST_LIMIT_TYPE_LIMITED', 'limited');

/**
 * Defines Automated list "unlimited" limit type.
 */
define('CIVICTHEME_AUTOMATED_LIST_LIMIT_TYPE_UNLIMITED', 'unlimited');

/**
 * Defines Automated list limit fallback.
 */
define('CIVICTHEME_AUTOMATED_LIST_LIMIT_FALLBACK', 12);

/**
 * Defines Automated list default theme.
 */
define('CIVICTHEME_AUTOMATED_LIST_DEFAULT_THEME', CIVICTHEME_THEME_LIGHT);

/**
 * Defines Navigation "none" dropdown type.
 */
define('CIVICTHEME_NAVIGATION_DROPDOWN_NONE', 'none');

/**
 * Defines Navigation "dropdown" dropdown type.
 */
define('CIVICTHEME_NAVIGATION_DROPDOWN_DROPDOWN', 'dropdown');

/**
 * Defines Navigation "drawer" dropdown type.
 */
define('CIVICTHEME_NAVIGATION_DROPDOWN_DRAWER', 'drawer');
