<?php

namespace Drupal\Tests\civictheme_migrate\Kernel;

use Drupal\civictheme_migrate\CivicthemeMigrateValidator;
use Drupal\KernelTests\KernelTestBase;
use Opis\JsonSchema\Validator;

/**
 * Class ValidatorKernelTestBase.
 *
 * Base test class for validating JSON migration source files.
 *
 * @group civictheme_migrate
 */
abstract class ValidatorKernelTestBase extends KernelTestBase {

  /**
   * Validator instance.
   *
   * @var \Drupal\civictheme_migrate\CivicthemeMigrateValidator
   */
  protected $validator;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'civictheme_migrate',
    'migrate',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->validator = new CivicthemeMigrateValidator(new Validator(), $this->container->get('extension.path.resolver'));
  }

  /**
   * Helper to test validator.
   */
  protected function assertJsonIsValid(mixed $data, $scheme_id, $expected, $message = '') {
    $this->assertEquals($expected, $this->validator->validate($data, $scheme_id), $message);
  }

}
