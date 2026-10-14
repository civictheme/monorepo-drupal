<?php

declare(strict_types=1);

namespace Drupal\civictheme;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Manages the colour recipe and its generated overrides stylesheet.
 *
 * CivicTheme 2.x colours are driven by the UI Kit colour picker embedded in
 * the theme settings form: an administrator edits a recipe (brand inputs
 * plus token overrides and additions), the picker's engine emits the changed
 * CSS custom properties in the browser, and both artefacts are stored as
 * strings in theme settings ('colors.recipe' and 'colors.css').
 *
 * This class only stores strings and writes the overrides stylesheet file.
 * No colour processing happens in PHP - resolution, contrast checking and
 * palette generation all live in the @civictheme/colour-picker package.
 */
class CivicthemeColorRecipeManager implements ContainerInjectionInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\civictheme\CivicthemeConfigManager $themeConfigManager
   *   Theme config manager.
   * @param \Drupal\civictheme\CivicthemeStylesheetGenerator $stylesheetGenerator
   *   Stylesheet generator.
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cacheTagsInvalidator
   *   Cache tags invalidator.
   */
  public function __construct(
    protected CivicthemeConfigManager $themeConfigManager,
    protected CivicthemeStylesheetGenerator $stylesheetGenerator,
    protected CacheTagsInvalidatorInterface $cacheTagsInvalidator,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('class_resolver')->getInstanceFromDefinition(CivicthemeConfigManager::class),
      $container->get('class_resolver')->getInstanceFromDefinition(CivicthemeStylesheetGenerator::class),
      $container->get('cache_tags.invalidator')
    );
  }

  /**
   * Get the stored recipe JSON string.
   */
  public function getRecipe(): string {
    return (string) $this->themeConfigManager->load('colors.recipe', '');
  }

  /**
   * Get the stored overrides CSS string.
   */
  public function getCss(): string {
    return (string) $this->themeConfigManager->load('colors.css', '');
  }

  /**
   * Store the recipe and its emitted overrides CSS.
   */
  public function save(string $recipe, string $css): static {
    $this->themeConfigManager->save('colors.recipe', $recipe);
    $this->themeConfigManager->save('colors.css', static::stripCssLineComments($css));
    $this->invalidateCache();

    return $this;
  }

  /**
   * Strip `//` line comments from emitted overrides CSS.
   *
   * The picker's overrides export leads with `//` banner lines - valid in
   * the .scss drop-in it is named for, but the stored string is served as
   * plain CSS, where `//` comments would break parsing of the first block.
   * Mirrors the stripping the theme settings form does client-side.
   */
  protected static function stripCssLineComments(string $css): string {
    $lines = array_filter(explode("\n", $css), static fn (string $line): bool => !str_starts_with(ltrim($line), '//'));
    $css = ltrim(implode("\n", $lines), "\n");

    // A comment-only stylesheet (identity recipe) stores as empty so no
    // overrides file is attached to the site.
    return str_contains($css, '{') ? $css : '';
  }

  /**
   * Generate (or return an existing) overrides stylesheet file.
   *
   * @param string $suffix
   *   Stylesheet suffix, usually the active theme name.
   *
   * @return string
   *   URI of the generated stylesheet, or an empty string when there is no
   *   stored overrides CSS to write.
   */
  public function stylesheet(string $suffix): string {
    $css = $this->getCss();
    if ($css === '') {
      return '';
    }

    return (string) $this->stylesheetGenerator
      ->setStylesheetUriSuffix($suffix)
      ->generateFromString($css);
  }

  /**
   * Invalidate caches: purge generated stylesheets and refresh libraries.
   *
   * @return $this
   *   Instance of the current class.
   */
  public function invalidateCache(): static {
    $this->stylesheetGenerator->purge();

    // The attached stylesheet list is embedded in cached responses, so
    // 'rendered' must be invalidated alongside the library definitions for
    // page and dynamic page caches to pick up the change.
    $this->cacheTagsInvalidator->invalidateTags(['library_info', 'rendered']);

    // Force browser reload by changing the dummy query string.
    // @codingStandardsIgnoreStart DrupalPractice.Objects.GlobalDrupal.GlobalDrupal
    // @phpstan-ignore-next-line
    \Drupal::service('asset.query_string')->reset();
    // @codingStandardsIgnoreEnd
    return $this;
  }

}
