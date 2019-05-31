<?php

namespace Drupal\advanced_migrations\Plugin\migrate_plus\data_fetcher;

use Drupal\migrate_plus\DataFetcherPluginBase;
use GuzzleHttp\Psr7\Response;

/**
 * Retrieve JSON data from a local directory.
 *
 * @DataFetcher(
 *   id = "json_directory",
 *   title = @Translation("JSON Directory")
 * )
 */
class JsonDirectory extends DataFetcherPluginBase {

  /**
   * {@inheritdoc}
   */
  public function setRequestHeaders(array $headers) {
    // Does nothing.
  }

  /**
   * {@inheritdoc}
   */
  public function getRequestHeaders() {
    // Does nothing.
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getResponse($url) {
    $json = [
      'articles' => [],
    ];
    foreach (scandir($url) as $file) {
      $info = pathinfo($file);
      if ($info['extension'] === 'json') {
        $json['articles'][$info['filename']] = json_decode(file_get_contents($url . '/' . $file));
      }
    }

    return new Response(200, [], json_encode($json));
  }

  /**
   * {@inheritdoc}
   */
  public function getResponseContent($url) {
    $response = $this->getResponse($url);
    return $response->getBody()->getContents();
  }

}
