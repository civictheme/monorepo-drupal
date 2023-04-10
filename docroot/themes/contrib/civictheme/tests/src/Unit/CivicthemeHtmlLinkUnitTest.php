<?php

// phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
namespace Drupal\Tests\civictheme\Unit;

/**
 * Class CivicThemeHtmlLinkUnitTest.
 *
 * Test cases for processing HTML link.
 *
 * @group CivicTheme
 * @group site:unit
 */
class CivicthemeHtmlLinkUnitTest extends CivicthemeUnitTestBase {

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
      ['example', 'example.com', [], FALSE],
      ['tel:+6121231234', 'example.com', [], FALSE],
      ['mailto:example@example.com', 'example.com', [], FALSE],

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
      ['<p>Word <a href="/path">Link</a></p>', 'http://example.com', FALSE, FALSE, [], '<p>Word <a href="/path" class="ct-content-link ct-theme-light">Link</a></p>'],
      ['<p>Word <a href="/path">Link</a></p>', 'http://example.com', TRUE, FALSE, [], '<p>Word <a href="/path" target="_blank" aria-label="Opens in a new tab" class="ct-content-link ct-theme-light">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a></p>'],
      ['<p>Word <a href="/path">Link</a></p>', 'http://example.com', TRUE, TRUE, [], '<p>Word <a href="/path" target="_blank" aria-label="Opens in a new tab" class="ct-content-link ct-theme-light">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a></p>'],
      ['<p>Word example@example.com</p>', 'http://example.com', FALSE, FALSE, [], '<p>Word <a href="mailto:example@example.com" class="ct-content-link ct-theme-light">example@example.com</a></p>'],
      ['<p>Word example@example.com check</p>', 'http://example.com', TRUE, FALSE, [], '<p>Word <a href="mailto:example@example.com" target="_blank" aria-label="Opens in a new tab" class="ct-content-link ct-theme-light">example@example.com<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a> check</p>'],

      // Internal, no CSS classes, no domain overrides.
      ['<a href="/path">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="/path" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="/path">Link</a>', 'example.com', TRUE, FALSE, [], '<a href="/path" target="_blank" aria-label="Opens in a new tab" class="ct-content-link ct-theme-light">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a>'],
      ['<a href="/path/">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="/path/" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com/">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com/path">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/path" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com/path/">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/path/" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path/">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path/" class="ct-content-link ct-theme-light">Link</a>'],

      // Internal, CSS classes, no domain overrides.
      ['<a href="/path" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="/path" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="/path" class="someclass">Link</a>', 'example.com', TRUE, FALSE, [], '<a href="/path" class="someclass ct-content-link ct-theme-light" target="_blank" aria-label="Opens in a new tab">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a>'],
      ['<a href="/path/" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="/path/" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080" class="someclass ct-content-link ct-theme-light">Link</a>'],

      ['<a href="http://example.com/" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com/path" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/path" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com/path/" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com/path/" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path/" class="someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path/" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path/" class="someclass ct-custom">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path/" class="someclass ct-custom">Link</a>'],
      ['<a href="http://example.com:8080/path/" class="someclass ct-custom">Link</a>', 'example.com', TRUE, TRUE, [], '<a href="http://example.com:8080/path/" class="someclass ct-custom" target="_blank" aria-label="Opens in a new tab">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a>'],

      // External, no CSS classes, no domain overrides.
      ['<a href="http://example.com">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com" class="ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com">Link</a>', 'example2.com', TRUE, FALSE, [], '<a href="http://example.com" target="_blank" aria-label="Opens in a new tab" class="ct-content-link ct-theme-light ct-content-link--external">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a>'],
      ['<a href="http://example.com:8080">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080" class="ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com/">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/" class="ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com:8080/">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/" class="ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com/path">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/path" class="ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com:8080/path">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path" class="ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com/path/">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/path/" class="ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com:8080/path/">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path/" class="ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com:8080/path/">Link</a>', 'example2.com', TRUE, TRUE, [], '<a href="http://example.com:8080/path/" target="_blank" aria-label="Opens in a new tab" class="ct-content-link ct-theme-light ct-content-link--external">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a>'],

