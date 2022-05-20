<?php

namespace Drupal\cs_demo\Plugin\ConfigFilter;

use Drupal\config_ignore\Plugin\ConfigFilter\IgnoreFilter;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Ignore all demo config.
 *
 * @ConfigFilter(
 *   id = "cs_demo_config_ignore",
 *   label = "CivicTheme Demo Config Ignore",
 *   weight = 100
 * )
 */
class CsDemoIgnoreFilter extends IgnoreFilter implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function filterWrite($name, array $data) {
    if ($name === 'core.extension') {
      $excluded_modules = ['cs_demo' => 'cs_demo'];
      $data['module'] = array_diff_key($data['module'], $excluded_modules);
    }

    return $data;
  }

}
