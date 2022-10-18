<?php

namespace Drupal\civictheme;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\File\FileSystem;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CSS variables stylesheet generator.
 */
class CivicthemeStylesheetGenerator implements ContainerInjectionInterface {

  /**
   * Defines CSS variables parts separator.
   */
  const CSS_VARIABLES_SEPARATOR = '-';

  /**
   * Defines stylesheet URI prefix.
   *
   * Used to generate and purge stylesheet files.
   */
  const STYLESHEET_URI_PREFIX = 'public://css-variables.';

  /**
   * The stylesheet URI.
   *
   * @var string
   */
  protected $stylesheetUri;

  /**
   * File system service.
   *
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fileSystem;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\File\FileSystem $file_system
   *   File system discovery service.
   */
  public function __construct(FileSystem $file_system) {
    $this->fileSystem = $file_system;
    $this->setStylesheetUriSuffix('default');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('file_system')
    );
  }

  /**
   * Generate a stylesheet using provided variables.
   *
   * Limited only to generation using variables.
   *
   * @param array $variables
   *   Array of variables to use for the generation.
   * @param array $parent_selectors
   *   Optional parent CSS selectors to wrap the variables with.
   *   Defaults to 'html'.
   * @param string $prefix
   *   Optional prefix to add to every generated variables.
   *   Should not include '--' (the CSS variable prefix).
   *
   * @return string
   *   Generated stylesheet URI.
   */
  public function generate(array $variables, array $parent_selectors = ['html'], $prefix = '') {
    $filepath = $this->getStylesheetUri();
    if (is_file($filepath) && file_exists($filepath)) {
      return $filepath;
    }

    return $this->generateStylesheet($variables, $parent_selectors, $prefix);
  }

  /**
   * Purge stylesheet.
   *
   * @return $this
   *   Instance of the current class.
   */
  public function purge() {
    foreach ($this->getAllStylesheetFiles() as $file) {
      $this->fileSystem->delete($file);
    }

    return $this;
  }

  /**
   * Get stylesheet URI.
   */
  public function getStylesheetUri() {
    return $this->stylesheetUri;
  }

  /**
   * Set stylesheet URL suffix.
   *
   * Used to contextualize stylesheet.
   *
   * @param string $suffix
   *   The stylesheet suffix.
   *
   * @return $this
   *   Instance of the current class.
   */
  public function setStylesheetUriSuffix($suffix) {
    $suffix = is_scalar($suffix) ? $suffix : 'default';
    $this->stylesheetUri = self::STYLESHEET_URI_PREFIX . $suffix . '.css';

    return $this;
  }

  /**
   * Get all stylesheet files.
   *
   * @return array
   *   Array of all found generated stylesheet files.
   */
  protected function getAllStylesheetFiles() {
    $files = glob($this->fileSystem->realpath(self::STYLESHEET_URI_PREFIX) . '*.css');

    return !empty($files) ? $files : [];
  }

  /**
   * Generate stylesheet using provided variables..
   *
   * @param array $variables
   *   Array of variables to use for the generation.
   * @param array $parent_selectors
   *   Optional parent CSS selectors to wrap the variables with.
   *   Defaults to 'html'.
   * @param string $prefix
   *   Optional prefix to add to every generated variables.
   *   Should not include '--' (the CSS variable prefix).
   *
   * @return string
   *   URI to a stylesheet file.
   *
   * @SuppressWarnings(StaticAccess)
   */
  protected function generateStylesheet(array $variables, array $parent_selectors = ['html'], $prefix = '') {
    $variables = CivicthemeUtility::flattenArray($variables, self::CSS_VARIABLES_SEPARATOR);

    foreach ($variables as $name => $value) {
      $variables['--' . $prefix . self::CSS_VARIABLES_SEPARATOR . $name] = (string) $value;
      unset($variables[$name]);
    }

    $content = implode(';', CivicthemeUtility::arrayMergeKeysValues($variables, ':')) . ';';

    foreach ($parent_selectors as $parent_selector) {
      $content = "$parent_selector { $content }";
    }

    return $this->saveStylesheet($content);
  }

  /**
   * Save data into a stylesheet.
   *
   * @param string $data
   *   Stylesheet data to save.
   *
   * @return string
   *   Path to stylesheet.
   */
  protected function saveStylesheet($data) {
    $filepath = $this->getStylesheetUri();
    $this->fileSystem->saveData($data, $filepath, FileSystemInterface::EXISTS_REPLACE);
    $this->fileSystem->chmod($filepath);

    return $filepath;
  }

}
