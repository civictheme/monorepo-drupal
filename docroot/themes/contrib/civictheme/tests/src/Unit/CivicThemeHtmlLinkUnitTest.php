<?php

// phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
namespace Drupal\Tests\civictheme\Unit;

/**
 * Class CivicThemeHtmlLinkUnitTest.
 *
 * Test cases for processing HTML link.
 *
 * @group CivicTheme
 */
class CivicThemeHtmlLinkUnitTest extends CivicThemeUnitTestBase {

  /**
   * Test for civictheme_link_is_external().
   *
   * @dataProvider dataProviderLinkIsExternal
   */
  public function testLinkIsExternal($url, $base_url, array $overridden_domains, $expected) {
    $actual = civictheme_url_is_external($url, $base_url, $overridden_domains);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testLinkIsExternal().
   */
  public function dataProviderLinkIsExternal() {
    return [
      ['', '', [], FALSE],
      ['', '', ['example.com'], FALSE],
      ['', 'example.com', ['example.com'], FALSE],

      // Path.
      ['/path', 'example.com', [], FALSE],
      ['/path/subpath', 'example.com', [], FALSE],

      // Current domain.
      ['http://example.com', 'example.com', [], FALSE],
      ['http://example.com:8080', 'example.com', [], FALSE],
      ['https://example.com', 'example.com', [], FALSE],
      ['https://example.com:8080', 'example.com', [], FALSE],
      ['//example.com', 'example.com', [], FALSE],
      ['//example.com:8080', 'example.com', [], FALSE],
      ['https://example.com/', 'example.com', [], FALSE],
      ['https://example.com:8080/', 'example.com', [], FALSE],
      ['https://example.com/path', 'example.com', [], FALSE],
      ['https://example.com:8080/path', 'example.com', [], FALSE],
      ['https://example.com/path/', 'example.com', [], FALSE],
      ['https://example.com:8080/path/', 'example.com', [], FALSE],

      // External domain.
      ['http://example.com', 'example2.com', [], TRUE],
      ['http://example.com:8080', 'example2.com', [], TRUE],
      ['https://example.com', 'example2.com', [], TRUE],
      ['https://example.com:8080', 'example2.com', [], TRUE],
      ['//example.com', 'example2.com', [], TRUE],
      ['//example.com:8080', 'example2.com', [], TRUE],
      ['https://example.com/', 'example2.com', [], TRUE],
      ['https://example.com:8080/', 'example2.com', [], TRUE],
      ['https://example.com/path', 'example2.com', [], TRUE],
      ['https://example.com:8080/path', 'example2.com', [], TRUE],
      ['https://example.com/path/', 'example2.com', [], TRUE],
      ['https://example.com:8080/path/', 'example2.com', [], TRUE],

      // External domain overridden as an internal.
      ['http://example.com', 'example2.com', ['example.com'], FALSE],
      ['http://example.com:8080', 'example2.com', ['example.com'], FALSE],
      ['https://example.com', 'example2.com', ['example.com'], FALSE],
      ['https://example.com:8080', 'example2.com', ['example.com'], FALSE],
      ['//example.com', 'example2.com', ['example.com'], FALSE],
      ['//example.com:8080', 'example2.com', ['example.com'], FALSE],
      ['https://example.com/', 'example2.com', ['example.com'], FALSE],
      ['https://example.com:8080/', 'example2.com', ['example.com'], FALSE],
      ['https://example.com/path', 'example2.com', ['example.com'], FALSE],
      ['https://example.com:8080/path', 'example2.com', ['example.com'], FALSE],
      ['https://example.com/path/', 'example2.com', ['example.com'], FALSE],
      ['https://example.com:8080/path/', 'example2.com', ['example.com'], FALSE],

      // External domain overridden as an internal with protocol.
      ['http://example.com', 'example2.com', ['http://example.com'], FALSE],
      ['http://example.com:8080', 'example2.com', ['http://example.com'], FALSE],
      ['https://example.com', 'example2.com', ['http://example.com'], FALSE],
      ['https://example.com:8080', 'example2.com', ['http://example.com'], FALSE],
      ['//example.com', 'example2.com', ['http://example.com'], FALSE],
      ['//example.com:8080', 'example2.com', ['http://example.com'], FALSE],
      ['https://example.com/', 'example2.com', ['http://example.com'], FALSE],
      ['https://example.com:8080/', 'example2.com', ['http://example.com'], FALSE],
      ['https://example.com/path', 'example2.com', ['http://example.com'], FALSE],
      ['https://example.com:8080/path', 'example2.com', ['http://example.com'], FALSE],
      ['https://example.com/path/', 'example2.com', ['http://example.com'], FALSE],
      ['https://example.com:8080/path/', 'example2.com', ['http://example.com'], FALSE],
    ];
  }

  /**
   * Test for _civictheme_process_html_links().
   *
   * @dataProvider dataProviderProcessHtmlLinks
   */
  public function testProcessHtmlLinks($html, $base_url, $new_window, $external_new_window, array $override_domains, $expected) {
    $actual = _civictheme_process_html_content_links($html, $base_url, $new_window, $external_new_window, $override_domains);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testProcessHtmlLinks().
   *
   * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
   */
  public function dataProviderProcessHtmlLinks() {
    return [
      // Empty.
      ['', '', FALSE, FALSE, [], ''],

      // No content.
      ['', 'http://example.com', FALSE, FALSE, [], ''],

      // No link content.
      ['<p>Word</p>', 'http://example.com', FALSE, FALSE, [], '<p>Word</p>'],

      // Basic link content.
      ['<p>Word <a href="/path">Link</a></p>', 'http://example.com', FALSE, FALSE, [], '<p>Word <a href="/path" class="civictheme-link civictheme-theme-light">Link</a></p>'],
      ['<p>Word <a href="/path">Link</a></p>', 'http://example.com', TRUE, FALSE, [], '<p>Word <a href="/path" target="_blank" class="civictheme-link civictheme-theme-light">Link</a></p>'],
      ['<p>Word <a href="/path">Link</a></p>', 'http://example.com', TRUE, TRUE, [], '<p>Word <a href="/path" target="_blank" class="civictheme-link civictheme-theme-light">Link</a></p>'],

      // Internal, no CSS classes, no domain overrides.
      ['<a href="/path">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="/path" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="/path">Link</a>', 'example.com', TRUE, FALSE, [], '<a href="/path" target="_blank" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="/path/">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="/path/" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com/">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com/path">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/path" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com/path/">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/path/" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path/">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path/" class="civictheme-link civictheme-theme-light">Link</a>'],

      // Internal, CSS classes, no domain overrides.
      ['<a href="/path" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="/path" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="/path" class="someclass">Link</a>', 'example.com', TRUE, FALSE, [], '<a href="/path" class="someclass civictheme-link civictheme-theme-light" target="_blank">Link</a>'],
      ['<a href="/path/" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="/path/" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080" class="someclass civictheme-link civictheme-theme-light">Link</a>'],

      ['<a href="http://example.com/" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com/path" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/path" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com/path/" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/path/" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path/" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path/" class="someclass civictheme-link civictheme-theme-light">Link</a>'],

      // External, no CSS classes, no domain overrides.
      ['<a href="http://example.com">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com" class="civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com">Link</a>', 'example2.com', TRUE, FALSE, [], '<a href="http://example.com" target="_blank" class="civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com:8080">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080" class="civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com/">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/" class="civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com:8080/">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/" class="civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com/path">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/path" class="civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com:8080/path">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path" class="civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com/path/">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/path/" class="civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com:8080/path/">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path/" class="civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],

      // External, CSS classes, no domain overrides.
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com" class="someclass civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', TRUE, FALSE, [], '<a href="http://example.com" class="someclass civictheme-link civictheme-theme-light civictheme-link--external" target="_blank">Link</a>'],
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', FALSE, TRUE, [], '<a href="http://example.com" class="someclass civictheme-link civictheme-theme-light civictheme-link--external" target="_blank">Link</a>'],
      ['<a href="http://example.com:8080" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080" class="someclass civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/" class="someclass civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com:8080/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/" class="someclass civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com/path" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/path" class="someclass civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com:8080/path" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path" class="someclass civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com/path/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/path/" class="someclass civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],
      ['<a href="http://example.com:8080/path/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path/" class="someclass civictheme-link civictheme-theme-light civictheme-link--external">Link</a>'],

      // External, no CSS classes, domain overrides.
      ['<a href="http://example.com">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com">Link</a>', 'example2.com', TRUE, FALSE, ['example.com'], '<a href="http://example.com" target="_blank" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com">Link</a>', 'example2.com', FALSE, TRUE, ['example.com'], '<a href="http://example.com" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com/">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com/path">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/path" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/path" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com/path/">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/path/" class="civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path/">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/path/" class="civictheme-link civictheme-theme-light">Link</a>'],

      // External, CSS classes, domain overrides.
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', TRUE, FALSE, ['example.com'], '<a href="http://example.com" class="someclass civictheme-link civictheme-theme-light" target="_blank">Link</a>'],
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', FALSE, TRUE, ['example.com'], '<a href="http://example.com" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com/path" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/path" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/path" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com/path/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/path/" class="someclass civictheme-link civictheme-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/path/" class="someclass civictheme-link civictheme-theme-light">Link</a>'],

      // Multiple links within content, no overrides.
      [
        '<p>Word1 <a href="http://example.com">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example.com/path">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example.com" class="someclass">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example.com/path" class="someclass">Link</a> word8</p>' .
        '<p>Word1 <a href="http://example2.com">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example2.com/path">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example2.com" class="someclass">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example2.com/path" class="someclass">Link</a> word8</p>',
        'example2.com',
        FALSE,
        FALSE,
        [],
        '<p>Word1 <a href="http://example.com" class="civictheme-link civictheme-theme-light civictheme-link--external">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example.com/path" class="civictheme-link civictheme-theme-light civictheme-link--external">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example.com" class="someclass civictheme-link civictheme-theme-light civictheme-link--external">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example.com/path" class="someclass civictheme-link civictheme-theme-light civictheme-link--external">Link</a> word8</p>' .
        '<p>Word1 <a href="http://example2.com" class="civictheme-link civictheme-theme-light">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example2.com/path" class="civictheme-link civictheme-theme-light">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example2.com" class="someclass civictheme-link civictheme-theme-light">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example2.com/path" class="someclass civictheme-link civictheme-theme-light">Link</a> word8</p>',
      ],

      // Multiple links within content, overrides.
      [
        '<p>Word1 <a href="http://example.com">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example.com/path">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example.com" class="someclass">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example.com/path" class="someclass">Link</a> word8</p>' .
        '<p>Word1 <a href="http://example2.com">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example2.com/path">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example2.com" class="someclass">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example2.com/path" class="someclass">Link</a> word8</p>',
        'example2.com',
        FALSE,
        FALSE,
        ['example.com'],
        '<p>Word1 <a href="http://example.com" class="civictheme-link civictheme-theme-light">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example.com/path" class="civictheme-link civictheme-theme-light">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example.com" class="someclass civictheme-link civictheme-theme-light">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example.com/path" class="someclass civictheme-link civictheme-theme-light">Link</a> word8</p>' .
        '<p>Word1 <a href="http://example2.com" class="civictheme-link civictheme-theme-light">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example2.com/path" class="civictheme-link civictheme-theme-light">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example2.com" class="someclass civictheme-link civictheme-theme-light">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example2.com/path" class="someclass civictheme-link civictheme-theme-light">Link</a> word8</p>',
      ],

      // Existing classes.
      ['<a href="http://example.com" class="civictheme-link">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="civictheme-link">Link</a>'],
      ['<a href="http://example.com" class="civictheme-button someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="civictheme-button someclass">Link</a>'],
      ['<a href="http://example.com" class="someclass civictheme-button">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="someclass civictheme-button">Link</a>'],
      ['<a href="http://example.com" class="someclass civictheme-link">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="someclass civictheme-link">Link</a>'],
    ];
  }

}
