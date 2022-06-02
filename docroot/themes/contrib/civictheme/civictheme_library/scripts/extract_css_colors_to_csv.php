<?php

/**
 * @file
 * Extract CSS4 color variables and output as CSV.
 *
 * Usage:
 * @code
 * php extract_css_colors_to_csv.php path/to/variables.css
 *
 * phpcs:disable Drupal.Commenting.InlineComment.SpacingBefore
 * phpcs:disable Drupal.Commenting.InlineComment.SpacingAfter
 * phpcs:disable DrupalPractice.Commenting.CommentEmptyLine.SpacingAfter
 */

/**
 * Defines exit codes.
 */
define('EXIT_SUCCESS', 0);
define('EXIT_ERROR', 1);

/**
 * Main functionality.
 */
function main(array $argv) {
  // Show help if not enough arguments or help was explicitly called.
  if (count($argv) > 2 || in_array($argv[1], ['--help', '-help', '-h', '-?'])) {
    print_help();
    return EXIT_SUCCESS;
  }

  $css_file = trim($argv[1]) ?: '';
  if (empty($css_file) || !is_readable($css_file)) {
    throw new \RuntimeException(sprintf('CSS variables file %s in not readable.', $css_file));
  }

  $content = file_get_contents($css_file);
  if ($content === FALSE) {
    throw new \RuntimeException(sprintf('Unable to read from CSS file %s.', $css_file));
  }

  $vars = collect_variables($content);
  $vars = filter_variables($vars);
  $vars = parse_variables($vars);
  output_csv($vars);

  return EXIT_SUCCESS;
}

/**
 * Print help.
 */
function print_help() {
  print <<<EOF
Extract CSS4 variables into CSV.
--------------------------------

Arguments:
  path/to/variables.css     Path to a file with CSS variables.

Options:
  --help                    This help.

Examples:
  php extract_css_colors_to_csv.php path/to/variables.css

EOF;
  print PHP_EOL;
}

/**
 * Collect variables from the provided content.
 *
 * @param string $content
 *   Variables to collect.
 *
 * @return array
 *   Array of collected variables as name=>value.
 */
function collect_variables($content) {
  $vars = [];

  $matches = [];
  preg_match_all('/(--[a-zA-Z0-9-]+)\s*:\s*([^;]+);/i', $content, $matches, PREG_SET_ORDER);

  array_walk($matches, function ($value) use (&$vars) {
    if (!empty($value[1])) {
      $vars[trim($value[1])] = trim($value[2]) ?? NULL;
    }
  });

  return $vars;
}

/**
 * Filter variables based on a custom criteria.
 */
function filter_variables($vars) {
  $vars = array_filter($vars, function ($k) {
    return strpos($k, '--civictheme') === 0;
  }, ARRAY_FILTER_USE_KEY);

  return $vars;
}

/**
 * Parse variables from name/value pair into structure.
 *
 * @param array $vars
 *   Array of variables with names as keys and values as values.
 *
 * @return array
 *   Array of parsed variables.
 */
function parse_variables(array $vars) {
  $variables = [];

  foreach ($vars as $name => $value) {
    try {
      $parsed = parse_variable_name($name, 'civictheme');
    }
    catch (\Exception) {
      // Ignore all variables with incorrectly parsed names.
      continue;
    }
    if (!$parsed) {
      continue;
    }
    $parsed['value'] = $value;

    $variables[] = $parsed;
  }

  return $variables;
}

/**
 * Output variable as CSV into specified stream.
 *
 * @param array $vars
 *   Array of variables.
 * @param string $stream
 *   Optional string name. Defaults to 'php://output'.
 *
 * @throws \Exception
 *   If no values were provided.
 */
function output_csv(array $vars, $stream = 'php://output') {
  if (empty($vars)) {
    throw new \Exception('No variables found');
  }

  $header = reset($vars);
  $header = array_keys($header);

  $out = fopen($stream, 'w');

  fputcsv($out, $header);
  foreach ($vars as $line) {
    fputcsv($out, $line);
  }

  fclose($out);
}

/**
 * Parse variable name.
 *
 * Examples of variables:
 *
 * @code
 * civictheme-[component]-[theme]-[?subcomponent]-[?state]-[rule]
 * --civictheme-collapsible-light-background-color
 * --civictheme-button-light-primary-active-background-color
 * @endcode
 *
 * @param string $name
 *   The variable name.
 * @param string $prefix
 *   Optional prefix to remove from the variable name.
 *
 * @return string[]
 *   Array of parsed variable name with the following keys:
 *   - component: (string) Component name.
 *   - theme: (string) Component theme.
 *   - subcomponent: (string) Optional sub-component name (space delimited).
 *   - state: (string) Optional state.
 *   - rule: (string) Rule.
 *
 * @throws \Exception
 *   - If component name is missing.
 *   - If theme is missing.
 *   - If rule is missing.
 */
