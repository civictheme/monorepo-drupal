<?php

// phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
namespace Drupal\Tests\civictheme_migrate\Kernel;

/**
 * Class PageValidatorKernelTest.
 *
 * Test cases for validating civictheme_page JSON migration source files.
 *
 * @group civictheme_migrate
 */
class PageValidatorKernelTest extends ValidatorKernelTestBase {

  /**
   * Test page JSON fields.
   *
   * @dataProvider dataProviderJsonPageFields
   */
  public function testJsonPageFields(mixed $data, $expected, $message = ''): void {
    $this->assertJsonIsValid($data, 'civictheme_page', $expected, $message);
  }

  /**
   * Data provider for testJsonPageFields().
   */
  public function dataProviderJsonPageFields() {
    return [
      [
        $this->getDataStructure(function ($data) {
          return [$data[0], $data[0], $data[0]];
        }),
        [],
      ],
      [$this->getDataStructure(), []],
      [
        $this->getDataStructure(function ($data) {
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
        $this->getDataStructure(function ($data) {
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
        $this->getDataStructure(function ($data) {
          return $data[0];
        }),
        [
          'The data (object) must match the type: array',
        ],
        'Data structure should be an array of objects but is not',
      ],
      [
        $this->getDataStructure(function ($data) {
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
        $this->getDataStructure(function ($data) {
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
        $this->getDataStructure(function ($data) {
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
        $this->getDataStructure(function ($data) {
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
        $this->getDataStructure(function ($data) {
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
        $this->getDataStructure(function ($data) {
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
        $this->getDataStructure(function ($data) {
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
   * Get expected data structure.
   */
  protected function getDataStructure($callback = NULL) {
    $data = [
      (object) [
        'id' => '4593761e-8a5d-4564-8c0e-2126fb4f3338',
        'title' => '[TEST] Migrated Content 1',
        'alias' => '/test/migrated-content-1',
        'summary' => 'Summary for [TEST] Migrated Content 1',
        'topics' => '[TEST] Topic 1,[TEST] Topic 2,[TEST] Topic 3,[TEST] Topic 4',
        'thumbnail' => [
          'f352fb5f-5319-4a09-a039-6b7080b31443',
        ],
        'vertical_spacing' => 'top',
        'hide_sidebar' => TRUE,
        'show_last_updated_date' => TRUE,
        'last_updated_date' => '8 Oct 2022',
        'show_toc' => TRUE,
        'banner' => (object) [
          'type' => 'container',
          'children' => [
            (object) [
              'theme' => 'dark',
              'title' => '[TEST] Banner title - Migrated Content 1',
              'banner_type' => 'large',
              'blend_mode' => 'darken',
              'featured_image' => [
                'f352fb5f-5319-4a09-a039-6b7080b31443',
              ],
              'background' => [
                '427186ad-c561-4441-9951-28399d8a4923',
              ],
              'hide_breadcrumb' => TRUE,
            ],
          ],
        ],
      ],
    ];

    return $callback === NULL ? $data : $callback($data);
  }

}
