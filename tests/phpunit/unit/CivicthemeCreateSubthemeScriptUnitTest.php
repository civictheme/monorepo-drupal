<?php

/**
 * Class CivicthemeCreateSubthemeScriptUnitTest.
 *
 * Unit tests for civictheme_create_subtheme.php.
 *
 * @group scripts
 *
 * phpcs:disable Drupal.Commenting.DocComment.MissingShort
 * phpcs:disable Drupal.Commenting.FunctionComment.Missing
 */
class CivicthemeCreateSubthemeScriptUnitTest extends ScriptUnitTestBase {

  /**
   * {@inheritdoc}
   */
  protected $script = 'docroot/themes/contrib/civictheme/civictheme_create_subtheme.php';

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
        'CivicTheme Starter Kit scaffolding',
      ],
      [
        '-help',
        0,
        'CivicTheme Starter Kit scaffolding',
      ],
      [
        '-h',
        0,
        'CivicTheme Starter Kit scaffolding',
      ],
      [
        '-?',
        0,
        'CivicTheme Starter Kit scaffolding',
      ],
      [
        [],
        1,
        'CivicTheme Starter Kit scaffolding',
      ],
      [
        [1, 2],
        1,
        'CivicTheme Starter Kit scaffolding',
      ],
      [
        [1, 2, 3, 4],
        1,
        'CivicTheme Starter Kit scaffolding',
      ],
    ];
  }

}
