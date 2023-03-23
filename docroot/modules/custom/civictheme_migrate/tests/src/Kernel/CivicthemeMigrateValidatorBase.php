<?php

namespace Drupal\Tests\civictheme_migrate\Kernel;

use Drupal\civictheme_migrate\CivicthemeMigrateValidator;
use Drupal\KernelTests\KernelTestBase;
use Opis\JsonSchema\Validator;

/**
 * Class CivicthemeMigrateValidatorBase.
 *
 * Base test class for processing validating JSON migration source files.
 *
 * @group CivicTheme
 */
class CivicthemeMigrateValidatorBase extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'civictheme_migrate',
    'migrate',
  ];

  /**
   * Validator instance.
   *
   * @var \Drupal\civictheme_migrate\CivicthemeMigrateValidator
   */
  protected $validator;

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
  protected function testJsonValidity(mixed $data, $scheme_id, $expected, $message = NULL) {
    $validation_result = $this->validator->validate($data, $scheme_id);
    if ($message !== NULL) {
      $this->assertEquals($expected, $validation_result, $message);

      return;
    }
    $this->assertEquals($expected, $validation_result);
  }

}
