<?php

declare(strict_types=1);

namespace Drupal\civictheme_starter\Hook\Preprocess;

use Drupal\civictheme_starter\CivicthemeConstants;
use Drupal\civictheme_starter\Theme\HtmlContentProcessor;
use Drupal\civictheme_starter\Utility\CivicthemeField;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Template\Attribute;
use Drupal\file\FileInterface;
use Drupal\media\MediaInterface;

/**
 * Image and media preprocess hook implementations for CivicTheme.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class CivicThemeStarterMediaHooks {

  use StringTranslationTrait;

  /**
   * Implements template_preprocess_image().
   */
  #[Hook('preprocess_image')]
  public function preprocessImage(array &$variables): void {
    if (isset($variables['attributes']['src'])) {
      $variables['url'] = $variables['attributes']['src'];
    }
    elseif (!empty($variables['uri'])) {
      $url_generator = \Drupal::service('file_url_generator');
      $variables['url'] = $url_generator->transformRelative($url_generator->generateAbsoluteString($variables['uri']));
    }
  }

  /**
   * Implements hook_preprocess_HOOK() for media__civictheme_image.
   */
  #[Hook('preprocess_media__civictheme_image')]
  public function preprocessMediaImage(array &$variables): void {
    $media = $variables['media'] ?? NULL;
    if (!$media instanceof MediaInterface) {
      return;
    }

    $this->preprocessVideoTheme($variables);
    $image_file = CivicthemeField::getReferencedEntity($media, 'field_c_m_image', $variables);
    if ($image_file instanceof FileInterface) {
      $image = \Drupal::service('image.factory')->get($image_file->getFileUri());
      if ($image->isValid()) {
        $variables['width'] = (int) $image->getWidth();
        $variables['height'] = (int) $image->getHeight();
        $image_url = \Drupal::service('file_url_generator')->generateString($image_file->getFileUri());
        $variables['url'] = \Drupal::service('file_url_generator')->transformRelative($image_url);
        $variables['alt'] = $media->get('field_c_m_image')->first()->get('alt')->getString();
        $variables['caption'] = $media->get('field_c_m_caption')->getString();
      }
    }
  }

  /**
   * Implements hook_preprocess_HOOK() for media__civictheme_video.
   */
  #[Hook('preprocess_media__civictheme_video')]
  public function preprocessMediaVideo(array &$variables): void {
    $media = $variables['media'] ?? NULL;
    if (!$media instanceof MediaInterface) {
      return;
    }

    foreach ($media->get('field_c_m_video_file')->referencedEntities() as $video_file) {
      if (!$video_file instanceof FileInterface) {
        continue;
      }

      $variables['sources'][] = [
        'url' => $video_file->createFileUrl(),
        'type' => $video_file->getMimeType(),
      ];
    }
    $this->preprocessVideoTheme($variables);
    if (!empty($variables['sources'])) {
      $this->preprocessVideoLinks($variables);
      $this->preprocessVideoTranscript($variables);
    }
  }

  /**
   * Implements hook_preprocess_HOOK() for media__civictheme_remote_video.
   *
   * @SuppressWarnings("PHPMD.MissingImport")
   */
  #[Hook('preprocess_media__civictheme_remote_video')]
  public function preprocessMediaRemoteVideo(array &$variables): void {
    $media = $variables['media'] ?? NULL;
    if (!$media instanceof MediaInterface) {
      return;
    }

    try {
      $resource_url = \Drupal::service('media.oembed.url_resolver')->getResourceUrl($media->get('field_c_m_oembed_video')->first()->getValue()['value'], $variables['width'] ?? NULL, $variables['height'] ?? NULL);
      $resource = \Drupal::service('media.oembed.resource_fetcher')->fetchResource($resource_url);

      $html = $resource->getHtml();

      // Set 'title' attribute (if provided) on the oEmbed iframe element.
      $doc = HtmlContentProcessor::getDomDocument($html);

      $iframe_elements = $doc->getElementsByTagName('iframe');
      // oEmbed can use an HTML tag other than <iframe> - skip setting an
      // attribute if this is a case.
      if (!empty($iframe_elements[0])) {
        $iframe_elements[0]->setAttribute('title', $media->getName());
        $html = $doc->saveHTML($iframe_elements[0]);
      }

      $variables['raw_source'] = [
        '#type' => 'inline_template',
        '#template' => $html,
      ];
      $this->preprocessVideoTheme($variables);
      $this->preprocessVideoLinks($variables);
      $this->preprocessVideoTranscript($variables);
    }
    catch (\Exception) {
      // Handling any oEmbed exceptions in rendering remote video.
    }
  }

  /**
   * Pre-process civictheme_video media theme.
   */
  protected function preprocessVideoTheme(array &$variables): void {
    $variables['theme'] = $variables['theme'] ?? CivicthemeConstants::THEME_LIGHT;
  }

  /**
   * Pre-process civictheme_video media links.
   */
  protected function preprocessVideoLinks(array &$variables): void {
    $media = $variables['media'] ?? NULL;
    if (!$media instanceof MediaInterface) {
      return;
    }

    // Initialize the transcript link & block to NULL preprocess from
    // civictheme.
    $variables['transcript_link'] = NULL;
    $variables['transcript_content'] = NULL;
    $variables['vertical_spacing'] = 'bottom';

    // Hide transcript when viewing in CK Editor.
    $route_match = \Drupal::routeMatch();
    $route_name = $route_match->getRouteName();
    if ($route_name === 'media.filter.preview') {
      return;
    }

    $transcript_attributes = new Attribute();
    $transcript_attributes->setAttribute('aria-label', (string) new FormattableMarkup('View transcript for @label', [
      '@label' => $media->label(),
    ]));

    $transcript_link_has_value = CivicthemeField::hasValue($media, 'field_c_m_transcript_link');
    if ($transcript_link_has_value) {
      /** @var \Drupal\link\LinkItemInterface $link */
      $link = $media->get('field_c_m_transcript_link')->first();
      $has_custom_link_title = !empty($link->get('title')->getValue());
      $variables['transcript_link'] = [
        'text' => $has_custom_link_title ? $link->get('title')->getValue() : $this->t('View transcript'),
        'url' => $link->getUrl()->toString(),
        'is_external' => $link->isExternal(),
        'is_new_window' => $link->isExternal(),
        'attributes' => $has_custom_link_title ? new Attribute() : $transcript_attributes,
      ];
    }
  }

  /**
   * Pre-process civictheme_video media transcript.
   */
  protected function preprocessVideoTranscript(array &$variables): void {
    $media = $variables['media'] ?? NULL;
    if (!$media instanceof MediaInterface) {
      return;
    }

    $transcript_has_value = CivicthemeField::hasValue($media, 'field_c_m_transcript');
    if ($transcript_has_value) {
      $variables['transcript_content'] = [
        'content' => $media->get('field_c_m_transcript')->view(),
      ];
      $video_title = $media->label();
      $variables['transcript_expand_text'] = $this->t('View transcript for @video', ['@video' => $video_title]);
      $variables['transcript_collapse_text'] = $this->t('Hide transcript for @video', ['@video' => $video_title]);
    }
  }

}