      // External, CSS classes, no domain overrides.
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com" class="someclass ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', TRUE, FALSE, [], '<a href="http://example.com" class="someclass ct-content-link ct-theme-light ct-content-link--external" target="_blank" aria-label="Opens in a new tab">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a>'],
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', FALSE, TRUE, [], '<a href="http://example.com" class="someclass ct-content-link ct-theme-light ct-content-link--external" target="_blank" aria-label="Opens in a new tab">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a>'],
      ['<a href="http://example.com:8080" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080" class="someclass ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/" class="someclass ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com:8080/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/" class="someclass ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com/path" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/path" class="someclass ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com:8080/path" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path" class="someclass ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com/path/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com/path/" class="someclass ct-content-link ct-theme-light ct-content-link--external">Link</a>'],
      ['<a href="http://example.com:8080/path/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, [], '<a href="http://example.com:8080/path/" class="someclass ct-content-link ct-theme-light ct-content-link--external">Link</a>'],

      // External, no CSS classes, domain overrides.
      ['<a href="http://example.com">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com">Link</a>', 'example2.com', TRUE, FALSE, ['example.com'], '<a href="http://example.com" target="_blank" aria-label="Opens in a new tab" class="ct-content-link ct-theme-light">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a>'],
      ['<a href="http://example.com" target="_blank">Link</a>', 'example2.com', TRUE, FALSE, ['example.com'], '<a href="http://example.com" target="_blank" aria-label="Opens in a new tab" class="ct-content-link ct-theme-light">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a>'],
      ['<a href="http://example.com">Link</a>', 'example2.com', FALSE, TRUE, ['example.com'], '<a href="http://example.com" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com/">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com/path">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/path" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/path" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com/path/">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/path/" class="ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path/">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/path/" class="ct-content-link ct-theme-light">Link</a>'],

      // External, CSS classes, domain overrides.
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', TRUE, FALSE, ['example.com'], '<a href="http://example.com" class="someclass ct-content-link ct-theme-light" target="_blank" aria-label="Opens in a new tab">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a>'],
      ['<a href="http://example.com" class="someclass">Link</a>', 'example2.com', FALSE, TRUE, ['example.com'], '<a href="http://example.com" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com/path" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/path" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/path" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com/path/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com/path/" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path/" class="someclass">Link</a>', 'example2.com', FALSE, FALSE, ['example.com'], '<a href="http://example.com:8080/path/" class="someclass ct-content-link ct-theme-light">Link</a>'],
      ['<a href="http://example.com:8080/path/" class="someclass">Link</a>', 'example2.com', TRUE, TRUE, ['example.com'], '<a href="http://example.com:8080/path/" class="someclass ct-content-link ct-theme-light" target="_blank" aria-label="Opens in a new tab">Link<span class="ct-visually-hidden">(Opens in a new tab/window)</span><svg xmlns="http://www.w3.org/2000/svg" class="ct-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z"></path></svg></a>'],

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
        '<p>Word1 <a href="http://example.com" class="ct-content-link ct-theme-light ct-content-link--external">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example.com/path" class="ct-content-link ct-theme-light ct-content-link--external">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example.com" class="someclass ct-content-link ct-theme-light ct-content-link--external">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example.com/path" class="someclass ct-content-link ct-theme-light ct-content-link--external">Link</a> word8</p>' .
        '<p>Word1 <a href="http://example2.com" class="ct-content-link ct-theme-light">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example2.com/path" class="ct-content-link ct-theme-light">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example2.com" class="someclass ct-content-link ct-theme-light">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example2.com/path" class="someclass ct-content-link ct-theme-light">Link</a> word8</p>',
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
        '<p>Word7 <a href="http://example2.com/path" class="someclass">Link</a> word8</p>' .
        '<p>Word9 <a href="mailto:example@example.com" class="someclass">example@example.com</a> word10</p>' .
        '<p>Word11 <a href="mailto:example@example.com" class="someclass">example@example.com</a> word12 example2@example.com word13</p>',
        'example2.com',
        FALSE,
        FALSE,
        ['example.com'],
        '<p>Word1 <a href="http://example.com" class="ct-content-link ct-theme-light">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example.com/path" class="ct-content-link ct-theme-light">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example.com" class="someclass ct-content-link ct-theme-light">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example.com/path" class="someclass ct-content-link ct-theme-light">Link</a> word8</p>' .
        '<p>Word1 <a href="http://example2.com" class="ct-content-link ct-theme-light">Link</a> word2</p>' .
        '<p>Word3 <a href="http://example2.com/path" class="ct-content-link ct-theme-light">Link</a> word4</p>' .
        '<p>Word5 <a href="http://example2.com" class="someclass ct-content-link ct-theme-light">Link</a> word6</p>' .
        '<p>Word7 <a href="http://example2.com/path" class="someclass ct-content-link ct-theme-light">Link</a> word8</p>' .
        '<p>Word9 <a href="mailto:example@example.com" class="someclass ct-content-link ct-theme-light">example@example.com</a> word10</p>' .
        '<p>Word11 <a href="mailto:example@example.com" class="someclass ct-content-link ct-theme-light">example@example.com</a> word12 <a href="mailto:example2@example.com" class="ct-content-link ct-theme-light">example2@example.com</a> word13</p>',
      ],

