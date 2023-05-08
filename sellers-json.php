<?php
/**
 * Plugin Name:     Sellers.json Editor
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Implements the sellers.json standard for your WordPress site.
 * Author:          TheWebist
 * Author URI:      https://mwender.com
 * Text Domain:     sellers-json
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Sellers_Json
 */

define( 'SELLERS_PATH', plugin_dir_path( __FILE__ ) );
define( 'SELLERS_URL', plugin_dir_url( __FILE__ ) );

require_once( SELLERS_PATH . 'lib/fns/include-acf.php' );
require_once( SELLERS_PATH . 'lib/fns/admin-columns.php' );
require_once( SELLERS_PATH . 'lib/fns/options-page.php' );
require_once( SELLERS_PATH . 'lib/fns/sellers_json_endpoint.php' );
require_once( SELLERS_PATH . 'lib/fns/sellers_post_type.php' );

function sellers_json_plugin_activate(){
  add_rewrite_rule('^sellers\.json$', 'index.php?json=sellers', 'top');
  flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sellers_json_plugin_activate' );