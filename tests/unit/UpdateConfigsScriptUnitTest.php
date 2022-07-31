<?php

/**
 * Class UpdateConfigsScriptUnitTest.
 *
 * Unit tests for update_configs.php.
 *
 * @group scripts
 *
 * phpcs:disable Drupal.Commenting.DocComment.MissingShort
 * phpcs:disable Drupal.Commenting.FunctionComment.Missing
 */
class UpdateConfigsScriptUnitTest extends ScriptUnitTestBase {

  /**
   * {@inheritdoc}
   */
  protected $script = 'docroot/themes/contrib/civictheme/civictheme_starter_kit/scripts/update_config.php';

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
        'Site configuration updater',
      ],
      [
        '-help',
        0,
        'Site configuration updater',
      ],
      [
        '-h',
        0,
        'Site configuration updater',
      ],
      [
        '-?',
        0,
        'Site configuration updater',
      ],
      [
        [],
        1,
        'Site configuration updater',
      ],
      [
        [1, 2, 3, 4, 5],
        1,
        'Site configuration updater',
      ],

      // Validation of path existence.
      [
        'some/non_existing/theme/config/dir',
        1,
        'Source configuration directory some/non_existing/theme/config/dir is not readable.',
      ],
      [
        [
          $this->tempdir(),
          'some/non_existing/site/config/dir',
        ],
        1,
        'Destination configuration directory some/non_existing/site/config/dir is not readable.',
      ],
      [
        [
          $this->tempdir(),
          $this->tempdir(),
          'some/non_existing/site_config_file.txt',
        ],
        1,
        'Configuration exclusion file some/non_existing/site_config_file.txt is not readable.',
      ],
    ];
  }

  /**
   * @dataProvider dataProviderCollectConfigs
   * @runInSeparateProcess
   */
  public function testCollectConfigs($file_structure, $expected) {
    $this->createTmpFilesFromFixtures($file_structure);
    $expected = $this->arrayReplaceValue($expected, function ($value) {
      return $this->toTmpPath($value);
    });
    $actual = collect_configs($this->tmpDir);
    $this->assertEquals($expected, $actual);
  }

  public function dataProviderCollectConfigs() {
    return [
      [
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
      ],

      [
        [
          'install/unchanged1.yml' => 'unchanged1.yml',
          'install/unchanged2.yml' => 'unchanged2.yml',
        ],
        [
          'install' => [
            'unchanged1.yml' => 'install/unchanged1.yml',
            'unchanged2.yml' => 'install/unchanged2.yml',
          ],
        ],
      ],
      [
        [
          'optional/unchanged1.yml' => 'unchanged1.yml',
          'optional/unchanged2.yml' => 'unchanged2.yml',
        ],
        [
          'optional' => [
            'unchanged1.yml' => 'optional/unchanged1.yml',
            'unchanged2.yml' => 'optional/unchanged2.yml',
          ],
        ],
      ],
      [
        [
          'install/unchanged1.yml' => 'unchanged1.yml',
          'install/unchanged2.yml' => 'unchanged2.yml',
          'optional/unchanged3.yml' => 'unchanged1.yml',
          'optional/unchanged4.yml' => 'unchanged2.yml',
        ],
        [
          'install' => [
            'unchanged1.yml' => 'install/unchanged1.yml',
            'unchanged2.yml' => 'install/unchanged2.yml',
          ],
          'optional' => [
            'unchanged3.yml' => 'optional/unchanged3.yml',
            'unchanged4.yml' => 'optional/unchanged4.yml',
          ],
        ],
      ],
    ];
  }

  /**
   * @dataProvider dataProviderCollectExcludedConfigs
   * @runInSeparateProcess
   */
  public function testCollectExcludedConfigs($custom_configs_content_lines, $configs, $expected) {
    $custom_configs_file = $this->tempdir() . DIRECTORY_SEPARATOR . 'site_custom_config.txt';
    file_put_contents($custom_configs_file, implode(PHP_EOL, $custom_configs_content_lines));
    $actual = collect_excluded_configs($custom_configs_file, $configs);
    $this->assertEquals($expected, $actual);
  }

  public function dataProviderCollectExcludedConfigs() {
    return [
      // No lines.
      [
        [],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [],
      ],

      // Comments.
      [
        [
          '# Comment 1',
          '# Comment 2',
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [],
      ],

      [
        [
          'pattern1.yml',
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'pattern1.yml' => 'pattern1.yml',
        ],
        [
          'pattern1.yml' => 'pattern1.yml',
        ],
      ],

      [
        [
          'pattern1.yml',
          'pattern2.yml',
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'pattern1.yml' => 'pattern1.yml',
          'pattern2.yml' => 'pattern2.yml',
        ],
        [
          'pattern1.yml' => 'pattern1.yml',
          'pattern2.yml' => 'pattern2.yml',
        ],
      ],

      // Wildcard.
      [
        [
          'pattern1.yml',
          'pattern2.yml',
          'pattern3*',
          'pattern4*',
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'pattern1.yml' => 'pattern1.yml',
          'pattern2.yml' => 'pattern2.yml',
          'pattern3.sub1.yml' => 'pattern3.sub1.yml',
          'pattern3.sub2.yml' => 'pattern3.sub2.yml',
        ],
        [
          'pattern1.yml' => 'pattern1.yml',
          'pattern2.yml' => 'pattern2.yml',
          'pattern3.sub1.yml' => 'pattern3.sub1.yml',
          'pattern3.sub2.yml' => 'pattern3.sub2.yml',
        ],
      ],

      // Full example.
      [
        [
          '# Comment 1.',
          'pattern1',
          'pattern2.yml',
          '# Comment 2.',
          'pattern3*',
          'pattern4*',
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'pattern1.yml' => 'pattern1.yml',
          'pattern2.yml' => 'pattern2.yml',
          'pattern3.sub1.yml' => 'pattern3.sub1.yml',
          'pattern3.sub2.yml' => 'pattern3.sub2.yml',
        ],
        [
          'pattern1.yml' => 'pattern1.yml',
          'pattern2.yml' => 'pattern2.yml',
          'pattern3.sub1.yml' => 'pattern3.sub1.yml',
          'pattern3.sub2.yml' => 'pattern3.sub2.yml',
        ],
      ],
    ];
  }

  /**
   * @dataProvider dataProviderYamlFilesAreIdentical
   * @runInSeparateProcess
   */
  public function testYamlFilesAreIdentical($file1, $file2, $expected) {
    $this->assertEquals($expected, yaml_files_are_identical($this->fixtureFile($file1), $this->fixtureFile($file2)));
  }

  public function dataProviderYamlFilesAreIdentical() {
    return [
      // Simple pass and fail.
      ['unchanged1.yml', 'unchanged1.yml', TRUE],
      ['unchanged1.yml', 'unchanged2.yml', FALSE],
      // UUID and Core removal.
      ['unchanged1.yml', 'unchanged1_no_uuid.yml', TRUE],
      ['unchanged1.yml', 'unchanged1_no_core.yml', TRUE],
      ['unchanged1.yml', 'unchanged1_no_uuid_core.yml', TRUE],
      // Alphabetical sort.
      ['unchanged1.yml', 'unchanged1_alphabetical.yml', TRUE],
    ];
  }

  /**
   * @dataProvider dataProviderCalcConfigDiffs
   * @runInSeparateProcess
   */
  public function testCalcConfigDiffs(array $src, array $dst, array $excluded, array $expected) {
    $src = $this->replaceFixturePaths($src, 'src');
    $dst = $this->replaceFixturePaths($dst, 'dst');
    $expected = $this->replaceFixturePaths($expected, 'src');
    $actual = calc_config_diffs($src, $dst, $excluded);
    $this->assertEquals($expected, $actual);
  }

  public function dataProviderCalcConfigDiffs() {
    return [
      [[], [], [], []],
      // No present configs.
      [
        ['unchanged1.yml' => 'unchanged1.yml'],
        [],
        [],
        ['unchanged1.yml' => 'unchanged1.yml'],
      ],
      [
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [],
        [],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
      ],
      [
        ['install' => ['unchanged1.yml' => 'unchanged1.yml']],
        [],
        [],
        ['unchanged1.yml' => 'unchanged1.yml'],
      ],
      [
        [
          'install' => [
            'unchanged1.yml' => 'unchanged1.yml',
            'unchanged2.yml' => 'unchanged2.yml',
          ],
        ],
        [],
        [],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
      ],

      // Identical configs.
      [
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [],
        [
          'unchanged1.yml' => TRUE,
          'unchanged2.yml' => TRUE,
        ],
      ],

      [
        [
          'install' => [
            'unchanged1.yml' => 'unchanged1.yml',
            'unchanged2.yml' => 'unchanged2.yml',
          ],
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [],
        [
          'unchanged1.yml' => TRUE,
          'unchanged2.yml' => TRUE,
        ],
      ],
      [
        [
          'install' => [
            'unchanged1.yml' => 'unchanged1.yml',
            'unchanged2.yml' => 'unchanged2.yml',
          ],
        ],
        [
          'unchanged1.yml' => 'unchanged1_alphabetical.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [],
        [
          'unchanged1.yml' => TRUE,
          'unchanged2.yml' => TRUE,
        ],
      ],
      [
        [
          'install' => [
            'unchanged1.yml' => 'unchanged1_no_uuid_core.yml',
            'unchanged2.yml' => 'unchanged2_no_uuid_core.yml',
          ],
        ],
        [
          'unchanged1.yml' => 'unchanged1_alphabetical.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [],
        [
          'unchanged1.yml' => TRUE,
          'unchanged2.yml' => TRUE,
        ],
      ],
      [
        [
          'unchanged1.yml' => 'unchanged1_no_uuid_core.yml',
          'unchanged2.yml' => 'unchanged2_no_uuid_core.yml',
        ],
        [
          'unchanged1.yml' => 'unchanged1_alphabetical.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [],
        [
          'unchanged1.yml' => TRUE,
          'unchanged2.yml' => TRUE,
        ],
      ],

      [
        [
          'install' => [
            'unchanged1.yml' => 'unchanged1_no_uuid_core.yml',
            'unchanged2.yml' => 'unchanged2_no_uuid_core.yml',
          ],
        ],
        [
          'unchanged1.yml' => 'unchanged1_alphabetical.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [],
        [
          'unchanged1.yml' => TRUE,
          'unchanged2.yml' => TRUE,
        ],
      ],

      // Different.
      [
        [
          'unchanged1.yml' => 'unchanged1_no_uuid_core.yml',
          'unchanged2.yml' => 'unchanged1_no_uuid_core.yml',
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [],
        [
          'unchanged1.yml' => TRUE,
          'unchanged2.yml' => 'unchanged1_no_uuid_core.yml',
        ],
      ],
      [
        [
          'install' => [
            'unchanged1.yml' => 'unchanged1_no_uuid_core.yml',
            'unchanged2.yml' => 'unchanged1_no_uuid_core.yml',
          ],
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [],
        [
          'unchanged1.yml' => TRUE,
          'unchanged2.yml' => 'unchanged1_no_uuid_core.yml',
        ],
      ],

      // Excluded, identical, excluded identical file.
      [
        [
          'install' => [
            'unchanged1.yml' => 'unchanged1_no_uuid_core.yml',
            'unchanged2.yml' => 'unchanged2_no_uuid_core.yml',
          ],
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [
          'unchanged1.yml' => TRUE,
          'unchanged2.yml' => TRUE,
        ],
      ],

      // Excluded, different, excluded identical file.
      [
        [
          'install' => [
            'unchanged1.yml' => 'unchanged1_no_uuid_core.yml',
            'unchanged2.yml' => 'unchanged2_no_uuid_core.yml',
          ],
        ],
        [
          'unchanged1.yml' => 'unchanged2.yml',
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [
          'unchanged1.yml' => 'unchanged1_no_uuid_core.yml',
        ],
        [
          'unchanged1.yml' => TRUE,
          'unchanged2.yml' => TRUE,
        ],
      ],

      // Excluded, different, excluded different file.
      [
        [
          'install' => [
            'unchanged1.yml' => 'unchanged1_no_uuid_core.yml',
            'unchanged2.yml' => 'unchanged2_no_uuid_core.yml',
          ],
        ],
        [
          'unchanged1.yml' => 'unchanged1.yml',
          'unchanged2.yml' => 'unchanged1.yml',
        ],
        [
          'unchanged2.yml' => 'unchanged2.yml',
        ],
        [
          'unchanged1.yml' => TRUE,
          'unchanged2.yml' => TRUE,
        ],
      ],
    ];
  }

}
