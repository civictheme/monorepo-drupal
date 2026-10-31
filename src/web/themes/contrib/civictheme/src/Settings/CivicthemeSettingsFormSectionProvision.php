<?php

declare(strict_types=1);

namespace Drupal\civictheme\Settings;

use Drupal\Core\Form\FormStateInterface;

/**
 * CivicTheme settings section to display colors.
 */
class CivicthemeSettingsFormSectionProvision extends CivicthemeSettingsFormSectionBase {

  /**
   * {@inheritdoc}
   */
  public function weight(): int {
    return 50;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    // Programmatically provision content.
    $path = $this->themeExtensionList->getPath('civictheme');
    $provision_file = $path . DIRECTORY_SEPARATOR . 'theme-settings.provision.inc';
    if (file_exists($provision_file)) {
      require_once $provision_file;
      _civictheme_form_system_theme_settings_alter_provision($form, $form_state);
    }
  }

}
