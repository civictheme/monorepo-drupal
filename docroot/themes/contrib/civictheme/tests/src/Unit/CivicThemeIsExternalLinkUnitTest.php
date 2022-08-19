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
        'test.com',
        [],
        FALSE,
      ],
      [
        '/about-us',
        'test.com',
        [
          'overridden.com',
          'overriddendomain.com',
        ],
        FALSE,
      ],
      // Link same as host.
      [
        'http://test.com/about-us',
        'test.com',
        [
          'overridden.com',
          'overriddendomain.com',
        ],
        FALSE,
      ],
      // Link with an overridden domain.
      [
        'http://overridden.com',
        'test.com',
        [
          'overridden.com',
          'overriddendomain.com',
        ],
        FALSE,
      ],
      // Link with an wildcard overridden domain.
      [
        'http://overriddendomain.com/about-us',
        'test.com',
        [
          'overridden.com',
          'overriddendomain.com/*',
        ],
        FALSE,
      ],
      [
        'http://overriddendomain.com/about-us',
        'test.com',
        [
          'overridden.com',
          'overriddendomain.com/test/*',
        ],
        TRUE,
      ],
      [
        'http://overriddendomain.com/test/about-us',
        'test.com',
        [
          'overridden.com',
          'overriddendomain.com/test/*',
        ],
        TRUE,
      ],
      [
        'http://overriddendomain.com/test/abc/about-us',
        'test.com',
        [
          'overridden.com',
          'overriddendomain.com/test/abc/*',
        ],
        TRUE,
      ],
      // Link with no wildcard overridden domain.
      [
        'http://overriddendomain.com/test/about-us',
        'test.com',
        [
          'overridden.com',
          'overriddendomain.com',
        ],
        TRUE,
      ],
      // Link with an external domain.
      [
        'http://example.com',
        'test.com',
        [
          'overridden.com',
          'overriddendomain.com',
        ],
        TRUE,
      ],
    ];
  }

}
