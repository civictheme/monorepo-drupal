<?php

declare(strict_types=1);

namespace Drupal\civictheme;

/**
 * CSS variables stylesheet parser.
 */
class CivicthemeStylesheetParser {

  /**
   * Defines CSS variables parts separator.
   */
  final const CSS_VARIABLES_SEPARATOR = '-';

  /**
   * The content to parse.
   *
   * @var string
   */
  protected $content;

  /**
   * The prefix of the CSS variable.
   *
   * @var string
   */
  protected $cssVariablePrefix;

  /**
   * Set content to parse.
   *
   * @param string $content
   *   The content to parse.
   *
   * @return $this
   *   Instance of the current class.
   */
  public function setContent(string $content): static {
    $this->content = $content;

    return $this;
  }

  /**
   * Set CSS variable prefix.
   *
   * @param string $cssVariablePrefix
   *   The CSS variable prefix.
   *
   * @return $this
   *   Instance of the current class.
   */
  public function setCssVariablePrefix($cssVariablePrefix): static {
    $this->cssVariablePrefix = $cssVariablePrefix;

    return $this;
  }

  /**
   * Get extracted CSS variables.
   *
   * @return array<string, string|null>
   *   Array of CSS variables, keyed by name (with CSS prefix '--' preserved).
   */
  public function variables(): array {
    $variables = static::parseVariables($this->content);

    if ($this->cssVariablePrefix !== '' && $this->cssVariablePrefix !== '0') {
      $variables = array_filter($variables, function ($key): bool {
        return str_starts_with($key, '--' . $this->cssVariablePrefix);
      }, ARRAY_FILTER_USE_KEY);
    }

    return $variables;
  }

  /**
   * Parse CSS variables into an array.
   *
   * @param string $content
   *   Content to parse.
   *
   * @return array<string, string|null>
   *   Array of parsed variables.
   */
  protected static function parseVariables($content): array {
    $variables = [];

    if (empty(trim($content))) {
      return $variables;
    }

    $matches = [];
    preg_match_all('/(--[a-zA-Z0-9-]+)\s*:\s*([^;]+);/i', $content, $matches, PREG_SET_ORDER);

    array_walk($matches, static function (array $value) use (&$variables): void {
      if (!empty($value[1])) {
        $variables[trim($value[1])] = empty($value[2]) ? NULL : trim($value[2]);
      }
    });

    return $variables;
  }

}
