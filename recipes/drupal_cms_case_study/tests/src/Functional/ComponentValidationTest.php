<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_case_study\Functional;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\drupal_cms_content_type_base\ContentModelTestTrait;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_case_study')]
#[IgnoreDeprecations]
class ComponentValidationTest extends BrowserTestBase {

  use ContentModelTestTrait;
  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $dir = realpath(__DIR__ . '/../../..');
    // The recipe should apply cleanly.
    $this->applyRecipe($dir);
    // Apply it again to prove that it is idempotent.
    $this->applyRecipe($dir);

    $this->ensureFileExists('47b880a9-c2a4-4ed6-844d-95c6d6677004');
    $this->ensureFileExists('831ca26d-590c-4f34-8545-379e9d77106f');
  }

  public function testEditForm(): void {
    $this->assertEditForm('case_study');
  }

  public function testContentModel(): void {
    /** @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface $display_repository */
    $display_repository = $this->container->get(EntityDisplayRepositoryInterface::class);

    $form_display = $display_repository->getFormDisplay('node', 'case_study');
    $this->assertFalse($form_display->isNew());
    $this->assertNull($form_display->getComponent('url_redirects'));
    $this->assertFieldsInOrder($form_display, [
      'title',
      'field_description',
      'field_case_study__client_name',
      'field_case_study__client_logo',
      'field_featured_image',
      'field_content',
      'field_case_study__client_link',
      'field_tags',
    ]);

    $default_display = $display_repository->getViewDisplay('node', 'case_study');
    $this->assertNull($default_display->getComponent('links'));
    $this->assertFieldsInOrder($default_display, [
      'field_case_study__client_logo',
      'field_featured_image',
      'content_moderation_control',
      'field_content',
      'field_case_study__client_link',
      'field_tags',
    ]);
    $this->assertSharedFieldsInSameOrder($form_display, $default_display);

    $card_display = $display_repository->getViewDisplay('node', 'case_study', 'card');
    $this->assertNull($card_display->getComponent('links'));
    $this->assertFieldsInOrder($card_display, [
      'field_featured_image',
      'field_case_study__client_name',
      'field_description',
    ]);
    $featured_image = $card_display->getComponent('field_featured_image');
    $this->assertSame('entity_reference_entity_view', $featured_image['type']);

    $teaser_display = $display_repository->getViewDisplay('node', 'case_study', 'teaser');
    $this->assertNull($teaser_display->getComponent('links'));
    $this->assertFieldsInOrder($teaser_display, [
      'field_featured_image',
      'field_description',
    ]);

    $this->assertContentModel([
      'case_study' => [
        'title' => [
          'type' => 'string',
          'cardinality' => 1,
          'required' => TRUE,
          'translatable' => TRUE,
          'label' => 'Title',
          'input type' => 'text',
          'help text' => '',
        ],
        'field_description' => [
          'type' => 'string_long',
          'cardinality' => 1,
          'required' => TRUE,
          'translatable' => TRUE,
          'label' => 'Description',
          'input type' => 'textarea',
          'help text' => 'Describe the page content. This appears as the description in search engine results.',
        ],
        'field_featured_image' => [
          'type' => 'entity_reference',
          'cardinality' => 1,
          'required' => FALSE,
          'translatable' => FALSE,
          'label' => 'Featured image',
          'input type' => 'media library',
          'help text' => 'Include an image. This appears as the image in search engine results.',
        ],
        'field_content' => [
          'type' => 'text_long',
          'cardinality' => 1,
          'required' => FALSE,
          'translatable' => TRUE,
          'label' => 'Content',
          'input type' => 'wysiwyg',
          'help text' => 'The content of this page.',
        ],
        'field_tags' => [
          'type' => 'entity_reference',
          'cardinality' => FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED,
          'required' => FALSE,
          'translatable' => FALSE,
          'label' => 'Tags',
          'input type' => 'tagify',
          'help text' => 'Include tags for relevant topics.',
        ],
        'field_case_study__client_name' => [
          'type' => 'string',
          'cardinality' => 1,
          'required' => FALSE,
          'translatable' => TRUE,
          'label' => 'Client name',
          'input type' => 'text',
          'help text' => 'Include the name of the client or organization.',
        ],
        'field_case_study__client_logo' => [
          'type' => 'entity_reference',
          'cardinality' => 1,
          'required' => FALSE,
          'translatable' => FALSE,
          'label' => 'Client logo',
          'input type' => 'media library',
          'help text' => 'Include the logo of the client or organization.'
        ],
        'field_case_study__client_link' => [
          'type' => 'link',
          'cardinality' => 1,
          'required' => FALSE,
          'translatable' => FALSE,
          'label' => 'Client link',
          'input type' => 'text',
          'help text' => 'Include a link to the client or organization website.',
        ],
      ],
    ]);
  }

  public function testPathAliasPatternPrecedence(): void {
    $dir = realpath(__DIR__ . '/../../../../drupal_cms_seo_basic');
    $this->applyRecipe($dir);

    // Confirm that case studies have the expected path aliases.
    $node = $this->drupalCreateNode([
      'type' => 'case_study',
      'title' => 'Test Case study',
    ]);
    $this->assertStringEndsWith("/case-studies/test-case-study", $node->toUrl()->toString());
  }

}
