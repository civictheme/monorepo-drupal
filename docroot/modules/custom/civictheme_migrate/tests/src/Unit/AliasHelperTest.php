<?php

namespace Drupal\Tests\civictheme_migrate\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\civictheme_migrate\Utils\AliasHelper;

/**
 * Tests the AliasHelper class.
 *
 * @group civictheme_migrate
 * @group site:unit
 */
class AliasHelperTest extends UnitTestCase {

  /**
   * Tests the extractDomainFromUrl() method.
   *
   * @dataProvider dataProviderExtractDomainFromUrl
   */
  public function testExtractDomainFromUrl($url, $expected) {
    $this->assertEquals($expected, AliasHelper::extractDomainFromUrl($url));
  }

  /**
   * Provides data for testing the extractDomainFromUrl() method.
   */
  public function dataProviderExtractDomainFromUrl() {
    return [
      ['https://www.example.com/some/path', 'https://www.example.com'],
      ['http://subdomain.example.com', 'http://subdomain.example.com'],
      ['https://www.example.com', 'https://www.example.com'],
      ['https://example.com', 'https://example.com'],
    ];
  }

  /**
   * Tests the extractAliasFromUrl() method.
   *
   * @dataProvider dataProviderExtractAliasFromUrl
   */
  public function testExtractAliasFromUrl($url, $expected) {
    $this->assertEquals($expected, AliasHelper::extractAliasFromUrl($url));
  }

  /**
   * Provides data for the testExtractAliasFromUrl() method.
   */
  public function dataProviderExtractAliasFromUrl() {
    return [
      [
        'https://www.example.com/some/path?param=value',
        '/some/path?param=value',
      ],
      [
        'https://www.example.com/another/path',
        '/another/path',
      ],
      [
        'https://www.example.com/',
        '/',
      ],
    ];
  }

  /**
   * Tests the extractAliasPathFromUrl() method.
   *
   * @dataProvider dataProviderExtractAliasPathFromUrl
   */
  public function testExtractAliasPathFromUrl($url, $expected) {
    $this->assertEquals($expected, AliasHelper::extractAliasPathFromUrl($url));
  }

  /**
   * Provides test data for testExtractAliasPathFromUrl().
   */
  public function dataProviderExtractAliasPathFromUrl() {
    return [
      ['https://www.example.com/some/path', '/some/path'],
      ['https://www.example.com/another/path', '/another/path'],
      ['https://www.example.com', ''],
      ['https://www.example.com/', '/'],
      ['https://www.example.com?query=param', ''],
      ['https://www.example.com/?query=param', '/'],
    ];
  }

  /**
   * Tests the isInternalUri() method.
   *
   * @dataProvider dataProviderIsInternalUri
   */
  public function testIsInternalUri($uri, $expected) {
    $this->assertEquals($expected, AliasHelper::isInternalUri($uri));
  }

  /**
   * Provides data for the testIsInternalUri() method.
   */
  public function dataProviderIsInternalUri() {
    return [
      ['internal:/some/path', TRUE],
      ['https://www.example.com', FALSE],
      ['/another/internal/path', TRUE],
      ['ftp://example.com', FALSE],
    ];
  }

  /**
   * Tests the getAliasFromInternalUri() method.
   */
  public function testGetAliasFromInternalUri() {
    $internal_uri = 'internal:/some/path';
    $external_uri = 'https://www.example.com';

    $expected_alias = '/some/path';

    $this->assertEquals($expected_alias, AliasHelper::getAliasFromInternalUri($internal_uri));
    $this->assertEquals($external_uri, AliasHelper::getAliasFromInternalUri($external_uri));
  }

  /**
   * Tests the standardiseInternalUri() method.
   */
  public function testStandardiseInternalUri() {
    $internal_uri = '/some/path';
    $expected_internal_uri = 'internal:/some/path';
    $this->assertEquals($expected_internal_uri, AliasHelper::standardiseInternalUri($internal_uri));

    $external_uri = 'https://www.example.com';
    $this->assertEquals($external_uri, AliasHelper::standardiseInternalUri($external_uri));
  }

  /**
   * Tests the sanitiseAlias() method.
   *
   * @dataProvider dataProviderSanitiseAlias
   */
  public function testSanitiseAlias(string $alias, string $expected) {
    $this->assertEquals($expected, AliasHelper::sanitiseAlias($alias));
  }

  /**
   * Data provider for the testSanitiseAlias() method.
   */
  public function dataProviderSanitiseAlias() {
    return [
      ['/some/path/', 'some/path'],
      ['/another/path/', 'another/path'],
      ['/example/', 'example'],
      ['example/', 'example'],
      ['/', ''],
    ];
  }

}
