<?php

declare(strict_types=1);

use FriendsOfTwig\Twigcs;

// Exclude directories with compiled components.
$excluded = [
  '.components-civictheme',
  'components_combined',
];

// Filter-out stories templates
$filter = function (\SplFileInfo $fileInfo) {
  return !str_contains($fileInfo->getFilename(), 'stories');
};

return Twigcs\Config\Config::create()
  ->setName('custom-config')
  ->setSeverity('error')
  ->setReporter('console')
  ->setRuleSet(Twigcs\Ruleset\Official::class)
  ->addFinder(Twigcs\Finder\TemplateFinder::create()->in(__DIR__ . '/web/modules/custom'))
  ->addFinder(Twigcs\Finder\TemplateFinder::create()->in(__DIR__ . '/web/themes/contrib/civictheme')->exclude($excluded)->filter($filter))
  ->addFinder(Twigcs\Finder\TemplateFinder::create()->in(__DIR__ . '/web/themes/custom')->exclude($excluded)->filter($filter));
