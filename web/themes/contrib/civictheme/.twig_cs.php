<?php

declare(strict_types=1);

use FriendsOfTwig\Twigcs;

return Twigcs\Config\Config::create()
  ->setName('custom-config')
  ->setSeverity('error')
  ->setReporter('console')
  ->setRuleSet(Twigcs\Ruleset\Official::class)
  ->addFinder(Twigcs\Finder\TemplateFinder::create()->in([
    __DIR__ . '/templates',
    __DIR__ . '/civictheme_starter_kit/components',
    __DIR__ . '/civictheme_starter_kit/templates',
  ])->followLinks());
