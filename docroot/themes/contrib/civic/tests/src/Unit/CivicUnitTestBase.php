<?php

namespace Drupal\Tests\civic\Unit;

use Drupal\Tests\civic\Traits\CivicTestHelperTrait;
use Drupal\Tests\UnitTestCase;

/**
 * Class CivicUnitTestBase.
 *
 * Base class for all unit test cases.
 */
abstract class CivicUnitTestBase extends UnitTestCase {

  use CivicTestHelperTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    require_once 'docroot/themes/contrib/civic/civic.theme';
  }

}
