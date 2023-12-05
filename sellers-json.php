<?php
/**
 * Plugin Name:     Sellers.json Editor
 * Plugin URI:      https://wenmarkdigital.com
 * Description:     Implements the sellers.json standard for your WordPress site.
 * Author:          TheWebist
 * Author URI:      https://mwender.com
 * Text Domain:     sellers-json
 * Domain Path:     /languages
 * Version:         1.3.0
 *
 * @package         Sellers_Json
 */

define( 'SELLERS_PATH', plugin_dir_path( __FILE__ ) );
define( 'SELLERS_URL', plugin_dir_url( __FILE__ ) );
define( 'SELLERS_PLUGIN_CHECK_EP', 'https://packages.wenmarkdigital.com/plugins/sellers-json/packages/' );
define( 'SELLERS_PLUGIN_CHECK_EXPIRATION', 300 );
define( 'SELLERS_PLUGIN_CHECK_TRANSIENT_NAME', 'sellers_plugin_update' );

require_once( SELLERS_PATH . 'lib/fns/include-acf.php' );
require_once( SELLERS_PATH . 'lib/fns/acf-local-save.php' );
require_once( SELLERS_PATH . 'lib/fns/admin-columns.php' );
require_once( SELLERS_PATH . 'lib/fns/options-page.php' );
require_once( SELLERS_PATH . 'lib/fns/sellers_json_endpoint.php' );
require_once( SELLERS_PATH . 'lib/fns/sellers_post_type.php' );
require_once( SELLERS_PATH . 'lib/fns/update_api.php' );

function sellers_json_plugin_activate(){
  add_rewrite_rule('^sellers\.json$', 'index.php?json=sellers', 'top');
  flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sellers_json_plugin_activate' );

/**
 * Handles plugin updates.
 */
if( is_admin() ){
  if( ! function_exists( 'get_plugin_data' ) )
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');

  $currentPluginData = get_plugin_data( __FILE__ );
}

add_filter('site_transient_update_plugins', function ($transient) {
  $checkPluginTransient = get_transient( SELLERS_PLUGIN_CHECK_TRANSIENT_NAME );

  $pluginData = $checkPluginTransient ?: SellersJson\updates\fetch_remote_data();

  if (!$checkPluginTransient) {
    set_transient(
      SELLERS_PLUGIN_CHECK_TRANSIENT_NAME,
      $pluginData,
      SELLERS_PLUGIN_CHECK_EXPIRATION
    );
  }

  if (version_compare($pluginData->new_version, $currentPluginData["Version"], ">")) {
    $transient->response['sellers-json/sellers-json.php'] = $pluginData;
  } else {
    $transient->no_update['sellers-json/sellers-json.php'] = $pluginData;
  }

  return $transient;
});
