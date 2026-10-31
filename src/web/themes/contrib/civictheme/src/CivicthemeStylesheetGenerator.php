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
   * Generate a stylesheet from a CSS string.
   *
   * Returns the existing stylesheet when one was already generated for the
   * current suffix; call purge() first to force regeneration.
   *
   * @param string $content
   *   The stylesheet content to save.
   *
   * @return string|null
   *   Generated stylesheet URI or NULL if the stylesheet cannot be saved.
   */
  public function generateFromString(string $content): null|string {
    $filepath = $this->getStylesheetUri();
    if (is_file($filepath) && file_exists($filepath)) {
      return $filepath;
    }

    return $this->saveStylesheet($content);
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
      $this->fileSystem->saveData($data, $filepath, 1);
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
