<?php

/**
 * @file
 * Update Drupal site configuration from provided source.
 *
 * Environment variables:
 * - SCRIPT_QUIET: Set to '1' to suppress verbose messages.
 * - SCRIPT_RUN_SKIP: Set to '1' to skip running of the script. Useful when
 *   unit-testing or requiring this file from other files.
 *
 * Usage:
 * @code
 * php update_config.php path/to/src/config
 *
 * php update_config.php path/to/src/config path/to/dst/config exclude_config.txt
 * @endcode
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
 * Defines error level to be reported as an error.
 */
define('ERROR_LEVEL', E_USER_WARNING);

/**
 * Main functionality.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function main(array $argv, $argc) {
  if (in_array($argv[1] ?? NULL, ['--help', '-help', '-h', '-?'])) {
    print_help();

    return EXIT_SUCCESS;
  }

  // Show help if not enough or more than required arguments.
  if ($argc < 2 || $argc > 4) {
    print_help();

    return EXIT_ERROR;
  }

  $src_config_dir = trim($argv[1]) ?: '';
  if (empty($src_config_dir) || !is_readable($src_config_dir) || !is_dir($src_config_dir)) {
    throw new \RuntimeException(sprintf('Source configuration directory %s is not readable.', $src_config_dir));
  }

  $dst_config_dir = trim($argv[2]) ?: 'config/default';
  if (empty($dst_config_dir) || !is_readable($dst_config_dir) || !is_dir($dst_config_dir)) {
    throw new \RuntimeException(sprintf('Destination configuration directory %s is not readable.', $dst_config_dir));
  }

  // File with configuration exclusion.
  // Entries in this file are used to consider the configuration as custom and
  // will be unchanged. Other entries that are not in this file or a source
  // config will be considered as obsolete and will be removed from the
  // resulting config.
  $config_exclude_file = $argv[3] ?? NULL;
  if (!empty($config_exclude_file) && !is_readable($config_exclude_file)) {
    throw new \RuntimeException(sprintf('Configuration exclusion file %s is not readable.', $config_exclude_file));
  }

  print "==> Started updating site config from $src_config_dir to $dst_config_dir." . PHP_EOL;

  print '  > Collecting source and destination configurations.' . PHP_EOL;
  $src_configs = collect_configs($src_config_dir);
  $dst_configs = collect_configs($dst_config_dir);
  $excluded_configs = $config_exclude_file ? collect_excluded_configs($config_exclude_file, $dst_configs) : [];

  print '  > Calculating configuration differences.' . PHP_EOL;
  print '    * Preserved (identical)' . PHP_EOL;
  print '    # Preserved (excluded)' . PHP_EOL;
  print '    + Added' . PHP_EOL;
  print '    - Removed' . PHP_EOL;
  print '    ^ Updated' . PHP_EOL;
  print '  ----------------------------------------' . PHP_EOL;
  $info = calc_config_diffs($src_configs, $dst_configs, $excluded_configs);
  print '  ----------------------------------------' . PHP_EOL;

  print '  > Processing configuration files.' . PHP_EOL;
  process_configs($dst_config_dir, $info);

  print PHP_EOL . "==> Finished updating site config in $dst_config_dir from $src_config_dir." . PHP_EOL;
  print_footer($info);

  return EXIT_SUCCESS;
}

/**
 * Print help.
 *
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
function print_help() {
  $script_name = basename(__FILE__);
  print <<<EOF
Site configuration updater
--------------------------

Arguments:
  path/to/src/config        The source configuration directory.
  path/to/dst/config        The existing configuration directory. Optional.
                            Defaults to 'config/default'.
  path/to/exlcude/file      Path to a file with excluded configurations.
                            One configuration per line.
                            File globbing wildcards and comments starting
                            with '#' are supported.

Options:
  --help                    This help.

Examples:
  php ${script_name} path/to/src/config

  php ${script_name} path/to/src/config path/to/dst/config exclude_config.txt

EOF;
  print PHP_EOL;
}

/**
 * Collect configuration files from a directory.
 *
 * @param string $dir
 *   Directory to collect configuration files from. If the directory has
 *   'install' or `optional` sub-directories - the configurations will be
 *   collected from there instead.
 *
 * @return array[]
 *   Array with 'install' and 'optional' as keys and an array of config arrays
 *   as values:
 *   - key is a filename without path.
 *   - value is a file path to the file.
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function collect_configs($dir) {
  $files = [];

  if (file_exists($dir . DIRECTORY_SEPARATOR . 'install')) {
    foreach (glob($dir . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . '*.yml') as $path) {
      $files['install'][basename($path)] = $path;
    }
  }
  else {
    foreach (glob($dir . DIRECTORY_SEPARATOR . '*.yml') as $path) {
      $files[basename($path)] = $path;
    }
  }

  foreach (glob($dir . DIRECTORY_SEPARATOR . 'optional' . DIRECTORY_SEPARATOR . '*.yml') as $path) {
    $files['optional'][basename($path)] = $path;
  }

  return $files;
}

/**
 * Collect excluded configs.
 *
 * @param string $exclude_file
 *   Path to a file with excluded configuration definitions. File may contain
 *   config definitions as a full file name with extension or just a config
 *   name, may contain wildcards defined by '*' and comments starting with '#'.
 * @param array $configs
 *   Array of configurations to collect the config from.
 *
 * @return array
 *   Array of excluded configuration items with file name as a key and a full
 *   path to a file as a value.
 */
