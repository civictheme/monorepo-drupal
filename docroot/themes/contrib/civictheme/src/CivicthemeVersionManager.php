<?php

namespace Drupal\civictheme;

use Drupal\Component\Serialization\Json;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ThemeExtensionList;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Get CivicTheme version manager.
 */
class CivicthemeVersionManager implements ContainerInjectionInterface {

  /**
   * Theme extension list.
   *
   * @var \Drupal\Core\Extension\ThemeExtensionList
   */
  protected $themeExtensionList;

  /**
   * Array of version information.
   *
   * @var array
   */
  protected $info;

  /**
   * {@inheritdoc}
   */
  public function __construct(ThemeExtensionList $theme_extension_list) {
    $this->themeExtensionList = $theme_extension_list;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('extension.list.theme'),
    );
  }

  /**
   * Returns information about version.
   */
  public function info() {
    if (empty($this->info)) {
      $this->info = [
        'version' => $this->parseVersion(),
        'source' => $this->readComposerJson()['support']['source'] ?? NULL,
        'homepage' => $this->readComposerJson()['homepage'] ?? NULL,
      ];
    }

    return $this->info;
  }

  /**
   * Version string.
   */
  public function version() {
    return $this->info()['version'];
  }

  /**
   * Source URL string.
   */
  public function source() {
    return $this->info()['source'];
  }

  /**
   * Homepage URL string.
   */
  public function homepage() {
    return $this->info()['homepage'];
  }

  /**
   * Render version with a specified selector.
   *
   * @param string $selector
   *   CSS selector.
   * @param string $color
   *   Optional CSS text color value. Defaults to 'inherit'.
   *
   * @return array
   *   Build array.
   */
  public function render($selector, $color = 'inherit') {
    $version = $this->version();

    if (!$version) {
      return [];
    }

    $style = <<<HTML
<style>
$selector {
  position: relative;
}
$selector::after {
  content: 'Version $version';
  bottom: 0;
  display: block;
  font-family: sans-serif;
  font-size: x-small;
  left: 50px;
  position: absolute;
  color: $color;
}
</style>
HTML;

    return [
      '#type' => 'inline_template',
      '#template' => '{{ content|raw }}',
      '#context' => [
        'content' => $style,
      ],
    ];
  }

  /**
   * Get version.
   *
   * @return string|null
   *   Version string or NULL if version could not be discovered.
   */
  protected function parseVersion() {
    $version = NULL;

    $theme = $this->themeExtensionList->get('civictheme');

    // Discover from the .info file set by the Drupal.org's publishing script.
    if (!empty($theme->info['version'])) {
      return $theme->info['version'];
    }

    // Discover from the composer.json.
    if (!empty($this->readComposerJson()['version']) && preg_match('/([0-9]+(?:\.[0-9]+)+(?:-rc[0-9]+)?)/', $this->readComposerJson()['version'])) {
      return $this->readComposerJson()['version'];
    }

    // Fallback to the version specified in CivicTheme's README.md.
    $theme_path = $theme->getPath();
    $readme_file = $theme_path . DIRECTORY_SEPARATOR . 'README.md';

    if (file_exists($readme_file)) {
      $contents = file_get_contents($readme_file);
      preg_match('/Version: `([0-9]+(?:\.[0-9]+)+(?:-rc[0-9]+)?)`/', $contents, $matches);
      if (!empty($matches[1])) {
        $version = $matches[1];
      }
    }

    return $version;
  }

  /**
   * Read from theme's Composer file.
   *
   * @param string $filename
   *   Composer config file name. Defaults to 'composer.json'.
   *
   * @return mixed|null
   *   Decoded Composer config file as an array or NULL if file does not exist.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function readComposerJson($filename = 'composer.json') {
    $json = NULL;

    $theme = $this->themeExtensionList->get('civictheme');
    $composer_json = $theme->getPath() . DIRECTORY_SEPARATOR . $filename;

    if (file_exists($composer_json)) {
      $json = file_get_contents($composer_json);
      $json = Json::decode($json);
    }

    return $json;
  }

}
