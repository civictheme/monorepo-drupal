<?php

/**
 * @file
 * Create generated users.
 */

declare(strict_types=1);

use Drupal\Core\Link;
use Drupal\Core\Utility\Error;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
use Drupal\user\Entity\User;

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_user_user_weight(): int {
  return 0;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_user_user_tracking(): bool {
  return TRUE;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE().
 *
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function cs_generated_content_generated_content_create_user_user(): array {
  $total_users_per_role = 3;

  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $users = [];

  $roles = user_roles();
  foreach ($roles as $role) {
    if (in_array($role->id(), [
      'anonymous', 'authenticated',
    ])) {
      continue;
    }

    for ($i = 1; $i <= $total_users_per_role; $i++) {
      $role_id = (string) $role->id();

      if (!empty($role_id)) {

        $name = sprintf('p.civictheme+%s_%s@salsa.digital', str_replace('civictheme_', '', $role_id), $i);

        $existing_user = user_load_by_name($name);
        if ($existing_user) {
          \Drupal::entityTypeManager()->getStorage('user')->load($existing_user->id())->delete();
        }

        /** @var \Drupal\user\RoleInterface|null $role */
        $role = \Drupal::entityTypeManager()->getStorage('user_role')->load($role_id);
        if ($role !== NULL) {
          $user = User::create();
          $user->setUsername($name);
          $user->setEmail($name);
          $user->addRole($role_id);
          $user->activate();
          $user->enforceIsNew();
          if (!empty(getenv('DRUPAL_CS_GENERATED_CONTENT_USER_PASSWORD'))) {
            $user->setPassword(getenv('DRUPAL_CS_GENERATED_CONTENT_USER_PASSWORD'));
          }

          try {
            $user->save();
            $users[] = $user;

            $helper::log(
              'Created an account %s [ID: %s] %s',
              Link::createFromRoute($user->getDisplayName(), 'entity.user.canonical', ['user' => $user->id()])->toString(),
              $user->id(),
              Link::createFromRoute('Edit', 'entity.user.edit_form', ['user' => $user->id()])->toString()
            );
          }
          catch (\Exception $exception) {
            $logger = \Drupal::logger('cs_generated_content');
            Error::logException($logger, $exception);
          }
        }
      }
    }
  }

  return $users;
}
