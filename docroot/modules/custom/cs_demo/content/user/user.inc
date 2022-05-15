<?php

/**
 * @file
 * Create demo users.
 *
 * @see https://www.drupal.org/project/coder/issues/2877475
 * phpcs:disable DrupalPractice.CodeAnalysis.VariableAnalysis.UndefinedVariable
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

  /** @var Drupal\cs_demo\CsDemoHelper $helper */
  $helper = CsDemoHelper::getInstance();

  $user_info = _cs_demo_create_user__get_users_info();

  $processed_info = [];

  // Produce users information.
  $roles = user_roles();
  foreach ($roles as $role) {
    if (in_array($role->id(), [
      'anonymous', 'authenticated', 'administrator',
    ])) {
      continue;
    }

    foreach ($user_info as $user_info_item) {
      for ($i = 1; $i <= $total_users_per_role; $i++) {
        $processed_info[] = _cs_demo_create_user__process_user_info($user_info_item, [
          'index' => $i,
          'role_name' => $role->label(),
          'role_id' => $role->id(),
        ]);
      }
    }
  }

  // Create users.
  $users = [];
  foreach ($processed_info as $processed_info_item) {
    $existing_user = user_load_by_name($processed_info_item['email']);
    if ($existing_user) {
      Drupal::entityTypeManager()->getStorage('user')->load($existing_user->id())->delete();
    }

    if (Role::load($processed_info_item['role'])) {
      try {
        $users[] = _cs_demo_create_user__instance($processed_info_item);

        $helper::log(
          'Created an account %s',
          $processed_info_item['email'],
        );
      }
      catch (Exception $exception) {
        watchdog_exception('cs_demo', $exception);
      }
    }
  }

  return $users;
}

/**
 * Process user information with demo data.
 */
function _cs_demo_create_user__process_user_info($user_info, $placeholders = []) {
  $defaults = [
    'email' => '',
    'password' => '',
  ];

  $user_info += $defaults;

  $user_info['role'] = $placeholders['role_id'] ?? '';

  $user_info['email'] = str_replace('@', '+civictheme_%s_%s@', $user_info['email']);
  $user_info['email'] = sprintf($user_info['email'], strtolower(str_replace(' ', '_', $placeholders['role_name'])), $placeholders['index']);

  return $user_info;
}

/**
 * Create user instance with required fields.
 */
function _cs_demo_create_user__instance($user_info) {
  $user = User::create();

  $user->setUsername($user_info['email']);
  $user->setPassword($user_info['password']);
  $user->setEmail($user_info['email']);
  $user->addRole($user_info['role']);
  $user->activate();
  $user->enforceIsNew();

  $user->save();

  return $user;
}
