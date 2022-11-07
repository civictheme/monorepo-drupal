<?php

namespace Drupal\civictheme;

/**
 * CSS variables stylesheet parser.
 */
class CivicthemeStylesheetParser {

  /**
   * Defines CSS variables parts separator.
   */
  const CSS_VARIABLES_SEPARATOR = '-';

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
  public function setContent($content) {
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
  public function setCssVariablePrefix($cssVariablePrefix) {
    $this->cssVariablePrefix = $cssVariablePrefix;

    return $this;
  }

  /**
   * Get extracted CSS variables.
   *
   * @return array
   *   Array of CSS variables, keyed by name (with CSS prefix '--' preserved).
   */
  public function variables() {
    $variables = static::parseVariables($this->content);

    if ($this->cssVariablePrefix) {
      $variables = array_filter($variables, function ($key) {
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
   * @return array
   *   Array of parsed variables.
   */
  protected static function parseVariables($content) {
    $variables = [];

    if (empty(trim($content))) {
      return $variables;
    }

    $matches = [];
    preg_match_all('/(--[a-zA-Z0-9-]+)\s*:\s*([^;]+);/i', $content, $matches, PREG_SET_ORDER);

    array_walk($matches, function ($value) use (&$variables) {
      if (!empty($value[1])) {
        $variables[trim($value[1])] = trim($value[2]) ?? NULL;
      }
    });

    return $variables;
  }

}