function collect_excluded_configs($exclude_file, array $configs) {
  $configs_copy = $configs;

  $content = file_get_contents($exclude_file);
  $patterns = explode(PHP_EOL, $content);
  $patterns = array_filter($patterns, function ($v) {
    // Remove empty lines and comments.
    return !empty($v) && strpos($v, '#') === FALSE;
  });

  foreach ($patterns as $pattern) {
    foreach (array_keys($configs_copy) as $file) {
      // Allow using full file name or just a config name.
      $name = str_replace(['.yml', '.yaml'], '', $file);
      if (fnmatch_portable($pattern, $file)) {
        unset($configs_copy[$file]);
      }
      elseif (fnmatch_portable($pattern, $name)) {
        unset($configs_copy[$file]);
      }
    }
  }

  return array_diff_key($configs, $configs_copy);
}

/**
 * Calculate configuration differences.
 *
 * @param array $src_configs
 *   Array of the source configs.
 * @param array $dst_configs
 *   Array of the destination configs.
 * @param array $excluded_configs
 *   Array of excluded configs.
 *
 * @return array
 *   Array with config as key and the following values:
 *   - TRUE - preserve file
 *   - FALSE - remove file
 *   - string - add or replace a file
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function calc_config_diffs(array $src_configs, array $dst_configs, array $excluded_configs = []) {
  $info = [];

  // If excluded configs were provided - collect all excluded configs
  // matching definitions in the file and mark them as preserved (with
  // boolean TRUE).
  if (!empty($excluded_configs)) {
    $info = array_combine(array_keys($excluded_configs), array_fill(0, count($excluded_configs), TRUE));
  }

  // Normalise $src_configs to always have 'install' key.
  $src_configs['install'] = $src_configs['install'] ?? $src_configs;

  foreach (array_keys($dst_configs) as $filename) {
    if (array_key_exists($filename, $info)) {
      verbose('    %s %s', '#', $filename);
      continue;
    }

    if (array_key_exists($filename, $src_configs['install'])) {
      if (yaml_files_are_identical($dst_configs[$filename], $src_configs['install'][$filename])) {
        $info[$filename] = TRUE;
        verbose('    %s %s', '*', $filename);
        continue;
      }

      $info[$filename] = $src_configs['install'][$filename];
      verbose('    %s %s', '^', $filename);
      continue;
    }

    // File is not in the src config.
    $info[$filename] = FALSE;
    verbose('    %s %s', '-', $filename);
  }

  // Missing src 'install' configs in the dst config should be added.
  $added_configs = array_diff_key($src_configs['install'], $info);
  // Missing src 'optional' configs in the dst config should be added, but only
  // if they do not exist in the dst site configs. Using array_filter to remove
  // entries scheduled for deletion.
  $added_configs += array_diff_key($src_configs['optional'] ?? [], array_filter($info));
  array_walk($added_configs, function ($value, $filename) {
    verbose('    %s %s', '+', $filename);
  });

  $info = $added_configs + $info;

  return $info;
}

/**
 * Process configurations.
 *
 * @param string $config_dir
 *   Configuration directory.
 * @param array $info
 *   Information array to process. Each item can have one of the following
 *   types:
 *   - FALSE: Delete file.
 *   - TRUE: Preserve file.
 *   - string: Overwrite file with a file specified in the value.
 */
function process_configs($config_dir, array $info) {
  foreach ($info as $filename => $path) {
    $dst = $config_dir . DIRECTORY_SEPARATOR . $filename;
    if (is_string($path)) {
      copy($path, $dst);
    }
    elseif ($path === FALSE) {
      unlink($dst);
    }
  }
}

/**
 * Compare contents of 2 YAML files.
 *
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function yaml_files_are_identical($file1, $file2) {
  $yaml = new Yaml();

  $file1_yaml = $yaml->load($file1);
  $file2_yaml = $yaml->load($file2);

  unset($file1_yaml['_core']);
  unset($file2_yaml['_core']);
  unset($file1_yaml['uuid']);
  unset($file2_yaml['uuid']);

  ksortr($file1_yaml);
  ksortr($file2_yaml);

  return json_encode($file1_yaml) == json_encode($file2_yaml);
}

/**
 * Print footer.
 */
