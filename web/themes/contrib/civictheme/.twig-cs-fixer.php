<?php

declare(strict_types = 1);

$ruleset = new TwigCsFixer\Ruleset\Ruleset();
$ruleset->addStandard(new TwigCsFixer\Standard\Twig());

$finder = new TwigCsFixer\File\Finder();
$finder->in(__DIR__ . '/web/themes/custom/civictheme/templates');
$finder->in(__DIR__ . '/web/themes/custom/civictheme/civictheme_starter_kit/components');
$finder->in(__DIR__ . '/web/themes/custom/civictheme/civictheme_starter_kit/templates');

$config = new TwigCsFixer\Config\Config();
$config->setRuleset($ruleset);
$config->setFinder($finder);

return $config;
