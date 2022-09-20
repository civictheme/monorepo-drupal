<?php

/**
 * @file
 * Create demo users.
 */

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
use Drupal\user\Entity\Role;
use Drupal\user\Entity\User;

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_user_user_weight() {
  return 0;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_user_user_tracking() {
  return TRUE;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE().
 */
function cs_generated_content_generated_content_create_user_user() {
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
      $name = sprintf('p.civictheme+%s_%s@salsadigital.com.au', str_replace('civictheme_', '', $role->id()), $i);

      $existing_user = user_load_by_name($name);
      if ($existing_user) {
        \Drupal::entityTypeManager()->getStorage('user')->load($existing_user->id())->delete();
      }

      if (Role::load($role->id())) {
        $user = User::create();
        $user->setUsername($name);
        $user->setEmail($name);
        $user->addRole($role->id());
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
          watchdog_exception('cs_generated_content', $exception);
        }
      }
    }
  }

  return $users;
}
