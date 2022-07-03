<?php

namespace Drupal\civictheme_govcms\Commands;

use Drupal\civictheme_govcms\CivicthemeGovcmsManager;
use Drush\Commands\DrushCommands;
use Drush\Log\LogLevel;

/**
 * Class Civictheme Govcms Commands.
 *
 * @package Drupal\civictheme_govcms\Commands
 */
class CivicthemeGovcmsCommands extends DrushCommands {

  /**
   * The Govcms Manager service.
   *
   * @var \Drupal\civictheme_govcms\CivicthemeGovcmsManager
   */
  protected $govcmsManager;

  /**
   * Migration commands constructor.
   *
   * @param \Drupal\civictheme_govcms\CivicthemeGovcmsManager $govcms_manager
   *   The govcms manager service.
   */
  public function __construct(CivicthemeGovcmsManager $govcms_manager) {
    $this->govcmsManager = $govcms_manager;
  }

  /**
   * Remove govcms configurations.
   *
   * @param array $options
   *   The Drush command options.
   *
   * @command civictheme_govcms:remove-config
   * @aliases ctgrc ctg:rc ctg-rc civictheme_govcms:remove-config
   * @usage drush civictheme_govcms:remove-config --preserve=media_types,content_types
   *  Remove all listed govcms configuration except mentioned in preserve.
   *
   * @option preserve A comma-separated list of config types to preserve (as a singular name)
   * list of allowed --preserve options
   * - media_types
   * - text_format
   * - fields
   * - content_types
   * - vocabularies
   * - user_roles
   * - menus
   * - pathauto_patterns
   */
  public function drushCivicthemeGovcmsRemoveConfig(array $options = [
    'preserve' => '',
  ]) {
    // Removing configs will lead to showing warnings about missing bundles,
    // which are only shown due to dependencies resolution concurrency issues.
    // We are suppressing them as they do not have any valuable information
    // and only confuse the user.
    $current = $this->io()->getVerbosity();
    $this->io()->setVerbosity(LogLevel::ERROR);

    $this->govcmsManager->civicthemeGovcmsRemoveConfig($options['preserve']);

    $this->io()->setVerbosity($current);
  }

}
