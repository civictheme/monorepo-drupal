<?php

namespace Drupal\civictheme;

use Drupal\Core\Asset\LibraryDiscovery;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ThemeExtensionList;
use Drupal\Core\Extension\ThemeHandler;
use Drupal\Core\File\FileSystem;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CivicTheme settings manager.
 */
class CivicthemeColorManager implements ContainerInjectionInterface {

  /**
   * Defines CSS variables library name in info file.
   */
  const CSS_VARIABLES_LIBRARY = 'css-variables';

  /**
   * Defines CSS variables prefix.
   */
  const CSS_VARIABLES_PREFIX = '--ct-color-';

  /**
   * Theme extension list.
   *
   * @var \Drupal\Core\Extension\ThemeExtensionList
   */
  protected $themeExtensionList;

  /**
   * Theme handler service.
   *
   * @var \Drupal\Core\Extension\ThemeHandler
   */
  protected $themeHandler;

  /**
   * File system service.
   *
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fileSystem;

  /**
   * Library discovery service.
   *
   * @var \Drupal\Core\Asset\LibraryDiscovery
   */
  protected $libraryDiscovery;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Extension\ThemeExtensionList $theme_extension_list
   *   Theme extension list service.
   * @param \Drupal\Core\Extension\ThemeHandler $theme_handler
   *   Theme handler service.
   * @param \Drupal\Core\File\FileSystem $file_system
   *   File system discovery service.
   * @param \Drupal\Core\Asset\LibraryDiscovery $library_discovery
   *   Library discovery service.
   */
  public function __construct(ThemeExtensionList $theme_extension_list, ThemeHandler $theme_handler, FileSystem $file_system, LibraryDiscovery $library_discovery) {
    $this->themeExtensionList = $theme_extension_list;
    $this->themeHandler = $theme_handler;
    $this->fileSystem = $file_system;
    $this->libraryDiscovery = $library_discovery;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('extension.list.theme'),
      $container->get('theme_handler'),
      $container->get('file_system'),
      $container->get('library.discovery'),
    );
  }

  /**
   * Implements hook_library_info_alter().
   */
  public function libraryInfoAlter(&$libraries, $extension) {
    $themes = array_keys($this->themeHandler->listInfo());
    if (in_array($extension, $themes)) {
      $libraries['css-variables']['css']['theme'][$this->stylesheet()] = [];
    }
  }

  /**
   * Invalidate cache.
   */
  public function invalidateCache() {
    $this->fileSystem->delete(self::stylesheetUri());
    // @todo Implement only libraries cache invalidation.
    drupal_flush_all_caches();
  }

  /**
   * Return existing or generate a new stylesheet.
   *
   * @return string
   *   URI of the stylesheet file.
   */
  protected function stylesheet() {
    $filepath = self::stylesheetUri();
    if (file_exists($filepath)) {
      return $filepath;
    }

    return $this->generateStylesheet($this->getColorsFromConfig());
  }

  /**
   * Generate stylesheet using colors.
   *
   * @param array $colors
   *   Array of colors.
   *
   * @return string
   *   URI to a stylesheet file.
   */
  protected function generateStylesheet(array $colors) {
    $content = $this->colorsToCss($colors, 'html');

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
    $filepath = self::stylesheetUri();
    $this->fileSystem->saveData($data, $filepath, FileSystemInterface::EXISTS_REPLACE);
    $this->fileSystem->chmod($filepath);

    return $filepath;
  }

  /**
   * Get stylesheet URI.
   */
  protected static function stylesheetUri() {
    return 'public://civictheme-css-variables.css';
  }

  /**
   * Convert colors to CSS content.
   *
   * @param array $colors
   *   Array of colors.
   * @param string $selector
   *   Optional selector to place CSS content into. Defaults to 'html'.
   *
   * @return string
   *   CSS content suitable for output into a file.
   */
  protected function colorsToCss(array $colors, $selector = 'html') {
    $vars = [];
    foreach ($colors as $theme => $theme_colors) {
      foreach ($theme_colors as $color => $value) {
        $color = str_replace('_', '-', $color);
        $vars[] = self::CSS_VARIABLES_PREFIX . "$theme-$color: $value;";
      }
    }
    $content = implode('', $vars);

    $content = "$selector { $content }";

    return $content;
  }

  /**
   * Get CSS colors from the CSS Variables library file.
   *
   * @return array
   *   Array of colors keyed by theme and name with values:
   *   - value: (string) Value.
   *   - original_name: (string) Original CSS variable name.
   */
  public function getCssColors() {
    $colors = [];

    $content = $this->loadCssVariablesContent();
    if ($content) {
      $vars = static::parseCssVariables($content);

      $vars = array_filter($vars, function ($key) {
        return str_starts_with($key, self::CSS_VARIABLES_PREFIX);
      }, ARRAY_FILTER_USE_KEY);

      foreach ($vars as $name => $value) {
        $name = str_replace(self::CSS_VARIABLES_PREFIX, '', $name);
        $parts = explode('-', $name);
        $theme = array_shift($parts);
        $name = implode('-', $parts);

        $colors[$theme][$name] = [
          'value' => $value,
          'original_name' => $name,
        ];
      }
    }

    return $colors;
  }

  /**
   * Load content from CSS variables file.
   *
   * @return string
   *   Loaded content or FALSE if the file is not readable.
   */
  protected function loadCssVariablesContent() {
    $library = $this->libraryDiscovery->getLibraryByName('civictheme', self::CSS_VARIABLES_LIBRARY);
    if (!empty($library['css'][0]['data']) && file_exists($library['css'][0]['data'])) {
      return file_get_contents($library['css'][0]['data']);
    }

    return FALSE;
  }

  /**
   * Parse CSS variables into an array.
   */
  protected static function parseCssVariables($content) {
    $vars = [];

    $matches = [];
    preg_match_all('/(--[a-zA-Z0-9-]+)\s*:\s*([^;]+);/i', $content, $matches, PREG_SET_ORDER);

    array_walk($matches, function ($value) use (&$vars) {
      if (!empty($value[1])) {
        $vars[trim($value[1])] = trim($value[2]) ?? NULL;
      }
    });

    return $vars;
  }

  /**
   * Process color formula.
   */
  public static function processColorFormula($formula, $theme) {
    $parts = explode('|', $formula);
    $name = array_shift($parts);
    $name = "colors[brand][$theme][$name]";
    array_unshift($parts, $name);

    return implode('|', $parts);
  }

  /**
   * Proxy to get colors from config.
   */
  protected function getColorsFromConfig() {
    // @todo Implement better config retrieval.
    return theme_get_setting('colors.palette') ?? [];
  }

}
