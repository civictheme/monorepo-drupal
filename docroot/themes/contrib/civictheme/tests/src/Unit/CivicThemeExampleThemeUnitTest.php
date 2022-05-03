<?php

namespace Drupal\Tests\civictheme\Unit;

/**
 * Class CivicThemeExampleUnitTest.
 *
 * Example test case class.
 *
 * @group CivicTheme
 */
class CivicThemeExampleThemeUnitTest extends CivicThemeUnitTestBase {

  /**
   * @dataProvider dataProviderAdd
   */
  public function testAdd($a, $b, $expected, $excpectExceptionMessage = NULL) {
    if ($excpectExceptionMessage) {
      $this->expectException(\Exception::class);
      $this->expectExceptionMessage($excpectExceptionMessage);
    }

    // Replace below with a call to your class method.
    $actual = $a + $b;

    $this->assertEquals($expected, $actual);
  }

  public function dataProviderAdd() {
    return [
      [0, 0, 0],
      [1, 1, 2],
    ];
  }

}
