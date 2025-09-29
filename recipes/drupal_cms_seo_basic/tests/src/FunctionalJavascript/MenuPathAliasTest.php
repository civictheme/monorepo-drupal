<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_seo_basic\FunctionalJavascript;

use Behat\Mink\Element\NodeElement;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_seo_basic')]
#[IgnoreDeprecations]
final class MenuPathAliasTest extends WebDriverTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['menu_ui', 'node'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->drupalCreateContentType(['type' => 'page']);
    $this->applyRecipe(__DIR__ . '/../../..');
  }

  public function testUrlAliasesAreBasedOnMenuPath(): void {
    $create_page = function (string $title, string $parent_link, string $expected_alias): void {
      $this->drupalGet('/node/add/page');
      $page = $this->getSession()->getPage();
      $page->fillField('Title', $title);

      // Menu settings are in a vertical tab.
      $this->assertSession()
        ->elementExists('css', '.vertical-tabs__menu-item-title:contains("Menu settings")')
        ->getParent()
        ->click();

      $page->checkField('Provide a menu link');
      $this->assertTrue(
        $page->findField('Menu link title')->waitFor(3, fn (NodeElement $field): bool => $field->getValue() === $title),
      );
      $page->selectFieldOption('Parent link', $parent_link);
      $page->pressButton('Save');

      // Wait to be redirected to the saved node, which should have the expected
      // alias.
      $this->assertTrue(
        $this->getSession()->wait(10000, "window.location.pathname.endsWith('$expected_alias');")
      );
    };

    $this->drupalLogin($this->rootUser);
    $create_page('Parent', '<Main navigation>', '/parent');
    $create_page('Child', '-- Parent', '/parent/child');
  }

}
