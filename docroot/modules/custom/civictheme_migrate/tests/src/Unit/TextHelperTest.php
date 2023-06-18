<?php

namespace Drupal\Tests\civictheme_migrate\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\civictheme_migrate\Utils\TextHelper;

/**
 * Tests the TextHelper class.
 *
 * @group civictheme_migrate
 * @group site:unit
 */
class TextHelperTest extends UnitTestCase {

  /**
   * Tests the reduceWhitespaces() method.
   *
   * @dataProvider dataProviderReduceWhitespaces
   */
  public function testReduceWhitespaces(string $value, string $expectedResult) {
    $result = TextHelper::reduceWhitespaces($value);
    $this->assertEquals($expectedResult, $result);
  }

  /**
   * Data provider for the reduceWhitespaces() method.
   */
  public function dataProviderReduceWhitespaces() {
    return [
      ['', ''],
      [' ', ''],
      ['  ', ''],
      [' a ', 'a'],
      ['   Hello    World   ', 'Hello World'],
      ['This is a test', 'This is a test'],
      ["Line 1\nLine 2\nLine 3", 'Line 1Line 2Line 3'],
      ["Line 1\r\nLine 2\r\nLine 3", 'Line 1Line 2Line 3'],
      ["Line 1\r\t\nLine 2\r\t\nLine 3", 'Line 1Line 2Line 3'],
      ["Line 1\tLine 2\r\t\nLine 3", 'Line 1Line 2Line 3'],
    ];
  }

}