function parse_variable_name($name, $prefix = NULL) {
  if (empty($name)) {
    throw new \Exception('Empty name provided.');
  }

  $defaults = [
    'component' => '',
    'theme' => '',
    'subcomponent' => '',
    'state' => '',
    'rule' => '',
  ];

  // Remove '--'.
  $name = strpos($name, '--') === 0 ? substr($name, 2) : $name;

  $parts = explode('-', $name);
  // Remove prefix.
  if (!empty($prefix) && $parts[0] == $prefix) {
    unset($parts[0]);
    $parts = array_values($parts);
  }

  // Filter by rule - maybe we do not need this variable at all.
  $allowed_rules = [
    'color',
    'background-color',
    'border-color',
    'border-left-color',
    'border-top-color',
    'border-bottom-color',
    'border-right-color',
  ];

  foreach ($allowed_rules as $k => $allowed_rule) {
    $allowed_rules[$k] = explode('-', $allowed_rule);
  }

  usort($allowed_rules, function ($a, $b) {
    return count($b) - count($a);
  });

  foreach ($allowed_rules as $k => $allowed_rule) {
    if (count($parts) <= count($allowed_rule)) {
      continue;
    }

    $matched_count = 0;
    $allowed_rule = array_reverse($allowed_rule);
    foreach ($allowed_rule as $allowed_rule_idx => $allowed_rule_part) {
      if ($parts[count($parts) - 1 - $allowed_rule_idx] == $allowed_rule_part) {
        $matched_count++;
      }
      else {
        break;
      }
    }

    if ($matched_count != count($allowed_rule)) {
      continue;
    }

    $defaults['rule'] = implode('-', array_reverse($allowed_rule));
    $parts = array_slice($parts, 0, -1 * count($allowed_rule));
    break;
  }

  if (empty($defaults['rule'])) {
    throw new \Exception(sprintf('Incorrectly named variable %s: rule is missing.', $name));
  }

  // Find theme.
  $theme_idx = array_search('light', $parts);
  $theme_idx = $theme_idx === FALSE ? array_search('dark', $parts) : $theme_idx;
  if ($theme_idx === FALSE) {
    throw new \Exception(sprintf('Incorrectly named variable %s: theme is missing.', $name));
  }
  $defaults['theme'] = $parts[$theme_idx];

  $component_name = implode(' ', array_slice($parts, 0, $theme_idx));
  if (empty($component_name)) {
    throw new \Exception(sprintf('Incorrectly named variable %s: component name is missing.', $name));
  }
  $defaults['component'] = $component_name;
  $parts = array_slice($parts, $theme_idx + 1);

  if (count($parts) > 0) {
    $allowed_states = [
      'active',
      'hover',
      'disabled',
      'visited',
    ];
    if (in_array($parts[count($parts) - 1], $allowed_states)) {
      $defaults['state'] = $parts[count($parts) - 1];
      $parts = array_slice($parts, 0, -1);
    }
  }

  if (count($parts) > 0) {
    $defaults['subcomponent'] = implode(' ', $parts);
  }

  return $defaults;
}

// ////////////////////////////////////////////////////////////////////////// //
//                                UTILITIES                                   //
// ////////////////////////////////////////////////////////////////////////// //

/**
 * Show a verbose message.
 */
function verbose() {
  if (getenv('SCRIPT_QUIET') != '1') {
    print call_user_func_array('sprintf', func_get_args()) . PHP_EOL;
  }
}

// ////////////////////////////////////////////////////////////////////////// //
//                                ENTRYPOINT                                  //
// ////////////////////////////////////////////////////////////////////////// //

ini_set('display_errors', 1);

if (PHP_SAPI != 'cli' || !empty($_SERVER['REMOTE_ADDR'])) {
  die('This script can be only ran from the command line.');
}

// Allow to skip the script run.
if (getenv('SCRIPT_RUN_SKIP') != 1) {
  try {
    $code = main($argv, $argc);
    if (is_null($code)) {
      throw new \Exception('Script exited without providing an exit code.');
    }
    exit($code);
  }
  catch (\Exception $exception) {
    print PHP_EOL . 'ERROR: ' . $exception->getMessage() . PHP_EOL;
    exit($exception->getCode() == 0 ? EXIT_ERROR : $exception->getCode());
  }
}
