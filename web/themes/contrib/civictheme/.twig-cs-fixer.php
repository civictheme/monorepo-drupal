<?php

declare(strict_types = 1);

$ruleset = new TwigCsFixer\Ruleset\Ruleset();
$ruleset->addStandard(new TwigCsFixer\Standard\Twig());
$config = new TwigCsFixer\Config\Config();
$config->setRuleset($ruleset);

return $config;
