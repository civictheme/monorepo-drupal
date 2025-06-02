<?php

namespace Drupal\Tests\civictheme\Kernel;

use Drupal\Core\StringTranslation\ByteSizeMarkup;
use Drupal\Tests\media\Kernel\MediaKernelTestBase;

/**
 * Class CivicthemeMediaUtilityUnitTest.
 *
 * Test cases for getting media entity variables.
 *
 * @group CivicTheme
 * @group site:unit
 */
class CivicthemeMediaUtilityKernelTest extends MediaKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    require_once __DIR__ . '/../../../civictheme.theme';
  }

  /**
   * Test civictheme_media_get_variables function.
   */
  public function testCivicThemeMediaGetVariables() {
    $mediaType = $this->createMediaType('file');
    $file_name = 'test.jpg';
    $extension = 'jpg';
    $creation_time = new \DateTime();
    $creation_time->modify('-1 day');
    $updated_time = new \DateTime();
    $updated_time->modify('-1 hour');
    $media_name = 'Test Media';
    $media = $this->generateMedia($file_name, $mediaType);
    $media->setCreatedTime($creation_time->getTimestamp());
    $media->setChangedTime($updated_time->getTimestamp());
    $media->setName($media_name);
    $media->save();
    /** @var \Drupal\file\FileInterface $file */
    $file = civictheme_get_field_referenced_entity($media, 'field_media_file');
    $this->assertNotNull($file);
    // VFS does not support external uri.
    $file_url = NULL;
    $file_size = ByteSizeMarkup::create($file->getSize());

    $variables = civictheme_media_get_variables($media);
    $this->assertArrayHasKey('name', $variables);
    $this->assertArrayHasKey('media_name', $variables);
    $this->assertArrayHasKey('ext', $variables);
    $this->assertArrayHasKey('url', $variables);
    $this->assertArrayHasKey('size', $variables);
    $this->assertArrayHasKey('created', $variables);
    $this->assertArrayHasKey('changed', $variables);
    $this->assertArrayHasKey('icon', $variables);
    $this->assertEquals($file_name, $variables['name']);
    $this->assertEquals($media_name, $variables['media_name']);
    $this->assertEquals($extension, $variables['ext']);
    $this->assertEquals($file_url, $variables['url']);
    $this->assertEquals($file_size, $variables['size']);
    $this->assertEquals('download-file', $variables['icon']);
  }

}
