<?php

/**
 * Class AddPhpcsExclusionsUnitTest.
 *
 * Unit tests for add_phpcs_exclusions.php.
 *
 * @group site:unit
 * @group scripts
 *
 * phpcs:disable Drupal.Commenting.DocComment.MissingShort
 * phpcs:disable Drupal.Commenting.FunctionComment.Missing
 */
class AddPhpcsExclusionsUnitTest extends ScriptUnitTestBase {

  /**
   * {@inheritdoc}
   */
  protected $script = 'web/themes/contrib/civictheme/civictheme_starter_kit/scripts/add_phpcs_exclusions.php';

  /**
   * @dataProvider dataProviderMain
   * @runInSeparateProcess
   */
  public function testMain(string|array $args, int $expected_code, string $expected_output): void {
    $args = is_array($args) ? $args : [$args];
    $result = $this->runScript($args, TRUE);

    $this->assertEquals($expected_code, $result['code']);
    $this->assertStringContainsString($expected_output, $result['output']);
  }

  public function dataProviderMain(): array {
    return [
      [
        '--help',
        0,
        'Add PHPCS exclusions',
      ],
      [
        '-help',
        0,
        'Add PHPCS exclusions',
      ],
      [
        '-h',
        0,
        'Add PHPCS exclusions',
      ],
      [
        '-?',
        0,
        'Add PHPCS exclusions',
      ],
      [
        [],
        1,
        'Add PHPCS exclusions',
      ],
      [
        [1, 2, 3, 4, 5],
        1,
        'Add PHPCS exclusions',
      ],

      [
        'path/to/storybook-static',
        1,
        'Started adding of PHPCS exclusions to files in directories path/to/storybook-static.',
      ],
      [
        'path/to/storybook-static,path/to/dist',
        1,
        'Started adding of PHPCS exclusions to files in directories path/to/storybook-static,path/to/dist.',
      ],
    ];
  }

}
