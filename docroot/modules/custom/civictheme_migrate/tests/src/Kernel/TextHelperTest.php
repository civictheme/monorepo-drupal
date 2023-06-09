<?php

namespace Drupal\Tests\civictheme_migrate\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal\civictheme_migrate\Utils\TextHelper;
use Drupal\civictheme_migrate\Utils\NodeHelper;
use Drupal\node\Entity\NodeType;
use Drupal\Core\Url;
use Drupal\Tests\TestFileCreationTrait;
use Drupal\media\Entity\Media;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;

/**
 * Tests the TextHelper class.
 *
 * @group civictheme_migrate
 * @group site:kernel
 */
class TextHelperTest extends KernelTestBase {
  use MediaTypeCreationTrait;
  use TestFileCreationTrait;

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
    'path',
    'path_alias',
  ];

  /**
   * The created file entity.
   *
   * @var \Drupal\file\FileInterface|null
   */
  protected $file;

  /**
   * The created node entity.
   *
   * @var \Drupal\node\NodeInterface|null
   */
  protected $node;

  /**
   * The created media entity.
   *
   * @var \Drupal\media\MediaInterface|null
   */
  protected $media;

  /**
   * The test media type.
   *
   * @var \Drupal\media\MediaTypeInterface
   */
  protected $testMediaType;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
    $this->installEntitySchema('file');
    $this->installEntitySchema('path_alias');
    $this->installEntitySchema('media');
    $this->installSchema('system', ['sequences']);
    $this->installSchema('user', ['users_data']);
    $this->installSchema('node', ['node_access']);
    $this->installSchema('file', 'file_usage');
    $this->installConfig(['file', 'image', 'media', 'node', 'system', 'field']);

    // Create a sample image file.
    $this->file = File::create([
      'uri' => $this->getTestFiles('image')[0]->uri,
    ]);
    $this->file->save();

    // Create the custom node type.
    $type = NodeType::create([
      'type' => 'node_type1',
      'name' => 'Custom Type 1',
      'description' => 'A custom node type.',
      'display_submitted' => FALSE,
    ]);
    $type->save();

    // Create a test media type.
    $this->testMediaType = $this->createMediaType('file');

    $this->media = Media::create([
      'bundle' => $this->testMediaType->id(),
      'field_media_file' => [
        'target_id' => $this->file->id(),
      ],
    ]);
    $this->media->save();

    // Create a sample node.
    $this->node = Node::create([
      'type' => 'node_type1',
      'title' => 'Sample Node',
    ]);
    $this->node->save();
  }

  /**
   * Tests the convertInlineReferencesToEmbeddedEntities method.
   */
  public function testConvertInlineReferencesToEmbeddedEntities() {
    // Mock the Url class and provide the necessary route parameters.
    $routeParameters = ['node' => (int) $this->node->id()];
    $url = $this->createMock(Url::class);
    $url->method('getRouteParameters')
      ->willReturn($routeParameters);

    // Set the test Url instance in NodeHelper.
    NodeHelper::setTestUrlInstance($url);

    $text = '<p>This is a sample <img src="' . $this->getTestFiles('image')[0]->uri . '" alt="Sample Image"> text with an <a href="/node/' . $this->node->id() . '">internal link</a>.</p>';

    // Call the method being tested.
    $result = TextHelper::convertInlineReferencesToEmbeddedEntities($text);

    // Assert the expected output.
    $expected = '<p>This is a sample <drupal-media data-entity-embed-display="view_mode:media.embedded" data-entity-type="media" data-entity-uuid="' . $this->media->uuid() . '" data-langcode="en" alt="Sample Image"></drupal-media> text with an <a href="entity:/node/' . $this->node->id() . '" data-entity-type="node" data-entity-uuid="' . $this->node->uuid() . '" data-entity-substitution="canonical">internal link</a>.</p>';
    $this->assertEquals($expected, $result);

    $text = 'This is a <a href="https://example.com">sample link</a> and an <img src="https://example.com/image.jpg" alt="External Image"> to an external resource.';
    $result = TextHelper::convertInlineReferencesToEmbeddedEntities($text);
    $expected = 'This is a <a href="https://example.com">sample link</a> and an <img src="https://example.com/image.jpg" alt="External Image" /> to an external resource.';
    $this->assertEquals($expected, $result);
  }

  /**
   * {@inheritdoc}
   */
  protected function tearDown(): void {
    // Clean up any created entities.
    $node = Node::load($this->node->id());
    $node->delete();
    $media = Media::load($this->media->id());
    $media->delete();

    parent::tearDown();
  }

}
