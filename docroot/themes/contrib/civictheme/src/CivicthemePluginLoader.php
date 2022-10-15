<?php

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
class CivicthemePluginLoader implements ContainerInjectionInterface {

  /**
   * Class resolver service.
   *
   * @var \Drupal\Core\DependencyInjection\ClassResolverInterface
   */
  protected $classResolver;

  /**
   * Plugin loader constructor.
   *
   * @param \Drupal\Core\DependencyInjection\ClassResolverInterface $class_resolver
   *   Class resolver.
   */
  public function __construct(ClassResolverInterface $class_resolver) {
    $this->classResolver = $class_resolver;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
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
   * @return array
   *   Array of loaded class instances.
   */
  public function load($path, $parent_class) {
    foreach (glob($path . '/*.php') as $filename) {
      require_once $filename;
    }

    $children = [];
    foreach (get_declared_classes() as $class) {
      if (is_subclass_of($class, $parent_class)) {
        $children[] = $this->classResolver->getInstanceFromDefinition($class);
      }
    }

    return $children;
  }

}
