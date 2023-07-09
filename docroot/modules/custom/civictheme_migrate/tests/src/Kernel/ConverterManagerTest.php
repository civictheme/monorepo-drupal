<?php

namespace Drupal\Tests\civictheme_migrate\Unit;

use Drupal\civictheme_migrate\Converter\ConverterManager;
use Drupal\civictheme_migrate\LookupManager;
use Drupal\civictheme_migrate_test\Converter\Test1EmbedConverter;
use Drupal\civictheme_migrate_test\Converter\Test2EmbedConverter;
use Drupal\file\Entity\File;
use Drupal\KernelTests\KernelTestBase;
use Drupal\media\Entity\Media;
use Drupal\media\MediaInterface;
use Drupal\media\MediaTypeInterface;
use Drupal\node\Entity\NodeType;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;
use Drupal\Tests\node\Traits\NodeCreationTrait;
use org\bovigo\vfs\vfsStream;

/**
 * Class ConverterManagerTest.
 *
 * Tests the converter manager functionality.
 *
 * @coversDefaultClass \Drupal\civictheme_migrate\Converter\ConverterManager
 * @group civictheme_migrate
 */
class ConverterManagerTest extends KernelTestBase {

  use MediaTypeCreationTrait;
  use NodeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'civictheme_migrate',
    'civictheme_migrate_test',
    'field',
    'file',
    'image',
    'media',
    'migrate',
    'node',
    'path_alias',
    'system',
    'user',
  ];

  /**
   * The converter manager.
   *
   * @var \Drupal\civictheme_migrate\Converter\ConverterManager
   */
  protected $converterManager;

  /**
   * The lookup manager.
   *
   * @var \Drupal\civictheme_migrate\LookupManager
   */
  protected $lookupManager;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installSchema('system', 'sequences');
    $this->installSchema('node', 'node_access');
    $this->installSchema('file', ['file_usage']);
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');
    $this->installEntitySchema('file');
    $this->installEntitySchema('media');
    $this->installConfig(['field', 'system', 'image', 'file', 'media']);

    $this->lookupManager = $this->getMockBuilder(LookupManager::class)->disableOriginalConstructor()->getMock();
    $this->converterManager = new ConverterManager($this->lookupManager);
  }

  /**
   * Tests the converters() method.
   *
   * @covers ::converters
   */
  public function testConverters(): void {
    $loc_real = $this->getModulePath('civictheme_migrate') . '/src/Converter';
    $loc_test = $this->getModulePath('civictheme_migrate_test') . '/src/Converter';

    $this->converterManager->setAutodiscoveryLocations([]);
    $this->assertEquals(0, count($this->callProtectedMethod($this->converterManager, 'converters')), 'There should be no converts autodiscovered if no locations provided.');

    $this->converterManager->setAutodiscoveryLocations([$loc_real]);
    $this->assertEquals(2, count($this->callProtectedMethod($this->converterManager, 'converters')), 'There should be 2 converters discovered in the real locations.');

    $this->converterManager->setAutodiscoveryLocations([$loc_real, $loc_test]);
    $this->assertEquals(5, count($this->callProtectedMethod($this->converterManager, 'converters')), 'Convertes can be autodiscovered from the multiple locations.');

    $converters = $this->callProtectedMethod($this->converterManager, 'converters');
    $names = array_map(function ($converter) {
      return $converter::name();
    }, $converters);

    $this->assertEquals([
      'media_embed',
      'test1',
      'test2',
      'test3',
      'link_embed',
    ], $names, 'Converters sorting works.');

    $this->converterManager->setExcludedConverters(['test2', 'test3']);
    $converters = $this->callProtectedMethod($this->converterManager, 'converters');
    $names = array_values(array_map(function ($converter) {
      return $converter::name();
    }, $converters));

    $this->assertEquals([
      'media_embed',
      'test1',
      'link_embed',
    ], $names, 'Converters filtering works.');
  }

  /**
   * Tests the addConverter() method.
   *
   * @covers ::addConverter
   */
  public function testConverterAdd(): void {
    $this->converterManager->setAutodiscoveryLocations([]);
    $converter1 = new Test1EmbedConverter($this->lookupManager);
    $converter1_copy = new Test1EmbedConverter($this->lookupManager);
    $this->converterManager->addConverter($converter1)->addConverter($converter1_copy);
    $this->assertEquals(1, count($this->callProtectedMethod($this->converterManager, 'converters')), 'Adding the same converter does not duplicate it.');

    $converter2 = new Test2EmbedConverter($this->lookupManager);
    $this->converterManager->addConverter($converter2);
    $this->assertEquals(2, count($this->callProtectedMethod($this->converterManager, 'converters')), 'Adding new converter works.');
  }

  /**
   * Test for convert().
   *
   * @covers ::convert
   * @dataProvider dataProviderConvert
   */
  public function testConvert($string, $expected): void {
    NodeType::create(['type' => 'page'])->save();
    $node = $this->createNode([
      'nid' => 1001,
      'uuid' => '58f47065-27fb-429b-9ccd-000000001001',
      'type' => 'page',
    ]);

    $media_type = $this->createMediaType('file', ['id' => 'document']);
    $media_document = $this->createMedia($media_type, 'src-file-1.txt', [
      'mid' => 2001,
      'id' => 2001,
      'uuid' => '58f47065-27fb-429b-9ccd-000000002001',
    ]);
    $media_type = $this->createMediaType('image', ['id' => 'image']);
    $media_image = $this->createMedia($media_type, 'src-image-1.png', [
      'mid' => 2002,
      'id' => 2002,
      'uuid' => '58f47065-27fb-429b-9ccd-000000002002',
    ]);

    $this->lookupManager->expects($this->any())
      ->method('lookupNodeByAlias')
      ->will($this->returnCallback(function ($alias) use ($node) {
        return $alias === '/src-page-1' ? $node : NULL;
      }));
    $this->lookupManager->expects($this->any())
      ->method('lookupMediaByFileName')
      ->will($this->returnCallback(function ($uri) use ($media_document, $media_image) {
        return $uri === '/src-file-1.txt' ? $media_document : ($uri === '/src-image-1.png' ? $media_image : NULL);
      }));

    $this->assertEquals($expected, $this->converterManager->convert($string));
  }

  /**
   * Data provider for testConvert().
   */
  public function dataProviderConvert(): array {
    return [
      // No tags.
      ['', ''],
      ['word', 'word'],
      ['word word', 'word word'],

      // Anchor tag with node paths.
      [
        '<p>prefix <a href="/src-page-1">Test page content <span class="ct-visually-hidden">with html</span></a> suffix</p>',
        '<p>prefix <a href="/node/1001" data-entity-type="node" data-entity-uuid="58f47065-27fb-429b-9ccd-000000001001" data-entity-substitution="canonical">Test page content <span class="ct-visually-hidden">with html</span></a> suffix</p>',
      ],
      [
        '<p>prefix <a href="/src-page-2-non-existing">Test page content <span class="ct-visually-hidden">with html</span></a> suffix</p>',
        '<p>prefix <a href="/src-page-2-non-existing">Test page content <span class="ct-visually-hidden">with html</span></a> suffix</p>',
      ],

      // Anchor tag with media file paths.
      [
        '<p>prefix <a href="/src-file-1.txt">Test link to file <span class="ct-visually-hidden">with html</span></a> suffix</p>',
        '<p>prefix <a href="/media/2001/edit" data-entity-type="media" data-entity-uuid="58f47065-27fb-429b-9ccd-000000002001" data-entity-substitution="media">Test link to file <span class="ct-visually-hidden">with html</span></a> suffix</p>',
      ],
      [
        '<p>prefix <a href="/src-file-2-non-existing.txt">Test link to file <span class="ct-visually-hidden">with html</span></a> suffix</p>',
        '<p>prefix <a href="/src-file-2-non-existing.txt">Test link to file <span class="ct-visually-hidden">with html</span></a> suffix</p>',
      ],

      // Image tag with media file paths.
      [
        '<p>prefix <img src="/src-image-1.png" title="test image" alt="Alternative text for a test image" /> <span class="ct-visually-hidden">with html</span> suffix</p>',
        '<p>prefix <drupal-media data-entity-type="media" data-entity-uuid="58f47065-27fb-429b-9ccd-000000002002" data-entity-substitution="canonical" alt="Alternative text for a test image"></drupal-media><span class="ct-visually-hidden">with html</span> suffix</p>',
      ],
      [
        '<p>prefix <img src="/src-image-2-non-existing.png" title="test image" alt="Alternative text for a test image" /><span class="ct-visually-hidden">with html</span> suffix</p>',
        '<p>prefix <img src="/src-image-2-non-existing.png" title="test image" alt="Alternative text for a test image" /><span class="ct-visually-hidden">with html</span> suffix</p>',
      ],

    ];
  }

  /**
   * Helper to create a media item.
   *
   * @param \Drupal\media\MediaTypeInterface $media_type
   *   The media type.
   * @param string $filename
   *   String filename with extension.
   * @param array $values
   *   Array of values to set on the media item.
   *
   * @return \Drupal\media\MediaInterface
   *   A media item.
   */
  protected function createMedia(MediaTypeInterface $media_type, $filename, array $values = []): MediaInterface {
    vfsStream::setup('drupal_root');
    vfsStream::create([
      'sites' => [
        'default' => [
          'files' => [
            $filename => str_repeat('a', 3000),
          ],
        ],
      ],
    ]);

    $file = File::create([
      'uri' => 'vfs://drupal_root/sites/default/files/' . $filename,
      'uid' => 1,
    ]);
    $file->setPermanent();
    $file->save();

    // @formatter:off
    return Media::create($values + [
      'bundle' => $media_type->id(),
      'name' => $this->randomString(),
      'field_media_file' => [
        'target_id' => $file->id(),
      ],
    ]);
  }

  /**
   * Call protected methods on the class.
   *
   * @param object|string $object
   *   Object or class name to use for a method call.
   * @param string $method
   *   Method name. Method can be static.
   * @param array $args
   *   Array of arguments to pass to the method. To pass arguments by reference,
   *   pass them by reference as an element of this array.
   *
   * @return mixed
   *   Method result.
   */
  protected static function callProtectedMethod($object, $method, array $args = []) {
    $class = new \ReflectionClass(is_object($object) ? get_class($object) : $object);
    $method = $class->getMethod($method);
    $method->setAccessible(TRUE);
    $object = $method->isStatic() ? NULL : $object;

    return $method->invokeArgs($object, $args);
  }

}
