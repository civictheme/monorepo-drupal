<?php

namespace Drupal\Tests\civictheme_migrate\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\civictheme_migrate\Utils\MediaHelper;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\media\MediaInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Tests the MediaHelper class.
 *
 * @group civictheme_migrate
 * @group site:unit
 */
class MediaHelperTest extends UnitTestCase {

  /**
   * The migration context.
   *
   * @var array
   */
  protected $context;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->context = [];
  }

  /**
   * Tests the getEmbeddedMediaCode method.
   *
   * @covers ::getEmbeddedMediaCode
   */
  public function testGetEmbeddedMediaCode() {
    // Test with a valid media UUID, alt text, and title.
    $uuid = '12345678-1234-1234-1234-1234567890ab';
    $alt = 'Sample Alt Text';
    $title = 'Sample Title';

    $expectedCode = '<drupal-media data-entity-embed-display="view_mode:media.embedded" data-entity-type="media" data-entity-uuid="' . $uuid . '" data-langcode="en" alt="' . $alt . '" title="' . $title . '"></drupal-media>';

    $actualCode = MediaHelper::getEmbeddedMediaCode($uuid, $alt, $title);

    $this->assertEquals($expectedCode, $actualCode);

    // Test with a valid media UUID and empty alt text and title.
    $uuid = '98765432-4321-4321-4321-0987654321ba';
    $expectedCode = '<drupal-media data-entity-embed-display="view_mode:media.embedded" data-entity-type="media" data-entity-uuid="' . $uuid . '" data-langcode="en"></drupal-media>';

    $actualCode = MediaHelper::getEmbeddedMediaCode($uuid);

    $this->assertEquals($expectedCode, $actualCode);
  }

  /**
   * Tests the lookupMediaFromUrl method.
   *
   * @covers ::lookupMediaFromUrl
   */
  public function testLookupMediaFromUrl(): void {
    // Test case where media entity exists.
    $file_url = 'http://example.com/image.jpg';
    $context = $this->context;

    // Create a mock file object.
    $file = $this->createMock(File::class);
    $file->expects($this->once())
      ->method('id')
      ->willReturn(123);

    // Create a mock media object.
    $media = $this->createMock(Media::class);
    $media->expects($this->once())
      ->method('uuid')
      ->willReturn('abc');

    // Mock the lookupFileFromUri method.
    $mediaHelper = $this->getMockBuilder(MediaHelper::class)
      ->setMethods(['lookupFileFromUri'])
      ->getMock();
    $mediaHelper->expects($this->once())
      ->method('lookupFileFromUri')
      ->with($file_url)
      ->willReturn($file);

    // Create a mock entity storage.
    $entityStorage = $this->createMock(EntityStorageInterface::class);
    $entityStorage->expects($this->once())
      ->method('load')
      ->with(123)
      ->willReturn($media);

    // Mock the entityTypeManager.
    $entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);
    $entityTypeManager->expects($this->once())
      ->method('getStorage')
      ->with('media')
      ->willReturn($entityStorage);

    // Mock the Drupal service container.
    $container = $this->getMockBuilder(ContainerInterface::class)
      ->getMock();
    $container->expects($this->exactly(2))
      ->method('get')
      ->withConsecutive(
      ['stream_wrapper_manager'],
      ['entity_type.manager']
    )
      ->willReturnOnConsecutiveCalls(
      $this->createMock(FileSystemInterface::class),
      $entityTypeManager
    );
    \Drupal::setContainer($container);

    // Test the lookupMediaFromUrl method.
    $result = MediaHelper::lookupMediaFromUrl($file_url, $context);
    $this->assertInstanceOf(MediaInterface::class, $result);
    $this->assertSame($media, $result);
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

    // Create a mock media object.
    $media = $this->createMock(Media::class);
    $media->expects($this->once())
      ->method('id')
      ->willReturn(456);

    // Mock the lookupFileFromUri method.
    $mediaHelper = $this->getMockBuilder(MediaHelper::class)
      ->setMethods(['lookupFileFromUri'])
      ->getMock();
    $mediaHelper->expects($this->once())
      ->method('lookupFileFromUri')
      ->with($file_url)
      ->willReturn($file);

    // Create a mock entity storage.
    $entityStorage = $this->createMock(EntityStorageInterface::class);
    $entityStorage->expects($this->once())
      ->method('load')
      ->with(123)
      ->willReturn($media);

    // Mock the entityTypeManager.
    $entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);
    $entityTypeManager->expects($this->once())
      ->method('getStorage')
      ->with('media')
      ->willReturn($entityStorage);

    // Mock the Drupal service container.
    $container = $this->getMockBuilder(ContainerInterface::class)
      ->getMock();
    $container->expects($this->exactly(2))
      ->method('get')
      ->withConsecutive(
      ['stream_wrapper_manager'],
      ['entity_type.manager']
    )
      ->willReturnOnConsecutiveCalls(
      $this->createMock(FileSystemInterface::class),
      $entityTypeManager
    );
    \Drupal::setContainer($container);

    // Test the lookupMediaIdFromUrl method.
    $result = MediaHelper::lookupMediaIdFromUrl($file_url, $context);
    $this->assertEquals(456, $result);
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

    // Create a mock media object.
    $media = $this->createMock(Media::class);
    $media->expects($this->once())
      ->method('uuid')
      ->willReturn('12345678-1234-5678-1234-567812345678');

    // Mock the lookupFileFromUri method.
    $mediaHelper = $this->getMockBuilder(MediaHelper::class)
      ->setMethods(['lookupFileFromUri'])
      ->getMock();
    $mediaHelper->expects($this->once())
      ->method('lookupFileFromUri')
      ->with($file_url)
      ->willReturn($file);

    // Create a mock entity storage.
    $entityStorage = $this->createMock(EntityStorageInterface::class);
    $entityStorage->expects($this->once())
      ->method('load')
      ->with(123)
      ->willReturn($media);

    // Mock the entityTypeManager.
    $entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);
    $entityTypeManager->expects($this->once())
      ->method('getStorage')
      ->with('media')
      ->willReturn($entityStorage);

    // Mock the Drupal service container.
    $container = $this->getMockBuilder(ContainerInterface::class)
      ->getMock();
    $container->expects($this->exactly(2))
      ->method('get')
      ->withConsecutive(
      ['stream_wrapper_manager'],
      ['entity_type.manager']
    )
      ->willReturnOnConsecutiveCalls(
      $this->createMock(FileSystemInterface::class),
      $entityTypeManager
    );
    \Drupal::setContainer($container);

    // Test the lookupMediaUuidFromUrl method.
    $result = MediaHelper::lookupMediaUuidFromUrl($file_url, $context);
    $this->assertEquals('12345678-1234-5678-1234-567812345678', $result);
  }

}
