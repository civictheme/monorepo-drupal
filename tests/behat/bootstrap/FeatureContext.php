<?php

/**
 * @file
 * Civic Demo Drupal context for Behat testing.
 */

use Drupal\DrupalExtension\Context\DrupalContext;
use DrevOps\BehatSteps\ContentTrait;
use DrevOps\BehatSteps\TaxonomyTrait;
use DrevOps\BehatSteps\WatchdogTrait;
use DrevOps\BehatSteps\FieldTrait;
use DrevOps\BehatSteps\PathTrait;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends DrupalContext {

  use ContentTrait;
  use FieldTrait;
  use PathTrait;
  use TaxonomyTrait;
  use WatchdogTrait;

  /**
   * Checks if the given value is default selected in the given dropdown
   *
   * @param $option
   *   string The value to be looked for
   * @param $field
   *   string The dropdown field that has the value
   *
   * @Given /^I should see the option "([^"]*)" selected in "([^"]*)" dropdown$/
   */
  public function iShouldSeeTheOptionSelectedInDropdown($option, $field) {
    $selector = $field;
    // Some fields do not have label, so set the selector here
    if (strtolower($field) == "default notification") {
      $selector = "edit-projects-default";
    }
    $chk = $this->getSession()->getPage()->findField($field);
    // Make sure that the dropdown $field and the value $option exists in the dropdown
    $optionObj = $chk->findAll('xpath', '//option[@selected="selected"]');
    // Check if at least one value is selected
    if (empty($optionObj)) {
      throw new \Exception("The field '" . $field . "' does not have any options selected");
    }
    $found = FALSE;
    foreach ($optionObj as $opt) {
      if ($opt->getText() == $option) {
        $found = TRUE;
        break;
      }
    }
    if (!$found) {
      throw new \Exception("The field '" . $field . "' does not have the option '" . $option . "' selected");
    }
  }

}
