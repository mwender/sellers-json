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
      'timeout' => 10,
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

  $remoteData = json_decode( wp_remote_retrieve_body( $remoteData ) );
  //return $remoteData;

  //*
  return (object) [
      'id'            => SELLERS_PLUGIN_BASENAME,
      'name'          => 'Sellers.json WordPress Plugin',
      'slug'          => SELLERS_PLUGIN_SLUG,
      'plugin'        => SELLERS_PLUGIN_BASENAME,
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
  /**/
}

/**
 * Filters the `plugins_api` information for our plugin.
 *
 * @param      stdClass  $res     The resource
 * @param      <type>    $action  The action
 * @param      <type>    $args    The arguments
 *
 * @return     stdClass  The standard class.
 */
function filter_plugin_info( $res, $action, $args ){
  // do nothing if you're not getting plugin information right now
  if( 'plugin_information' !== $action ) {
    return $res;
  }

  // do nothing if it is not our plugin
  if( SELLERS_PLUGIN_BASENAME !== $args->slug ) {
    return $res;
  }

  $remoteData = fetch_remote_data();
  if( ! $remote ) {
    return $res;
  }

  $res = new stdClass();

  $res->name = $remoteData->name;
  $res->slug = $remoteData->slug;
  $res->version = $remoteData->new_version;
  //$res->tested = $remoteData->tested;
  //$res->requires = $remoteData->requires;
  //$res->author = $remoteData->author;
  //$res->author_profile = $remoteData->author_profile;
  //$res->download_link = $remoteData->download_url;
  //$res->trunk = $remoteData->download_url;
  //$res->requires_php = $remoteData->requires_php;
  //$res->last_updated = $remoteData->last_updated;

  $res->sections = array(
    'description' => 'Provides an interface for editing your site\'s sellers.json.',
    'installation' => null,
    'changelog' => 'Coming soon.'
  );

  if( ! empty( $remoteData->banners ) ) {
    $res->banners = array(
      'low' => $remoteData->banners->low,
      'high' => $remoteData->banners->high
    );
  }

  return $res;
}
add_filter( 'plugins_api', __NAMESPACE__ . '\\filter_plugin_info', 20, 3 );

/**
 * Checks to see if our plugin has an update available.
 *
 * @param      object  $update_plugins  The update plugins object
 *
 * @return     object  The filtered update plugins object
 */
function filter_update_plugins( $update_plugins ){
  if( ! is_object( $update_plugins ) )
    return $update_plugins;

  if( ! isset( $update_plugins->response ) || ! is_array( $update_plugins->response ) )
    $update_plugins->response = [];

  $remoteData = get_transient( SELLERS_PLUGIN_CHECK_TRANSIENT_NAME );
  if( false === $remoteData ){
    $remoteData = fetch_remote_data();
    set_transient( SELLERS_PLUGIN_CHECK_TRANSIENT_NAME, $remoteData, SELLERS_PLUGIN_CHECK_EXPIRATION );
  }

  if( ! function_exists( 'get_plugin_data' ) )
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');

  $currentPluginData = get_plugin_data( SELLERS_PLUGIN_FILE );

  if( $remoteData && version_compare( $remoteData->new_version, $currentPluginData['Version'], '>' ) ){
    //uber_log('👉 Plugin needs an update!');
    $update_plugins->response[ SELLERS_PLUGIN_BASENAME ] = (object) [
      'slug'        => SELLERS_PLUGIN_SLUG,
      'plugin'      => SELLERS_PLUGIN_BASENAME,
      'new_version' => $remoteData->new_version,
      'url'         => 'https://wenmarkdigital.com/sellers-json-wordpress-plugin',
      'package'     => $remoteData->package,
    ];
  } else {
    //uber_log('🛑 Plugin DOES NOT need an update.');
    $update_plugins->no_update[ SELLERS_PLUGIN_BASENAME ] = (object) [
      'slug'        => SELLERS_PLUGIN_SLUG,
      'plugin'      => SELLERS_PLUGIN_BASENAME,
      'new_version' => $remoteData->new_version,
      'url'         => 'https://wenmarkdigital.com/sellers-json-wordpress-plugin',
      'package'     => $remoteData->package,
    ];
  }

  return $update_plugins;
}
add_filter( 'site_transient_update_plugins', __NAMESPACE__ . '\\filter_update_plugins' );
//add_filter( 'transient_update_plugins', __NAMESPACE__ . '\\filter_update_plugins' );