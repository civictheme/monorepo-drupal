<?php

/**
 * @file
 * Media theme alterations.
 */

use Drupal\Core\Template\Attribute;

/**
 * Implements hook_preprocess__HOOK().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_media__civictheme_image(&$variables) {
  if (empty($variables['media'])) {
    return;
  }

  /** @var \Drupal\media\MediaInterface $media */
  $media = $variables['media'];
  /** @var \Drupal\file\FileInterface|null $image_file */
  $image_file = civictheme_get_field_referenced_entities($media, 'field_c_m_image', TRUE);
  if ($image_file) {
    /** @var \Drupal\Core\Image\ImageInterface $image */
    $image = \Drupal::service('image.factory')->get($image_file->getFileUri());
    if ($image->isValid()) {
      $variables['width'] = $image->getWidth();
      $variables['height'] = $image->getHeight();
      $variables['src'] = $image_file->createFileUrl();
      $variables['alt'] = $media->get('field_c_m_image')->first()->get('alt')->getString();
      $variables['caption'] = $media->get('field_c_m_caption')->getString();
    }
  }
}

/**
 * Implements hook_preprocess__HOOK().
 */
function civictheme_preprocess_media__civictheme_video(&$variables) {
  if (empty($variables['media'])) {
    return;
  }

  /** @var \Drupal\media\MediaInterface $media */
  $media = $variables['media'];

  /** @var \Drupal\file\Entity\File $video_file */
  foreach ($media->get('field_c_m_video_file')->referencedEntities() as $video_file) {
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
 * @todo what's the deal with those warning suppression? They are everywhere.
 * PHPMD.StaticAccess only applies to '\Drupal::' call in class member methods.
 * Static methods and functions can call the service helper without any warning.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function civictheme_preprocess_media__civictheme_remote_video(&$variables) {
  if (empty($variables['media'])) {
    return;
  }

  /** @var \Drupal\media\MediaInterface $media */
  $media = $variables['media'];
  try {
    $resource_url = \Drupal::service('media.oembed.url_resolver')->getResourceUrl($media->get('field_c_m_oembed_video')->first()->getValue()['value'], $variables['width'] ?? NULL, $variables['height'] ?? NULL);
    $resource = \Drupal::service('media.oembed.resource_fetcher')->fetchResource($resource_url);

    $html = $resource->getHtml();

    // Set 'title' attribute (if provided) on the oEmbed iframe element.
    $doc = _civictheme_get_dom_document($html);
    if ($doc) {
      $iframe_elements = $doc->getElementsByTagName('iframe');
      // oEmbed can use an HTML tag other than <iframe> - skip setting an
      // attribute if this is a case.
      if (!empty($iframe_elements[0])) {
        $iframe_elements[0]->setAttribute('title', $media->getName());
        $html = $doc->saveHTML($iframe_elements[0]);
      }
    }

    $variables['raw_source'] = $html;

    _civictheme_preprocess_media__civictheme_video_links($variables);
    _civictheme_preprocess_media__civictheme_video_transcript($variables);
  }
  catch (Exception $e) {
    // Handling any oEmbed exceptions in rendering remote video.
  }
}

/**
 * Pre-process civictheme_video media links.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_media__civictheme_video_links(&$variables) {
  if (empty($variables['media'])) {
    return;
  }

  /** @var \Drupal\media\MediaInterface $media */
  $media = $variables['media'];

  if (civictheme_field_has_value($media, 'field_c_m_share_link')) {
    $share_attributes = new Attribute();
    $share_attributes->setAttribute('aria-label', t('Share this video @label', [
      '@label' => $media->label(),
    ]));
    // @todo calling '->first()->' without checking for the value can result in
    // null pointer exception.
    $variables['share_link'] = [
      'text' => !empty($media->get('field_c_m_share_link')->first()->get('title')->getValue()) ? $media->get('field_c_m_share_link')->first()->get('title')->getValue() : t('Share this video'),
      'is_external' => $media->get('field_c_m_share_link')->first()->isExternal(),
      'is_new_window' => $media->get('field_c_m_share_link')->first()->isExternal(),
      'url' => $media->get('field_c_m_share_link')->first()->getUrl()->toString(),
      'attributes' => $share_attributes,
    ];
  }

  $transcript_attributes = new Attribute();
  $transcript_attributes->setAttribute('aria-label', t('View transcript @label', [
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
    $variables['transcript_link'] = [
      'text' => !empty($media->get('field_c_m_transcript_link')->first()->get('title')->getValue()) ? $media->get('field_c_m_transcript_link')->first()->get('title')->getValue() : t('View transcript'),
      'url' => $media->get('field_c_m_transcript_link')->first()->getUrl()->toString(),
      'is_external' => $media->get('field_c_m_transcript_link')->first()->isExternal(),
      'is_new_window' => $media->get('field_c_m_transcript_link')->first()->isExternal(),
      'attributes' => $transcript_attributes,
    ];
  }
}

/**
 * Pre-process civictheme_video media transcript.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_media__civictheme_video_transcript(&$variables) {
  // Show transcript on the media landing page.
  if ($variables['view_mode'] === 'full') {
    if (!empty($variables['content']['field_c_m_transcript'])) {
      $variables['content'] = \Drupal::service('renderer')->render($variables['content']['field_c_m_transcript']);
    }
  }
}
