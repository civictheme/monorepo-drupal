<?php

declare(strict_types=1);

namespace Drupal\civictheme;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CSS variables stylesheet generator.
 */
final class CivicthemeStylesheetGenerator implements ContainerInjectionInterface {

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
   */
  protected string $stylesheetUri;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\File\FileSystemInterface $fileSystem
   *   File system discovery service.
   */
  public function __construct(protected FileSystemInterface $fileSystem) {
    $this->setStylesheetUriSuffix('default');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
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
  public function generate(array $variables, array $parent_selectors = ['html'], string $prefix = ''): string {
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
  public function purge(): static {
    foreach ($this->getAllStylesheetFiles() as $file) {
      $this->fileSystem->delete($file);
    }

    return $this;
  }

  /**
   * Get stylesheet URI.
   */
  public function getStylesheetUri(): string {
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
  public function setStylesheetUriSuffix(string $suffix): static {
    $this->stylesheetUri = self::STYLESHEET_URI_PREFIX . $suffix . '.css';

    return $this;
  }

  /**
   * Get all stylesheet files.
   *
   * @return array<string>
   *   Array of all found generated stylesheet files.
   */
  protected function getAllStylesheetFiles(): array {
    $files = glob($this->fileSystem->realpath(self::STYLESHEET_URI_PREFIX) . '*.css');

    return empty($files) ? [] : $files;
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
   * @return string|null
   *   URI to a stylesheet file or NULL if stylesheet cannot be generated.
   *
   * @SuppressWarnings(StaticAccess)
   */
  protected function generateStylesheet(array $variables, array $parent_selectors = ['html'], string $prefix = ''): ?string {
    $variables = CivicthemeUtility::flattenArray($variables, self::CSS_VARIABLES_SEPARATOR);

    foreach ($variables as $name => $value) {
      $variables['--' . $prefix . self::CSS_VARIABLES_SEPARATOR . str_replace('_', '-', $name)] = (string) $value;
      unset($variables[$name]);
    }

    $content = implode(';', CivicthemeUtility::arrayMergeKeysValues($variables, ':')) . ';';

    foreach ($parent_selectors as $parent_selector) {
      $content = sprintf('%s { %s }', $parent_selector, $content);
    }

    return $this->saveStylesheet($content);
  }

  /**
   * Save data into a stylesheet.
   *
   * @param string $data
   *   Stylesheet data to save.
   *
   * @return string|null
   *   Path to saved stylesheet or NULL if unable to save.
   */
  protected function saveStylesheet($data): ?string {
    $filepath = $this->getStylesheetUri();
    try {
      $this->fileSystem->saveData($data, $filepath, FileSystemInterface::EXISTS_REPLACE);
      $this->fileSystem->chmod($filepath);
    }
    catch (\Exception) {
      // Drupal\Core\File\FileSystem handles logging, so simply handle the
      // exception and assign NULL.
      $filepath = NULL;
    }

    return $filepath;
  }

}
