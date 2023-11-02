<?php

use Drupal\Core\Serialization\Yaml;
use PHPUnit\Framework\TestCase;

/**
 * Class CircleCiConfigTest.
 *
 * Tests for CircleCI configurations.
 *
 * @SuppressWarnings(PHPMD)
 *
 * phpcs:disable Drupal.NamingConventions.ValidVariableName.LowerCamelName
 * phpcs:disable Drupal.NamingConventions.ValidGlobal.GlobalUnderScore
 * phpcs:disable Squiz.WhiteSpace.FunctionSpacing.Before
 * phpcs:disable Squiz.WhiteSpace.FunctionSpacing.After
 */
class CircleCiConfigTest extends TestCase {

  /**
   * CircleCI loaded config.
   *
   * @var mixed
   */
  protected $config;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $file = file_get_contents(__DIR__ . '/../../.circleci/config.yml');
    if (!$file) {
      throw new \RuntimeException('Unable to read CircleCI config file.');
    }
    $this->config = Yaml::decode($file);
  }

  /**
   * Tests for deploy branch regex.
   *
   * @see https://semver.org/
   *
   * @dataProvider dataProviderDeployBranchRegex
   */
  public function testDeployBranchRegex(string $branch, bool $expected = TRUE): void {
    $job = array_column($this->config['workflows']['commit']['jobs'], 'deploy')[0];
    $this->assertEquals($expected, preg_match($job['filters']['branches']['only'], $branch));
  }

  /**
   * Data provider for testDeployBranchRegex().
   */
  public function dataProviderDeployBranchRegex(): array {
    return [
      // Positive branches.
      ['main'],
      ['master'],
      ['develop'],

      ['ci'],
      ['cisomething'],

      ['deps/something'],

      ['release/123.456.789'],
      ['release/123.456.789-rc.123'],
      ['hotfix/123.456.789'],
      ['hotfix/123.456.789-rc.123'],

      ['release/2023-04-17'],
      ['release/2023-04-17.1'],
      ['hotfix/2023-04-17'],
      ['hotfix/2023-04-17.1'],

      ['feature/description'],
      ['feature/Description'],
      ['feature/Description-With-Hyphens'],
      ['feature/123-description'],
      ['feature/123-Description'],
      ['feature/1.x'],
      ['feature/0.x'],
      ['feature/0.1.x'],
      ['feature/0.1.2.x'],
      ['feature/1.x-description'],
      ['feature/0.x-description'],
      ['feature/0.1.x-description'],
      ['feature/0.1.2.x-description'],

      // Negative branches.
      ['something', FALSE],
      ['premain', FALSE],
      ['premaster', FALSE],
      ['predevelop', FALSE],
      ['mainpost', FALSE],
      ['masterpost', FALSE],
      ['developpost', FALSE],
      ['premainpost', FALSE],
      ['premasterpost', FALSE],
      ['predeveloppost', FALSE],

      ['preci', FALSE],
      ['precipost', FALSE],

      ['deps', FALSE],
      ['predeps', FALSE],
      ['depspost', FALSE],
      ['predepspost', FALSE],

      ['feature', FALSE],
      ['release', FALSE],
      ['hotfix', FALSE],
      ['prefeature', FALSE],
      ['prerelease', FALSE],
      ['prehotfix', FALSE],
      ['featurepost', FALSE],
      ['releasepost', FALSE],
      ['hotfixpost', FALSE],
      ['prefeaturepost', FALSE],
      ['prereleasepost', FALSE],
      ['prehotfixpost', FALSE],

      ['release/123', FALSE],
      ['release/123.456', FALSE],
      ['hotfix/123', FALSE],
      ['hotfix/123.456', FALSE],

      ['release/202-04-17', FALSE],
      ['release/2023-4-17', FALSE],
      ['release/2023-04-1', FALSE],
      ['release/pre2023-04-17', FALSE],
      ['release/2023-04-17post', FALSE],
      ['release/pre2023-04-17post', FALSE],

      ['hotfix/202-04-17', FALSE],
      ['hotfix/2023-4-17', FALSE],
      ['hotfix/2023-04-1', FALSE],
      ['hotfix/pre2023-04-17', FALSE],
      ['hotfix/2023-04-17post', FALSE],
      ['hotfix/pre2023-04-17post', FALSE],

      ['release/123.456.789-something', FALSE],
      ['release/123.456.789-rc', FALSE],
      ['release/123.456.789-rc123', FALSE],
      ['release/123.456.789-rc-123', FALSE],
      ['release/123.456.789-prerc123', FALSE],
      ['release/123.456.789-rcpost123', FALSE],
      ['release/123.456.789-prercpost123', FALSE],
      ['release/123.456.789-rc123something', FALSE],
      ['release/123.456.789-rc.123something', FALSE],
      ['release/123.456.789-rc.123-something', FALSE],

      ['hotfix/123.456.789-something', FALSE],
      ['hotfix/123.456.789-rc', FALSE],
      ['hotfix/123.456.789-rc123', FALSE],
      ['hotfix/123.456.789-rc-123', FALSE],
      ['hotfix/123.456.789-prerc123', FALSE],
      ['hotfix/123.456.789-rcpost123', FALSE],
      ['hotfix/123.456.789-prercpost123', FALSE],
      ['hotfix/123.456.789-rc123something', FALSE],
      ['hotfix/123.456.789-rc.123something', FALSE],
      ['hotfix/123.456.789-rc.123-something', FALSE],

      ['prefeature/something', FALSE],
      ['prerelease/something', FALSE],
      ['prehotfix/something', FALSE],
      ['featurepost/something', FALSE],
      ['releasepost/something', FALSE],
      ['hotfixpost/something', FALSE],
      ['prefeaturepost/something', FALSE],
      ['prereleasepost/something', FALSE],
      ['prehotfixpost/something', FALSE],
    ];
  }

}
