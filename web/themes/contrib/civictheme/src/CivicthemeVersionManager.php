<?php

declare(strict_types=1);

namespace Drupal\civictheme;

use Drupal\Component\Serialization\Json;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ThemeExtensionList;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Get CivicTheme version manager.
 */
final class CivicthemeVersionManager implements ContainerInjectionInterface {

  const DEFAULT_VERSION = 'dev';

  /**
   * Array of version information.
   *
   * @var array<string>
   */
  protected $info;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    protected ThemeExtensionList $themeExtensionList
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('extension.list.theme'),
    );
  }

  /**
   * Returns information about version.
   *
   * @return array<string>
   *   Array of version information.
   */
  public function info(): array {
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
  public function version(): string {
    return $this->info()['version'] ?? self::DEFAULT_VERSION;
  }

  /**
   * Source URL string.
   */
  public function source(): ?string {
    return $this->info()['source'] ?? NULL;
  }

  /**
   * Homepage URL string.
   */
  public function homepage(): ?string {
    return $this->info()['homepage'] ?? NULL;
  }

  /**
   * Render version with a specified selector.
   *
   * @param string $selector
   *   CSS selector.
   *
   * @return array<string, array<string, string>|string>
   *   Build array.
   */
  public function render($selector): array {
    $style = <<<HTML
<style>
{$selector}::after {
  content: 'CivicTheme version: {$this->version()}';
  bottom: 0;
  display: block;
  font-family: sans-serif;
  font-size: x-small;
  right: 0;
  position: fixed;
  color: #ffffff;
  background-color: #000000;
  padding: 0.25rem 0.5rem;
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
  protected function parseVersion(): ?string {
    $version = NULL;

    $theme = $this->themeExtensionList->get('civictheme');

    // Discover from the .info file set by the Drupal.org's publishing script.
    if (!empty($theme->info['version'])) {
      return $theme->info['version'];
    }

    // Discover from the composer.json.
    if (!empty($this->readComposerJson()['version']) && preg_match('/(\d+(?:\.\d+)+(?:-rc\d+)?)/', (string) $this->readComposerJson()['version'])) {
      return $this->readComposerJson()['version'];
    }

    // Fallback to the version specified in CivicTheme's README.md.
    $theme_path = $theme->getPath();
    $readme_file = $theme_path . DIRECTORY_SEPARATOR . 'README.md';

    if (file_exists($readme_file)) {
      $contents = (string) file_get_contents($readme_file);
      preg_match('/Version: `(\d+(?:\.\d+)+(?:-rc\d+)?)`/', $contents, $matches);
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
   * @return array<string, mixed>|null
   *   Decoded Composer config file as an array or NULL if file does not exist.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function readComposerJson(string $filename = 'composer.json'): ?array {
    $json = NULL;

    $theme = $this->themeExtensionList->get('civictheme');
    $composer_json = $theme->getPath() . DIRECTORY_SEPARATOR . $filename;

    if (file_exists($composer_json)) {
      $json = (string) file_get_contents($composer_json);
      $json = Json::decode($json);
    }

    return $json;
  }

}
