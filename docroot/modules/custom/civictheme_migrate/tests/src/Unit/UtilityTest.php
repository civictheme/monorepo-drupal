<?php

namespace Drupal\Tests\civictheme_migrate\Unit;

use Drupal\civictheme_migrate\Utility;
use Drupal\Tests\UnitTestCase;

/**
 * Class UtilityTest.
 *
 * Tests for Drupal\civictheme_migrate\Utility class.
 *
 * @group civictheme_migrate
| */
class UtilityTest extends UnitTestCase {

  /**
   * Test for Utility::multilineToArray().
   *
   * @dataProvider providerMultilineToArray
   */
  public function testMultilineToArray($string, $expected) {
    $actual = Utility::multilineToArray($string);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for multilineToArray().
   */
  public function providerMultilineToArray() {
    return [
      ['', []],
      [' ', []],
      ['a', ['a']],
      [
        'a
        b', ['a', 'b'],
      ],
      [
        'a aa
        b', ['a aa', 'b'],
      ],
      [
        'a aa
        b
        c', ['a aa', 'b', 'c'],
      ],
      [
        'a aa
        b

        c', ['a aa', 'b', 'c'],
      ],
      // Array as input.
      [
        ['a', 'b'], ['a', 'b'],
      ],
    ];
  }

  /**
   * Test for Utility::arrayToMultiline().
   *
   * @dataProvider providerArrayToMultiline
   */
  public function testArrayToMultiline($array, $expected) {
    $actual = Utility::arrayToMultiline($array);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for arrayToTextarea().
   */
  public function providerArrayToMultiline() {
    return [
      [[], ''],
      [[''], ''],
      [['', ''], ''],
      [[' ', ''], ' '],
      [['a'], 'a'],
      [
        ['a', 'b'], 'a' . PHP_EOL . 'b',
      ],
      [[' a ', 'b'], ' a ' . PHP_EOL . 'b'],
      [[' a ', '', 'b'], ' a ' . PHP_EOL . 'b'],
      [[' a ', ' ', 'b'], ' a ' . PHP_EOL . ' ' . PHP_EOL . 'b'],
      // String as input.
      ['', ''],
      ['a', 'a'],
    ];
  }

}
