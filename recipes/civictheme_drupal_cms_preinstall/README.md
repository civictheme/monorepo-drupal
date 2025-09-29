# CivicTheme Preinstall Recipe

## Purpose
This Drupal recipe serves as a preinstallation step for CivicTheme, ensuring that the theme is properly enabled before other configuration steps. This is a workaround recipe that addresses a known Drupal core issue where layouts need to be registered before they can be used in recipes.

## Description
- **Recipe Name:** CivicTheme
- **Type:** Enabling civictheme to register layouts
- **License:** GPL-2.0-or-later

## What Gets Installed

### Modules/Themes
- **civictheme**: Enables the CivicTheme theme

## Technical Background
This recipe exists to work around a Drupal core limitation documented in [Issue #3204271](https://www.drupal.org/project/drupal/issues/3204271). The issue relates to the fact that layouts provided by themes need to be registered in the system before they can be referenced in configuration imports.

By enabling CivicTheme in a separate preinstall recipe, we ensure that:
1. The theme is enabled and its layouts are registered
2. Subsequent recipes can safely reference CivicTheme layouts in their configuration

## Requirements
- Drupal core compatible with recipes
- CivicTheme theme package available

## Installation
This recipe should be applied before the main CivicTheme configuration recipe. It's typically used as part of a multi-step recipe application process:

1. Apply `civictheme_drupal_cms__preinstall` recipe (this recipe)
2. Apply `civictheme_drupal_cms` recipe (main configuration)

## Notes
- This is a minimal recipe that only handles theme enablement
- No configuration imports or actions are performed
- This workaround may become unnecessary once the core issue is resolved
- The recipe name includes double underscores (`__preinstall`) to indicate it's a special-purpose preinstallation recipe