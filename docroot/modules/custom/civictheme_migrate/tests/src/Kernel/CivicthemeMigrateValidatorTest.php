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
  protected function setUp(): void {
    parent::setUp();
    $this->validator = new CivicthemeMigrateValidator(new Validator(), $this->container->get('extension.path.resolver'));
  }

  /**
   * Test page JSON fields.
   *
   * @dataProvider dataProviderJsonPageFields
   */
  public function testJsonPageFields(mixed $data, $expected, $message = NULL): void {
    $this->testJsonValidity($data, $expected, $message);
  }

  /**
   * Test banner JSON fields.
   *
   * @dataProvider dataProviderBannerFields
   */
  public function testJsonBannerFields(mixed $data, $expected, $message = NULL) {
    $this->testJsonValidity($data, $expected, $message);
  }

  /**
   * Helper to test validator.
   */
  protected function testJsonValidity(mixed $data, $expected, $message = NULL) {
    $validation_result = $this->validator->validate($data, 'civictheme_page');
    if ($message !== NULL) {
      $this->assertEquals($expected, $validation_result, $message);
      return;
    }
    $this->assertEquals($expected, $validation_result);
  }

  /**
   * Data provider for testJsonPageFields().
   */
  public function dataProviderJsonPageFields() {
    return [
      [
        $this->getTestDataStructure(function ($data) {
          return [$data[0], $data[0], $data[0]];
        }),
        [],
      ],
      [$this->getTestDataStructure(), []],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->id);
          return $data;
        }),
        [
          'All array items must match schema',
          'The required properties (id) are missing',
        ],
        'ID should be a required field but is not',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->title);
          return $data;
        }),
        [
          'All array items must match schema',
          'The required properties (title) are missing',
        ],
        'Title should be a required field but is not',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          return $data[0];
        }),
        [
          'The data (object) must match the type: array',
        ],
        'Data structure should be an array of objects but is not',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->alias);
          return $data;
        }),
        [
          'All array items must match schema',
          'The required properties (alias) are missing',
        ],
        'Alias should be a required field but is not',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->show_toc);
          return $data;
        }),
        [
          'All array items must match schema',
          'The required properties (show_toc) are missing',
        ],
        'Show_toc should be a required field but is not',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->hide_sidebar);
          return $data;
        }),
        [
          'All array items must match schema',
          'The required properties (hide_sidebar) are missing',
        ],
        'Hide_sidebar should be a required field but is not',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->show_last_updated_date);
          return $data;
        }),
        [
          'All array items must match schema',
          'The required properties (show_last_updated_date) are missing',
        ],
        'Show_last_updated_date should be a required field but is not',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          $data[0]->alias = 'test/test123';
          return $data;
        }),
        [
          'All array items must match schema',
          'The properties must match schema: alias',
          'The string should match pattern: ^\/[\S]+$',
        ],
        'Alias is required to start with a leading backslash but is not',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          $data[0]->vertical_spacing = 'not_valid';
          return $data;
        }),
        [
          'All array items must match schema',
          'The properties must match schema: vertical_spacing',
          'The string should match pattern: top|bottom|both',
        ],
        'Vertical spacing should only allow top, bottom and both but does not.',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->thumbnail[0]->file);
          unset($data[0]->thumbnail[0]->uuid);
          return $data;
        }),
        [
          'All array items must match schema',
          'The properties must match schema: thumbnail',
          'At least one array item must match schema',
          'The required properties (file) are missing',
        ],
        'Thumbnail requires file and uuid field',
      ],
    ];
  }

  /**
   * Data provider for testJsonBannerFields().
   */
  public function dataProviderBannerFields() {
    return [
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->banner->children[0]->featured_image[0]->file);
          unset($data[0]->banner->children[0]->featured_image[0]->uuid);
          return $data;
        }),
        [
          'All array items must match schema',
          'The properties must match schema: banner',
          'The properties must match schema: children',
          'At least one array item must match schema',
          'The properties must match schema: featured_image',
          'At least one array item must match schema',
          'The required properties (file) are missing',
        ],
        'Thumbnail requires file field',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->banner->children[0]->featured_image[0]->uuid);
          return $data;
        }),
        [
          'All array items must match schema',
          'The properties must match schema: banner',
          'The properties must match schema: children',
          'At least one array item must match schema',
          'The properties must match schema: featured_image',
          'At least one array item must match schema',
          'The required properties (uuid) are missing',
        ],
        'Thumbnail requires uuid field',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->banner->children[0]->background[0]->file);
          return $data;
        }),
        [
          'All array items must match schema',
          'The properties must match schema: banner',
          'The properties must match schema: children',
          'At least one array item must match schema',
          'The properties must match schema: background',
          'At least one array item must match schema',
          'The required properties (file) are missing',
        ],
        'Background requires file field',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->banner->children[0]->background[0]->uuid);
          return $data;
        }),
        [
          'All array items must match schema',
          'The properties must match schema: banner',
          'The properties must match schema: children',
          'At least one array item must match schema',
          'The properties must match schema: background',
          'At least one array item must match schema',
          'The required properties (uuid) are missing',
        ],
        'Background requires uuid field',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          $data[0]->banner->children[0]->blend_mode = 'invalid_value';
          return $data;
        }),
        [
          'All array items must match schema',
          'The properties must match schema: banner',
          'The properties must match schema: children',
          'At least one array item must match schema',
          'The properties must match schema: blend_mode',
          'The string should match pattern: color|color-burn|darken|difference|exclusion|hard-light|hue|lighten|luminosity|multiply|normal|overlay|saturation|screen|soft-light',
        ],
        'Banner blend mode requires specific string values',
      ],
    ];
  }

  /**
   * Helper to generate test data for tests.
   */
  protected function getTestDataStructure($callback = NULL) {
    $thumbnail = (object) [
      "uuid" => "f352fb5f-5319-4a09-a039-6b7080b31443",
      "name" => "D10 launch.png",
      "file" => "https://www.civictheme.io/sites/default/files/images/2022-10/D10%20launch.png",
      "alt" => "Test alt text for thumbnail",
    ];

    $featured_image = (object) [
      "uuid" => "f352fb5f-5319-4a09-a039-6b7080b31443",
      "name" => "D10 launch.png",
      "file" => "https://www.civictheme.io/sites/default/files/images/2022-10/D10%20launch.png",
      "alt" => "Test alt text for thumbnail",
    ];

    $background = (object) [
      "uuid" => "427186ad-c561-4441-9951-28399d8a4923",
      "name" => "demo_banner-background.png",
      "file" => "https://www.civictheme.io/sites/default/files/demo_banner-background.png",
      "alt" => "",
    ];

    $banner_children = (object) [
      "theme" => "dark",
      "title" => "[TEST] Banner title - Migrated Content 1",
      "banner_type" => "large",
      "blend_mode" => "darken",
      "featured_image" => [
        $featured_image,
      ],
      "background" => [
        $background,
      ],
      "hide_breadcrumb" => TRUE,
    ];

    $banner = (object) [
      "type" => "container",
      "children" => [
        $banner_children,
      ],
    ];

    $page = (object) [
      "id" => "4593761e-8a5d-4564-8c0e-2126fb4f3338",
      "title" => "[TEST] Migrated Content 1",
      "alias" => "/test/migrated-content-1",
      "summary" => "Summary for [TEST] Migrated Content 1",
      "topics" => "[TEST] Topic 1,[TEST] Topic 2,[TEST] Topic 3,[TEST] Topic 4",
      "thumbnail" => [
        $thumbnail,
      ],
      "vertical_spacing" => "top",
      "hide_sidebar" => TRUE,
      "show_last_updated_date" => TRUE,
      "last_updated_date" => "8 Oct 2022",
      "show_toc" => TRUE,
      "banner" => $banner,
    ];
    $data = [
      $page,
    ];

    return $callback === NULL ? $data : $callback($data);
  }

}
