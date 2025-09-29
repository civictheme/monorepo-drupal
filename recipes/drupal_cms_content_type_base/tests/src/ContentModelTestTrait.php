<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_content_type_base;

use Drupal\Core\Entity\Display\EntityDisplayInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\file\FileInterface;
use Drupal\layout_builder\Entity\LayoutEntityDisplayInterface;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Assert;

trait ContentModelTestTrait {

  /**
   * Asserts generic things about a content type add/edit form.
   *
   * @param string $node_type
   *   A content type ID.
   */
  protected function assertEditForm(string $node_type): void {
    assert($this instanceof BrowserTestBase);

    $account = $this->drupalCreateUser();
    $account->addRole('content_editor')->save();
    $this->drupalLogin($account);

    $this->drupalGet('/node/add/' . $node_type);
    $assert_session = $this->assertSession();
    // We should have permission to add content, as a content editor.
    $assert_session->statusCodeEquals(200);
    // No content type should expose a distinct summary field.
    $assert_session->fieldNotExists('Summary');
    // There is only one text format, so there shouldn't be any choice.
    $assert_session->fieldNotExists('Text format');
  }

  /**
   * Ensures that the file associated with a file entity, actually exists.
   *
   * @param string $uuid
   *   The UUID of a file entity.
   */
  protected function ensureFileExists(string $uuid): void {
    $file = $this->container->get(EntityRepositoryInterface::class)
      ->loadEntityByUuid('file', $uuid);
    Assert::assertInstanceOf(FileInterface::class, $file, "There is no file with UUID '$uuid'.");
    Assert::assertFileExists($file->getFileUri());
  }

  /**
   * Returns the weights of all components in a display.
   *
   * @param \Drupal\Core\Entity\Display\EntityDisplayInterface $display
   *   An entity display.
   *
   * @return int[]
   *   The weights of the display's components, keyed by name, and sorted.
   */
  protected function getComponentsInOrder(EntityDisplayInterface $display): array {
    if ($display instanceof LayoutEntityDisplayInterface && $display->isLayoutBuilderEnabled()) {    $fields = [];
      $components = [];

      foreach ($display->getSections() as $index => $section) {
        foreach ($section->getComponents() as $component) {
          $plugin_id = $component->getPluginId();

          if (str_starts_with($plugin_id, 'field_block:') || str_starts_with($plugin_id, 'extra_field_block:')) {
            Assert::assertSame(3, substr_count($plugin_id, ':'), "Section component plugin ID '$plugin_id' should have exactly 4 parts.");
            [,,, $name] = explode(':', $plugin_id);
            $components[$name] = $component->getWeight() + ($index * 100);
          }
        }
      }
    }
    else {
      $components = $display->getComponents();
      $components = array_combine(
        array_keys($components),
        array_column($components, 'weight'),
      );
    }
    asort($components);
    return $components;
  }

  /**
   * Asserts that fields shared by multiple displays, are in the same order.
   *
   * @param \Drupal\Core\Entity\Display\EntityDisplayInterface ...$displays
   *   A set of entity displays to compare.
   */
  protected function assertSharedFieldsInSameOrder(EntityDisplayInterface ...$displays): void {
    $all_components = array_map($this->getComponentsInOrder(...), $displays);
    $expected_order = array_keys(array_intersect_key(...$all_components));
    foreach ($displays as $display) {
      $this->assertFieldsInOrder($display, $expected_order);
    }
  }

  /**
   * Asserts relative order of the fields in an entity display.
   *
   * @param \Drupal\Core\Entity\Display\EntityDisplayInterface $display
   *   An entity display.
   * @param string[] $expected_order
   *   The fields that should be in the display, in their expected order.
   */
  protected function assertFieldsInOrder(EntityDisplayInterface $display, array $expected_order): void {
    $actual_order = array_keys($this->getComponentsInOrder($display));

    $missing_fields = array_diff($expected_order, $actual_order);
    Assert::assertEmpty($missing_fields, $display->getConfigDependencyName() . " is missing fields: " . implode(', ', $missing_fields));

    $actual_order = array_intersect($actual_order, $expected_order);
    $actual_order = array_values($actual_order);
    Assert::assertSame($expected_order, $actual_order);
  }

  protected function assertContentModel(array $content_model): void {
    assert($this instanceof BrowserTestBase);

    $editor = $this->drupalCreateUser();
    $editor->addRole('content_editor')->save();
    $this->drupalLogin($editor);

    $assert_session = $this->assertSession();
    foreach ($content_model as $node_type => $fields) {
      $this->drupalGet("/node/add/$node_type");
      $assert_session->statusCodeEquals(200);

      $field_definitions = $this->container->get(EntityFieldManagerInterface::class)
        ->getFieldDefinitions('node', $node_type);

      foreach ($fields as $field_name => $spec) {
        Assert::assertArrayHasKey($field_name, $field_definitions);
        $field = $field_definitions[$field_name];

        $storage = $field->getFieldStorageDefinition();
        Assert::assertSame($spec['type'], $storage->getType(), "Field $field_name does not have the expected type.");
        Assert::assertSame($spec['cardinality'], $storage->getCardinality(), "Field $field_name does not have the expected cardinality.");
        Assert::assertSame($spec['required'], $field->isRequired(), "Field $field_name does not have the expected required flag.");
        Assert::assertSame($spec['translatable'], $field->isTranslatable(), "Field $field_name does not have the expected translatability.");
        // The field label might be a TranslatableMarkup object, so use
        // assertEquals() here.
        Assert::assertEquals($spec['label'], $field->getLabel(), "Field $field_name does not have the expected label.");

        if (in_array($spec['input type'], ['text', 'email', 'tel', 'date'], TRUE)) {
          $selector = sprintf('input[name^="%s["]', $field_name);
          Assert::assertSame($spec['input type'], $assert_session->elementExists('css', $selector)->getAttribute('type'));
        }
        elseif ($spec['input type'] === 'tagify') {
          $assert_session->elementExists('css', sprintf('input[name=%s]', $field_name));
        }
        elseif ($spec['input type'] === 'textarea') {
          $assert_session->elementExists('css', sprintf('textarea[name^="%s["]', $field_name));
        }
        elseif ($spec['input type'] === 'media library') {
          $assert_session->hiddenFieldExists($field_name . '[media_library_selection]');
        }
        elseif ($spec['input type'] === 'wysiwyg') {
          $selector = sprintf('[name^="%s["][name$="[format]"]', $field_name);
          $assert_session->elementExists('css', $selector);
        }
        elseif ($spec['input type'] === 'address') {
          $selector = sprintf('[name^="%s["][name$="[address][country_code]"]', $field_name);
          $assert_session->elementExists('css', $selector);
        }
        else {
          $this->fail("Unknown input type for field $field_name.");
        }

        if ($spec['help text']) {
          $assert_session->pageTextContains($spec['help text']);
        }
      }
    }
  }

}
