<?php

namespace Drupal\Tests\civictheme_migrate\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\civictheme_migrate\Utils\TextHelper;

/**
 * Tests the TextHelper class.
 *
 * @group civictheme_migrate
 */
class TextHelperTest extends UnitTestCase {

  /**
   * Tests the trimWhitespaces() method.
   *
   * @dataProvider trimWhitespacesDataProvider
   */
  public function testTrimWhitespaces(string $value, string $expectedResult) {
    $result = TextHelper::trimWhitespaces($value);
    $this->assertEquals($expectedResult, $result);
  }

  /**
   * Data provider for the testTrimWhitespaces() method.
   *
   * @return array
   *   An array of test cases with the input value and expected result.
   */
  public function trimWhitespacesDataProvider() {
    return [
      ['   Hello    World   ', 'Hello World'],
      ['This is a test', 'This is a test'],
      ["Line 1\nLine 2\nLine 3", 'Line 1Line 2Line 3'],
    ];
  }

}
