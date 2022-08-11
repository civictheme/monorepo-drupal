<?php

namespace Drupal\Tests\civictheme\Unit;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class CivicThemeIsExternalLinkUnitTest.
 *
 * Test cases for _civictheme_is_link_external().
 *
 * @group CivicTheme
 */
class CivicThemeIsExternalLinkUnitTest extends CivicThemeUnitTestBase {

  /**
   * The Drupal service container.
   *
   * @var \Drupal\Core\DependencyInjection\Container
   */
  protected $container;


  /**
   * @var \Symfony\Component\HttpFoundation\Request|\Prophecy\Prophecy\ProphecyInterface
   */
  protected $request;

  /**
   * @var \Symfony\Component\HttpFoundation\RequestStack|\Prophecy\Prophecy\ProphecyInterface
   */
  protected $requestStack;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $container = new ContainerBuilder();

    // Mock a request.
    $this->request = $this->prophesize(Request::class);

    // Mock the request_stack service, make it return our mocked request,
    // and register it in the container.
    $this->requestStack = $this->prophesize(RequestStack::class);
    $this->requestStack->getCurrentRequest()->willReturn($this->request->reveal());
    $container->set('request_stack', $this->requestStack->reveal());

    $config_factory = $this->getConfigFactoryStub([
      'system.theme' => ['default' => 'civictheme'],
      'civictheme.settings' => ['civictheme_override_domains' => 'http://overridden.com'],
    ]);
    $container->set('config.factory', $config_factory);

    \Drupal::setContainer($container);
    $this->container = $container;
  }

  /**
   * Test for civictheme_is_link_external().
   *
   * @dataProvider dataProviderIsExternalLink
   */
  public function testParse($url, $expected) {
    $actual = civictheme_is_link_external($url);

    $this->assertEquals($expected, $actual);
  }

  /**
   * Data provider for testParse().
   */
  public function dataProviderIsExternalLink() {
    return [
      // Link without domain.
      ['/about-us', FALSE],
      // Link with an overridden domain.
      ['http://overridden.com', FALSE],
      // Link with an external domain.
      ['http://example.com', TRUE],
    ];
  }

}
