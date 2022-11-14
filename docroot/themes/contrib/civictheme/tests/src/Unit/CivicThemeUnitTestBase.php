<?php

namespace Drupal\Tests\civictheme\Unit;

use Drupal\Tests\civictheme\Traits\CivicthemeTestHelperTrait;
use Drupal\Tests\UnitTestCase;

/**
 * Class CivicUnitTestBase.
 *
 * Base class for all unit test cases.
 */
abstract class CivicthemeUnitTestBase extends UnitTestCase {

  use CivicthemeTestHelperTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    require_once 'docroot/themes/contrib/civictheme/civictheme.theme';
  }

}
