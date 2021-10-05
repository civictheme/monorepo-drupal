<?php

/**
 * @file
 * Civic Demo Drupal context for Behat testing.
 */

use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Exception\ElementNotFoundException;
use DrevOps\BehatSteps\ContentTrait;
use DrevOps\BehatSteps\ElementTrait;
use DrevOps\BehatSteps\FieldTrait;
use DrevOps\BehatSteps\FileTrait;
use DrevOps\BehatSteps\JsTrait;
use DrevOps\BehatSteps\LinkTrait;
use DrevOps\BehatSteps\MediaTrait;
use DrevOps\BehatSteps\PathTrait;
use DrevOps\BehatSteps\ParagraphsTrait;
use DrevOps\BehatSteps\SelectTrait;
use DrevOps\BehatSteps\TaxonomyTrait;
use DrevOps\BehatSteps\WaitTrait;
use DrevOps\BehatSteps\WatchdogTrait;
use Drupal\DrupalExtension\Context\DrupalContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends DrupalContext {

  use ContentTrait;
  use JsTrait;
  use ElementTrait;
  use FieldTrait;
  use FileTrait;
  use LinkTrait;
  use MediaTrait;
  use PathTrait;
  use ParagraphsTrait;
  use SelectTrait;
  use TaxonomyTrait;
  use WatchdogTrait;
  use WaitTrait;

  /**
   * @Then I see content in iframe with id :id
   */
  public function iSeeContentInIframe($id) {
    $driver = $this->getSession()->getDriver();
    if (!$driver instanceof Selenium2Driver) {
      throw new \RuntimeException('Unsupported driver for this step');
    }

    $page_iframe_elements = $driver->find('//iframe[@id="' . $id . '"]');
    if (empty($page_iframe_elements) || $page_iframe_elements[0] === NULL) {
      throw new ElementNotFoundException($driver, 'iFrame', $id);
    }

    // Select WYSIWYG iframe frame.
    $driver->switchToIFrame($id);

    if (!$driver->find('//body')) {
      throw new \Exception(sprintf('The contents of the iFrame with id "%s" was not loaded', $id));
    }

    // Reset frame to the default window.
    $driver->switchToIFrame();
  }

}
