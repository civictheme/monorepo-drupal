<?php

declare(strict_types = 1);

namespace Drupal\civictheme_dev\Plugin\ConfigFilter;

use Drupal\config_filter\Plugin\ConfigFilterBase;

/**
 * Ignore selected config items.
 *
 * These items should not be included in any configuration shipped with a
 * default CivicTheme configuration.
 *
 * Any profile specific config items (e.g. permissions for roles as in GovCMS)
 * roles are added manually or by installing an accompanying module
 * (e.g. civictheme_govcms).
 *
 * @ConfigFilter(
 *   id = "civictheme_dev_config_ignore",
 *   label = "CS Core Config Ignore",
 *   weight = 100
 * )
 */
class CivicthemeDevIgnoreFilter extends ConfigFilterBase {

  /**
   * {@inheritdoc}
   *
   * @phpstan-ignore-next-line
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   * @SuppressWarnings(PHPMD.NPathComplexity)
   */
  public function filterWrite($name, array $data): array {
    // Exclude permissions and dependencies for user roles.
    // phpcs:disable Drupal.Functions.DiscouragedFunctions.Discouraged
    // @phpstan-ignore-next-line
    if (fnmatch('user.role.*', $name)) {
      $role_name = substr($name, strlen('user.role.'));

      // Exclude permissions.
      $permissions = $this->getExcludedRolePermissions($role_name);
      if (isset($data['permissions'])) {
        $data['permissions'] = array_values(array_diff($data['permissions'], $permissions));
      }

      // Exclude modules, but only if permissions that are not ignored do not
      // depend on them.
      $modules = $this->getExcludedRoleDependencyModules($role_name);
      $used_modules = $this->getPermissionsProviders($data['permissions']);
      $modules = array_diff($modules, $used_modules);
      if (isset($data['dependencies']['module'])) {
        foreach ($data['dependencies']['module'] as $key => $module_name) {
          if (in_array($module_name, $modules)) {
            unset($data['dependencies']['module'][$key]);
          }
        }
        $data['dependencies']['module'] = array_values($data['dependencies']['module']);
      }
    }

    // Exclude hidden fields added by the optional modules.
    // @phpstan-ignore-next-line
    if (fnmatch('core.entity_view_display.*', $name)) {
      unset($data['hidden']['search_api_excerpt']);
    }

    // Exclude processing for some properties of the blocks.
    // @phpstan-ignore-next-line
    if (fnmatch('block.block.civictheme_*', $name)) {
      // Unset dependencies.
      unset($data['dependencies']['content']);

      if (!empty($data['dependencies']['module'])) {
        foreach ($data['dependencies']['module'] as $key => $module_name) {
          if (in_array($module_name, ['block_content'])) {
            unset($data['dependencies']['module'][$key]);
          }
        }
        if (empty($data['dependencies']['module'])) {
          unset($data['dependencies']['module']);
        }
      }
    }

    // Exclude processing for some properties of the views.
    // @phpstan-ignore-next-line
    if (fnmatch('views.view.civictheme_*', $name) && !str_contains($name, 'example') && !str_contains($name, 'test')) {
      if (!empty($data['display'])) {
        foreach (array_keys($data['display']) as $display_name) {
          if (!empty($data['display'][$display_name]['display_options']['display_extenders'])) {
            foreach (array_keys($data['display'][$display_name]['display_options']['display_extenders']) as $extender_name) {
              if (str_starts_with((string) $extender_name, 'simple_sitemap')) {
                unset($data['display'][$display_name]['display_options']['display_extenders'][$extender_name]);
              }
            }
            if (empty($data['display'][$display_name]['display_options']['display_extenders'])) {
              unset($data['display'][$display_name]['display_options']['display_extenders']);
            }
          }
        }
      }
    }

    return $data;
  }

  /**
   * Get excluded permissions for a role.
   *
   * @param string $role_name
   *   Role machine name.
   *
   * @return array<string>
   *   Array of excluded permissions.
   */
  protected function getExcludedRolePermissions(string $role_name): array {
    $permissions = [];

    $map = $this->getRolePermissionsMap();

    if (!empty($map[$role_name])) {
      foreach ($map[$role_name] as $module_permissions) {
        $permissions = array_merge($permissions, $module_permissions);
      }
    }

    array_filter($permissions);

    return $permissions;
  }

  /**
   * Get excluded dependency modules for a role.
   *
   * @param string $role_name
   *   Role machine name.
   *
   * @return array<int, int|string>.
   *   Array of excluded dependency modules.
   */
  protected function getExcludedRoleDependencyModules(string $role_name): array {
    $modules = [];

    $map = $this->getRolePermissionsMap();

    if (!empty($map[$role_name])) {
      $modules = array_keys($map[$role_name]);
    }

    array_filter($modules);

    return $modules;
  }

  /**
   * Get a list of providers for permissions.
   *
   * @param array $permissions
   *   Array of permissions.
   *
   * @return array<string>
   *   Array of providers.
   */
  protected function getPermissionsProviders(array $permissions): array {
    $modules = [];

    // @phpstan-ignore-next-line
    $permissions_data = \Drupal::service('user.permissions')->getPermissions();

    foreach ($permissions as $permission) {
      if (!empty($permissions_data[$permission])) {
        $modules[] = $permissions_data[$permission]['provider'];
      }
    }

    $modules = array_unique($modules);

    return $modules;
  }

  /**
   * Get pre-defined role-permissions map from the provisioning file.
   *
   * This allows to centrally manage additional permissions that may be
   * optionally provisioned for specific roles.
   *
   * @return array<string, array<string, array<string>>>
   *   Array of excluded dependency modules.
   */
  protected function getRolePermissionsMap(): array {
    // @phpstan-ignore-next-line
    $civictheme_path = \Drupal::service('extension.list.theme')->getPath('civictheme');
    $provision_file = $civictheme_path . DIRECTORY_SEPARATOR . 'theme-settings.provision.inc';
    if (!file_exists($provision_file)) {
      return [];
    }

    require_once $provision_file;

    return civictheme_provision_permissions_map();
  }

}
