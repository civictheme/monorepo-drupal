# CivicTheme Drupal module for GovCMS

----

Version: `{{ VERSION }}`

## CivicTheme Setup Script for GovCMS

This script automates the installation and configuration of CivicTheme, the `civictheme_govcms` support module, and a new custom subtheme within your GovCMS project.

### Prerequisites

1. **GovCMS Project:** You must have a GovCMS project / scaffold already set up.
2. **Ahoy:** `ahoy` must be installed and configured for your project (i.e., you can run `ahoy` commands).
3. **Docker:** Docker must be running, as `ahoy` typically interacts with Docker containers.
4. **`tar` and `curl`:** These command-line utilities must be available in your shell environment where you run the script.
5. **Bash:** The script is written for Bash.

## What the Script Does

The script will perform the following actions:
1. Download and extract the specified CivicTheme version into `themes/custom/civictheme`.
2. Run initial CivicTheme provisioning commands.
3. Clear Drupal caches.
4. Enable CivicTheme and set it as the default theme temporarily.
5. Download, install, configure, and then uninstall and remove the `civictheme_govcms` module (as per the specified workflow).
6. Generate a new subtheme based on CivicTheme using the provided names and description.
7. Enable the new subtheme and set it as the site's default theme.

After the script completes successfully, your new subtheme will be active and ready for customisation in `themes/custom/<your_subtheme_machine_name>`.


### Running the script and setting up CivicTheme for your GovCMS SaaS installation

1. **Download and Setup the Script:**
   From your GovCMS project root directory, run:
   ```bash
   curl -o setup_civictheme.sh \
     https://raw.githubusercontent.com/civictheme/civictheme_govcms/refs/heads/main/scripts/setup_civictheme.sh \
     && chmod +x setup_civictheme.sh
   ```

**Note:** Download the script to your GovCMS project root directory on your host machine (not inside a Docker container).

## How to Use

1. **Run the Script:**
   Execute the script from your GovCMS project's root directory. You'll need to provide values for all the required arguments.

   **Command Structure:**
   ```bash
   ./setup_civictheme.sh -c <civictheme_version> \
                         -g <govcms_module_ref> \
                         -m <subtheme_machine_name> \
                         -u "<subtheme_human_name>" \
                         -d "<subtheme_description>" \
                         [-n] \
                         [-p]
   ```

**Note:** Make sure to run the script from your GovCMS project root directory on your host machine (not inside a Docker container).

2. After completing installation, delete the `setup_civictheme.sh` file from your repository.

**Arguments:**

- `-c <civictheme_version>`: **(Required)** The version of the CivicTheme base theme to download (e.g., "1.11.0").
- `-g <govcms_module_ref>`: **(Required)** The Git reference (branch or tag) for the `civictheme_govcms` module.
  - For a branch: e.g., "main"
  - For a tag: e.g., "1.0.1" or "v1.0.1"
- `-m <subtheme_machine_name>`: **(Required)** The machine-readable name for your new subtheme. Use lowercase letters, numbers, and hyphens/underscores (e.g., "my_custom_site_theme").
- `-u "<subtheme_human_name>"`: **(Required)** The human-readable name for your new subtheme. Enclose in quotes if it contains spaces (e.g., "My Custom Site Theme").
- `-d "<subtheme_description>"`: **(Required)** A short description for your new subtheme. Enclose in quotes (e.g., "A custom theme for My Awesome GovCMS Project").
- `-n`: **(Optional)** Skip content provisioning. By default, the script will provision demo content. Use this flag to skip that step.
- `-p`: **(Optional)** Apply Drupal cache backend [patch](https://www.drupal.org/files/issues/2023-07-16/3204271-20-missing-layout-exception.patch) ([drupal.org issue](https://www.drupal.org/node/3204271)). This patches LayoutPluginManager to add cache tags for better cache invalidation.

1. **Example:**

   ```bash
   ./setup_civictheme.sh -c "{{ VERSION }}" \
                         -g "main" \
                         -m "my_gov_project_theme" \
                         -u "My Gov Project Theme" \
                         -d "Custom theme for the My Gov Project website on GovCMS."
   ```

   ```bash
   ./setup_civictheme.sh \
     -c "1.11.0" \
     -g "{{ VERSION }}" \
     -m "my_gov_theme" \
     -u "My Awesome Gov Theme" \
     -d "A custom subtheme for GovCMS." \
     -p
   ```

   To skip content provisioning:
   ```bash
   ./setup_civictheme.sh \
     -c "1.11.0" \
     -g "{{ VERSION }}" \
     -m "my_gov_theme" \
     -u "My Awesome Gov Theme" \
     -d "A custom subtheme for GovCMS." \
     -n
   ```

2. **View Help:**
   For a reminder of the options and usage:
   ```bash
   ./setup_civictheme.sh -h
   ```

## How to use this module manually

These instructions are not needed if you are using the automated script.

1. Enable module
2. Run Drush `drush civictheme_govcms:remove-config` command to remove GovCMS
   configurations.

## Drush Commands

Remove all GovCMS configurations:

    drush civictheme_govcms:remove-config

Remove all GovCMS configurations, but preserve `media_types`
and `content_types`:

    drush civictheme_govcms:remove-config --preserve=media_types,content_types

List of `--preserve` options:

- `media_types`
- `text_format`
- `fields`
- `content_types`
- `vocabularies`
- `user_roles`
- `menus`
- `pathauto_patterns`

## Other resources

- [CivicTheme Source site](https://github.com/civictheme/monorepo-drupal)
- [CivicTheme UI Kit](https://github.com/civictheme/uikit)
- [CivicTheme Drupal theme](https://github.com/civictheme/civictheme)
- [Default content for CivicTheme](https://github.com/civictheme/civictheme_content)

---
