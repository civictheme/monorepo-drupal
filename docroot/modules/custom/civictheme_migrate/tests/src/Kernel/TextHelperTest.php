<?php

namespace Drupal\Tests\civictheme_migrate\Kernel\Utils;

use Drupal\KernelTests\KernelTestBase;
use Drupal\file\Entity\File;
use Drupal\civictheme_migrate\Utils\TextHelper;
use Drupal\civictheme_migrate\Utils\MediaHelper;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * Tests the TextHelper class.
 *
 * @coversDefaultClass \Drupal\civictheme_migrate\Utils\TextHelper
 *
 * @group civictheme_migrate
 * @group site:kernel
 */
class TextHelperTest extends KernelTestBase {

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
  }

  /**
   * Tests the convertEmbeddedImagesWithMedia method.
   *
   * @covers ::convertEmbeddedImagesWithMedia
   * @covers ::getEmbeddedMediaCode
   */
  public function testConvertEmbeddedImagesWithMedia() {
    // Create a test image file.
    $file = $this->createImageFile();

    // Create a test node.
    $node = $this->createNode();

    // Generate the input text with an image and a link.
    $input_text = '<p>Some text with an image: <img src="/sites/default/files/test.jpg" alt="Test Image"></p>';
    $input_text .= '<p>Some text with a link: <a href="/node/' . $node->id() . '">Test Link</a></p>';

    // Test the convertEmbeddedImagesWithMedia method.
    $converted_text = TextHelper::convertEmbeddedImagesWithMedia($input_text);

    // Assert that the image tag is converted to the embedded media code.
    $expected_media_code = MediaHelper::getEmbeddedMediaCode($file->uuid(), 'Test Image', '');
    $this->assertStringContainsString($expected_media_code, $converted_text);

    // Assert that the link tag is not modified.
    $this->assertStringContainsString('<a href="/node/' . $node->id() . '">Test Link</a>', $converted_text);
  }

  /**
   * Tests the convertInternalLinkEntities method.
   *
   * @covers ::convertInternalLinkEntities
   */
  public function testConvertInternalLinkEntities() {
    // Create a test file.
    $file = $this->createFileEntity();

    // Create a test node.
    $node = $this->createNode();

    // Generate the input text with an internal link and a file link.
    $input_text = '<p>Some text with an internal link: <a href="/node/' . $node->id() . '">Test Internal Link</a></p>';
    $input_text .= '<p>Some text with a file link: <a href="/sites/default/files/test.txt">Test File Link</a></p>';

    // Test the convertInternalLinkEntities method.
    $converted_text = TextHelper::convertInternalLinkEntities($input_text);

    // Assert that the internal link is converted to the data attributes.
    $expected_internal_link = '<a href="entity:/node/' . $node->id() . '" data-entity-type="node" data-entity-uuid="' . $node->uuid() . '" data-entity-substitution="canonical">Test Internal Link</a>';
    $this->assertStringContainsString($expected_internal_link, $converted_text);

    // Assert that the file link is converted to the data attributes.
    $expected_file_link = '<a href="' . $file->createFileUrl(TRUE) . '" data-entity-type="file" data-entity-uuid="' . $file->uuid() . '" data-entity-substitution="file">Test File Link</a>';
    $this->assertStringContainsString($expected_file_link, $converted_text);
  }

  /**
   * Tests the convertInlineReferencesToEmbeddedEntities method.
   *
   * @covers ::convertInlineReferencesToEmbeddedEntities
   * @covers ::getEmbeddedMediaCode
   */
  public function testConvertInlineReferencesToEmbeddedEntities() {
    // Create a test image file.
    $file = $this->createImageFile();

    // Create a test node.
    $node = $this->createNode();

    // Generate the input text with an image, a link, and a file link.
    $input_text = '<p>Some text with an image: <img src="/sites/default/files/test.jpg" alt="Test Image"></p>';
    $input_text .= '<p>Some text with a link: <a href="/node/' . $node->id() . '">Test Link</a></p>';
    $input_text .= '<p>Some text with a file link: <a href="/sites/default/files/test.txt">Test File Link</a></p>';

    // Test the convertInlineReferencesToEmbeddedEntities method.
    $converted_text = TextHelper::convertInlineReferencesToEmbeddedEntities($input_text);

    // Assert that the image tag is converted to the embedded media code.
    $expected_media_code = MediaHelper::getEmbeddedMediaCode($file->uuid(), 'Test Image', '');
    $this->assertStringContainsString($expected_media_code, $converted_text);

    // Assert that the internal link is converted to the data attributes.
    $expected_internal_link = '<a href="entity:/node/' . $node->id() . '" data-entity-type="node" data-entity-uuid="' . $node->uuid() . '" data-entity-substitution="canonical">Test Link</a>';
    $this->assertStringContainsString($expected_internal_link, $converted_text);

    // Assert that the file link is converted to the data attributes.
    $expected_file_link = '<a href="' . $file->createFileUrl(TRUE) . '" data-entity-type="file" data-entity-uuid="' . $file->uuid() . '" data-entity-substitution="file">Test File Link</a>';
    $this->assertStringContainsString($expected_file_link, $converted_text);
  }

  /**
   * Creates a test image file.
   *
   * @return \Drupal\file\Entity\File
   *   The created file entity.
   */
  protected function createImageFile() {
    // Create a test file.
    $file = File::create([
      'uri' => PublicStream::basePath() . '/assets/test.jpg',
      'filename' => 'test.jpg',
      'filemime' => 'image/jpeg',
    ]);
    $file->save();

    return $file;
  }

  /**
   * Creates a test node.
   *
   * @return \Drupal\node\Entity\Node
   *   The created node entity.
   */
  protected function createNode() {
    // Create a test node.
    $node = $this->createNode([
      'type' => 'article',
      'title' => 'Test Node',
    ]);
    $node->save();

    return $node;
  }

  /**
   * Creates a test file entity.
   *
   * @return \Drupal\file\Entity\File
   *   The created file entity.
   */
  protected function createFileEntity() {
    // Create a test file.
    $file = File::create([
      'uri' => PublicStream::basePath() . '/assets/test.jpg',
      'filename' => 'test.txt',
      'filemime' => 'text/plain',
    ]);
    $file->save();

    return $file;
  }

}
