<?php

namespace Drupal\Tests\civictheme\Traits;

/**
 * Trait CivicThemeTestHelperTrait.
 *
 * Helper trait for tests.
 *
 * @package Drupal\civictheme\Tests
 */
trait CivicThemeTestHelperTrait {

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
   */
  protected static function callProtectedMethod($object, $method, array $args = []) {
    $class = new \ReflectionClass(is_object($object) ? get_class($object) : $object);
    $method = $class->getMethod($method);
    $method->setAccessible(TRUE);
    $object = $method->isStatic() ? NULL : $object;

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
   */
  protected static function setProtectedValue($object, $property, $value) {
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
   */
  protected static function getProtectedValue($object, $property) {
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
   * @return object
   *   Mocked class.
   */
  protected function prepareMock($class, array $methodsMap = [], array $args = []) {
    $methods = array_keys($methodsMap);
    $reflectionClass = new \ReflectionClass($class);

    $mock = $reflectionClass->isAbstract() ? $this->getMockForAbstractClass(
        $class, $args, '', !empty($args), TRUE, TRUE, $methods
      ) : $this->getMockForConcreteClass($class, $args, $methods);

    foreach ($methodsMap as $method => $value) {
      // Handle callback values differently.
      if (is_object($value) && strpos(get_class($value), 'Callback') !== FALSE) {
        $mock->expects($this->any())
          ->method($method)
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
   * @return object
   *   Mocked class.
   */
  protected function getMockForConcreteClass($class, array $args = [], array $methods = []) {
    $mock = $this->getMockBuilder($class);
    $mock = !empty($args) ? $mock->enableOriginalConstructor()->setConstructorArgs($args) : $mock->disableOriginalConstructor();
    $mock = $mock->addMethods($methods)->getMock();

    return $mock;
  }

  /**
   * Check if testing framework was ran with --debug option.
   */
  protected function isDebug() {
    return in_array('--debug', $_SERVER['argv'], TRUE);
  }

}
