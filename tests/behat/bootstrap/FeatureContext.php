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
use DrevOps\BehatSteps\JsTrait;
use DrevOps\BehatSteps\SelectTrait;
use DrevOps\BehatSteps\WaitTrait;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends DrupalContext {

  use ContentTrait;
  use JsTrait;
  use FieldTrait;
  use PathTrait;
  use SelectTrait;
  use TaxonomyTrait;
  use WatchdogTrait;
  use WaitTrait;

}
