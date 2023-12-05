<?php
namespace SellersJson\updates;

/**
 * Fetches remote data from our Plugin Update Server.
 *
 * @return     object  The remote data.
 */
function fetch_remote_data() {
  $remoteData = wp_remote_get(
    SELLERS_PLUGIN_CHECK_EP,
    [
      'headers' => [
        'Accept' => 'application/json'
      ]
    ]
  );

  // Something went wrong!
  if (
    is_wp_error($remoteData) ||
    wp_remote_retrieve_response_code($remoteData) !== 200
  ) {
    return null;
  }

  $remoteData = json_decode($remoteData['body']);

  return (object) [
      'id'            => 'sellers-json/sellers-json.php',
      'slug'          => 'sellers-json',
      'plugin'        => 'sellers-json/sellers-json.php',
      'new_version'   => $remoteData->version,  // <-- Important!
      'url'           => 'https://wenmarkdigital.com',
      'package'       => $remoteData->package,  // <-- Important!
      'icons'         => [],
      'banners'       => [],
      'banners_rtl'   => [],
      'tested'        => '',
      'requires_php'  => '',
      'compatibility' => new \stdClass(),
  ];
}
