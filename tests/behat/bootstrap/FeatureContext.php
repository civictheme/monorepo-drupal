<?php

/**
 * @file
 * CivicTheme Drupal context for Behat testing.
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
use DrevOps\BehatSteps\OverrideTrait;
use DrevOps\BehatSteps\ParagraphsTrait;
use DrevOps\BehatSteps\PathTrait;
use DrevOps\BehatSteps\SelectTrait;
use DrevOps\BehatSteps\TaxonomyTrait;
use DrevOps\BehatSteps\VisibilityTrait;
use DrevOps\BehatSteps\WaitTrait;
use DrevOps\BehatSteps\WatchdogTrait;
use DrevOps\BehatSteps\WysiwygTrait;
use Drupal\DrupalExtension\Context\DrupalContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends DrupalContext {

  use ContentTrait;
  use ElementTrait;
  use FieldTrait;
  use FileTrait;
  use JsTrait;
  use LinkTrait;
  use MediaTrait;
  use OverrideTrait;
  use PathTrait;
  use ParagraphsTrait;
  use SelectTrait;
  use TaxonomyTrait;
  use WatchdogTrait;
  use WaitTrait;
  use WysiwygTrait;
  use VisibilityTrait;

  /**
   * @Then I see content in iframe with id :id
   */
  public function iSeeContentInIframe($id) {
    $driver = $this->getSession()->getDriver();
    if (!$driver instanceof Selenium2Driver) {
      throw new RuntimeException('Unsupported driver for this step');
    }

    $page_iframe_elements = $driver->find('//iframe[@id="' . $id . '"]');
    if (empty($page_iframe_elements) || $page_iframe_elements[0] === NULL) {
      throw new ElementNotFoundException($driver, 'iFrame', $id);
    }

    // Select WYSIWYG iframe frame.
    $driver->switchToIFrame($id);

    if (!$driver->find('//body')) {
      throw new Exception(sprintf('The contents of the iFrame with id "%s" was not loaded', $id));
    }

    // Reset frame to the default window.
    $driver->switchToIFrame();
  }

  /**
   * Navigate to delete page with specified type and title.
   *
   * @code
   * When I delete "article" "Test article"
   * @endcode
   *
   * @When I delete :type :title
   */
  public function contentDeletePageWithTitle($type, $title) {
    $nids = $this->contentNodeLoadMultiple($type, [
      'title' => $title,
    ]);

    if (empty($nids)) {
      throw new \RuntimeException(sprintf('Unable to find %s page "%s"', $type, $title));
    }

    $nid = current($nids);
    $path = $this->locatePath('/node/' . $nid) . '/delete';
    print $path;
    $this->getSession()->visit($path);
  }

  /**
   * Selects a filter chip.
   *
   * @When I select the filter chip :label
   */
  public function assertSelectFilterChip($label) {
    $element = $this->getSession()->getPage();
    $filter_chip = $element->find('named', ['radio', $this->getSession()->getSelectorsHandler()->xpathLiteral($label)]);
    if ($filter_chip === NULL) {
      throw new \Exception(sprintf('The filter chip with "%s" was not found on the page %s', $label, $this->getSession()->getCurrentUrl()));
    }

    $this->clickFilterChip($label, $filter_chip, $element);
  }

  /**
   * Checks a multi-value filter chip.
   *
   * @Given I check the filter chip :label
   */
  public function assertCheckFilterChip($label) {
    $element = $this->getSession()->getPage();
    $filter_chip = $element->find('named', ['checkbox', $this->getSession()->getSelectorsHandler()->xpathLiteral($label)]);

    if ($filter_chip === NULL) {
      throw new \Exception(sprintf('The filter chip with "%s" was not found on the page %s', $label, $this->getSession()->getCurrentUrl()));
    }

    if ($filter_chip->isChecked()) {
      throw new \Exception(sprintf('Cannot check filter chip with "%s" because it already checked', $label, $this->getSession()->getCurrentUrl()));
    }

    $this->clickFilterChip($label, $filter_chip, $element);

  }

  /**
   * Unchecks a multi-value filter chip.
   *
   * @Given I uncheck the filter chip :checkbox
   */
  public function assertUncheckFilterChip($label) {
    $element = $this->getSession()->getPage();
    $filter_chip = $element->find('named', ['checkbox', $this->getSession()->getSelectorsHandler()->xpathLiteral($label)]);

    if ($filter_chip === NULL) {
      throw new \Exception(sprintf('The filter chip with "%s" was not found on the page %s', $label, $this->getSession()->getCurrentUrl()));
    }

    if (!$filter_chip->isChecked()) {
      throw new \Exception(sprintf('Cannot uncheck filter chip with "%s" because it not checked', $label, $this->getSession()->getCurrentUrl()));
    }

    $this->clickFilterChip($label, $filter_chip, $element);
  }

  /**
   * Helper to get the filter chip label element which is then clicked.
   */
  protected function clickFilterChip($label, $filter_chip, $element) {
    $filter_chip_id = $filter_chip->getAttribute('id');
    $label_element = $element->find('css', "label[for='$filter_chip_id']");
    $labelonpage = $label_element->getText();
    if ($label != $labelonpage) {
      throw new \Exception(sprintf("Filter chip with id '%s' has label '%s' instead of '%s' on the page %s", $filter_chip_id, $labelonpage, $label, $this->getSession()->getCurrentUrl()));
    }

    $label_element->click();
  }

}
