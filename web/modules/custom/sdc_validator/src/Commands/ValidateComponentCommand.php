<?php

declare(strict_types=1);

namespace Drupal\sdc_validator\Commands;

use Drupal\Core\Plugin\Component;
use Drupal\Core\Render\Component\Exception\InvalidComponentException;
use Drupal\Core\Template\ComponentNodeVisitor;
use Drupal\Core\Template\TwigEnvironment;
use Drupal\Core\Theme\Component\ComponentValidator;
use Drupal\Core\Theme\ComponentPluginManager;
use Drush\Commands\DrushCommands;
use Symfony\Component\Yaml\Yaml;
use Twig\Error\Error;
use Twig\Node\Node;

/**
 * Validates SDC component definitions using Drupal core's ComponentValidator.
 *
 * @package Drupal\sdc_validator\Commands
 */
class ValidateComponentCommand extends DrushCommands {

  /**
   * Defines the component validator.
   */
  protected ComponentValidator $componentValidator;

  /**
   * Validates slots of a component.
   */
  protected ComponentNodeVisitor $componentSlotValidator;

  /**
   * {@inheritdoc}
   */
  public function __construct(protected ComponentPluginManager $componentPluginManager, protected TwigEnvironment $twig) {
    parent::__construct();
    $this->componentValidator = new ComponentValidator();
    $this->componentValidator->setValidator();
    $this->componentSlotValidator = new ComponentNodeVisitor($this->componentPluginManager);
  }

  /**
   * Validates all component definitions in a given path.
   *
   * @param string $components_path
   *   Path to the directory containing component folders.
   *
   * @command sdc_validator:validate
   * @usage drush sdc_validator:validate '<path to components>'
   * @usage drush sdc_validator:validate 'web/themes/custom/civictheme/components'
   */
  public function validateComponentDefinitions(string $components_path): void {
    if (!is_dir($components_path)) {
      throw new \Exception('❌ Components directory not found: ' . $components_path);
    }
    $this->output()->writeln(sprintf('🔍 Validating components in %s', $components_path));
    $component_path_parts = explode('/', $components_path);
    array_filter($component_path_parts);
    $component_base_identifier = $component_path_parts[count($component_path_parts) - 2] ?? NULL;
    if ($component_base_identifier === NULL) {
      throw new \Exception('❌ Cannot validate components that are not located in a theme or a module: ' . $components_path);
    }
    $component_files = [];
    $iterator = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator($components_path),
      \RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($iterator as $file) {
      if ($file->isFile() && $file->getExtension() === 'yml' && str_ends_with((string) $file->getFilename(), '.component.yml')) {
        $component_files[] = $file->getPathname();
      }
    }

    if (empty($component_files)) {
      throw new \Exception('❌ No component definition files found in: ' . $components_path);
    }

    $errors = [];
    $valid_count = 0;

    foreach ($component_files as $component_file) {
      try {
        $component_name = basename((string) $component_file, '.component.yml');
        $component_id = $component_base_identifier . ':' . $component_name;
        $component = $this->componentPluginManager->find($component_id);
        $template_path = $component->getTemplatePath();
        if ($template_path === NULL) {
          throw new \Exception(sprintf('❌ %s does not have a template.', $component_id));
        }
        $source = $this->twig->getLoader()->getSourceContext($template_path);
        try {
          // Need to load as a component.
          $node_tree = $this->twig->parse($this->twig->tokenize($source));
        }
        catch (Error $error) {
          throw new \Exception("❌ Error parsing twig file: " . $error->getMessage(), $error->getCode(), $error);
        }
        $this->validateSlots($component, $node_tree->getNode('blocks'));
        $definition = Yaml::parseFile($component_file);
        // Merge with additional required keys.
        $definition = array_merge(
          $definition,
          [
            'machineName' => $component_name,
            'extension_type' => 'theme',
            'id' => 'civictheme:' . $component_name,
            'library' => ['css' => ['component' => ['foo.css' => []]]],
            'path' => '',
            'provider' => 'civictheme',
            'template' => $component_name . '.twig',
            'group' => 'civictheme-group',
            'description' => 'CivicTheme component',
          ]
        );
        $this->validateComponentFile($definition);
        $valid_count++;
      }
      catch (\Exception $e) {
        $errors[] = [
          'file' => basename((string) $component_file),
          'error' => $e->getMessage(),
        ];
      }
    }

    // Display summary.
    if ($valid_count > 0) {
      $this->output()->writeln(sprintf("✅ %d components are valid", $valid_count));
    }
    if ($errors !== []) {
      $this->output()->writeln("Failed components:");
      foreach ($errors as $error) {
        $this->output()->writeln(sprintf("❌ %s - %s", $error['file'], $error['error']));
      }
      throw new \Exception("Component validation failed.");
    }
    $this->output()->writeln("✨ All components are valid");
  }

  /**
   * Validates a single component definition file.
   *
   * @param array $definition
   *   The component definition.
   *
   * @throws \Drupal\Core\Render\Component\Exception\InvalidComponentException
   */
  public function validateComponentFile(array $definition): void {
    $this->componentValidator->validateDefinition($definition, TRUE);
  }

  /**
   * Moved from \Drupal\Core\Template\ComponentNodeVisitor::validateSlots.
   *
   * Performs a cheap validation of the slots in the template.
   *
   * It validates them against the JSON Schema provided in the component
   * definition file and massaged in the ComponentMetadata class. We don't use
   * the JSON Schema validator because we just want to validate required and
   * undeclared slots. This cheap validation lets us validate during runtime
   * even in production.
   *
   * @throws \Drupal\Core\Render\Component\Exception\InvalidComponentException
   *   When the slots don't pass validation.
   *
   * @see \Drupal\Core\Template\ComponentNodeVisitor::validateSlots
   */
  protected function validateSlots(Component $component, Node $node): void {
    $metadata = $component->metadata;
    if (!$metadata->mandatorySchemas) {
      return;
    }
    $slot_definitions = $metadata->slots;
    $ids_available = array_keys($slot_definitions);
    $undocumented_ids = [];
    try {
      $it = $node->getIterator();
    }
    catch (\Exception) {
      return;
    }
    if ($it instanceof \SeekableIterator) {
      while ($it->valid()) {
        $provided_id = $it->key();
        if (!in_array($provided_id, $ids_available, TRUE)) {
          $undocumented_ids[] = $provided_id;
        }
        $it->next();
      }
    }
    // Now build the error message.
    $error_messages = [];
    if (!empty($undocumented_ids)) {
      $error_messages[] = sprintf(
        'We found an unexpected slot that is not declared: [%s]. Declare them in "%s.component.yml".',
        implode(', ', $undocumented_ids),
        $component->machineName
      );
    }
    if (!empty($error_messages)) {
      $message = implode("\n", $error_messages);
      throw new InvalidComponentException($message);
    }
  }

}
