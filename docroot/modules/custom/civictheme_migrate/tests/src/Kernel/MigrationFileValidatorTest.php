<?php

// phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
namespace Drupal\Tests\civictheme_migrate\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Class PageValidatorKernelTest.
 *
 * Test cases for validating civictheme_page JSON migration source files.
 *
 * @group civictheme_migrate
 * @group site:kernel
 */
class MigrationFileValidatorTest extends KernelTestBase {

  /**
   * Validator instance.
   *
   * @var \Drupal\civictheme_migrate\Validator\MigrationFileValidator
   */
  protected $migrationFileValidator;

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
    $this->migrationFileValidator = $this->container->get('civictheme_migrate.migration_file_validator');
  }

  /**
   * Test page JSON fields.
   *
   * @dataProvider dataProvideTestValidate
   */
  public function testValidate(string $migration_schema_id, string $filepath, $expected): void {
    $filepath = __DIR__ . '/../../fixtures/' . $filepath;
    $this->assertEquals($expected, $this->migrationFileValidator->validate($migration_schema_id, $filepath));
  }

  /**
   * Data provider for testValidate().
   */
  public function dataProvideTestValidate() {
    return [
      ['node_civictheme_page', 'civictheme_migrate.node_civictheme_page_1.json', []],
      ['node_civictheme_page', 'civictheme_migrate.node_civictheme_page_components.json', []],
      [
        'node_civictheme_page',
        'civictheme_migrate.media_civictheme_image_1.json',
        [
          'The data (object) must match the type: array',
        ],
      ],

      ['media_civictheme_document', 'civictheme_migrate.media_civictheme_document_1.json', []],
      [
        'media_civictheme_document',
        'civictheme_migrate.node_civictheme_page_1.json',
        [
          'The data (array) must match the type: object',
        ],
      ],

      ['media_civictheme_image', 'civictheme_migrate.media_civictheme_image_1.json', []],
      [
        'media_civictheme_image',
        'civictheme_migrate.node_civictheme_page_1.json',
        [
          'The data (array) must match the type: object',
        ],
      ],

      ['menu_link_content_civictheme_primary_navigation', 'civictheme_migrate.menu_link_content_civictheme_primary_navigation.json', []],
      [
        'menu_link_content_civictheme_primary_navigation',
        'civictheme_migrate.node_civictheme_page_1.json',
        [
          'The data (array) must match the type: object',
        ],
      ],

      ['menu_link_content_civictheme_secondary_navigation', 'civictheme_migrate.menu_link_content_civictheme_secondary_navigation.json', []],
      [
        'menu_link_content_civictheme_secondary_navigation',
        'civictheme_migrate.node_civictheme_page_1.json',
        [
          'The data (array) must match the type: object',
        ],
      ],

      ['menu_link_content_civictheme_footer', 'civictheme_migrate.menu_link_content_civictheme_footer.json', []],
      [
        'menu_link_content_civictheme_footer',
        'civictheme_migrate.node_civictheme_page_1.json',
        [
          'The data (array) must match the type: object',
        ],
      ],
    ];
  }

}
