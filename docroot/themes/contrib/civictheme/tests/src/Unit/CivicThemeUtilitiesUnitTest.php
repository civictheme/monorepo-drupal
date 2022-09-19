<?php

namespace Drupal\Tests\civictheme\Unit;

/**
 * Class CivicThemeUtilitiesUnitTest.
 *
 * Test cases for utility functions.
 *
 * @group CivicTheme
 */
class CivicThemeUtilitiesUnitTest extends CivicThemeUnitTestBase {

  /**
   * Test for civictheme_parse_attributes().
   *
   * @dataProvider dataProviderParseAttributes
   */
  public function testParseAttributes($string, $expected) {
    $actual = civictheme_parse_attributes($string);

    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testParseAttributes().
   */
  public function dataProviderParseAttributes() {
    return [
      // Empty.
      ['', []],
      // Minimal.
      ['a="b"', ['a' => 'b']],
      // With a space.
      ['a = "b"', ['a' => 'b']],
      // Multiple.
      ['a = "b" c="d"', ['a' => 'b', 'c' => 'd']],
      // Vertical spacings.
      ['a = "b c d" e="f g"', ['a' => 'b c d', 'e' => 'f g']],
      // Numeric.
      ['a = "b1 c2 d3" e="f4 g5"', ['a' => 'b1 c2 d3', 'e' => 'f4 g5']],
      // Repeating.
      ['a="b" a="c"', ['a' => 'c']],
      // No value - only works when the value is set to an empty string.
      ['a=""', ['a' => NULL]],
      // HTML tag.
      ['<tag a="" b="c">', ['a' => NULL, 'b' => 'c']],
      ['<tag a="" b="c" />', ['a' => NULL, 'b' => 'c']],
    ];
  }

  /**
   * Test for civictheme_multiline_to_array().
   *
   * @dataProvider dataProviderMultilineToArray
   */
  public function testMultilineToArray($string, $expected) {
    $actual = civictheme_multiline_to_array($string);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testMultilineToArray().
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
   * Test for civictheme_array_to_multiline().
   *
   * @dataProvider dataProviderArrayToMultiline
   */
  public function testArrayToMultiline($array, $expected) {
    $actual = civictheme_array_to_multiline($array);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testArrayToMultiline().
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

}