function print_footer($info) {
  $processed_num = count($info);

  $identical_num = count(array_filter($info, function ($v) {
    return $v === TRUE;
  }));

  $diff_num = count(array_filter($info, function ($v) {
    return is_string($v);
  }));

  $obsolete_num = count(array_filter($info, function ($v) {
    return $v === FALSE;
  }));

  print <<<EOF
    Processed: $processed_num
    Identical: $identical_num
    Different: $diff_num
    Obsolete:  $obsolete_num

Next steps:
 - Review changes in site configuration directory.
 - Restore UUIDs and core hash in new and updated config files, if requried.
 - Rebuild site with new configs. Simple config import may not be sufficient.
 - Re-export configuration to add/update UUIDs and core hashes were needed.
 - Commit changes.

EOF;
  print PHP_EOL;
}

// ////////////////////////////////////////////////////////////////////////// //
//                                UTILITIES                                   //
// ////////////////////////////////////////////////////////////////////////// //

/**
 * Portable implementation of fnmatch.
 */
function fnmatch_portable($pattern, $string) {
  $replacements = ['\*' => '.*', '\?' => '.'];

  return preg_match("#^" . strtr(preg_quote($pattern, '#'), $replacements) . "$#i", $string);
}

/**
 * Recursively sort arrays by key.
 */
function ksortr(&$array) {
  foreach ($array as &$value) {
    if (is_array($value)) {
      ksortr($value);
    }
  }
  ksort($array);
}

// Yaml loader class.
// @formatter:off
// phpcs:disable
// @see https://github.com/eriknyk/Yaml/blob/master/Yaml.php
// The MIT License
//
// Copyright (c) 2012 Erik Amaru Ortiz
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
/**
 * @SuppressWarnings(PHPMD)
 */
