<?php

namespace Drupal\Tests\civictheme\Unit;

use Drupal\Tests\civictheme\Traits\CivicThemeTestHelperTrait;
use Drupal\Tests\UnitTestCase;

/**
 * Class CivicUnitTestBase.
 *
 * Base class for all unit test cases.
 */
abstract class CivicThemeUnitTestBase extends UnitTestCase {

  use CivicThemeTestHelperTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    require_once 'docroot/themes/contrib/civictheme/civictheme.theme';
  }

}
