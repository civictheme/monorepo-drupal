<?php

/**
 * @file
 * Rector configuration.
 *
 * Usage:
 * ./vendor/bin/rector process (paths default to the theme tree).
 *
 * @see https://github.com/palantirnet/drupal-rector/blob/main/rector.php
 */

declare(strict_types=1);

use DrupalFinder\DrupalFinderComposerRuntime;
use DrupalRector\Set\Drupal10SetList;
use DrupalRector\Set\Drupal11SetList;
use DrupalRector\Set\Drupal8SetList;
use DrupalRector\Set\Drupal9SetList;
use Rector\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector;
use Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;
use Rector\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;
use Rector\Set\ValueObject\SetList;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return static function (RectorConfig $rectorConfig): void {
  $rectorConfig->sets([
    // Provided by Rector.
    SetList::PHP_80,
    SetList::PHP_81,
    SetList::PHP_82,
    SetList::CODE_QUALITY,
    SetList::CODING_STYLE,
    SetList::DEAD_CODE,
    SetList::INSTANCEOF,
    SetList::TYPE_DECLARATION,
    // Provided by Drupal Rector.
    Drupal8SetList::DRUPAL_8,
    Drupal9SetList::DRUPAL_9,
    Drupal10SetList::DRUPAL_10,
    Drupal11SetList::DRUPAL_11,
    // The "breaking" sets rewrite to replacement symbols introduced in the
    // named minor, so they require core_version_requirement >= that minor.
    Drupal11SetList::DRUPAL_111_BREAKING,
    Drupal11SetList::DRUPAL_112_BREAKING,
    Drupal11SetList::DRUPAL_113_BREAKING,
    Drupal11SetList::DRUPAL_114_BREAKING,
  ]);

  $rectorConfig->rule(DeclareStrictTypesRector::class);

  $drupalFinder = new DrupalFinderComposerRuntime();
  $drupalRoot = $drupalFinder->getDrupalRoot();
  $rectorConfig->autoloadPaths([
    $drupalRoot . '/core',
    $drupalRoot . '/modules',
    $drupalRoot . '/themes',
    $drupalRoot . '/profiles',
  ]);

  // The isolated CI build copies this file into the `build/` directory at the
  // theme root and runs rector from there without CLI paths. The theme is
  // symlinked into the built site and rector does not traverse symlinks, so
  // point at the theme source tree instead. Paths passed on the CLI override
  // this.
  $themeRoot = basename(__DIR__) === 'build' ? dirname(__DIR__) : __DIR__;
  $rectorConfig->paths([$themeRoot]);

  $rectorConfig->skip([
    // Rules added by Rector's rule sets.
    DisallowedEmptyRuleFixerRector::class,
    InlineArrayReturnAssignRector::class,
    NewlineAfterStatementRector::class,
    NewlineBeforeNewAssignSetRector::class,
    RemoveAlwaysTrueIfConditionRector::class,
    SimplifyEmptyCheckOnEmptyArrayRector::class,
    // Dependencies.
    '*/vendor/*',
    '*/node_modules/*',
    // Core and contribs. The theme itself lives under themes/contrib in the
    // monorepo, so that pattern must not be listed here: the configured path
    // already scopes processing to the theme's own tree.
    '*/core/*',
    '*/modules/contrib/*',
    '*/profiles/contrib/*',
    // Files.
    '*/sites/simpletest/*',
    '*/sites/default/files/*',
    // Composer scripts.
    '*/scripts/composer/*',
    // Dependencies.
    '*/vendor/*',
    '*/node_modules/*',
    '*/lib/*',
    // The isolated CI build directory and generated assets within the theme.
    '*/build/*',
    '*/dist/*',
    '*/components/*',
  ]);

  $rectorConfig->fileExtensions([
    'engine',
    'inc',
    'install',
    'module',
    'php',
    'profile',
    'theme',
  ]);

  $rectorConfig->importNames(TRUE, FALSE);
  $rectorConfig->importShortClasses(FALSE);
};
