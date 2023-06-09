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
   * Provides data for testing the extractDomainFromUrl() method.
   *
   * @return array
   *   An array of test cases, each containing the URL and the expected domain.
   */
  public function domainDataProvider() {
    return [
      ['https://www.example.com/some/path', 'https://www.example.com'],
      ['http://subdomain.example.com', 'http://subdomain.example.com'],
      ['https://www.example.com', 'https://www.example.com'],
      ['https://example.com', 'https://example.com'],
    ];
  }

  /**
   * Tests the extractDomainFromUrl() method.
   *
   * @dataProvider domainDataProvider
   */
  public function testExtractDomainFromUrl($url, $expected_domain) {
    $actual_domain = AliasHelper::extractDomainFromUrl($url);
    $this->assertEquals($expected_domain, $actual_domain);
  }

  /**
   * Tests the extractAliasFromUrl() method.
   *
   * @dataProvider extractAliasDataProvider
   */
  public function testExtractAliasFromUrl($url, $expectedAlias) {
    $actualAlias = AliasHelper::extractAliasFromUrl($url);

    $this->assertEquals($expectedAlias, $actualAlias);
  }

  /**
   * Provides data for the testExtractAliasFromUrl() method.
   *
   * @return array
   *   An array of test cases with URL and expected alias.
   */
  public function extractAliasDataProvider() {
    return [
      [
        'https://www.example.com/some/path?param=value',
        '/some/path?param=value',
      ],
      ['https://www.example.com/another/path', '/another/path'],
      ['https://www.example.com/', '/'],
    ];
  }

  /**
   * Tests the extractAliasPathFromUrl() method.
   *
   * @dataProvider aliasPathProvider
   */
  public function testExtractAliasPathFromUrl($url, $expectedPath) {
    $actualPath = AliasHelper::extractAliasPathFromUrl($url);
    $this->assertEquals($expectedPath, $actualPath);
  }

  /**
   * Provides test data for testExtractAliasPathFromUrl().
   */
  public function aliasPathProvider() {
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
   * @dataProvider isInternalUriDataProvider
   */
  public function testIsInternalUri($uri, $expectedResult) {
    $actualResult = AliasHelper::isInternalUri($uri);
    $this->assertEquals($expectedResult, $actualResult);
  }

  /**
   * Provides data for the testIsInternalUri() method.
   */
  public function isInternalUriDataProvider() {
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
    $external_uri = 'https://www.example.com';

    $expected_internal_uri = 'internal:/some/path';

    $this->assertEquals($expected_internal_uri, AliasHelper::standardiseInternalUri($internal_uri));
    $this->assertEquals($external_uri, AliasHelper::standardiseInternalUri($external_uri));
  }

  /**
   * Tests the sanitiseAlias() method.
   *
   * @dataProvider sanitiseAliasDataProvider
   */
  public function testSanitiseAlias(string $alias, string $expectedSanitisedAlias) {
    $actualSanitisedAlias = AliasHelper::sanitiseAlias($alias);

    $this->assertEquals($expectedSanitisedAlias, $actualSanitisedAlias);
  }

  /**
   * Data provider for the testSanitiseAlias() method.
   *
   * @return array
   *   An array of test cases with the alias and expected sanitised alias.
   */
  public function sanitiseAliasDataProvider() {
    return [
      ['/some/path/', 'some/path'],
      ['/another/path/', 'another/path'],
      ['/example/', 'example'],
      ['example/', 'example'],
      ['/', ''],
    ];
  }

}
