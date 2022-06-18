<?php

namespace Drupal\cs_generated_content;

use Drupal\node\Entity\Node;

/**
 * Trait CsGeneratedContentVariationTrait.
 *
 * Trait to handle variations collection and processing.
 */
trait CsGeneratedContentVariationTrait {

  /**
   * Collect and return variations.
   *
   * @param string $callback_prefix
   *   Variations callback prefix. Used to distinguish between variations
   *   used in different generation cases.
   * @param string $path
   *   Optional path to the directory with variation files to include. If not
   *   provided, variation callbacks will be used from the currently available
   *   user-defined functions.
   *
   * @return array
   *   Array of variations.
   *
   * @throws \Exception
   *   If $path is not a directory or not readable.
   */
  public static function variations($callback_prefix, $path = NULL) {
    $variations = [];

    // If path was provided - include all files from it.
    if ($path) {
      // Check path to variation files.
      if (!file_exists($path) || !is_dir($path)) {
        throw new \Exception(sprintf('Variation directory path "%s" does not exist.', $path));
      }

      // Include all variation files. It is a good idea to prefix files with
      // sequential numbers to predict loading order.
      foreach (glob($path . DIRECTORY_SEPARATOR . '*.inc') as $filename) {
        require_once $filename;
      }
    }

    // Find all callbacks, excluding post-process callback.
    $postprocess_callback = $callback_prefix . 'post_process';
    $functions = get_defined_functions();
    $functions = $functions['user'];
    $callbacks = array_filter($functions, function ($value) use ($callback_prefix, $postprocess_callback) {
      return strpos($value, $callback_prefix) === 0 && $value != $postprocess_callback;
    });

    // Collect variations from callbacks.
    foreach ($callbacks as $function) {
      if (is_callable($function)) {
        $return = call_user_func($function);
        if (!empty($return) && is_array($return)) {
          $variations = array_merge($variations, $return);
        }
      }
    }

    // Call post-processing callback, if exists. It is a good idea to place it
    // into common helper file that will be required first (e.g. 01.helper.inc).
    if ($postprocess_callback && is_callable($postprocess_callback)) {
      $variations = call_user_func($postprocess_callback, $variations);
    }

    return $variations;
  }

  /**
   * Create a node object from the variation.
   *
   * @param string $bundle
   *   Node bundle.
   * @param mixed $variation
   *   Variation value.
   * @param int $variation_idx
   *   Variation index.
   * @param callable $postprocess_callback
   *   Optional post-process callback. Used to additionally enhance created node
   *   with field values.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\node\Entity\Node
   *   Created unsaved node object.
   */
  public static function variationCreateNode($bundle, $variation, $variation_idx, callable $postprocess_callback = NULL) {
    $node = Node::create([
      'type' => $bundle,
      'title' => 'Node title from variation',
      'status' => $variation['status'],
      'moderation_state' => $variation['status'] ? 'published' : 'draft',
    ]);

    // Process variation using a post-processor, if defined.
    // Post-processors should do at least 2 checks for every field:
    // - the field in variation was proved,
    // - the Drupal field exists on the entity.
    if ($postprocess_callback && is_callable($postprocess_callback)) {
      call_user_func($postprocess_callback, $node, $variation, $variation_idx);
    }

    return $node;
  }

}
