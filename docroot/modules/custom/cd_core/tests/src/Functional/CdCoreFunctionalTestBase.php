<?php

namespace Drupal\Tests\cd_core\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\cd_core\Traits\CdCoreTestHelperTrait;

/**
 * Class CdCoreKernelTestBase.
 */
abstract class CdCoreFunctionalTestBase extends BrowserTestBase {

  use CdCoreTestHelperTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

}
