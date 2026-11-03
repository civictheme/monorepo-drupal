<?php

/**
 * @file
 * Theme settings form for CivicTheme theme.
 */

declare(strict_types=1);

use Drupal\civictheme_starter\CivicthemeSettingsFormManager;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_system_theme_settings_alter().
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
function civictheme_starter_form_system_theme_settings_alter(array &$form, FormStateInterface &$form_state): void {
  /** @var \Drupal\civictheme_starter\CivicthemeSettingsFormManager $settings */
  $settings = \Drupal::classResolver(CivicthemeSettingsFormManager::class);
  $settings->form($form, $form_state);
}
