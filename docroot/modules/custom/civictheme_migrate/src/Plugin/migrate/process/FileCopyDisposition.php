<?php

namespace Drupal\civictheme_migrate\Plugin\migrate\process;

use Drupal\migrate_file\Plugin\migrate\process\FileImport;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateSkipRowException;

/**
 * Imports a file from a remote URL.
 *
 * Also, infers file names based on the content disposition header.
 *
 * @MigrateProcessPlugin(
 *    id = "file_copy_disposition"
 * )
 */
class FileCopyDisposition extends FileImport {

  /**
   * Parse a header string into an array.
   *
   * @return array
   *   An array of header values.
   */
  public function parseHeader($header) {
    $header_map = [];

    foreach ($header as $string) {
      $parts = array_map('trim', explode(';', $string));
      foreach ($parts as $part) {
        [$name, $value] = explode('=', $part);
        $header_map[$name] = str_replace('"', '', $value);
      }
    }

    return $header_map;
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!$value) {
      return NULL;
    }

    if (!$curl = curl_init()) {
      throw new MigrateSkipRowException("Unable to download file: Cannot initialise curl");
    }

    $value = str_replace(' ', '%20', $value);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept-Encoding: gzip, deflate']);
    curl_setopt($curl, CURLOPT_URL, $value);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_TIMEOUT, 600);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');

    $data = curl_exec($curl);

    if (curl_errno($curl)) {
      $options = ['http' => ['user_agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1']];
      if (!$data = file_get_contents($value, FILE_TEXT, stream_context_create($options))) {
        $error = error_get_last();
        echo "HTTP request failed. Error was: " . $error['message'] . PHP_EOL;
        throw new MigrateSkipRowException("Unable to download file {$value}: {$error['message']}");
      }
      echo "Downloaded {$value} via file_get_contents, size is: " . mb_strlen($data) . PHP_EOL;
    }

    $uuid = $row->getSourceProperty('uuid');

    curl_close($curl);

    $filename = urldecode(basename($value));
    $filename = "{$uuid}_{$filename}";
    $filename = "temporary://{$filename}";

    file_put_contents($filename, (string) $data);
    return parent::transform($filename, $migrate_executable, $row, $destination_property);
  }

}
