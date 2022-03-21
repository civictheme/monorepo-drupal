<?php

namespace Drupal\Tests\civic\Unit;

/**
 * Class CivicParseAttributesUnitTest.
 *
 * Test cases for _civic_parse_attributes().
 *
 * @group Civic
 */
class CivicParseAttributesUnitTest extends CivicUnitTestBase {

  /**
   * @dataProvider dataProviderParseAttributes
   */
  public function testParse($string, $expected) {
    $actual = _civic_parse_attributes($string);

    $this->assertEquals($expected, $actual);
  }

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
      // With spaces.
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

}