      // Existing classes.
      ['<a href="http://example.com" class="ct-content-link">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="ct-content-link">Link</a>'],
      ['<a href="http://example.com" class="ct-button someclass">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="ct-button someclass">Link</a>'],
      ['<a href="http://example.com" class="someclass ct-button">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="someclass ct-button">Link</a>'],
      ['<a href="http://example.com" class="someclass ct-content-link">Link</a>', 'example.com', FALSE, FALSE, [], '<a href="http://example.com" class="someclass ct-content-link">Link</a>'],
      ['<a href="mailto:example@example.com" class="someclass ct-content-link">example@example.com</a>', 'example.com', FALSE, FALSE, [], '<a href="mailto:example@example.com" class="someclass ct-content-link">example@example.com</a>'],
    ];
  }

  /**
   * Test for _civictheme_process_html_content_links_get_email_regex().
   *
   * @dataProvider dataProviderEmailRegex
   */
  public function testEmail($string, $match) {
    preg_match(_civictheme_process_html_content_links_get_email_regex(), $string, $matches);

    if ($match) {
      $this->assertEquals(1, count($matches));
      $this->assertEquals($match, $matches[0]);
    }
    else {
      $this->assertEquals(0, count($matches));
    }
  }

  /**
   * Data provider for testEmail().
   *
   * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
   */
  public function dataProviderEmailRegex() {
    return [
      ['a@example.com', 'a@example.com'],
      ['a@example2.com', 'a@example2.com'],
      ['a@e2xample2.com', 'a@e2xample2.com'],
      ['a.a@e2xample2.com', 'a.a@e2xample2.com'],
      ['a.a1@e2xample2.com', 'a.a1@e2xample2.com'],
      ['a@e2xample2.com.au', 'a@e2xample2.com.au'],
      ['a@e2xample2.digital', 'a@e2xample2.digital'],
      ['a+b_c.d+e@e2xample2.digital', 'a+b_c.d+e@e2xample2.digital'],
      ['a+b_c.d+e-f--g@e2xample2.digital', 'a+b_c.d+e-f--g@e2xample2.digital'],

      ['  a+b_c.d+e@e2xample2.digital', 'a+b_c.d+e@e2xample2.digital'],
      ["\ta+b_c.d+e@e2xample2.digital", 'a+b_c.d+e@e2xample2.digital'],
      ["\na+b_c.d+e@e2xample2.digital", 'a+b_c.d+e@e2xample2.digital'],

      ['', FALSE],
      ['a@b.c', FALSE],
      ['a.a@b.c', FALSE],
      ['a.1@b.c', FALSE],
      ['a1@b.c', FALSE],
      ['a1.2@b.c', FALSE],

      ['a@example.com.a', FALSE],
      ['a@example.com.a.b', FALSE],
      ['a@example.com.a.b.c', FALSE],

      ['a@example.com.', 'a@example.com'],
      ['a@example.com,', 'a@example.com'],
      ['a@e2xample2.com.', 'a@e2xample2.com'],
      ['1a@e2xample2.com.', '1a@e2xample2.com'],
      ['1a@3e2xample2.com.', '1a@3e2xample2.com'],

      ['<p>  a+b_c.d+e@e2xample2.digital.</p>', 'a+b_c.d+e@e2xample2.digital'],
    ];
  }

}
