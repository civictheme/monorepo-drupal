<?php

namespace Drupal\Tests\civictheme\Unit;

/**
 * Class CivicThemeMultilineToArrayUnitTest.
 *
 * Test cases for _civictheme_multiline_to_array().
 *
 * @group CivicTheme
 */
class CivicThemeMultilineToArrayUnitTest extends CivicThemeUnitTestBase {

  /**
   * Test for civictheme_multiline_to_array().
   *
   * @dataProvider dataProviderMultilineToArray
   */
  public function testCivicthemeMultilineToArray($string, $expected) {
    $actual = civictheme_multiline_to_array($string);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for civictheme_multiline_to_array().
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

}
