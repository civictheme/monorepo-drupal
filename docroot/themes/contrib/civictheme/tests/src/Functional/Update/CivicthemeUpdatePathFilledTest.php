<?php

namespace Drupal\Tests\civictheme\Functional\Update;

/**
 * Tests the hook_post_update_NAME() implementations in filled database.
 *
 * @group civictheme:functional:update
 */
class CivicthemeUpdatePathFilledTest extends CivicthemeUpdatePathBareTest {

  /**
   * {@inheritdoc}
   */
  protected function setDatabaseDumpFiles() {
    $this->databaseDumpFiles = [
      __DIR__ . '/../../../fixtures/updates/drupal_10.0.0-rc1.minimal.civictheme_1.3.2.filled.php.gz',
    ];
  }

}
