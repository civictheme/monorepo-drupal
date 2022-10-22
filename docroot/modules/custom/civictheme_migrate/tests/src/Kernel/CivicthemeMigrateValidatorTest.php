<?php

// phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
namespace Drupal\Tests\civictheme_migrate\Kernel;

use Drupal\civictheme_migrate\CivicthemeMigrateValidator;
use Drupal\KernelTests\KernelTestBase;
use Opis\JsonSchema\Validator;

/**
 * Class CivicthemeMigrateValidatorTest.
 *
 * Test cases for processing validating JSON migration source files.
 *
 * @group CivicTheme
 */
class CivicthemeMigrateValidatorTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['civictheme_migrate'];

  /**
   * Validator instance.
   *
   * @var \Drupal\civictheme_migrate\CivicthemeMigrateValidator
   */
  protected $validator;

  /**
   * {@inheritdoc}
   */
  protected function setUp():void {
    parent::setUp();
    $this->validator = new CivicthemeMigrateValidator(
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
    $this->assertEquals($expected, $validation_result);
  }

  /**
   * Data provider for testJsonIsValid().
   */
  public function dataProviderJsonIsValid() {
    return [
      [
        'invalid_no_id_key.json',
        'civictheme_page',
        [
          'All array items must match schema',
          'The required properties (id) are missing',
        ],
      ],
      [
        'invalid_no_title_key.json',
        'civictheme_page',
        [
          'All array items must match schema',
          'The required properties (title) are missing',
        ],
      ],
      [
        'invalid_not_array_of_objects.json',
        'civictheme_page',
        [
          'All array items must match schema',
          'The data (integer) must match the type: object',
        ],
      ],
      ['valid_multiple_element.json', 'civictheme_page', []],
      ['valid_single_element.json', 'civictheme_page', []],
    ];
  }

}
