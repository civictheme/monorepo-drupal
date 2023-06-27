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
 * @group site:unit
 */
class UtilityTest extends UnitTestCase {

  /**
   * Test for Utility::multilineToArray().
   *
   * @dataProvider dataProviderMultilineToArray
   */
  public function testMultilineToArray($string, $expected) {
    $this->assertEquals($expected, Utility::multilineToArray($string));
  }

  /**
   * Data provider for multilineToArray().
   */
  public function dataProviderMultilineToArray() {
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
   * @dataProvider dataProviderArrayToMultiline
   */
  public function testArrayToMultiline($array, $expected) {
    $this->assertEquals($expected, Utility::arrayToMultiline($array));
  }

  /**
   * Data provider for arrayToTextarea().
   */
  public function dataProviderArrayToMultiline() {
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

  /**
   * Test for Utility::extractValueByPath().
   *
   * @covers ::extractValueByPath
   * @dataProvider dataProviderExtractValueByPath
   * @group wip2
   */
  public function testExtractValueByPath($data, $path, $expected) {
    $this->assertEquals($expected, Utility::extractValueByPath($data, $path));
  }

  /**
   * Data provider for testExtractValueByPath().
   */
  public function dataProviderExtractValueByPath(): array {
    return [
      [[], '', []],
      [[], '/', []],

      [['k1' => 'v1', 'k2' => 'v2'], 'k1', 'v1'],
      [['k1' => 'v1', 'k2' => 'v2'], 'k2', 'v2'],
      [['k1' => 'v1', 'k2' => 'v2'], 'k99', ''],

      [['k1' => ['k11' => 'v11'], 'k2' => 'v2'], 'k1/k11', 'v11'],
      [['k1' => ['k11' => 'v11'], 'k2' => 'v2'], 'k1/k99', ''],

      [
        [
          'k1' => [
            ['k11' => 'v11'],
            ['k12' => 'v12'],
            ['k13' => 'v13'],
          ],
          'k2' => 'v2',
        ],
        'k1/*/k12',
        'v12',
      ],

      [
        [
          'k1' => [
            ['k11' => 'v11'],
            ['k12' => 'v12'],
            ['k13' => 'v13'],
          ],
          'k2' => 'v2',
        ],
        'k1/*',
        ['k11' => 'v11'],
      ],
    ];
  }

}
