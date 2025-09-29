# CivicTheme Drupal CMS Recipe

## Purpose
This Drupal recipe provides a complete installation and configuration of the CivicTheme theme for Drupal CMS. It sets up CivicTheme as the default theme and configures all necessary components for a fully functional civic-focused website.

## Description
- **Recipe Name:** CivicTheme
- **Type:** Theme installer
- **License:** GPL-2.0-or-later

## What Gets Installed

### Dependencies
- **core/recipes/basic_block_type**: Core recipe for basic block type functionality

### Modules/Themes
- **civictheme**: The main CivicTheme theme

### Configuration Imports
The recipe imports configuration for:
- **Shortcut**: All shortcut configurations
- **Media**:
  - Full view mode for media entities (`core.entity_view_mode.media.full`)
- **Media Library**:
  - Media library form mode (`core.entity_form_mode.media.media_library`)
  - Media library view mode (`core.entity_view_mode.media.media_library`)
- **CivicTheme**: All CivicTheme-specific configurations

### Configuration Actions
The recipe performs the following configuration changes:

1. **Sets CivicTheme as Default Theme**
   - Updates `system.theme` to set CivicTheme as the default theme

2. **Disables Default Blocks**
   The following CivicTheme blocks are disabled by default:
   - Site branding
   - Help
   - Main menu
   - Messages
   - Account menu
   - Breadcrumbs
   - Page title
   - Powered by Drupal
   - Primary admin actions
   - Footer menu
   - Tabs

## Requirements
- Drupal core compatible with recipes
- Media and Media Library modules

## Installation
This recipe can be applied to your Drupal installation using the standard recipe application process. It will automatically configure CivicTheme as your default theme with optimized settings.

## Notes
- The recipe uses non-strict configuration import mode (`strict: false`) to allow for flexible configuration merging
- Default blocks are disabled to allow for custom block placement and configuration
- This recipe is designed to provide a clean starting point for CivicTheme-based sites