class Yaml { const REMPTY = "\0\x0\x0\x0\x0"; public $settingDumpForceQuotes = false; public $settingUseSyckIsPossible = false; private $dumpIndent; private $dumpWordWrap; private $containsGroupAnchor = false; private $containsGroupAlias = false; private $path; private $result; private $LiteralPlaceHolder = "\137\x5f\x5f\131\101\x4d\114\137\114\x69\x74\x65\162\x61\154\x5f\102\154\157\x63\x6b\x5f\x5f\x5f"; private $SavedGroups = array(); private $indent; private $delayedPath = array(); public $nodeId; public function __construct($file = '') { if (!empty($file)) { $this->load($file); } } public function load($file) { return $this->loadWithSource($this->loadFromFile($file)); } public function loadString($yamlContent) { return $this->loadWithSource($this->loadFromString($yamlContent)); } public function loadFile($file) { return $this->load($file); } public function dump($array, $indent = false, $wordwrap = false) { if ($indent === false or !is_numeric($indent)) { $this->dumpIndent = 2; } else { $this->dumpIndent = $indent; } if ($wordwrap === false or !is_numeric($wordwrap)) { $this->dumpWordWrap = 40; } else { $this->dumpWordWrap = $wordwrap; } $string = "\x2d\55\x2d\xa"; if ($array) { $array = (array) $array; $previous_key = -1; foreach ($array as $key => $value) { if (!isset($first_key)) { $first_key = $key; } $string .= $this->yamlize($key, $value, 0, $previous_key, $first_key, $array); $previous_key = $key; } } return $string; } private function yamlize($key, $value, $indent, $previous_key = -1, $first_key = 0, $source_array = null) { if (is_array($value)) { if (empty($value)) { return $this->dumpNode($key, array(), $indent, $previous_key, $first_key, $source_array); } $string = $this->dumpNode($key, self::REMPTY, $indent, $previous_key, $first_key, $source_array); $indent += $this->dumpIndent; $string .= $this->yamlizeArray($value, $indent); } elseif (!is_array($value)) { $string = $this->dumpNode($key, $value, $indent, $previous_key, $first_key, $source_array); } return $string; } private function yamlizeArray($array, $indent) { if (is_array($array)) { $string = ''; $previous_key = -1; foreach ($array as $key => $value) { if (!isset($first_key)) { $first_key = $key; } $string .= $this->yamlize($key, $value, $indent, $previous_key, $first_key, $array); $previous_key = $key; } return $string; } else { return false; } } private function dumpNode($key, $value, $indent, $previous_key = -1, $first_key = 0, $source_array = null) { if (is_string($value) && (strpos($value, "\12") !== false || strpos($value, "\x3a\x20") !== false || strpos($value, "\x2d\x20") !== false || strpos($value, "\x2a") !== false || strpos($value, "\43") !== false || strpos($value, "\74") !== false || strpos($value, "\76") !== false || strpos($value, "\x20\40") !== false || strpos($value, "\x5b") !== false || strpos($value, "\x5d") !== false || strpos($value, "\173") !== false || strpos($value, "\x7d") !== false || strpos($value, "\x26") !== false || strpos($value, "\47") !== false || strpos($value, "\x21") === 0 || substr($value, -1, 1) == "\72")) { $value = $this->doLiteralBlock($value, $indent); } else { $value = $this->doFolding($value, $indent); } if ($value === array()) { $value = "\133\40\x5d"; } if (in_array($value, array("\164\162\165\x65", "\x54\122\125\x45", "\146\141\x6c\163\145", "\106\x41\114\x53\x45", "\171", "\131", "\156", "\116", "\x6e\x75\154\154", "\x4e\x55\114\114"), true)) { $value = $this->doLiteralBlock($value, $indent); } if (trim($value) != $value) { $value = $this->doLiteralBlock($value, $indent); } if (is_bool($value)) { $value = $value ? "\164\162\165\145" : "\146\x61\154\163\145"; } if ($value === null) { $value = "\x6e\x75\x6c\154"; } if ($value === "\x27" . self::REMPTY . "\47") { $value = null; } $spaces = str_repeat("\40", $indent); if (is_array($source_array) && array_keys($source_array) === range(0, count($source_array) - 1)) { $string = $spaces . "\55\x20" . $value . "\xa"; } else { if (strpos($key, "\x3a") !== false || strpos($key, "\x23") !== false) { $key = "\42" . $key . "\42"; } $string = rtrim($spaces . $key . "\72\x20" . $value) . "\12"; } return $string; } private function doLiteralBlock($value, $indent) { if ($value === "\xa") { return "\x5c\x6e"; } if (strpos($value, "\xa") === false && strpos($value, "\x27") === false) { return sprintf("\x27\45\x73\47", $value); } if (strpos($value, "\12") === false && strpos($value, "\42") === false) { return sprintf("\42\x25\x73\42", $value); } $exploded = explode("\12", $value); $newValue = "\174"; $indent += $this->dumpIndent; $spaces = str_repeat("\40", $indent); foreach ($exploded as $line) { $newValue .= "\12" . $spaces . $line; } return $newValue; } private function doFolding($value, $indent) { if ($this->dumpWordWrap !== 0 && is_string($value) && strlen($value) > $this->dumpWordWrap) { $indent += $this->dumpIndent; $indent = str_repeat("\40", $indent); $wrapped = wordwrap($value, $this->dumpWordWrap, "\12{$indent}"); $value = "\x3e\xa" . $indent . $wrapped; } else { if ($this->settingDumpForceQuotes && is_string($value) && $value !== self::REMPTY) { $value = "\42" . $value . "\x22"; } } return $value; } private function loadWithSource($Source) { if (empty($Source)) { return array(); } if ($this->settingUseSyckIsPossible && function_exists("\x73\171\143\153\137\154\157\141\144")) { $array = syck_load(implode('', $Source)); return is_array($array) ? $array : array(); } $this->path = array(); $this->result = array(); $cnt = count($Source); for ($i = 0; $i < $cnt; $i++) { $line = $Source[$i]; $this->indent = strlen($line) - strlen(ltrim($line)); $tempPath = $this->getParentPathByIndent($this->indent); $line = self::stripIndent($line, $this->indent); if (self::isComment($line)) { continue; } if (self::isEmpty($line)) { continue; } $this->path = $tempPath; $literalBlockStyle = self::startsLiteralBlock($line); if ($literalBlockStyle) { $line = rtrim($line, $literalBlockStyle . "\40\12"); $literalBlock = ''; $line .= $this->LiteralPlaceHolder; $literal_block_indent = strlen($Source[$i + 1]) - strlen(ltrim($Source[$i + 1])); while (++$i < $cnt && $this->literalBlockContinues($Source[$i], $this->indent)) { $literalBlock = $this->addLiteralLine($literalBlock, $Source[$i], $literalBlockStyle, $literal_block_indent); } $i--; } while (++$i < $cnt && self::greedilyNeedNextLine($line)) { $line = rtrim($line, "\40\12\x9\xd") . "\x20" . ltrim($Source[$i], "\x20\11"); } $i--; if (strpos($line, "\43")) { if (strpos($line, "\42") === false && strpos($line, "\x27") === false) { $line = preg_replace("\57\134\x73\x2b\43\x28\x2e\x2b\51\44\57", '', $line); } } $lineArray = $this->parseLine($line); if ($literalBlockStyle) { $lineArray = $this->revertLiteralPlaceHolder($lineArray, $literalBlock); } $this->addArray($lineArray, $this->indent); foreach ($this->delayedPath as $indent => $delayedPath) { $this->path[$indent] = $delayedPath; } $this->delayedPath = array(); } return $this->result; } private function loadFromFile($file) { if (!file_exists($file)) { throw new Exception("\105\162\162\157\x72\x3a\40\171\x61\x6d\154\x20\146\151\154\x65\x20\x64\x6f\145\x73\40\156\x6f\x74\x20\x65\x78\x69\x73\164\x3a\x20{$file}"); } return file($file); } private function loadFromString($input) { $lines = explode("\xa", $input); foreach ($lines as $k => $_) { $lines[$k] = rtrim($_, "\xd"); } return $lines; } private function parseLine($line) { if (!$line) { return array(); } $line = trim($line); if (!$line) { return array(); } $array = array(); $group = $this->nodeContainsGroup($line); if ($group) { $this->addGroup($line, $group); $line = $this->stripGroup($line, $group); } if ($this->startsMappedSequence($line)) { return $this->returnMappedSequence($line); } if ($this->startsMappedValue($line)) { return $this->returnMappedValue($line); } if ($this->isArrayElement($line)) { return $this->returnArrayElement($line); } if ($this->isPlainArray($line)) { return $this->returnPlainArray($line); } return $this->returnKeyValuePair($line); } private function toType($value) { if ($value === '') { return null; } $first_character = $value[0]; $last_character = substr($value, -1, 1); $is_quoted = false; do { if (!$value) { break; } if ($first_character != "\x22" && $first_character != "\x27") { break; } if ($last_character != "\42" && $last_character != "\x27") { break; } $is_quoted = true; } while (0); if ($is_quoted) { return strtr(substr($value, 1, -1), array("\134\42" => "\42", "\x27\47" => "\47", "\134\47" => "\47")); } if (strpos($value, "\40\43") !== false && !$is_quoted) { $value = preg_replace("\x2f\x5c\163\x2b\x23\x28\56\x2b\x29\x24\57", '', $value); } if (!$is_quoted) { $value = str_replace("\134\156", "\xa", $value); } if ($first_character == "\x5b" && $last_character == "\x5d") { $innerValue = trim(substr($value, 1, -1)); if ($innerValue === '') { return array(); } $explode = $this->inlineEscape($innerValue); $value = array(); foreach ($explode as $v) { $value[] = $this->toType($v); } return $value; } if (strpos($value, "\72\40") !== false && $first_character != "\173") { $array = explode("\72\x20", $value); $key = trim($array[0]); array_shift($array); $value = trim(implode("\72\40", $array)); $value = $this->toType($value); return array($key => $value); } if ($first_character == "\x7b" && $last_character == "\x7d") { $innerValue = trim(substr($value, 1, -1)); if ($innerValue === '') { return array(); } $explode = $this->inlineEscape($innerValue); $array = array(); foreach ($explode as $v) { $SubArr = $this->toType($v); if (empty($SubArr)) { continue; } if (is_array($SubArr)) { $array[key($SubArr)] = $SubArr[key($SubArr)]; continue; } $array[] = $SubArr; } return $array; } if ($value == "\156\x75\154\154" || $value == "\116\125\x4c\114" || $value == "\116\x75\x6c\x6c" || $value == '' || $value == "\176") { return null; } if (is_numeric($value) && preg_match("\x2f\136\50\x2d\x7c\51\x5b\x31\55\71\135\53\133\60\x2d\71\x5d\x2a\44\x2f", $value)) { $intvalue = (int) $value; if ($intvalue != PHP_INT_MAX) { $value = $intvalue; } return $value; } if (in_array($value, array("\164\x72\x75\x65", "\x6f\x6e", "\53", "\x79\145\x73", "\x79", "\124\162\x75\145", "\x54\122\x55\x45", "\x4f\x6e", "\117\116", "\131\105\x53", "\131\145\x73", "\131"))) { return true; } if (in_array(strtolower($value), array("\x66\x61\x6c\163\145", "\157\x66\146", "\55", "\x6e\157", "\x6e"))) { return false; } if (is_numeric($value)) { if ($value === "\60") { return 0; } if (rtrim($value, 0) === $value) { $value = (double) $value; } return $value; } return $value; } private function inlineEscape($inline) { $seqs = array(); $maps = array(); $saved_strings = array(); $regex = "\x2f\x28\77\x3a\50\x22\51\x7c\50\x3f\x3a\47\x29\51\x28\x28\x3f\50\x31\x29\133\136\42\135\53\174\133\x5e\x27\135\53\51\51\50\77\50\x31\x29\42\x7c\47\51\57"; if (preg_match_all($regex, $inline, $strings)) { $saved_strings = $strings[0]; $inline = preg_replace($regex, "\131\101\x4d\x4c\x53\164\x72\151\x6e\x67", $inline); } unset($regex); $i = 0; do { while (preg_match("\57\134\x5b\50\x5b\136\x7b\x7d\x5c\133\x5c\135\x5d\x2b\51\x5c\x5d\x2f\x55", $inline, $matchseqs)) { $seqs[] = $matchseqs[0]; $inline = preg_replace("\x2f\x5c\133\50\133\136\173\175\134\133\x5c\135\135\x2b\x29\134\135\x2f\125", "\131\x41\x4d\114\x53\145\x71" . (count($seqs) - 1) . "\163", $inline, 1); } while (preg_match("\x2f\x7b\50\133\136\134\x5b\134\135\173\x7d\x5d\53\x29\175\x2f\x55", $inline, $matchmaps)) { $maps[] = $matchmaps[0]; $inline = preg_replace("\x2f\x7b\50\x5b\136\x5c\x5b\x5c\135\x7b\175\135\x2b\x29\x7d\57\125", "\x59\x41\x4d\x4c\x4d\x61\x70" . (count($maps) - 1) . "\x73", $inline, 1); } if ($i++ >= 10) { break; } } while (strpos($inline, "\x5b") !== false || strpos($inline, "\x7b") !== false); $explode = explode("\x2c\x20", $inline); $stringi = 0; $i = 0; while (1) { if (!empty($seqs)) { foreach ($explode as $key => $value) { if (strpos($value, "\131\x41\x4d\114\123\145\x71") !== false) { foreach ($seqs as $seqk => $seq) { $explode[$key] = str_replace("\x59\101\115\x4c\x53\145\161" . $seqk . "\x73", $seq, $value); $value = $explode[$key]; } } } } if (!empty($maps)) { foreach ($explode as $key => $value) { if (strpos($value, "\131\x41\x4d\x4c\x4d\141\x70") !== false) { foreach ($maps as $mapk => $map) { $explode[$key] = str_replace("\131\101\115\114\x4d\x61\x70" . $mapk . "\163", $map, $value); $value = $explode[$key]; } } } } if (!empty($saved_strings)) { foreach ($explode as $key => $value) { while (strpos($value, "\131\x41\x4d\x4c\x53\x74\162\151\x6e\x67") !== false) { $explode[$key] = preg_replace("\57\x59\101\115\114\123\164\x72\151\156\x67\57", $saved_strings[$stringi], $value, 1); unset($saved_strings[$stringi]); ++$stringi; $value = $explode[$key]; } } } $finished = true; foreach ($explode as $key => $value) { if (strpos($value, "\x59\101\x4d\x4c\x53\145\x71") !== false) { $finished = false; break; } if (strpos($value, "\x59\x41\x4d\x4c\x4d\141\x70") !== false) { $finished = false; break; } if (strpos($value, "\131\101\115\114\x53\164\x72\151\x6e\x67") !== false) { $finished = false; break; } } if ($finished) { break; } $i++; if ($i > 10) { break; } } return $explode; } private function literalBlockContinues($line, $lineIndent) { if (!trim($line)) { return true; } if (strlen($line) - strlen(ltrim($line)) > $lineIndent) { return true; } return false; } private function referenceContentsByAlias($alias) { do { if (!isset($this->SavedGroups[$alias])) { echo "\x42\141\144\40\147\x72\157\165\160\40\x6e\141\x6d\145\72\40{$alias}\56"; break; } $groupPath = $this->SavedGroups[$alias]; $value = $this->result; foreach ($groupPath as $k) { $value = $value[$k]; } } while (false); return $value; } private function addArrayInline($array, $indent) { $CommonGroupPath = $this->path; if (empty($array)) { return false; } foreach ($array as $k => $_) { $this->addArray(array($k => $_), $indent); $this->path = $CommonGroupPath; } return true; } private function addArray($incoming_data, $incoming_indent) { if (count($incoming_data) > 1) { return $this->addArrayInline($incoming_data, $incoming_indent); } $key = key($incoming_data); $value = isset($incoming_data[$key]) ? $incoming_data[$key] : null; if ($key === "\137\137\x21\131\x41\x4d\x4c\x5a\145\162\157") { $key = "\x30"; } if ($incoming_indent == 0 && !$this->containsGroupAlias && !$this->containsGroupAnchor) { if ($key || $key === '' || $key === "\x30") { $this->result[$key] = $value; } else { $this->result[] = $value; end($this->result); $key = key($this->result); } $this->path[$incoming_indent] = $key; return; } $history = array(); $history[] = $_arr = $this->result; foreach ($this->path as $k) { $history[] = $_arr = $_arr[$k]; } if ($this->containsGroupAlias) { $value = $this->referenceContentsByAlias($this->containsGroupAlias); $this->containsGroupAlias = false; } if (is_string($key) && $key == "\74\74") { if (!is_array($_arr)) { $_arr = array(); } $_arr = array_merge($_arr, $value); } elseif ($key || $key === '' || $key === "\x30") { if (!is_array($_arr)) { $_arr = array($key => $value); } else { $_arr[$key] = $value; } } else { if (!is_array($_arr)) { $_arr = array($value); $key = 0; } else { $_arr[] = $value; end($_arr); $key = key($_arr); } } $reverse_path = array_reverse($this->path); $reverse_history = array_reverse($history); $reverse_history[0] = $_arr; $cnt = count($reverse_history) - 1; for ($i = 0; $i < $cnt; $i++) { $reverse_history[$i + 1][$reverse_path[$i]] = $reverse_history[$i]; } $this->result = $reverse_history[$cnt]; $this->path[$incoming_indent] = $key; if ($this->containsGroupAnchor) { $this->SavedGroups[$this->containsGroupAnchor] = $this->path; if (is_array($value)) { $k = key($value); if (!is_int($k)) { $this->SavedGroups[$this->containsGroupAnchor][$incoming_indent + 2] = $k; } } $this->containsGroupAnchor = false; } } private static function startsLiteralBlock($line) { $lastChar = substr(trim($line), -1); if ($lastChar != "\76" && $lastChar != "\x7c") { return false; } if ($lastChar == "\174") { return $lastChar; } if (preg_match("\43\x3c\56\52\x3f\76\44\x23", $line)) { return false; } return $lastChar; } private static function greedilyNeedNextLine($line) { $line = trim($line); if (!strlen($line)) { return false; } if (substr($line, -1, 1) == "\x5d") { return false; } if ($line[0] == "\x5b") { return true; } if (preg_match("\x23\136\x5b\x5e\x3a\135\53\x3f\72\134\x73\x2a\134\133\43", $line)) { return true; } return false; } private function addLiteralLine($literalBlock, $line, $literalBlockStyle, $indent = -1) { $line = self::stripIndent($line, $indent); if ($literalBlockStyle !== "\174") { $line = self::stripIndent($line); } $line = rtrim($line, "\15\12\x9\x20") . "\xa"; if ($literalBlockStyle == "\174") { return $literalBlock . $line; } if (strlen($line) == 0) { return rtrim($literalBlock, "\40") . "\xa"; } if ($line == "\12" && $literalBlockStyle == "\76") { return rtrim($literalBlock, "\40\x9") . "\12"; } if ($line != "\12") { $line = trim($line, "\15\xa\40") . "\x20"; } return $literalBlock . $line; } public function revertLiteralPlaceHolder($lineArray, $literalBlock) { foreach ($lineArray as $k => $_) { if (is_array($_)) { $lineArray[$k] = $this->revertLiteralPlaceHolder($_, $literalBlock); } elseif (substr($_, -1 * strlen($this->LiteralPlaceHolder)) == $this->LiteralPlaceHolder) { $lineArray[$k] = rtrim($literalBlock, "\40\15\xa"); } } return $lineArray; } private static function stripIndent($line, $indent = -1) { if ($indent == -1) { $indent = strlen($line) - strlen(ltrim($line)); } return substr($line, $indent); } private function getParentPathByIndent($indent) { if ($indent == 0) { return array(); } $linePath = $this->path; do { end($linePath); $lastIndentInParentPath = key($linePath); if ($indent <= $lastIndentInParentPath) { array_pop($linePath); } } while ($indent <= $lastIndentInParentPath); return $linePath; } private function clearBiggerPathValues($indent) { if ($indent == 0) { $this->path = array(); } if (empty($this->path)) { return true; } foreach ($this->path as $k => $_) { if ($k > $indent) { unset($this->path[$k]); } } return true; } private static function isComment($line) { if (!$line) { return false; } if ($line[0] == "\x23") { return true; } if (trim($line, "\x20\15\12\11") == "\x2d\55\55") { return true; } return false; } private static function isEmpty($line) { return trim($line) === ''; } private function isArrayElement($line) { if (!$line) { return false; } if ($line[0] != "\x2d") { return false; } if (strlen($line) > 3) { if (substr($line, 0, 3) == "\x2d\x2d\55") { return false; } } return true; } private function isHashElement($line) { return strpos($line, "\72"); } private function isLiteral($line) { if ($this->isArrayElement($line)) { return false; } if ($this->isHashElement($line)) { return false; } return true; } private static function unquote($value) { if (!$value) { return $value; } if (!is_string($value)) { return $value; } if ($value[0] == "\47") { return trim($value, "\x27"); } if ($value[0] == "\42") { return trim($value, "\42"); } return $value; } private function startsMappedSequence($line) { return $line[0] == "\55" && substr($line, -1, 1) == "\x3a"; } private function returnMappedSequence($line) { $array = array(); $key = self::unquote(trim(substr($line, 1, -1))); $array[$key] = array(); $this->delayedPath = array(strpos($line, $key) + $this->indent => $key); return array($array); } private function returnMappedValue($line) { $array = array(); $key = self::unquote(trim(substr($line, 0, -1))); $array[$key] = ''; return $array; } private function startsMappedValue($line) { return substr($line, -1, 1) == "\72"; } private function isPlainArray($line) { return $line[0] == "\x5b" && substr($line, -1, 1) == "\135"; } private function returnPlainArray($line) { return $this->toType($line); } private function returnKeyValuePair($line) { $array = array(); $key = ''; if (strpos($line, "\x3a")) { if (($line[0] == "\x22" || $line[0] == "\47") && preg_match("\x2f\136\50\x5b\x22\47\x5d\x28\x2e\x2a\x29\133\x22\47\x5d\x28\x5c\163\51\52\x3a\x29\x2f", $line, $matches)) { $value = trim(str_replace($matches[1], '', $line)); $key = $matches[2]; } else { $explode = explode("\72", $line); $key = trim($explode[0]); array_shift($explode); $value = trim(implode("\x3a", $explode)); } $value = $this->toType($value); if ($key === "\x30") { $key = "\x5f\x5f\x21\x59\x41\x4d\x4c\x5a\x65\162\x6f"; } $array[$key] = $value; } else { $array = array($line); } return $array; } private function returnArrayElement($line) { if (strlen($line) <= 1) { return array(array()); } $array = array(); $value = trim(substr($line, 1)); $value = $this->toType($value); $array[] = $value; return $array; } private function nodeContainsGroup($line) { $symbolsForReference = "\x41\55\172\x30\x2d\71\x5f\x5c\x2d"; if (strpos($line, "\46") === false && strpos($line, "\52") === false) { return false; } if ($line[0] == "\x26" && preg_match("\57\x5e\50\x26\x5b" . $symbolsForReference . "\x5d\53\x29\57", $line, $matches)) { return $matches[1]; } if ($line[0] == "\x2a" && preg_match("\x2f\x5e\x28\134\52\x5b" . $symbolsForReference . "\x5d\x2b\x29\x2f", $line, $matches)) { return $matches[1]; } if (preg_match("\x2f\50\46\133" . $symbolsForReference . "\x5d\x2b\51\x24\x2f", $line, $matches)) { return $matches[1]; } if (preg_match("\57\x28\x5c\x2a\133" . $symbolsForReference . "\x5d\x2b\x24\51\x2f", $line, $matches)) { return $matches[1]; } if (preg_match("\43\x5e\134\163\52\x3c\x3c\134\x73\52\x3a\x5c\x73\52\50\134\52\133\136\134\163\135\53\x29\x2e\x2a\x24\x23", $line, $matches)) { return $matches[1]; } return false; } private function addGroup($line, $group) { if ($group[0] == "\x26") { $this->containsGroupAnchor = substr($group, 1); } if ($group[0] == "\x2a") { $this->containsGroupAlias = substr($group, 1); } } private function stripGroup($line, $group) { $line = trim(str_replace($group, '', $line)); return $line; } }
// @formatter:on

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

// Custom error handler to catch errors based on set ERROR_LEVEL.
set_error_handler(function ($severity, $message, $file, $line) {
  if (!(error_reporting() & $severity)) {
    // This error code is not included in error_reporting.
    return;
  }
  throw new ErrorException($message, 0, $severity, $file, $line);
});

// Allow to skip the script run.
if (getenv('SCRIPT_RUN_SKIP') != 1) {
  try {
    $code = main($argv, $argc);
    if (is_null($code)) {
      throw new \Exception('Script exited without providing an exit code.');
    }
    exit($code);
  }
  catch (\ErrorException $exception) {
    if ($exception->getSeverity() <= ERROR_LEVEL) {
      print PHP_EOL . 'RUNTIME ERROR: ' . $exception->getMessage() . PHP_EOL;
      exit($exception->getCode() == 0 ? EXIT_ERROR : $exception->getCode());
    }
  }
  catch (\Exception $exception) {
    print PHP_EOL . 'ERROR: ' . $exception->getMessage() . PHP_EOL;
    exit($exception->getCode() == 0 ? EXIT_ERROR : $exception->getCode());
  }
}
