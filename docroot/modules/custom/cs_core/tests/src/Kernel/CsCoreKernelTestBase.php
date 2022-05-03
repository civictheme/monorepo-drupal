<?php

namespace Drupal\Tests\cs_core\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\cs_core\Traits\CsCoreTestHelperTrait;

/**
 * Class CsCoreKernelTestBase.
 *
 * Base class for kernel tests.
 */
abstract class CsCoreKernelTestBase extends KernelTestBase {

  use CsCoreTestHelperTrait;

}
