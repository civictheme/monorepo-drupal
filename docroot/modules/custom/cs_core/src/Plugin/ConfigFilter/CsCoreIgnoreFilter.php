<?php

namespace Drupal\cs_core\Plugin\ConfigFilter;

use Drupal\config_ignore\Plugin\ConfigFilter\IgnoreFilter;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

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
 *   id = "cs_core_config_ignore",
 *   label = "CS Core Config Ignore",
 *   weight = 100
 * )
 */
class CsCoreIgnoreFilter extends IgnoreFilter implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function filterWrite($name, array $data) {
    // Exclude permissions and dependencies for user roles.
    // phpcs:disable Drupal.Functions.DiscouragedFunctions.Discouraged
    if (fnmatch('user.role.*', $name)) {
      $role_name = substr($name, strlen('user.role.'));

      $permissions = $this->getExcludedRolePermissions($role_name);
      if (isset($data['permissions'])) {
        $data['permissions'] = array_values(array_diff($data['permissions'], $permissions));
      }

      $modules = $this->getExcludedRoleDependencyModules($role_name);
      if (isset($data['dependencies']['module'])) {
        foreach ($data['dependencies']['module'] as $key => $module_name) {
          if (in_array($module_name, $modules)) {
            unset($data['dependencies']['module'][$key]);
          }
        }
        $data['dependencies']['module'] = array_values($data['dependencies']['module']);
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
   * @return array
   *   Array of excluded permissions.
   */
  protected function getExcludedRolePermissions($role_name) {
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
   * @return array
   *   Array of excluded dependency modules.
   */
  protected function getExcludedRoleDependencyModules($role_name) {
    $modules = [];

    $map = $this->getRolePermissionsMap();

    if (!empty($map[$role_name])) {
      $modules = array_keys($map[$role_name]);
    }

    array_filter($modules);

    return $modules;
  }

  /**
   * Get pre-defined role-permissions map from the provisioning file.
   *
   * This allows to centrally manage additional permissions that may be
   * optionally provisioned for specific roles.
   */
  protected function getRolePermissionsMap() {
    $civictheme_path = \Drupal::service('extension.list.theme')->getPath('civictheme');
    $provision_file = $civictheme_path . DIRECTORY_SEPARATOR . 'civictheme.provision.inc';
    if (!file_exists($provision_file)) {
      return [];
    }

    require_once $provision_file;

    return civictheme_provision_permissions_map();
  }

}
