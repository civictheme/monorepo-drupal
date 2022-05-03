<?php

/**
 * @file
 * Post update hooks for core.
 */

use Drupal\user\Entity\User;

/**
 * Creates administrator users.
 */
function cs_core_post_update_provision_users() {
  $emails = [
    'alan@salsadigital.com.au',
    'akhil.bhandari@salsadigital.com.au',
    'alex.skrypnyk@salsadigital.com.au',
    'chris.darke@salsadigital.com.au',
    'danielle.sheffler@salsadigital.com.au',
    'govind@salsadigital.com.au',
    'jack.kelly@salsadigital.com.au',
    'kate.swayne@salsadigital.com.au',
    'lokender.singh@salsadigital.com.au',
    'richard.gaunt@salsadigital.com.au',
    'satyajit.das@salsadigital.com.au',
    'arpita.jain@salsadigital.com.au',
    'john.cloys@salsadigital.com.au',
    'nicola.hardy@salsadigital.com.au',
  ];

  foreach ($emails as $email) {
    $user = User::create();
    $user->setUsername($email);
    $user->setEmail($email);
    $user->addRole('civictheme_site_administrator');
    $user->activate();
    $user->enforceIsNew();
    $user->save();
  }
}
