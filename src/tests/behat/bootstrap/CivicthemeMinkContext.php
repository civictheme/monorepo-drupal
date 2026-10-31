<?php

/**
 * @file
 * CivicTheme Mink context.
 */

declare(strict_types=1);

use Drupal\DrupalExtension\Context\MinkContext;

/**
 * Mink context with assertions hardened for real-browser timing.
 */
class CivicthemeMinkContext extends MinkContext {

  /**
   * How long to retry a text assertion before failing, in seconds.
   */
  protected const TEXT_ASSERTION_WAIT = 10;

  /**
   * Assert text is visible, retrying while the browser settles.
   *
   * In javascript sessions the click that triggers a navigation can return
   * before the new document is committed, so a single immediate DOM read can
   * land in that gap and miss text that is on the page milliseconds later
   * (observed with both the login redirect and theme-settings form saves).
   * Non-javascript sessions see the final page on the first try, so the loop
   * exits immediately and behaviour is unchanged.
   *
   * No step attribute here: Behat finds the parent's Then attribute through
   * the method prototype, and re-declaring it registers the step twice.
   */
  public function assertTextVisible(string $text): void {
    $end = microtime(TRUE) + static::TEXT_ASSERTION_WAIT;
    while (TRUE) {
      try {
        parent::assertTextVisible($text);
        return;
      }
      catch (\Exception $e) {
        if (microtime(TRUE) >= $end) {
          throw $e;
        }
        usleep(100000);
      }
    }
  }

}
