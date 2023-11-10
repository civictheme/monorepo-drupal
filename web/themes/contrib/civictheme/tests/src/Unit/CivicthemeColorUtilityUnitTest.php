<?php

namespace Drupal\Tests\civictheme\Unit;

use Drupal\civictheme\Color\CivicthemeColorUtility;

/**
 * Class CivicthemeColorUtilityUnitTest.
 *
 * Test cases for color utility functions.
 *
 * @group CivicTheme
 * @group site:unit
 */
class CivicthemeColorUtilityUnitTest extends CivicthemeUnitTestBase {

  /**
   * Test for normalizeHex().
   *
   * @dataProvider dataProviderNormalizeHex
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function testNormalizeHex(string $value, bool $preserve_hash, string $expected): void {
    $actual = CivicthemeColorUtility::normalizeHex($value, $preserve_hash);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testNormalizeHex().
   */
  public function dataProviderNormalizeHex(): array {
    return [
      ['#00698f', FALSE, '00698f'],
      ['#000', FALSE, '000000'],
      ['fff', TRUE, 'ffffff'],
      ['00698f', TRUE, '00698f'],
      ['61daff', FALSE, '61daff'],
    ];
  }

  /**
   * Test for mix().
   *
   * @dataProvider dataProviderMix
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function testMix(string $color, string $mixer, int $range, string $expected): void {
    $actual = CivicthemeColorUtility::mix($color, $mixer, $range);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testMix().
   */
  public function dataProviderMix(): array {
    return [
      ['#00698f', '#000', 10, '#005e80'],
      ['#00698f', '#000', 0, '#00698f'],
      ['#e6e9eb', '#fff', 80, '#fafafb'],
    ];
  }

  /**
   * Test for hexToRgb().
   *
   * @dataProvider dataProviderHexToRgb
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function testHexToRgb(string $hex, array $expected): void {
    $actual = CivicthemeColorUtility::hexToRgb($hex);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testHexToRgb().
   */
  public function dataProviderHexToRgb(): array {
    return [
      ['#00698f', [0, 105, 143]],
      ['e6e9eb', [230, 233, 235]],
    ];
  }

  /**
   * Test for intToHex().
   *
   * @dataProvider dataProviderIntToHex
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function testIntToHex(int $value, string|int $expected): void {
    $actual = CivicthemeColorUtility::intToHex($value);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testIntToHex().
   */
  public function dataProviderIntToHex(): array {
    return [
      [124, '7c'],
      [250, 'fa'],
      [0, 00],
    ];
  }

}
