<?php

namespace Drupal\Tests\civictheme\Traits;

use PHPUnit\Framework\MockObject\MockObject;

/**
 * Trait CivicthemeTestHelperTrait.
 *
 * Helper trait for tests.
 *
 * @package Drupal\civictheme\Tests
 */
trait CivicthemeTestHelperTrait {

  /**
   * Call protected methods on the class.
   *
   * @param object|string $object
   *   Object or class name to use for a method call.
   * @param string $method
   *   Method name. Method can be static.
   * @param array $args
   *   Array of arguments to pass to the method. To pass arguments by reference,
   *   pass them by reference as an element of this array.
   *
   * @return mixed
   *   Method result.
   *
   * @SuppressWarnings(PHPMD.MissingImport)
   */
  protected static function callProtectedMethod(object|string $object, string $method, array $args = []): mixed {
    // @phpstan-ignore-next-line
    $class = new \ReflectionClass(is_object($object) ? get_class($object) : $object);
    $method = $class->getMethod($method);
    $method->setAccessible(TRUE);
    $object = $method->isStatic() ? NULL : $object;

    // @phpstan-ignore-next-line
    return $method->invokeArgs($object, $args);
  }

  /**
   * Set protected property value.
   *
   * @param object $object
   *   Object to set the value on.
   * @param string $property
   *   Property name to set the value. Property should exists in the object.
   * @param mixed $value
   *   Value to set to the property.
   *
   * @SuppressWarnings(PHPMD.MissingImport)
   */
  protected static function setProtectedValue(object $object, string $property, mixed $value): void {
    $class = new \ReflectionClass(get_class($object));
    $property = $class->getProperty($property);
    $property->setAccessible(TRUE);

    $property->setValue($object, $value);
  }

  /**
   * Get protected value from the object.
   *
   * @param object $object
   *   Object to set the value on.
   * @param string $property
   *   Property name to get the value. Property should exists in the object.
   *
   * @return mixed
   *   Protected property value.
   *
   * @SuppressWarnings(PHPMD.MissingImport)
   */
  protected static function getProtectedValue(object $object, string $property): mixed {
    $class = new \ReflectionClass(get_class($object));
    $property = $class->getProperty($property);
    $property->setAccessible(TRUE);

    return $property->getValue($class);
  }

  /**
   * Helper to prepare class mock.
   *
   * @param string $class
   *   Class name to generate the mock.
   * @param array $methodsMap
   *   Optional array of methods and values, keyed by method name.
   * @param array $args
   *   Optional array of constructor arguments. If omitted, a constructor will
   *   not be called.
   *
   * @return \PHPUnit\Framework\MockObject\MockObject
   *   Mocked class.
   *
   * @SuppressWarnings(PHPMD.MissingImport)
   */
  protected function prepareMock(string|object $class, array $methodsMap = [], array $args = []): MockObject {
    $methods = array_keys($methodsMap);
    // @phpstan-ignore-next-line
    $reflectionClass = new \ReflectionClass($class);

    // @phpstan-ignore-next-line
    $mock = $reflectionClass->isAbstract() ?
      // @phpstan-ignore-next-line
      $this->getMockForAbstractClass($class, $args, '', !empty($args), TRUE, TRUE, $methods)
      // @phpstan-ignore-next-line
      : $this->getMockForConcreteClass($class, $args, $methods);

    foreach ($methodsMap as $method => $value) {
      // Handle callback values differently.
      // @phpstan-ignore-next-line
      if (is_object($value) && strpos(get_class($value), 'Callback') !== FALSE) {
        $mock->expects($this->any())
          ->method($method)
          // @phpstan-ignore-next-line
          ->will($value);
        continue;
      }
      $mock->expects($this->any())
        ->method($method)
        ->willReturn($value);
    }

    return $mock;
  }

  /**
   * Helper to prepare concrete class mock.
   *
   * @param string $class
   *   Class name to generate the mock.
   * @param array $args
   *   Optional array of constructor arguments. If omitted, a constructor will
   *   not be called.
   * @param array $methods
   *   Optional array of methods to be added to mock.
   *
   * @return \PHPUnit\Framework\MockObject\MockObject
   *   Mocked class.
   *
   * @SuppressWarnings(PHPMD.MissingImport)
   */
  protected function getMockForConcreteClass(string $class, array $args = [], array $methods = []): MockObject {
    // @phpstan-ignore-next-line
    $mock = $this->getMockBuilder($class);
    $mock = !empty($args) ? $mock->enableOriginalConstructor()->setConstructorArgs($args) : $mock->disableOriginalConstructor();
    $mock = $mock->addMethods($methods)->getMock();

    return $mock;
  }

  /**
   * Check if testing framework was ran with --debug option.
   */
  protected function isDebug(): bool {
    return in_array('--debug', $_SERVER['argv'], TRUE);
  }

}
