<?php



function _civic_preprocess_node_civic_card_promo(&$variables) {
//  $elements = $variables['elements'];
//  $node = $variables['node'];

  if (isset($variables['content']['field_c_n_thumbnail'][0])) {
    $image_data = $variables['content']['field_c_n_thumbnail'][0]['#item'];
    $file = \Drupal\file\Entity\File::load($image_data->target_id);
    $image['src'] = file_create_url($file->getFileUri());
    $image['alt'] = $image_data->alt;
    $variables['image'] = $image;
  }
}
