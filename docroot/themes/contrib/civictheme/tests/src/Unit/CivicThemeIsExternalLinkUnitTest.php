<?php

namespace Drupal\Tests\civictheme\Unit;

/**
 * Class CivicThemeIsExternalLinkUnitTest.
 *
 * Test cases for _civictheme_link_is_external().
 *
 * @group CivicTheme
 */
class CivicThemeIsExternalLinkUnitTest extends CivicThemeUnitTestBase {

  /**
   * Test for civictheme_link_is_external().
   *
   * @dataProvider dataProviderIsExternalLink
   */
  public function testParse($url, $host, $overridden_domains, $expected) {
    $actual = civictheme_link_is_external($url, $host, $overridden_domains);

    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testParse().
   */
  public function dataProviderIsExternalLink() {
    $host = 'http://test.com';
    $overridden_domains = [
      'http://overridden.com',
      'http://overriddendomain.com',
    ];
    return [
      // Link without domain.
      ['/about-us', $host, $overridden_domains, FALSE],
      // Link with an overridden domain.
      ['http://overridden.com', $host, $overridden_domains, FALSE],
      // Link with an wildcard overridden domain.
      [
        'http://overriddendomain.com/about-us',
        $host, $overridden_domains,
        FALSE,
      ],
      // Link with an external domain.
      ['http://example.com', $host, $overridden_domains, TRUE],
    ];
  }

}
