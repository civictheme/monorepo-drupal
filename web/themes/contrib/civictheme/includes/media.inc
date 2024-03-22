<?php

/**
 * @file
 * Media theme alterations.
 */

declare(strict_types=1);

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Template\Attribute;
use Drupal\file\FileInterface;
use Drupal\media\MediaInterface;

/**
 * Implements hook_preprocess__HOOK().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_media__civictheme_image(array &$variables): void {
  if (empty($variables['media'])) {
    return;
  }

  $media = $variables['media'];
  if (!$media instanceof MediaInterface) {
    return;
  }

  $image_file = civictheme_get_field_referenced_entity($media, 'field_c_m_image');
  if ($image_file instanceof FileInterface) {
    $image = \Drupal::service('image.factory')->get($image_file->getFileUri());
    if ($image->isValid()) {
      $variables['width'] = $image->getWidth();
      $variables['height'] = $image->getHeight();
      $variables['url'] = $image_file->createFileUrl();
      $variables['alt'] = $media->get('field_c_m_image')->first()->get('alt')->getString();
      $variables['caption'] = $media->get('field_c_m_caption')->getString();
    }
  }
}

/**
 * Implements hook_preprocess__HOOK().
 */
function civictheme_preprocess_media__civictheme_video(array &$variables): void {
  if (empty($variables['media'])) {
    return;
  }

  $media = $variables['media'];
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

  if (!empty($variables['sources'])) {
    _civictheme_preprocess_media__civictheme_video_links($variables);
    _civictheme_preprocess_media__civictheme_video_transcript($variables);
  }
}

/**
 * Implements hook_preprocess__HOOK().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function civictheme_preprocess_media__civictheme_remote_video(array &$variables): void {
  if (empty($variables['media'])) {
    return;
  }

  $media = $variables['media'];
  if (!$media instanceof MediaInterface) {
    return;
  }

  try {
    $resource_url = \Drupal::service('media.oembed.url_resolver')->getResourceUrl($media->get('field_c_m_oembed_video')->first()->getValue()['value'], $variables['width'] ?? NULL, $variables['height'] ?? NULL);
    $resource = \Drupal::service('media.oembed.resource_fetcher')->fetchResource($resource_url);

    $html = $resource->getHtml();

    // Set 'title' attribute (if provided) on the oEmbed iframe element.
    $doc = _civictheme_get_dom_document($html);

    $iframe_elements = $doc->getElementsByTagName('iframe');
    // oEmbed can use an HTML tag other than <iframe> - skip setting an
    // attribute if this is a case.
    if (!empty($iframe_elements[0])) {
      $iframe_elements[0]->setAttribute('title', $media->getName());
      $html = $doc->saveHTML($iframe_elements[0]);
    }

    $variables['raw_source'] = $html;

    _civictheme_preprocess_media__civictheme_video_links($variables);
    _civictheme_preprocess_media__civictheme_video_transcript($variables);
  }
  catch (Exception) {
    // Handling any oEmbed exceptions in rendering remote video.
  }
}

/**
 * Pre-process civictheme_video media links.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_media__civictheme_video_links(array &$variables): void {
  if (empty($variables['media'])) {
    return;
  }

  $media = $variables['media'];
  if (!$media instanceof MediaInterface) {
    return;
  }

  $transcript_attributes = new Attribute();
  $transcript_attributes->setAttribute('aria-label', (string) new FormattableMarkup('View transcript @label', [
    '@label' => $media->label(),
  ]));
  // Transcript link points to the video if the transcript is empty or
  // to the external transcript, if provided.
  if (civictheme_field_has_value($media, 'field_c_m_transcript')) {
    $variables['transcript_link'] = [
      'text' => t('View transcript'),
      'url' => $media->toUrl()->toString(),
      'attributes' => $transcript_attributes,
    ];
  }
  elseif (civictheme_field_has_value($media, 'field_c_m_transcript_link')) {
    /** @var \Drupal\link\LinkItemInterface $link */
    $link = $media->get('field_c_m_transcript_link')->first();
    $variables['transcript_link'] = [
      'text' => empty($link->get('title')->getValue()) ? t('View transcript') : $link->get('title')->getValue(),
      'url' => $link->getUrl()->toString(),
      'is_external' => $link->isExternal(),
      'is_new_window' => $link->isExternal(),
      'attributes' => $transcript_attributes,
    ];
  }
}

/**
 * Pre-process civictheme_video media transcript.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_media__civictheme_video_transcript(array &$variables): void {
  // Show transcript on the media landing page.
  if ($variables['view_mode'] == 'full' && !empty($variables['content']['field_c_m_transcript'])) {
    $variables['content'] = \Drupal::service('renderer')->render($variables['content']['field_c_m_transcript']);
  }
}
