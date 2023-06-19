<?php

namespace Drupal\Tests\civictheme_migrate\Kernel\Utils;

use Drupal\civictheme_migrate\Utils\NodeHelper;
use Drupal\Core\Url;
use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\node\NodeInterface;

/**
 * Tests the NodeHelper class.
 *
 * @coversDefaultClass \Drupal\civictheme_migrate\Utils\NodeHelper
 *
 * @group civictheme_migrate
 * @group site:kernel
 */
class NodeHelperTest extends KernelTestBase {

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
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
    $this->installEntitySchema('file');
    $this->installEntitySchema('path_alias');
    $this->installSchema('system', ['sequences']);
    $this->installSchema('user', ['users_data']);
    $this->installSchema('node', ['node_access']);
    $this->installSchema('file', 'file_usage');
    $this->installConfig(['file', 'image', 'media', 'node', 'system', 'field']);

    // Create the custom node type.
    $type = NodeType::create([
      'type' => 'type1',
      'name' => 'Custom Type 1',
      'description' => 'A custom node type.',
      'display_submitted' => FALSE,
    ]);
    $type->save();

    // Create the custom node type.
    $type = NodeType::create([
      'type' => 'type2',
      'name' => 'Custom Type 2',
      'description' => 'A custom node type.',
      'display_submitted' => FALSE,
    ]);
    $type->save();

    // Create and save a node with ID '99999999'.
    $node = Node::create([
      'type' => 'type1',
      'nid' => 99999999,
      'title' => 'Title 1',
      'status' => 1,
      'path' => [
        'alias' => '/alias1',
      ],
    ]);
    $node->save();
  }

  /**
   * {@inheritdoc}
   */
  protected function tearDown(): void {
    // Clean up the created node with ID '99999999'.
    $node = Node::load(99999999);
    if ($node instanceof NodeInterface) {
      $node->delete();
    }

    parent::tearDown();
  }

  /**
   * Tests the lookupNodeFromAlias method.
   *
   * @dataProvider dataProviderNodeAlias
   *
   * @covers ::lookupNodeFromAlias
   */
  public function testLookupNodeFromAlias(string $alias, ?string $expectedNodeId) {
    // Mock the Url class and provide the necessary route parameters.
    $routeParameters = ['node' => (int) $expectedNodeId];
    $url = $this->createMock(Url::class);
    $url->method('getRouteParameters')
      ->willReturn($routeParameters);

    // Set the test Url instance in NodeHelper.
    NodeHelper::setTestUrlInstance($url);

    $node = NodeHelper::lookupNodeFromAlias($alias);

    if ($expectedNodeId) {
      $this->assertInstanceOf(NodeInterface::class, $node);
      $this->assertEquals($expectedNodeId, $node->id());
    }
    else {
      $this->assertNull($node);
    }
  }

  /**
   * Provides test data for testLookupNodeFromAlias.
   *
   * @return array
   *   An array of test data.
   */
  public function dataProviderNodeAlias() {
    return [
      ['/alias1', '99999999'],
      ['/alias2', NULL],
      ['', NULL],
    ];
  }

  /**
   * Tests the lookupNodeFromTitleAndType method.
   *
   * @dataProvider nodeTitleAndTypeDataProvider
   *
   * @covers ::lookupNodeFromTitleAndType
   */
  public function testLookupNodeFromTitleAndType(string $title, string $type, ?string $expectedNodeId) {
    $node = NodeHelper::lookupNodeFromTitleAndType($title, $type);

    if ($expectedNodeId) {
      $this->assertInstanceOf(NodeInterface::class, $node);
      $this->assertEquals($expectedNodeId, $node->id());
    }
    else {
      $this->assertNull($node);
    }
  }

  /**
   * Provides test data for testLookupNodeFromTitleAndType.
   *
   * @return array
   *   An array of test data.
   */
  public function nodeTitleAndTypeDataProvider() {
    return [
      ['Title 1', 'type1', '99999999'],
      ['Title 2', 'type2', NULL],
      ['', 'type1', NULL],
      ['Title 1', '', NULL],
    ];
  }

}
