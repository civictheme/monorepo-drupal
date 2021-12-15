<?php

/**
 * @file
 * Media theme alterations.
 */

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
  $variables['title'] = $media->get('field_c_m_title')->first() !== NULL ? $media->get('field_c_m_title')->first()->getValue()['value'] : '';
  if ($media->hasField('field_c_m_share_link') && !$media->get('field_c_m_share_link')->isEmpty()) {
    $variables['share_link'] = [
      'text' => $media->get('field_c_m_share_link')->first()->get('title')->getValue(),
      'is_external' => $media->get('field_c_m_share_link')->first()->isExternal(),
      'new_window' => $media->get('field_c_m_share_link')->first()->isExternal(),
      'url' => $media->get('field_c_m_share_link')->first()->getUrl()->toString(),
    ];
  }
  if ($media->hasField('field_c_m_transcript_link') && !$media->get('field_c_m_transcript_link')->isEmpty()) {
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
    ];
  }
  $url_resolver = \Drupal::service('media.oembed.url_resolver');
  $resource_fetcher = \Drupal::service('media.oembed.resource_fetcher');
  try {
    $resource_url = $url_resolver->getResourceUrl($media->get('field_c_m_oembed_video')->first()->getValue()['value'], $variables['width'], $variables['height']);
    $resource = $resource_fetcher->fetchResource($resource_url);
    $variables['oembed'] = $resource->getHtml();
  }
  catch (Exception $e) {
    // Handling any oEmbed exceptions in rendering remote video.
  }
}
