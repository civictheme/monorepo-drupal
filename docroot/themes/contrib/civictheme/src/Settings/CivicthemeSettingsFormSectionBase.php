<?php

namespace Drupal\civictheme\Settings;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ThemeExtensionList;
use Drupal\Core\File\FileSystem;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Theme\ThemeManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Abstract Settings section.
 *
 * Allows to split large setting forms into smaller sections.
 */
abstract class CivicthemeSettingsFormSectionBase implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The theme manager.
   *
   * @var \Drupal\Core\Theme\ThemeManager
   */
  protected $themeManager;

  /**
   * The theme extension list.
   *
   * @var \Drupal\Core\Extension\ThemeExtensionList
   */
  protected $themeExtensionList;

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fileSystem;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Theme\ThemeManager $theme_manager
   *   Theme manager service.
   * @param \Drupal\Core\Extension\ThemeExtensionList $theme_extension_list
   *   Theme extension list service.
   * @param \Drupal\Core\File\FileSystem $file_system
   *   File system service.
   */
  public function __construct(ThemeManager $theme_manager, ThemeExtensionList $theme_extension_list, FileSystem $file_system) {
    $this->themeManager = $theme_manager;
    $this->themeExtensionList = $theme_extension_list;
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('theme.manager'),
      $container->get('extension.list.theme'),
      $container->get('file_system')
    );
  }

  /**
   * Section form.
   *
   * Works as hook_form_alter().
   */
  abstract public function form(&$form, FormStateInterface &$form_state);

  /**
   * Section weight used to order sections on the form.
   */
  public function weight() {
    return 0;
  }

  /**
   * Retrieves a setting for the current theme or for a given theme.
   *
   * @param string $setting_name
   *   The name of the setting to be retrieved.
   * @param string $theme
   *   The name of a given theme; defaults to the current theme.
   *
   * @return mixed
   *   The value of the requested setting, NULL if the setting does not exist.
   */
  protected function getSetting($setting_name, $theme = NULL) {
    return theme_get_setting($setting_name, $theme);
  }

}
