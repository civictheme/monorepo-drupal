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
  $media = $variables['media'];
  $image = $media->get('field_c_m_image')->referencedEntities()[0];
  $variables['caption'] = $media->get('field_c_m_caption')->getString();
  $variables['src'] = $image->createFileUrl();
  $variables['alt'] = $media->get('field_c_m_image')->first()->get('alt')->getString();
}

/**
 * Implements hook_preprocess__HOOK().
 */
function civic_preprocess_media__civic_remote_video(&$variables) {
  if (empty($variables['media'])) {
    return;
  }
  $media = $variables['media'];
  $variables['width'] = 854;
  $variables['height'] = 400;
  $title = $media->get('field_c_m_title')->first() !== NULL ? $media->get('field_c_m_title')->first()->getValue()['value'] : '';
  _civic_preprocess_media_civic_video($variables);
  $variables['title'] = $title;

  $url_resolver = \Drupal::service('media.oembed.url_resolver');
  $resource_fetcher = \Drupal::service('media.oembed.resource_fetcher');
  try {
    $resource_url = $url_resolver->getResourceUrl($media->get('field_c_m_oembed_video')->first()->getValue()['value'], $variables['width'], $variables['height']);
    $resource = $resource_fetcher->fetchResource($resource_url);
    $doc = new DOMDocument();
    if (!empty($title) && $doc->loadHTML($resource->getHtml())) {
      $elements = $doc->getElementsByTagName('iframe');
      // Handle the case when, although unlikely, oEmbed can use an HTML
      // tag other than <iframe>.
      $iframe = $elements[0] ?? FALSE;
      $html = FALSE;
      if ($iframe) {
        $elements[0]->setAttribute('title', $title);
        $html = $doc->saveHTML($elements[0]);
      }

      $variables['oembed'] = $html !== FALSE ? $html : $resource->getHtml();
    }
    else {
      $variables['oembed'] = $resource->getHtml();
    }
  }
  catch (Exception $e) {
    // Handling any oEmbed exceptions in rendering remote video.
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

  if (isset($variables['content']['field_c_m_video_file'])) {
    $variables['oembed'] = $variables['content']['field_c_m_video_file'];
  }
}

/**
 * Implements hook_preprocess__HOOK().
 */
function _civic_preprocess_media_civic_video(&$variables) {
  if (empty($variables['media'])) {
    return;
  }

  $media = $variables['media'];
  if ($media->hasField('field_c_m_share_link') && !$media->get('field_c_m_share_link')->isEmpty()) {
    $share_attributes = new Attribute();
    $share_attributes->setAttribute('aria-label', 'Share this video ' . $media->label());
    $variables['share_link'] = [
      'text' => t('Share this video'),
      'is_external' => $media->get('field_c_m_share_link')->first()->isExternal(),
      'new_window' => $media->get('field_c_m_share_link')->first()->isExternal(),
      'url' => $media->get('field_c_m_share_link')->first()->getUrl()->toString(),
      'attributes' => $share_attributes,
    ];
  }

  $transcript_attributes = new Attribute();
  $transcript_attributes->setAttribute('aria-label', 'View transcript ' . $media->label());
  if ($media->hasField('field_c_m_transcript') && !$media->get('field_c_m_transcript')->isEmpty()) {
    $variables['transcript_link'] = [
      'text' => "View transcript",
      'url' => $media->toUrl()->toString(),
      'attributes' => $transcript_attributes,
    ];
  }
  elseif ($media->hasField('field_c_m_transcript_link') && !$media->get('field_c_m_transcript_link')->isEmpty()) {
    $variables['transcript_link'] = [
      'text' => $media->get('field_c_m_transcript_link')
        ->first()
        ->get('title')
        ->getValue(),
      'is_external' => $media->get('field_c_m_transcript_link')
        ->first()
        ->isExternal(),
      'new_window' => $media->get('field_c_m_transcript_link')
        ->first()
        ->isExternal(),
      'url' => $media->get('field_c_m_transcript_link')
        ->first()
        ->getUrl()
        ->toString(),
      'attributes' => $transcript_attributes,
    ];
  }

  // Media landing page to show transcript.
  if ($variables['view_mode'] == 'full') {
    $content = '';
    if (!empty($variables['content']['field_c_m_transcript'])) {
      $content = \Drupal::service('renderer')->render($variables['content']['field_c_m_transcript']);
    }
    $variables['content'] = $content;
  }
}
