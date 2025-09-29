<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_seo_tools\Functional;

use Composer\InstalledVersions;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\image\Entity\ImageStyle;
use Drupal\media\Entity\Media;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\TestWith;

#[Group('drupal_cms_seo_tools')]
#[IgnoreDeprecations]
class ContentMetaTagsTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  private function generateImage(string $extension): Media {
    $random = $this->getRandomGenerator();

    $uri = uniqid('public://') .  '.' . $extension;
    $uri = $random->image($uri, '100x100', '200x200');
    $this->assertFileExists($uri);
    $file = File::create(['uri' => $uri]);
    $file->save();

    $media = Media::create([
      'name' => $random->word(16),
      'bundle' => 'image',
      'field_media_image' => [
        'target_id' => $file->id(),
        'alt' => $random->machineName(),
      ],
    ]);
    $media->save();

    return $media;
  }

  #[TestWith(['drupal/drupal_cms_blog', 'blog'])]
  #[TestWith(['drupal/drupal_cms_case_study', 'case_study'])]
  #[TestWith(['drupal/drupal_cms_events', 'event'])]
  #[TestWith(['drupal/drupal_cms_news', 'news'])]
  #[TestWith(['drupal/drupal_cms_page', 'page'])]
  #[TestWith(['drupal/drupal_cms_person', 'person'])]
  #[TestWith(['drupal/drupal_cms_project', 'project'])]
  public function testMetaTagsForContentType(string $recipe, string $node_type): void {
    $dir = InstalledVersions::getInstallPath($recipe);
    $this->applyRecipe($dir);

    $dir = realpath(__DIR__ . '/../../..');
    $this->applyRecipe($dir);

    // If we create a node of this content type, all expected meta tags should
    // be there.
    $random = $this->getRandomGenerator();
    $node = $this->drupalCreateNode([
      'type' => $node_type,
      'field_featured_image' => $this->generateImage('png'),
      'field_description' => $random->sentences(4),
      'moderation_state' => 'published',
    ]);
    $node_url = $node->toUrl();
    $this->drupalGet($node_url);
    $assert_session = $this->assertSession();
    $assert_session->statusCodeEquals(200);

    $save_node = function () use ($node): void {
      $node->save();
      $this->container->get('cache.page')->deleteAll();
      $this->getSession()->reload();
    };

    // Assert the meta tags which are static, or don't have any configured
    // overrides.
    $absolute_node_url = $node_url->setAbsolute()->toString();
    $assert_session->elementAttributeContains('css', 'link[rel="canonical"]', 'href', $absolute_node_url);
    $site_name = $this->config('system.site')->get('name');
    $assert_session->elementAttributeContains('css', 'meta[property="og:site_name"]', 'content', $site_name);
    $assert_session->elementAttributeContains('css', 'meta[property="og:type"]', 'content', 'article');
    $assert_session->elementAttributeContains('css', 'meta[property="og:url"]', 'content', $absolute_node_url);
    $assert_session->elementAttributeContains('css', 'meta[name="referrer"]', 'content', 'unsafe-url');
    $assert_session->elementAttributeContains('css', 'link[rel="shortlink"]', 'href', Url::fromRoute('<front>')->setAbsolute()->toString());
    $assert_session->elementAttributeContains('css', 'meta[name="rights"]', 'content', sprintf('Copyright ©%s All rights reserved.', date('Y')));
    $assert_session->elementAttributeContains('css', 'meta[name="twitter:card"]', 'content', 'summary_large_image');
    $original_changed_time = $node->getChangedTime();
    $assert_session->elementAttributeContains('css', 'meta[property="og:updated_time"]', 'content', date('c', $original_changed_time));

    // Re-saving the node should update the og:updated_time meta tag.
    $updated_changed_time = $original_changed_time + 30;
    $node->setChangedTime($updated_changed_time);
    $save_node();
    $assert_session->elementAttributeContains('css', 'meta[property="og:updated_time"]', 'content', date('c', $updated_changed_time));

    // Assert the meta tags for field_featured_image, and that field_seo_image
    // takes precedence over it.
    $assert_image = function (Media $image) use ($assert_session): void {
      /** @var \Drupal\file\FileInterface $file */
      $file = $image->field_media_image->entity;
      $name = $file->getFilename();

      $facebook_dimensions = [];
      ImageStyle::load('social_media_facebook')
        ?->transformDimensions($facebook_dimensions, $file->getFileUri());
      $this->assertArrayHasKey('width', $facebook_dimensions);
      $this->assertArrayHasKey('height', $facebook_dimensions);

      $assert_session->elementAttributeContains('css', 'link[rel="image_src"]', 'href', $name);
      $assert_session->elementAttributeContains('css', 'meta[property="og:image"]', 'content', $name);
      $alt_text = $image->field_media_image->alt;
      $assert_session->elementAttributeContains('css', 'meta[property="og:image:alt"]', 'content', $alt_text);
      $assert_session->elementAttributeContains('css', 'meta[property="og:image:width"]', 'content', (string) $facebook_dimensions['width']);
      $assert_session->elementAttributeContains('css', 'meta[property="og:image:height"]', 'content', (string) $facebook_dimensions['height']);
      $assert_session->elementAttributeContains('css', 'meta[property="og:image:type"]', 'content', 'image/webp');
      $assert_session->elementAttributeContains('css', 'meta[name="twitter:image"]', 'content', $name);
      $assert_session->elementAttributeContains('css', 'meta[name="twitter:image:alt"]', 'content', $alt_text);
    };
    $assert_image($node->field_featured_image->entity);
    $node->set('field_seo_image', $this->generateImage('jpg'));
    $save_node();
    $assert_image($node->field_seo_image->entity);

    // Assert the meta tags for the node title and that field_seo_title takes
    // precedence over it.
    $assert_title = function (string $title) use ($assert_session, $site_name): void {
      $assert_session->elementAttributeContains('css', 'meta[property="og:title"]', 'content', $title);
      $assert_session->elementAttributeContains('css', 'meta[name="twitter:title"]', 'content', $title);
      $assert_session->titleEquals("$title | $site_name");
    };
    $assert_title($node->getTitle());
    $seo_title = $this->randomMachineName();
    $node->set('field_seo_title', $seo_title);
    $save_node();
    $assert_title($seo_title);

    // Assert the meta tags for field_description and that field_seo_description
    // takes precedence over it.
    $assert_description = function (string $description) use ($assert_session): void {
      $assert_session->elementAttributeContains('css', 'meta[name="description"]', 'content', $description);
      $assert_session->elementAttributeContains('css', 'meta[property="og:description"]', 'content', $description);
      $assert_session->elementAttributeContains('css', 'meta[name="twitter:description"]', 'content', $description);
    };
    $assert_description($node->field_description->value);
    $node->set('field_seo_description', $random->sentences(4));
    $save_node();
    $assert_description($node->field_seo_description->value);
  }

}
