<?php

/**
 * @file
 * Media theme alterations.
 */

use Drupal\Core\Template\Attribute;

/**
 * Implements hook_preprocess__HOOK().
 */
function civic_preprocess_media__civic_image(&$variables) {
  if (empty($variables['media'])) {
    return;
  }

  /** @var \Drupal\media\MediaInterface $media */
  $media = $variables['media'];
  /** @var \Drupal\file\Entity\File $image_file */
  $image_file = $media->get('field_c_m_image')->referencedEntities()[0];
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
function civic_preprocess_media__civic_video(&$variables) {
  if (empty($variables['media'])) {
    return;
  }

  _civic_preprocess_media_civic_video($variables);

  if (!empty($variables['content']['field_c_m_video_file'])) {
    $variables['oembed'] = $variables['content']['field_c_m_video_file'];
  }
}

/**
 * Implements hook_preprocess__HOOK().
 */
function civic_preprocess_media__civic_remote_video(&$variables) {
  if (empty($variables['media'])) {
    return;
  }

  /** @var \Drupal\media\MediaInterface $media */
  $media = $variables['media'];

//  $variables['width'] = 854;
//  $variables['height'] = 400;

  $title = $media->get('field_c_m_title')->first() !== NULL ? $media->get('field_c_m_title')->first()->getValue()['value'] : '';

  _civic_preprocess_media_civic_video($variables);

  $variables['title'] = $title;

  /** @var \Drupal\media\OEmbed\UrlResolverInterface $url_resolver */
  $url_resolver = \Drupal::service('media.oembed.url_resolver');
  /** @var \Drupal\media\OEmbed\ResourceFetcherInterface $resource_fetcher */
  $resource_fetcher = \Drupal::service('media.oembed.resource_fetcher');
  try {
    $resource_url = $url_resolver->getResourceUrl($media->get('field_c_m_oembed_video')->first()->getValue()['value'], $variables['width'] ?? NULL, $variables['height'] ?? NULL);
    $resource = $resource_fetcher->fetchResource($resource_url);

    $html = $resource->getHtml();

    // Set 'title' attribute (if provided) on the oEmbed iframe element.
    $doc = new DOMDocument();
    if (!empty($title) && $doc->loadHTML($html)) {
      $iframe_elements = $doc->getElementsByTagName('iframe');
      // oEmbed can use an HTML tag other than <iframe> - skip setting an
      // attribute if this is a case.
      if (!empty($iframe_elements[0])) {
        $iframe_elements[0]->setAttribute('title', $title);
        $html = $doc->saveHTML($iframe_elements[0]);
      }
    }

    $variables['oembed'] = $html;
  }
  catch (Exception $e) {
    // Handling any oEmbed exceptions in rendering remote video.
  }
}

/**
 * Implements hook_preprocess__HOOK().
 */
function _civic_preprocess_media_civic_video(&$variables) {
  if (empty($variables['media'])) {
    return;
  }

  /** @var \Drupal\media\MediaInterface $media */
  $media = $variables['media'];

  if ($media->hasField('field_c_m_share_link') && !$media->get('field_c_m_share_link')->isEmpty()) {
    $share_attributes = new Attribute();
    $share_attributes->setAttribute('aria-label', t('Share this video @label', [
      '@label' => $media->label(),
    ]));
    $variables['share_link'] = [
      'text' => t('Share this video'),
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
  if ($media->hasField('field_c_m_transcript') && !$media->get('field_c_m_transcript')->isEmpty()) {
    $variables['transcript_link'] = [
      'text' => t('View transcript'),
      'url' => $media->toUrl()->toString(),
      'attributes' => $transcript_attributes,
    ];
  }
  elseif ($media->hasField('field_c_m_transcript_link') && !$media->get('field_c_m_transcript_link')->isEmpty()) {
    $variables['transcript_link'] = [
      'text' => $media->get('field_c_m_transcript_link')->first()->get('title')->getValue(),
      'url' => $media->get('field_c_m_transcript_link')->first()->getUrl()->toString(),
      'is_external' => $media->get('field_c_m_transcript_link')->first()->isExternal(),
      'is_new_window' => $media->get('field_c_m_transcript_link')->first()->isExternal(),
      'attributes' => $transcript_attributes,
    ];
  }

  // Show transcript on the media landing page.
  if ($variables['view_mode'] == 'full') {
    if (!empty($variables['content']['field_c_m_transcript'])) {
      $variables['content'] = \Drupal::service('renderer')->render($variables['content']['field_c_m_transcript']);
    }
  }
}
