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
        [1, 2, 3, 4, 5, 6, 7],
        1,
        'CivicTheme Starter Kit scaffolding',
      ],
    ];
  }

  /**
   * @dataProvider dataProviderTestLocation
   * @runInSeparateProcess
   */
  public function testLocation($civictheme_dir, $newtheme_rel_dir, $expected_newtheme_dir, $expected_rel_path) {
    $newtheme_name = 'new_theme';

    $this->prepareSut($civictheme_dir);

    $expected_new_theme_dir_full = $this->tmpDir . '/' . $expected_newtheme_dir;

    $result = $this->runScript([
      $newtheme_name,
      $newtheme_name,
      $newtheme_name,
      $newtheme_rel_dir,
    ], TRUE);
    $this->assertEquals(0, $result['code']);
    $this->assertStringContainsString('sub-theme was created successfully ', $result['output']);
    $this->assertStringContainsString($expected_new_theme_dir_full, $result['output']);

    $expected_new_theme_dir_full .= '/';
    $this->assertDirectoryExists($expected_new_theme_dir_full);

    $this->assertDirectoryExists($expected_new_theme_dir_full . '.storybook');
    $this->assertDirectoryExists($expected_new_theme_dir_full . 'assets');
    $this->assertDirectoryExists($expected_new_theme_dir_full . 'components');
    $this->assertDirectoryExists($expected_new_theme_dir_full . 'templates');
    $this->assertDirectoryExists($expected_new_theme_dir_full . 'webpack');
    $this->assertFileExists($expected_new_theme_dir_full . '.eslintignore');
    $this->assertFileExists($expected_new_theme_dir_full . '.eslintrc.yml');
    $this->assertFileExists($expected_new_theme_dir_full . '.gitignore');
    $this->assertFileExists($expected_new_theme_dir_full . '.nvmrc');
    $this->assertFileExists($expected_new_theme_dir_full . '.stylelintrc.json');
    $this->assertFileExists($expected_new_theme_dir_full . $newtheme_name . '.info.yml');
    $this->assertFileExists($expected_new_theme_dir_full . $newtheme_name . '.libraries.yml');
    $this->assertFileExists($expected_new_theme_dir_full . $newtheme_name . '.theme');
    $this->assertFileExists($expected_new_theme_dir_full . 'gulpfile.js');
    $this->assertFileExists($expected_new_theme_dir_full . 'package.json');
    $this->assertFileExists($expected_new_theme_dir_full . 'README.md');
    $this->assertFileExists($expected_new_theme_dir_full . 'screenshot.png');

    $this->assertStringContainsString($expected_rel_path, file_get_contents($expected_new_theme_dir_full . 'gulpfile.js'));

    // Examples assertions.
    $this->assertDirectoryExists($expected_new_theme_dir_full . 'components/01-atoms/demo-button');
    $this->assertDirectoryExists($expected_new_theme_dir_full . 'components/02-molecules/navigation-card');
    $this->assertDirectoryExists($expected_new_theme_dir_full . 'components/03-organisms/header');
  }

  public function dataProviderTestLocation() {
    return [
      // CivicTheme in 'contrib', new theme in 'custom' dir.
      // No new_theme_directory specified - use default one.
      [
        'docroot/themes/contrib/civictheme',
        '',
        'docroot/themes/custom/new_theme',
        '../../contrib/civictheme/',
      ],
      // CivicTheme in 'contrib', new theme in 'custom' dir. Same as default.
      [
        'docroot/themes/contrib/civictheme',
        '../../custom/new_theme',
        'docroot/themes/custom/new_theme',
        '../../contrib/civictheme/',
      ],
      // CivicTheme in 'contrib', new theme not in 'custom' dir.
      [
        'docroot/themes/contrib/civictheme',
        '../../new_theme',
        'docroot/themes/new_theme',
        '../contrib/civictheme/',
      ],
      // CivicTheme not in 'contrib', new theme not in 'custom' dir.
      [
        'docroot/themes/civictheme',
        '../new_theme',
        'docroot/themes/new_theme',
        '../civictheme/',
      ],
      // CivicTheme in root, new theme in root dir.
      [
        'civictheme',
        '../new_theme',
        'new_theme',
        '../civictheme/',
      ],
      // CivicTheme in root, new theme in 'custom' dir.
      [
        'civictheme',
        '../custom/new_theme',
        'custom/new_theme',
        '../../civictheme/',
      ],
    ];
  }

  /**
   * @runInSeparateProcess
   */
  public function testExamplesRemoval() {
    $civictheme_dir = 'docroot/themes/contrib/civictheme';
    $newtheme_rel_dir = '';
    $newtheme_name = 'new_theme';
    $expected_newtheme_dir = 'docroot/themes/custom/new_theme';
    $expected_rel_path = '../../contrib/civictheme/';

    $this->prepareSut($civictheme_dir);
    $expected_new_theme_dir_full = $this->tmpDir . '/' . $expected_newtheme_dir;

    $result = $this->runScript([
      $newtheme_name,
      $newtheme_name,
      $newtheme_name,
      $newtheme_rel_dir,
      '--remove-examples',
    ], TRUE);
    $this->assertEquals(0, $result['code']);
    $this->assertStringContainsString('sub-theme was created successfully ', $result['output']);
    $this->assertStringContainsString($expected_new_theme_dir_full, $result['output']);

    $expected_new_theme_dir_full .= '/';
    $this->assertDirectoryExists($expected_new_theme_dir_full);

    // Standard assertions.
    $this->assertDirectoryExists($expected_new_theme_dir_full . '.storybook');
    $this->assertDirectoryExists($expected_new_theme_dir_full . 'assets');
    $this->assertDirectoryExists($expected_new_theme_dir_full . 'components');
    $this->assertDirectoryExists($expected_new_theme_dir_full . 'templates');
    $this->assertDirectoryExists($expected_new_theme_dir_full . 'webpack');
    $this->assertFileExists($expected_new_theme_dir_full . '.eslintignore');
    $this->assertFileExists($expected_new_theme_dir_full . '.eslintrc.yml');
    $this->assertFileExists($expected_new_theme_dir_full . '.gitignore');
    $this->assertFileExists($expected_new_theme_dir_full . '.nvmrc');
    $this->assertFileExists($expected_new_theme_dir_full . '.stylelintrc.json');
    $this->assertFileExists($expected_new_theme_dir_full . $newtheme_name . '.info.yml');
    $this->assertFileExists($expected_new_theme_dir_full . $newtheme_name . '.libraries.yml');
    $this->assertFileExists($expected_new_theme_dir_full . $newtheme_name . '.theme');
    $this->assertFileExists($expected_new_theme_dir_full . 'gulpfile.js');
    $this->assertFileExists($expected_new_theme_dir_full . 'package.json');
    $this->assertFileExists($expected_new_theme_dir_full . 'README.md');
    $this->assertFileExists($expected_new_theme_dir_full . 'screenshot.png');

    $this->assertStringContainsString($expected_rel_path, file_get_contents($expected_new_theme_dir_full . 'gulpfile.js'));

    // Examples assertions.
    $this->assertDirectoryDoesNotExist($expected_new_theme_dir_full . 'components/01-atoms/demo-button');
    $this->assertDirectoryDoesNotExist($expected_new_theme_dir_full . 'components/02-molecules/navigation-card');
    $this->assertDirectoryDoesNotExist($expected_new_theme_dir_full . 'components/03-organisms/header');
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

    $this->fileCopyRecursively(getcwd() . '/' . $this->civicthemeDir, $sut_dir, [
      'node_modules',
      'vendor',
      'storybook-static',
      'dist',
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
