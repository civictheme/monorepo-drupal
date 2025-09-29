<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_image\Functional;

use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\image\Entity\ImageStyle;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_image')]
#[IgnoreDeprecations]
class ComponentValidationTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  public function test(): void {
    $dir = realpath(__DIR__ . '/../../..');

    // The recipe should apply cleanly.
    $this->applyRecipe($dir);
    // Apply it again to prove that it is idempotent.
    $this->applyRecipe($dir);

    // Ensure all image styles convert to the expected format.
    $avif_styles = [
      'large',
      'media_library',
      'medium',
      'thumbnail',
    ];
    foreach (ImageStyle::loadMultiple() as $id => $image_style) {
      $expected_extension = in_array($id, $avif_styles, TRUE) ? 'avif' : 'webp';

      $this->assertSame(
        $expected_extension,
        $image_style->getDerivativeExtension('png'),
        "The '$id' image style does not convert to '$expected_extension'.",
      );
    }
  }

}
