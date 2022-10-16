<?php

// phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
namespace Drupal\Tests\civictheme_migrate\Kernel;

use Drupal\civictheme_migrate\CivicThemeMigrateValidator;
use Drupal\KernelTests\KernelTestBase;
use Opis\JsonSchema\Validator;

/**
 * Class CivicThemeMigrateValidatorTest.
 *
 * Test cases for processing validating JSON migration source files.
 *
 * @group CivicTheme
 */
class CivicThemeMigrateValidatorTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['civictheme_migrate'];

  /**
   * Validator instance.
   *
   * @var \Drupal\civictheme_migrate\CivicThemeMigrateValidator
   */
  protected $validator;

  /**
   * {@inheritdoc}
   */
  protected function setUp():void {
    parent::setUp();
    $this->validator = new CivicThemeMigrateValidator(
      new Validator(),
      $this->container->get('extension.path.resolver')
    );
  }

  /**
   * Test http headers option.
   *
   * @dataProvider dataProviderJsonIsValid
   */
  public function testJsonSchema(string $file_path, string $schema_id, $expected): void {
    $json = json_decode(file_get_contents(__DIR__ .
      '/../../fixtures/migration_source_json/' . $file_path));
    $validation_result = $this->validator->validate($json, $schema_id);
    $this->assertEquals($expected, $validation_result->isValid());
  }

  /**
   * Data provider for testJsonIsValid().
   */
  public function dataProviderJsonIsValid() {
    return [
      ['invalid_no_id_key.json', 'civictheme_page', FALSE],
      ['invalid_no_title_key.json', 'civictheme_page', FALSE],
      ['invalid_not_array_of_objects.json', 'civictheme_page', FALSE],
      ['valid_multiple_element.json', 'civictheme_page', TRUE],
      ['valid_single_element.json', 'civictheme_page', TRUE],
    ];
  }

}
