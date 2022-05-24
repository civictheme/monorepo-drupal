<?php

/**
 * @file
 * Create demo users.
 */

use Drupal\cs_demo\CsDemoHelper;
use Drupal\user\Entity\Role;
use Drupal\user\Entity\User;

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_demo_create_user_user_weight() {
  return 20;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_demo_create_user_user_tracking() {
  return TRUE;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE().
 */
function cs_demo_create_user_user() {
  $total_users_per_role = 3;

  /** @var \Drupal\cs_demo\CsDemoHelper $helper */
  $helper = CsDemoHelper::getInstance();

  $users = [];

  $roles = user_roles();
  foreach ($roles as $role) {
    if (in_array($role->id(), [
      'anonymous', 'authenticated', 'administrator',
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
        try {
          $user->save();
          $users[] = $user;

          $helper::log(
            'Created an account %s',
            $name,
          );
        }
        catch (\Exception $exception) {
          watchdog_exception('cs_demo', $exception);
        }
      }
    }
  }

  return $users;
}
