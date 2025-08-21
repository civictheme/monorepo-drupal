<?php

declare(strict_types=1);

$ruleset = new TwigCsFixer\Ruleset\Ruleset();
$ruleset->addRule(new TwigCsFixer\Rules\Delimiter\BlockNameSpacingRule());
$ruleset->addRule(new TwigCsFixer\Rules\Delimiter\DelimiterSpacingRule());
$ruleset->addRule(new TwigCsFixer\Rules\Function\NamedArgumentSpacingRule());
$ruleset->addRule(new TwigCsFixer\Rules\Operator\OperatorNameSpacingRule());
$ruleset->addRule(new TwigCsFixer\Rules\Operator\OperatorSpacingRule());
$ruleset->addRule(new TwigCsFixer\Rules\Punctuation\PunctuationSpacingRule());
$ruleset->addRule(new TwigCsFixer\Rules\Punctuation\TrailingCommaMultiLineRule());
$ruleset->addRule(new TwigCsFixer\Rules\Punctuation\TrailingCommaSingleLineRule());
$ruleset->addRule(new TwigCsFixer\Rules\Literal\HashQuoteRule());
$ruleset->addRule(new TwigCsFixer\Rules\Literal\SingleQuoteRule());
$ruleset->addRule(new TwigCsFixer\Rules\Variable\VariableNameRule(TwigCsFixer\Rules\Variable\VariableNameRule::SNAKE_CASE, '_'));
$ruleset->addRule(new TwigCsFixer\Rules\Whitespace\BlankEOFRule());
$ruleset->addRule(new TwigCsFixer\Rules\Whitespace\EmptyLinesRule());
$ruleset->addRule(new TwigCsFixer\Rules\Whitespace\IndentRule(2));
$ruleset->addRule(new TwigCsFixer\Rules\Whitespace\TrailingSpaceRule());

$config = new TwigCsFixer\Config\Config();
$config->setRuleset($ruleset);
$config->allowNonFixableRules();
$config->addTokenParser(new Drupal\Core\Template\TwigTransTokenParser());

return $config;
