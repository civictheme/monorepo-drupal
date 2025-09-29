<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_events\Functional;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\drupal_cms_content_type_base\ContentModelTestTrait;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_events')]
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

    $this->ensureFileExists('601c1f74-4633-4069-ae90-57c645568b1c');

    // The privacy settings should be available to anonymous users.
    $this->drupalPlaceBlock('system_menu_block:footer', ['label' => 'Footer']);
    $this->drupalGet('<front>');
    $footer_menu = $this->assertSession()
      ->elementExists('css', 'nav > h2:contains("Footer") + ul');
    $this->assertTrue($footer_menu->hasLink('My privacy settings'));
  }

  public function testEditForm(): void {
    $this->assertEditForm('event');
  }

  public function testContentModel(): void {
    /** @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface $display_repository */
    $display_repository = $this->container->get(EntityDisplayRepositoryInterface::class);

    $form_display = $display_repository->getFormDisplay('node', 'event');
    $this->assertFalse($form_display->isNew());
    $this->assertNull($form_display->getComponent('url_redirects'));
    $this->assertFieldsInOrder($form_display, [
      'title',
      'field_featured_image',
      'field_event__date',
      'field_event__location_name',
      'field_event__location_address',
      'field_description',
      'field_content',
      'field_event__link',
      'field_event__file',
      'field_tags',
    ]);

    $default_display = $display_repository->getViewDisplay('node', 'event');
    $this->assertNull($default_display->getComponent('links'));
    $this->assertFieldsInOrder($default_display, [
      'content_moderation_control',
      'field_featured_image',
      'field_event__date',
      'field_event__location_name',
      'field_event__location_address',
      'field_geofield',
      'field_content',
      'field_event__link',
      'field_event__file',
      'field_tags',
    ]);
    $this->assertSharedFieldsInSameOrder($form_display, $default_display);

    $card_display = $display_repository->getViewDisplay('node', 'event', 'card');
    $this->assertNull($card_display->getComponent('links'));
    $this->assertFieldsInOrder($card_display, [
      'field_featured_image',
      'field_event__date',
      'field_description',
    ]);
    $featured_image = $card_display->getComponent('field_featured_image');
    $this->assertSame('entity_reference_entity_view', $featured_image['type']);

    $teaser_display = $display_repository->getViewDisplay('node', 'event', 'teaser');
    $this->assertNull($teaser_display->getComponent('links'));
    $this->assertFieldsInOrder($teaser_display, [
      'field_featured_image',
      'field_event__date',
      'field_description',
    ]);

    $this->assertContentModel([
      'event' => [
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
        'field_event__date' => [
          'type' => 'smartdate',
          'cardinality' => 1,
          'required' => TRUE,
          'translatable' => FALSE,
          'label' => 'Date',
          'input type' => 'date',
          'help text' => '',
        ],
        'field_event__location_name' => [
          'type' => 'string',
          'cardinality' => 1,
          'required' => FALSE,
          'translatable' => FALSE,
          'label' => 'Location name',
          'input type' => 'text',
          'help text' => '',
        ],
        'field_event__location_address' => [
          'type' => 'address',
          'cardinality' => 1,
          'required' => FALSE,
          'translatable' => FALSE,
          'label' => 'Location address',
          'input type' => 'address',
          'help text' => '',
        ],
        'field_event__file' => [
          'type' => 'entity_reference',
          'cardinality' => 1,
          'required' => FALSE,
          'translatable' => FALSE,
          'label' => 'File',
          'input type' => 'media library',
          'help text' => '',
        ],
        'field_event__link' => [
          'type' => 'link',
          'cardinality' => 1,
          'required' => FALSE,
          'translatable' => FALSE,
          'label' => 'Link',
          'input type' => 'text',
          'help text' => '',
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
      ],
    ]);

    // The geofield should not be on the edit form in any way, shape, or form.
    $assert_session = $this->assertSession();
    $assert_session->addressEquals('/node/add/event');
    $assert_session->responseNotContains('field_geofield');
  }

  public function testPathAliasPatternPrecedence(): void {
    $dir = realpath(__DIR__ . '/../../../../drupal_cms_seo_basic');
    $this->applyRecipe($dir);

    // Ensure there's at least one text format we can use as an anonymous user.
    $this->applyRecipe('core/recipes/restricted_html_format');

    // Confirm that events have the expected path aliases.
    $node = $this->drupalCreateNode([
      'type' => 'event',
      'title' => 'Grand Jubilee',
    ]);
    $this->assertStringEndsWith('/events/grand-jubilee', $node->toUrl()->toString());
  }

}
