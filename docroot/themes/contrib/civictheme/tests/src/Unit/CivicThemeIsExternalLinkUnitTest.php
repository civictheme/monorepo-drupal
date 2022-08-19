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
  public function testCivichthemeLinkIsExternal($url, $host, $overridden_domains, $expected) {
    $actual = civictheme_link_is_external($url, $host, $overridden_domains);

    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testCivichthemeLinkIsExternal().
   */
  public function dataProviderIsExternalLink() {
    return [
      // Empty.
      ['', '', [], TRUE],
      // Link without domain.
      [
        '/about-us',
        'http://test.com',
        [
          'overridden.com',
          'overriddendomain.com',
        ],
        FALSE,
      ],
      // Link with an overridden domain.
      [
        'http://overridden.com',
        'http://test.com',
        [
          'overridden.com',
          'overriddendomain.com',
        ],
        FALSE,
      ],
      // Link with an wildcard overridden domain.
      [
        'http://overriddendomain.com/about-us',
        'http://test.com',
        [
          'overridden.com',
          'overriddendomain.com/*',
        ],
        FALSE,
      ],
      [
        'http://overriddendomain.com/about-us',
        'http://test.com',
        [
          'overridden.com',
          'overriddendomain.com/test/*',
        ],
        TRUE,
      ],
      [
        'http://overriddendomain.com/test/about-us',
        'http://test.com',
        [
          'overridden.com',
          'overriddendomain.com/test/*',
        ],
        TRUE,
      ],
      // Link with an external domain.
      [
        'http://example.com',
        'http://test.com',
        [
          'overridden.com',
          'overriddendomain.com',
        ],
        TRUE,
      ],
    ];
  }

}
