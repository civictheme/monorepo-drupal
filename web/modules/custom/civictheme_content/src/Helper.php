<?php

declare(strict_types=1);

namespace Drupal\civictheme_content;

use Drupal\node\Entity\Node;
use Drush\Drush;
use Psr\Log\LogLevel;

/**
 * Class Helper.
 *
 * Helper class for manipulating content.
 *
 * @package Drupal\civictheme_content
 */
class Helper {

  /**
   * Helper to print a log message to stdout.
   *
   * It is important to note that using \Drupal::messenger() when running Drush
   * commands have side effects where messages are displayed only after the
   * command has finished rather than during the command run.
   *
   * @param string|\Stringable $message
   *   String containing message.
   *
   * @SuppressWarnings(PHPMD.ElseExpression)
   */
  public static function log(string|\Stringable $message): void {
    if (PHP_SAPI === 'cli') {
      $message = strip_tags(html_entity_decode((string) $message));
      if (class_exists('\\' . Drush::class)) {
        Drush::getContainer()->get('logger')->log(LogLevel::INFO, $message);
      }
      else {
        print $message . PHP_EOL;
      }
    }
    else {
      \Drupal::messenger()->addMessage((string) $message);
    }
  }

  /**
   * Load node by title.
   *
   * @param string $title
   *   Title to search for.
   * @param string $type
   *   Optional bundle name to limit the search. Defaults to NULL.
   *
   * @return \Drupal\node\Entity\Node
   *   Found node object or NULL if the node was not found.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public static function loadNodeByTitle(string $title, ?string $type = NULL): ?Node {
    $query = \Drupal::entityQuery('node')->accessCheck(FALSE);
    $query->condition('title', $title);
    if ($type) {
      $query->condition('type', $type);
    }
    $ids = $query->execute();

    if (empty($ids)) {
      return NULL;
    }

    $id = array_shift($ids);

    return Node::load($id);
  }

  /**
   * Set the site homepage from node.
   *
   * @param string $title
   *   Node title to set as a homepage.
   *
   * @SuppressWarnings(PHPMD.MissingImport)
   */
  public static function setHomepageFromNode(string $title): void {
    $node = static::loadNodeByTitle($title, 'civictheme_page');

    if (!$node instanceof Node) {
      throw new \Exception('Unable to find homepage node.');
    }

    $config = \Drupal::service('config.factory')->getEditable('system.site');
    $config->set('page.front', '/node/' . $node->id())->save();
  }

}
