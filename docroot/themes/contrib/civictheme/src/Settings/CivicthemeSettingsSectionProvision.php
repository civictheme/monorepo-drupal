<?php

namespace Drupal\civictheme\Settings;

use Drupal\Core\Form\FormStateInterface;

/**
 * CivicTheme settings section to display colors.
 */
class CivicthemeSettingsSectionProvision extends CivicthemeSettingsAbstractSection {

  /**
   * {@inheritdoc}
   */
  public function weight() {
    return 4;
  }

  /**
   * {@inheritdoc}
   */
  public function form(&$form, FormStateInterface &$form_state) {
    // Programmatically provision content.
    $path = $this->themeExtensionList->getPath('civictheme');
    $provision_file = $path . DIRECTORY_SEPARATOR . 'theme-settings.provision.inc';
    if (file_exists($provision_file)) {
      require_once $provision_file;
      _civictheme_form_system_theme_settings_alter_provision($form, $form_state);
    }
  }

}
