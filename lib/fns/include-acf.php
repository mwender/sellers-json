<?php

// Define path and URL to the ACF plugin.
define( 'MY_ACF_PATH', SELLERS_PATH . 'lib/acf/' );
define( 'MY_ACF_URL', SELLERS_URL . 'lib/acf/' );

// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );

// Customize the url setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}

// (Optional) Hide the ACF admin menu item.
if(
  ! in_array( 'advanced-custom-fields/acf.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
  && ! in_array( 'advanced-custom-fields-pro/acf.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
){
  //plugin is activated
  add_filter('acf/settings/show_admin', '__return_false');
  // When including the PRO plugin, hide the ACF Updates menu
  add_filter('acf/settings/show_updates', '__return_false', 100);
}