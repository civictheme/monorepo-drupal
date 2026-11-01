<?php

declare(strict_types=1);

namespace Drupal\civictheme\Theme;

use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\views\ViewExecutable;

/**
 * CivicTheme feature opt-out handling.
 *
 * Internal to CivicTheme.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class FeatureOptout {

  /**
   * Check if the CivicTheme feature was opted out.
   *
   * @param string $type
   *   The type of the optout.
   * @param string $name
   *   The name of the optout flag.
   * @param mixed $context
   *   Optional context to pass to assess the optout.
   *
   * @return bool
   *   TRUE if a feature should be opted out, FALSE otherwise.
   */
  public static function isOptedOut(string $type, string $name, mixed $context = NULL): bool {
    $optedout = FALSE;

    switch ($type) {
      case 'views':
        // For views, use view Tag as an opt-out data source.
        if (!empty($context) && $context instanceof ViewExecutable) {
          $view = $context;
          $tags = $view->storage->get('tag');
          $tags = empty($tags) ? [] : explode(',', (string) $tags);
          $tags = array_map('trim', $tags);

          if (in_array($name, $tags)) {
            $optedout = TRUE;
          }
        }
        break;

      default:
        try {
          /** @var \Drupal\civictheme\CivicthemeConfigManager $theme_config_manager */
          $theme_config_manager = \Drupal::classResolver(CivicthemeConfigManager::class);
          $optouts = $theme_config_manager->load('optouts', []);
          // Additionally filter using available flags to ensure that only
          // correctly mapped flags are used.
          if (array_key_exists($name, static::flags())) {
            $optedout = in_array($name, $optouts);
          }
        }
        catch (\Exception) {
          $optedout = FALSE;
        }
    }

    return $optedout;
  }

  /**
   * Map of available opt-out flags.
   *
   * @return array<string,\Drupal\Core\StringTranslation\TranslatableMarkup>
   *   Array of opt-out flags.
   */
  public static function flags(): array {
    $flags = [
      'components.link' => t('Links processing'),
    ];
    $flags[CivicthemeConstants::OPTOUT_SUMMARY_HIDE_ELLIPSIS] = t('Hide card summary ellipsis');
    return $flags;
  }

}
