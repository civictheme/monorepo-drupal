<?php

declare(strict_types=1);

namespace Drupal\civictheme;

use Drupal\Core\DependencyInjection\ClassResolverInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CivicTheme plugin loader.
 *
 * Since there is no support for services in themes, this plugin loader is used
 * to discover and load "plugins" manually.
 */
final class CivicthemePluginLoader implements ContainerInjectionInterface {

  /**
   * Plugin loader constructor.
   *
   * @param \Drupal\Core\DependencyInjection\ClassResolverInterface $classResolver
   *   Class resolver.
   */
  public function __construct(protected ClassResolverInterface $classResolver) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('class_resolver')
    );
  }

  /**
   * Load plugins using parent class.
   *
   * @param string $path
   *   Parent class name.
   * @param string $parent_class
   *   Lookup path.
   *
   * @return array<object>
   *   Array of loaded class instances.
   */
  public function load(string $path, string $parent_class = NULL): array {
    $files = glob($path . '/*.php') ?: [];
    foreach ($files as $filename) {
      require_once $filename;
    }

    $children = [];

    if ($parent_class) {
      foreach (get_declared_classes() as $class) {
        if (is_subclass_of($class, $parent_class)) {
          $children[] = $this->classResolver->getInstanceFromDefinition($class);
        }
      }
    }

    return $children;
  }

}
