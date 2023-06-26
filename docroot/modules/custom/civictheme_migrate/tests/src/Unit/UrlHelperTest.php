<?php

namespace Drupal\Tests\civictheme_migrate\Unit;

use Drupal\civictheme_migrate\Converter\UrlHelper;
use Drupal\Tests\UnitTestCase;

/**
 * Class UrlHelperTest.
 *
 * Tests for Drupal\civictheme_migrate\Converter\UrlHelper class.
 *
 * @group civictheme_migrate
 * @group site:unit
 */
class UrlHelperTest extends UnitTestCase {

  /**
   * Test for UrlHelper::extractLocalUrl().
   *
   * @covers ::extractLocalUrl
   * @dataProvider dataProviderExtractLocalUrl
   */
  public function testExtractLocalUrl($uri, $domains, $expected) {
    $this->assertEquals($expected, UrlHelper::extractLocalUrl($uri, $domains));
  }

  /**
   * Data provider for testExtractLocalUrl().
   */
  public function dataProviderExtractLocalUrl() {
    return [
      ['', [], NULL],
      ['', ['example.com'], NULL],
      [' ', [], '/ '],

      ['#', [], NULL],
      ['#abc', [], NULL],
      ['mailto:a.b@cde.com', [], NULL],
      ['a.b@cde.com', [], '/a.b@cde.com'],

      ['/path', [], '/path'],
      ['path', [], '/path'],
      ['/path?q=1', [], '/path'],

      ['http://example.com', [], NULL],
      ['http://example.com/', [], NULL],
      ['http://example.com/path', [], NULL],

      ['http://example.com/', ['http://example.com'], '/'],
      ['http://example.com/', ['http://example.com/'], '/'],
      ['http://example.com/path', ['http://example.com'], '/path'],
      ['http://example.com/path', ['http://example.com/'], '/path'],

      ['http://example.com/', ['example.com'], NULL],
    ];
  }

}
