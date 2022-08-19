<?php

/**
 * Class AddPhpcsExclusionsUnitTest.
 *
 * Unit tests for add_phpcs_exclusions.php.
 *
 * @group scripts
 *
 * phpcs:disable Drupal.Commenting.DocComment.MissingShort
 * phpcs:disable Drupal.Commenting.FunctionComment.Missing
 */
class AddPhpcsExclusionsUnitTest extends ScriptUnitTestBase {

  /**
   * {@inheritdoc}
   */
  protected $script = 'docroot/themes/contrib/civictheme/civictheme_starter_kit/scripts/add_phpcs_exclusions.php';

  /**
   * @dataProvider dataProviderMain
   * @runInSeparateProcess
   */
  public function testMain($args, $expected_code, $expected_output) {
    $args = is_array($args) ? $args : [$args];
    $result = $this->runScript($args, TRUE);

    $this->assertEquals($expected_code, $result['code']);
    $this->assertStringContainsString($expected_output, $result['output']);
  }

  public function dataProviderMain() {
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

      // Validation of path existence.
      [
        'some/non_existing/storybook-static',
        1,
        'Directory "/app/some/non_existing/storybook-static" is not readable.',
      ],
    ];
  }

}
