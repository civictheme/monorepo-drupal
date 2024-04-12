<?php

declare(strict_types=1);

namespace Drupal\civictheme\Settings;

use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Config\ConfigManager;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ThemeExtensionList;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\File\FileUrlGenerator;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Image\ImageFactory;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\StreamWrapper\StreamWrapperManager;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Theme\ThemeManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Abstract Settings section.
 *
 * Allows to split large setting forms into smaller sections.
 *
 * @phpstan-consistent-constructor
 */
abstract class CivicthemeSettingsFormSectionBase implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Theme\ThemeManager $themeManager
   *   Theme manager service.
   * @param \Drupal\Core\Extension\ThemeExtensionList $themeExtensionList
   *   Theme extension list service.
   * @param \Drupal\Core\File\FileSystemInterface $fileSystem
   *   File system service.
   * @param \Drupal\Core\File\FileUrlGenerator $fileUrlgenerator
   *   File URL generator.
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *   Messenger.
   * @param \Drupal\Core\Config\ConfigManager $configManager
   *   Config manager.
   * @param \Drupal\civictheme\CivicthemeConfigManager $themeConfigManager
   *   Theme config manager.
   * @param \Drupal\Core\Image\ImageFactory $imageFactory
   *   The image factory.
   */
  public function __construct(protected ThemeManager $themeManager, protected ThemeExtensionList $themeExtensionList, protected FileSystemInterface $fileSystem, protected FileUrlGenerator $fileUrlgenerator, protected Messenger $messenger, protected ConfigManager $configManager, protected CivicthemeConfigManager $themeConfigManager, protected ImageFactory $imageFactory) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('theme.manager'),
      $container->get('extension.list.theme'),
      $container->get('file_system'),
      $container->get('file_url_generator'),
      $container->get('messenger'),
      $container->get('config.manager'),
      $container->get('class_resolver')->getInstanceFromDefinition(CivicthemeConfigManager::class),
      $container->get('image.factory')
    );
  }

  /**
   * Section form.
   *
   * Works as hook_form_alter().
   */
  abstract public function form(array &$form, FormStateInterface $form_state): void;

  /**
   * Section weight used to order sections on the form.
   */
  public function weight(): int {
    return 0;
  }

  /**
   * Convert path to a human-friendly path.
   *
   * If path has a public:// URI, return the path relative to the Drupal root
   * as stream wrappers are not end-user friendly.
   *
   * @param string $path
   *   The original path.
   *
   * @return string
   *   Friendly path or original path if an invalid stream wrapper was provided.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function toFriendlyFilePath($path): string {
    if ($path && StreamWrapperManager::getScheme($path) == $this->getDefaultFileScheme()) {
      $path = $this->fileUrlgenerator->generateString($path);
    }

    return $path !== '' && $path !== '0' ? ltrim($path, '/') : $path;
  }

  /**
   * Get default file scheme.
   *
   * @return string
   *   Default file scheme, e.g. 'public'.
   */
  protected function getDefaultFileScheme(): string {
    return $this->configManager->getConfigFactory()->get('system.file')->get('default_scheme');
  }

  /**
   * Get URI of the theme settings asset.
   */
  protected function getCivicthemeThemeSettingsAssetUri(string $file): string {
    return '/' . $this->themeExtensionList->getPath('civictheme') . '/theme-settings/assets/' . $file;
  }

  /**
   * Destination path for all uploaded theme assets.
   */
  protected function getUploadedAssetsDestinationPath(): string {
    return $this->getDefaultFileScheme() . '://';
  }

  /**
   * Validate file upload consisting of upload and path fields.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function validateFileUpload(array &$form, FormStateInterface $form_state, array $upload_field_name_key, array $path_field_name_key): void {
    // Check for a newly uploaded file and save it into the temp file in
    // 'form_values' so we can save it on submit.
    // We want to validate the upload before validating path value to
    // avoid situation when a valid path needs to be specified before
    // an upload. Uploaded file will update then update the path anyway.
    $upload_element = NestedArray::getValue($form, $upload_field_name_key);

    if ($upload_element) {
      $file = _file_save_upload_from_form($upload_element, $form_state, 0, FileSystemInterface::EXISTS_REPLACE);
      if ($file) {
        $form_state->setValue($upload_field_name_key, $file);
        // Do not validate the path as it will be re-written in the submit
        // handler with the path of the uploaded file.
        return;
      }
    }

    // Validate and normalise path, if provided.
    $path = $form_state->getValue($path_field_name_key);

    if (!empty($path)) {
      $path = $this->toFriendlyFilePath($path);
      if ($path === '' || $path === '0') {
        $form_state->setErrorByName(implode('][', $path_field_name_key), (string) $this->t('The file path is invalid.'));

        return;
      }

      if (!file_exists($path)) {
        $form_state->setErrorByName(implode('][', $path_field_name_key), (string) $this->t('The file at provided path does not exist.'));

        return;
      }

      $form_state->setValue($path_field_name_key, $path);
    }
  }

  /**
   * Submit file upload consisting of upload and path fields.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  protected function submitFileUpload(array &$form, FormStateInterface $form_state, string|array $upload_field_name_key, string|array $path_field_name_key): void {
    $uploaded_file = $form_state->getValue($upload_field_name_key);
    // If upload was provided - move it to the desired location, set the
    // path and remove upload key from form state as we only want the path
    // to be saved.
    if (!empty($uploaded_file)) {
      $copied_filepath = $this->fileSystem->copy(
        $uploaded_file->getFileUri(),
        $this->getUploadedAssetsDestinationPath()
      );

      if ($copied_filepath !== '' && $copied_filepath !== '0') {
        $copied_filepath = $this->toFriendlyFilePath($copied_filepath);
        $form_state->setValue($path_field_name_key, $copied_filepath);
      }
    }

    $form_state->unsetValue($upload_field_name_key);
  }

}
