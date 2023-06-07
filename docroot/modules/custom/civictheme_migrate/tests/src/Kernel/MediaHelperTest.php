<?php

namespace Drupal\Tests\civictheme_migrate\Kernel\Utils;

use Drupal\KernelTests\KernelTestBase;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\civictheme_migrate\Utils\MediaHelper;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * Tests the MediaHelper class.
 *
 * @coversDefaultClass \Drupal\civictheme_migrate\Utils\MediaHelper
 *
 * @group civictheme_migrate
 * @group site:kernel
 */
class MediaHelperTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'civictheme_migrate',
    'file',
    'media',
    'node',
    'migrate',
    'user',
    'system',
    'image',
    'field',
    'text',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
    $this->installEntitySchema('file');
    $this->installSchema('system', ['sequences']);
    $this->installSchema('user', ['users_data']);
    $this->installSchema('node', ['node_access']);
    $this->installSchema('file', 'file_usage');
    $this->installConfig(['file', 'image', 'media', 'node', 'system', 'field']);

    // Create a test file entity.
    $file = File::create([
      'uri' => 'public://example.jpg',
    ]);
    $file->save();
  }

  /**
   * Tests the lookupMediaIdFromUrl method.
   *
   * @covers ::lookupMediaIdFromUrl
   */
  public function testLookupMediaIdFromUrl() {
    $fileUrl = 'https://example.com/example.jpg';
    $context = [];

    $mediaId = MediaHelper::lookupMediaIdFromUrl($fileUrl, $context);

    $this->assertNotNull($mediaId);
  }

  /**
   * Tests the lookupMediaUuidFromUrl method.
   *
   * @covers ::lookupMediaUuidFromUrl
   */
  public function testLookupMediaUuidFromUrl() {
    $fileUrl = 'https://example.com/example.jpg';
    $context = [];

    $mediaUuid = MediaHelper::lookupMediaUuidFromUrl($fileUrl, $context);

    $this->assertNotNull($mediaUuid);
  }

  /**
   * Tests the getEmbeddedMediaCode method.
   *
   * @covers ::getEmbeddedMediaCode
   */
  public function testGetEmbeddedMediaCode() {
    $uuid = '12345678-1234-5678-1234-567812345678';
    $alt = 'Example Image';
    $title = 'Example Image Title';

    $embeddedCode = MediaHelper::getEmbeddedMediaCode($uuid, $alt, $title);

    $this->assertStringContainsString('<drupal-media', $embeddedCode);
    $this->assertStringContainsString('data-entity-uuid="' . $uuid . '"', $embeddedCode);
    $this->assertStringContainsString('alt="' . $alt . '"', $embeddedCode);
    $this->assertStringContainsString('title="' . $title . '"', $embeddedCode);
  }

  /**
   * Tests the downloadMediaFromUrl method.
   *
   * @covers ::downloadMediaFromUrl
   */
  public function testDownloadMediaFromUrl() {
    $fileUrl = 'https://example.com/example.jpg';
    $context = [];

    $media = MediaHelper::downloadMediaFromUrl($fileUrl, $context);

    $this->assertInstanceOf(Media::class, $media);
  }

  /**
   * Tests the lookupFileFromUri method.
   *
   * @covers ::lookupFileFromUri
   */
  public function testLookupFileFromUri() {
    $uri = PublicStream::basePath() . '/assets/test.jpg';

    $file = MediaHelper::lookupFileFromUri($uri);

    $this->assertInstanceOf(File::class, $file);
  }

  /**
   * Tests the downloadRemoteFile method.
   *
   * @covers ::downloadRemoteFile
   */
  public function testDownloadRemoteFile() {
    $source = 'https://example.com/example.jpg';
    $destination = PublicStream::basePath() . '/assets/test.jpg';
    $context = [];

    $file = MediaHelper::downloadRemoteFile($source, $destination, $context);

    $this->assertInstanceOf(File::class, $file);
  }

  /**
   * Tests the createMediaFromFile method.
   *
   * @covers ::createMediaFromFile
   */
  public function testCreateMediaFromFile() {
    // Create a test file entity.
    $file = File::create([
      'uri' => PublicStream::basePath() . '/assets/test.jpg',
    ]);
    $file->save();

    $context = [];

    $media = MediaHelper::createMediaFromFile($file, $context);

    $this->assertInstanceOf(Media::class, $media);
  }

}
