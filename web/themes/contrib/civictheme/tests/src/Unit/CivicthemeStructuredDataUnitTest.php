<?php

declare(strict_types=1);

namespace Drupal\Tests\civictheme\Unit;

/**
 * Class CivicthemeStructuredDataUnitTest.
 *
 * Test cases for the structured data helper functions.
 *
 * @group CivicTheme
 * @group site:unit
 */
class CivicthemeStructuredDataUnitTest extends CivicthemeUnitTestBase {

  /**
   * Test for _civictheme_structured_data_truncate().
   *
   * @dataProvider dataProviderTruncate
   */
  public function testTruncate(string $text, int $length, string $expected): void {
    $this->assertEquals($expected, _civictheme_structured_data_truncate($text, $length));
  }

  /**
   * Data provider for testTruncate().
   */
  public static function dataProviderTruncate(): array {
    return [
      // Empty.
      ['', 10, ''],
      // Whitespace is trimmed.
      ['  Summary  ', 10, 'Summary'],
      // Shorter than the limit.
      ['Summary', 10, 'Summary'],
      // Exactly at the limit.
      ['Summarybox', 10, 'Summarybox'],
      // Truncated on a word boundary with an ellipsis.
      ['One two three four five', 12, 'One two…'],
      // Truncation disabled.
      ['One two three four five', 0, 'One two three four five'],
      ['One two three four five', -1, 'One two three four five'],
      // Multibyte text is measured in characters, not bytes.
      ['Ærøskøbing Ærøskøbing', 12, 'Ærøskøbing…'],
    ];
  }

  /**
   * Test that the truncated value never exceeds the requested length.
   */
  public function testTruncateLength(): void {
    $text = str_repeat('Lorem ipsum dolor sit amet ', 20);

    foreach ([1, 5, 20, 110, 300] as $length) {
      $actual = _civictheme_structured_data_truncate($text, $length);
      $this->assertLessThanOrEqual($length, mb_strlen($actual), sprintf('Truncated to %s characters.', $length));
    }
  }

  /**
   * Test for _civictheme_structured_data_modified_value().
   *
   * @dataProvider dataProviderModifiedValue
   */
  public function testModifiedValue(?string $last_updated, int $changed, int $published, int|string $expected): void {
    $this->assertSame($expected, _civictheme_structured_data_modified_value($last_updated, $changed, $published));
  }

  /**
   * Data provider for testModifiedValue().
   */
  public static function dataProviderModifiedValue(): array {
    $published = (int) strtotime('2026-06-25T10:00:00+00:00');

    return [
      // Without the "last updated" value - the changed date is used.
      [NULL, $published + 3600, $published, $published + 3600],
      // The changed date is clamped to the published date.
      [NULL, $published - 3600, $published, $published],
      // An empty "last updated" value is ignored.
      ['', $published + 3600, $published, $published + 3600],
      ['   ', $published + 3600, $published, $published + 3600],
      // A date-only value after the published date is used as-is.
      ['2026-06-26', $published, $published, '2026-06-26'],
      // A date-only value on the day of publishing resolves to midnight, which
      // precedes the published date, so the published date is used instead.
      ['2026-06-25', $published, $published, $published],
      // A date-only value before the published date is clamped.
      ['2026-06-24', $published, $published, $published],
      // An invalid value is clamped.
      ['not a date', $published, $published, $published],
    ];
  }

  /**
   * Test for _civictheme_structured_data_type_definitions().
   */
  public function testTypeDefinitions(): void {
    $definitions = _civictheme_structured_data_type_definitions();

    $this->assertNotEmpty($definitions);

    foreach ($definitions as $type => $definition) {
      $this->assertNotEmpty($definition['label'], sprintf('Type %s has a label.', $type));
      $this->assertNotEmpty($definition['types'], sprintf('Type %s has Schema.org types.', $type));
      $this->assertNotEmpty($definition['fragment'], sprintf('Type %s has a fragment.', $type));
      $this->assertIsCallable($definition['builder'], sprintf('Type %s has a callable builder.', $type));
      $this->assertContains($type, $definition['types'], sprintf('Type %s is the primary Schema.org type.', $type));
    }

    // Single and multiple type mappings.
    $this->assertEquals(['WebPage'], $definitions['WebPage']['types']);
    $this->assertEquals(['NewsArticle', 'Article'], $definitions['NewsArticle']['types']);
    $this->assertEquals(['Report', 'Article'], $definitions['Report']['types']);

    // Bundles of the same family share the '@id' fragment and the builder.
    $this->assertEquals('article', $definitions['Report']['fragment']);
    $this->assertEquals($definitions['Article']['builder'], $definitions['Report']['builder']);
    $this->assertEquals('event', $definitions['Event']['fragment']);
  }

}
