<?php

namespace Drupal\Tests\cs_core\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\cs_core\Traits\CsCoreTestHelperTrait;

/**
 * Class CsCoreKernelTestBase.
 */
abstract class CsCoreFunctionalTestBase extends BrowserTestBase {

  use CsCoreTestHelperTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

}
