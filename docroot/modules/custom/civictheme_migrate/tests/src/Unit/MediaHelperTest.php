<?php

namespace Drupal\Tests\civictheme_migrate\Unit;

use Drupal\civictheme_migrate\Utils\MediaHelper;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\File;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Site\Settings;
use Drupal\Tests\UnitTestCase;
use Psr\Container\ContainerInterface;

/**
 * Tests the MediaHelper class.
 *
 * @group civictheme_migrate
 */
class MediaHelperTest extends UnitTestCase {

  /**
   * The media helper service.
   *
   * @var \Drupal\civictheme_migrate\Utils\MediaHelper
   */
  protected $mediaHelper;

  /**
   * The context array.
   *
   * @var array
   */
  protected $context;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Mock the settings object.
    $settings = $this->createMock(Settings::class);
    $settings->expects($this->once())
      ->method('get')
      ->with('file_public_path')
      ->willReturn('/public');

    // Create a mock file system object.
    $fileSystem = $this->createMock(FileSystemInterface::class);
    $fileSystem->expects($this->once())
      ->method('realpath')
      ->with('/public')
      ->willReturn('/var/www/html/public');

    // Create a mock entity type manager.
    $entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);

    // Create a mock container interface.
    $container = $this->createMock(ContainerInterface::class);
    $container->expects($this->exactly(2))
      ->method('get')
      ->withConsecutive(
        ['settings'],
        ['file_system']
      )
      ->willReturnOnConsecutiveCalls($settings, $fileSystem);

    // Create a new instance of the media helper.
    $this->mediaHelper = new MediaHelper($entityTypeManager, $container);

    // Set up the context array.
    $this->context = [
      'sandbox' => [
        'migration' => 'test_migration',
      ],
    ];
  }

  /**
   * Tests the lookupMediaIdFromUrl method.
   *
   * @covers ::lookupMediaIdFromUrl
   */
  public function testLookupMediaIdFromUrl(): void {
    // Test case where media entity exists.
    $file_url = 'http://example.com/image.jpg';
    $context = $this->context;

    // Create a mock file object.
    $file = $this->createMock(File::class);
    $file->expects($this->once())
      ->method('id')
      ->willReturn(123);

    // Mock the lookupFileFromUri method.
    $mediaHelper = $this->getMockBuilder(MediaHelper::class)
      ->setMethods(['lookupFileFromUri'])
      ->getMock();
    $mediaHelper->expects($this->once())
      ->method('lookupFileFromUri')
      ->with($file_url)
      ->willReturn($file);

    // Create a mock entity storage for media.
    $entityStorage = $this->createMock(EntityStorageInterface::class);
    $entityStorage->expects($this->once())
      ->method('loadByProperties')
      ->with(['field_media_file' => 123])
      ->willReturn(['media_id']);

    // Create a mock entity type manager.
    $entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);
    $entityTypeManager->expects($this->once())
      ->method('getStorage')
      ->with('media')
      ->willReturn($entityStorage);

    // Set the mocked entity type manager and media helper on the test instance.
    $this->mediaHelper->setEntityTypeManager($entityTypeManager);
    $this->mediaHelper->setMediaHelper($mediaHelper);

    // Call the method under test.
    $result = $this->mediaHelper->lookupMediaIdFromUrl($file_url, $context);

    // Check the expected result.
    $this->assertEquals(['media_id'], $result);
  }

  /**
   * Tests the lookupMediaUuidFromUrl method.
   *
   * @covers ::lookupMediaUuidFromUrl
   */
  public function testLookupMediaUuidFromUrl(): void {
    // Test case where media entity exists.
    $file_url = 'http://example.com/image.jpg';
    $context = $this->context;

    // Create a mock file object.
    $file = $this->createMock(File::class);
    $file->expects($this->once())
      ->method('id')
      ->willReturn(123);

    // Mock the lookupFileFromUri method.
    $mediaHelper = $this->getMockBuilder(MediaHelper::class)
      ->setMethods(['lookupFileFromUri'])
      ->getMock();
    $mediaHelper->expects($this->once())
      ->method('lookupFileFromUri')
      ->with($file_url)
      ->willReturn($file);

    // Create a mock entity storage for media.
    $entityStorage = $this->createMock(EntityStorageInterface::class);
    $entityStorage->expects($this->once())
      ->method('loadByProperties')
      ->with(['field_media_file' => 123])
      ->willReturn(['media_id']);

    // Create a mock entity type manager.
    $entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);
    $entityTypeManager->expects($this->once())
      ->method('getStorage')
      ->with('media')
      ->willReturn($entityStorage);

    // Set the mocked entity type manager and media helper on the test instance.
    $this->mediaHelper->setEntityTypeManager($entityTypeManager);
    $this->mediaHelper->setMediaHelper($mediaHelper);

    // Call the method under test.
    $result = $this->mediaHelper->lookupMediaUuidFromUrl($file_url, $context);

    // Check the expected result.
    $this->assertEquals(['media_id'], $result);
  }

  /**
   * Tests the lookupFileFromUri method.
   *
   * @covers ::lookupFileFromUri
   */
  public function testLookupFileFromUri(): void {
    // Test case where file entity exists.
    $file_url = 'http://example.com/image.jpg';

    // Create a mock file object.
    $file = $this->createMock(File::class);
    $file->expects($this->once())
      ->method('id')
      ->willReturn(123);

    // Mock the file loadByProperties method.
    $entityStorage = $this->createMock(EntityStorageInterface::class);
    $entityStorage->expects($this->once())
      ->method('loadByProperties')
      ->with(['uri' => $file_url])
      ->willReturn([$file]);

    // Create a mock entity type manager.
    $entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);
    $entityTypeManager->expects($this->once())
      ->method('getStorage')
      ->with('file')
      ->willReturn($entityStorage);

    // Set the mocked entity type manager on the test instance.
    $this->mediaHelper->setEntityTypeManager($entityTypeManager);

    // Call the method under test.
    $result = $this->mediaHelper->lookupFileFromUri($file_url);

    // Check the expected result.
    $this->assertEquals($file, $result);
  }

}
