<?php

namespace Drupal\Tests\cd_core\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\cd_core\Traits\CdCoreTestHelperTrait;

/**
 * Class CdCoreKernelTestBase.
 *
 * Base class for kernel tests.
 */
abstract class CdCoreKernelTestBase extends KernelTestBase {

  use CdCoreTestHelperTrait;

}
