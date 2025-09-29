<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_search\Functional;

use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * @group drupal_cms_search
 */
class ComponentValidationTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['block', 'node'];

  public function test(): void {
    // The PlaceBlock config action has a core bug, where it doesn't account
    // for the possibility of there being no blocks in a region. As a
    // workaround, prevent that from happening by placing a useless block into
    // the content region.
    $this->drupalPlaceBlock('system_powered_by_block');

    $dir = realpath(__DIR__ . '/../../..');

    // The recipe should apply cleanly.
    $this->applyRecipe($dir);
    // Apply it again to prove that it is idempotent.
    $this->applyRecipe($dir);
  }

}
