<?php

// phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
namespace Drupal\Tests\civictheme_migrate\Kernel;

/**
 * Class MediaValidatorKernelTest.
 *
 * Test cases for validating JSON civictheme_media migration source files.
 *
 * @group civictheme_migrate
 * @group site:kernel
 */
class MediaValidatorKernelTest extends ValidatorKernelTestBase {

  /**
   * Test page JSON fields.
   *
   * @dataProvider dataProviderMedia
   */
  public function testMediaJsonSchema(mixed $data, $expected, $message = ''): void {
    $this->assertJsonIsValid($data, 'civictheme_media_image', $expected, $message);
  }

  /**
   * Data provider for testJsonPageFields().
   */
  public function dataProviderMedia() {
    return [
      [$this->getTestDataStructure(), [], 'Data should be valid but is not.'],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->file);

          return $data;
        }),
        [
          'All array items must match schema',
          'The required properties (file) are missing',
        ],
        'Media item requires file field',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->uuid);

          return $data;
        }),
        [
          'All array items must match schema',
          'The required properties (uuid) are missing',
        ],
        'Media item requires uuid field',
      ],
      [
        $this->getTestDataStructure(function ($data) {
          unset($data[0]->alt);

          return $data;
        }),
        [
          'All array items must match schema',
          'The required properties (alt) are missing',
        ],
        'Media item requires alt field',
      ],
    ];
  }

  /**
   * Helper to generate test data for tests.
   */
  protected function getTestDataStructure($callback = NULL) {
    $thumbnail = (object) [
      'uuid' => 'f352fb5f-5319-4a09-a039-6b7080b31443',
      'name' => 'D10 launch.png',
      'file' => 'https://www.civictheme.io/sites/default/files/images/2022-10/D10%20launch.png',
      'alt' => 'Test alt text for thumbnail',
    ];

    $featured_image = (object) [
      'uuid' => 'f352fb5f-5319-4a09-a039-6b7080b31443',
      'name' => 'D10 launch.png',
      'file' => 'https://www.civictheme.io/sites/default/files/images/2022-10/D10%20launch.png',
      'alt' => 'Test alt text for thumbnail',
    ];

    $background = (object) [
      'uuid' => '427186ad-c561-4441-9951-28399d8a4923',
      'name' => 'demo_banner-background.png',
      'file' => 'https://www.civictheme.io/sites/default/files/demo_banner-background.png',
      'alt' => '',
    ];

    $data = [$thumbnail, $featured_image, $background];

    return $callback === NULL ? $data : $callback($data);
  }

}
