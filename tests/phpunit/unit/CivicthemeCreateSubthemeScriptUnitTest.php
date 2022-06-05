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
   * CivicTheme dir.
   *
   * @var string
   */
  protected $civicthemeDir = 'docroot/themes/contrib/civictheme';

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
        [1, 2, 3, 4, 5, 6],
        1,
        'CivicTheme Starter Kit scaffolding',
      ],
    ];
  }

  /**
   * @runInSeparateProcess
   */
  public function testDefaultLocation() {
    $newtheme_name = 'new_theme';
    $civictheme_dir = 'themes/contrib/civictheme';
    $newtheme_path = '/../../custom/' . $newtheme_name;

    $sut_dir = $this->prepareSut($civictheme_dir);

    $result = $this->runScript([$newtheme_name, $newtheme_name, $newtheme_name, $newtheme_path], TRUE);
    $this->assertEquals(0, $result['code']);
    $this->assertStringContainsString('sub-theme was created successfully ', $result['output']);
    $this->assertStringContainsString('sub-theme was created successfully ', $result['output']);

    $newtheme_path_full = $sut_dir . $newtheme_path . '/';
    $this->assertDirectoryExists($newtheme_path_full);
    $this->assertDirectoryExists($newtheme_path_full . '.storybook');
    $this->assertDirectoryExists($newtheme_path_full . 'assets');
    $this->assertDirectoryExists($newtheme_path_full . 'components');
    $this->assertDirectoryExists($newtheme_path_full . 'templates');
    $this->assertDirectoryExists($newtheme_path_full . 'webpack');
    $this->assertFileExists($newtheme_path_full . '.eslintignore');
    $this->assertFileExists($newtheme_path_full . '.eslintrc.yml');
    $this->assertFileExists($newtheme_path_full . '.gitignore');
    $this->assertFileExists($newtheme_path_full . '.nvmrc');
    $this->assertFileExists($newtheme_path_full . '.stylelintrc.json');
    $this->assertFileExists($newtheme_path_full . $newtheme_name . '.info.yml');
    $this->assertFileExists($newtheme_path_full . $newtheme_name . '.libraries.yml');
    $this->assertFileExists($newtheme_path_full . $newtheme_name . '.theme');
    $this->assertFileExists($newtheme_path_full . 'gulpfile.js');
    $this->assertFileExists($newtheme_path_full . 'package.json');
    $this->assertFileExists($newtheme_path_full . 'package-lock.json');
    $this->assertFileExists($newtheme_path_full . 'README.md');
    $this->assertFileExists($newtheme_path_full . 'screenshot.png');
  }

  /**
   * Prepare SUT codebase.
   *
   * @param string $path
   *   Optional path for SUT codebase location.
   *
   * @return string
   *   Path to created SUT codebase.
   */
  protected function prepareSut($path = NULL) {
    $sut_dir = $this->tmpDir . (!empty($path) ? '/' . $path : '');
    mkdir($sut_dir, 0755, TRUE);

    $this->copyr($this->civicthemeDir, $sut_dir, [
      'node_modules',
      'vendor',
      'storybook-static',
      'dist',
      'assets',
    ]);

    $this->script = $sut_dir . '/civictheme_create_subtheme.php';

    return $sut_dir;
  }

  /**
   * @dataProvider dataProviderFileGetRelativeDir
   * @runInSeparateProcess
   */
  public function testFileGetRelativeDir($from, $to, $expected) {
    $this->assertEquals($expected, file_get_relative_dir($from, $to));
  }

  public function dataProviderFileGetRelativeDir() {
    return [
      ['/a/b/c/d', '/a/b/c/d', './'],
      ['/a/b/c/d', '/a/b/c', '../'],
      ['/a/b/c/d', '/a/b', '../../'],
      ['/a/b/c/d', '/a/b/c/other', '../other/'],
      ['/a/b/c/d', '/a/b/other2', '../../other2/'],

      ['/a/b/c/d', '/a/b/c/d/', './'],
      ['/a/b/c/d', '/a/b/c/', '../'],
      ['/a/b/c/d', '/a/b/', '../../'],
      ['/a/b/c/d', '/a/b/c/other/', '../other/'],
      ['/a/b/c/d', '/a/b/other2/', '../../other2/'],

      ['/a/b/c/d/', '/a/b/c/d/', './'],
      ['/a/b/c/d/', '/a/b/c/', '../'],
      ['/a/b/c/d/', '/a/b/', '../../'],
      ['/a/b/c/d/', '/a/b/c/other/', '../other/'],
      ['/a/b/c/d/', '/a/b/other2/', '../../other2/'],
    ];
  }

  /**
   * @dataProvider dataProviderFilePathCanonicalize
   * @runInSeparateProcess
   */
  public function testFilePathCanonicalize($path, $expected) {
    $this->assertEquals($expected, file_path_canonicalize($path));
  }

  public function dataProviderFilePathCanonicalize() {
    return [
      ['', ''],
      ['a', 'a'],

      // Absolute.
      ['/a', '/a'],
      ['/a/b', '/a/b'],

      ['/a/b/.', '/a/b/'],
      ['/a/b/..', '/a/'],
      ['/a/b/c/..', '/a/b/'],
      ['/a/b/c/../d/', '/a/b/d/'],
      ['/a/b/c/../../d/', '/a/d/'],
      ['/a/b/c/../.././d/', '/a/d/'],
      ['/a/b/c/./../.././d/', '/a/d/'],
      ['/a/b/c/././././.././././.././d/', '/a/d/'],
      ['/a/././././b/c/././././.././././.././d/', '/a/d/'],

      ['/a/././././', '/a/'],
      ['/a/./', '/a/'],
      ['/a/./../', '/'],
      ['/a/./../.', '/'],
      ['/a/./.././', '/'],

      ['/a/./../../', '/'],
      ['/a/./../../.', '/'],
      ['/a/./../.././../', '/'],
      ['/a/./../.././../.', '/'],

      // Relative.
      ['a', 'a'],
      ['a/b', 'a/b'],

      ['a/b/.', 'a/b/'],
      ['a/b/..', 'a/'],
      ['a/b/c/..', 'a/b/'],
      ['a/b/c/../d/', 'a/b/d/'],
      ['a/b/c/../../d/', 'a/d/'],
      ['a/b/c/../.././d/', 'a/d/'],
      ['a/b/c/./../.././d/', 'a/d/'],
      ['a/b/c/././././.././././.././d/', 'a/d/'],
      ['a/././././b/c/././././.././././.././d/', 'a/d/'],

      ['a/././././', 'a/'],
      ['a/./', 'a/'],
      ['a/./../', '/'],
      ['a/./../.', '/'],
      ['a/./.././', '/'],

      ['a/./../../', '/'],
    ];
  }
}
