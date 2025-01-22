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
      /*'id'            => SELLERS_PLUGIN_FILE,*/
      'name'          => 'Sellers.json Editor',
      'slug'          => SELLERS_PLUGIN_SLUG,
      'plugin'        => SELLERS_PLUGIN_FILE,
      'new_version'   => $remoteData->version,  // <-- Important!
      'url'           => 'https://sellers-json.wenmarkdigital.com',
      'package'       => $remoteData->package,  // <-- Important!
      'icons'         => [],
      'banners'       => [],
      'banners_rtl'   => [],
      'tested'        => '',
      'requires_php'  => '',
      'compatibility' => new \stdClass(),
      'banners'       => [
        'low' => 'There is a new version of the Sellers.json Editor.',
      ],
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
function filter_plugin_info( $res, $action, $args ) {
  // Do nothing if you're not getting plugin information right now
  if ( 'plugin_information' !== $action ) {
    return $res;
  }

  // Do nothing if it is not our plugin
  if ( SELLERS_PLUGIN_SLUG !== $args->slug ) {
    return $res;
  }

  $remoteData = fetch_remote_data();
  if ( ! $remoteData ) {
    return $res;
  }

  $res = new \stdClass();
  $res->name = $remoteData->name;
  $res->slug = $remoteData->slug;
  $res->version = $remoteData->new_version;

  // Fetch changelog dynamically
  $changelog = get_plugin_changelog();

  $res->sections = array(
    'description' => 'Provides an interface for editing your site\'s sellers.json.',
    'changelog' => $changelog ? $changelog : 'Changelog could not be retrieved.',
  );

  if ( ! empty( $remoteData->banners ) ) {
    $res->banners = array(
      'low' => $remoteData->banners['low'],
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

  $currentPluginData = get_plugin_data( SELLERS_PLUGIN_FULL_FILENAME );

  if( $remoteData ){
    $res = new \stdClass();
    $res->slug = SELLERS_PLUGIN_SLUG;
    $res->plugin = SELLERS_PLUGIN_FILE;
    $res->new_version = $remoteData->new_version;
    $res->url = 'https://wenmarkdigital.com/sellers-json-wordpress-plugin';
    $res->package = $remoteData->package;
  }

  if( $remoteData && version_compare( $remoteData->new_version, $currentPluginData['Version'], '>' ) ){
    //uber_log('👉 Plugin needs an update! ' . $currentPluginData['Version'] . ' => ' . $remoteData->new_version );
    $update_plugins->response[ SELLERS_PLUGIN_FILE ] = $res;
  } else {
    //uber_log('🛑 Plugin DOES NOT need an update.');
    $update_plugins->no_update[ SELLERS_PLUGIN_FILE ] = $res;
  }

  return $update_plugins;
}
add_filter( 'site_transient_update_plugins', __NAMESPACE__ . '\\filter_update_plugins' );

/**
 * Retrieves the plugin changelog content.
 *
 * This function checks for the presence of a `readme.txt` or `README.md` file
 * within the plugin directory defined by `SELLERS_PATH`. It prioritizes the
 * `README.md` file and falls back to `readme.txt` if the former is not found.
 *
 * @return string The changelog content extracted from the appropriate file.
 */
function get_plugin_changelog() {
  
  // Prioritize `README.md`, fallback to `readme.txt`
  $readme_md_path  = SELLERS_PATH . 'README.md';
  $readme_txt_path = SELLERS_PATH . 'readme.txt';
  $changelog_content = '';

  if ( file_exists( $readme_md_path ) ) {
    $changelog_content = parse_readme_md_changelog( $readme_md_path );
  } elseif ( file_exists( $readme_txt_path  )) {
    $changelog_content = parse_readme_txt_changelog( $readme_txt_path );
  } 

  return $changelog_content;
}

/**
 * Parses the changelog section from a `readme.txt` file.
 *
 * This function reads the contents of the specified `readme.txt` file
 * and extracts the changelog section, if present. The changelog content
 * is formatted with HTML line breaks and wrapped in a `<pre>` tag for
 * display purposes.
 *
 * @param string $file_path The file path to the `readme.txt` file.
 * @return string The formatted changelog content or a message indicating
 *                that no changelog section was found.
 */
function parse_readme_txt_changelog( $file_path ) {
  $content = file_get_contents( $file_path );
  $matches = [];
  
  if ( preg_match( '/==\s*Changelog\s*==\n(.*?)(==|$)/s', $content, $matches ) ) {
    $changelog = trim( $matches[1] );

    // Convert newlines to HTML list
    $changelog = nl2br( esc_html( $changelog ) );
    return '<pre>' . $changelog . '</pre>';
  }

  return 'No changelog section found in readme.txt.';
}

/**
 * Parses the changelog section from a `README.md` file.
 *
 * This function reads the contents of the specified `README.md` file
 * and extracts the changelog section. It processes Markdown-style changelog 
 * formatting, converting version headers (###) to `<h4>` elements and list 
 * items (`* `) to HTML unordered lists.
 *
 * @param string $file_path The file path to the `README.md` file.
 * @return string The formatted changelog content in HTML or a message indicating
 *                that no changelog section was found.
 */
function parse_readme_md_changelog( $file_path ) {
  $content = file_get_contents( $file_path );
  $matches = [];

  if ( preg_match( '/##\s{1}Changelog\s{1}##\s*\R([\s\S]*?)(?=\n##\s{1}|\z)/m', $content, $matches ) ) {
    $changelog_content = trim( $matches[1] );
    $lines = explode( "\n", $changelog_content );
    $formatted_content = '';
    $in_list = false;

    foreach ( $lines as $line ) {
      $line = trim( $line );

      // Skip empty lines
      if ( empty( $line ) ) {
        continue;
      }

      // If line starts with ###, treat it as a version header
      if ( preg_match( '/^###\s*(.*?)\s*###$/', $line, $version_match ) ) {
        // Close previous list if open
        if ( $in_list ) {
          $formatted_content .= '</ul>';
          $in_list = false;
        }

        $formatted_content .= '<h4>' . esc_html( $version_match[1] ) . '</h4>';
      }
      // If line starts with *, treat it as a list item
      elseif ( strpos( $line, '* ' ) === 0 ) {
        if ( ! $in_list ) {
          $formatted_content .= '<ul>';
          $in_list = true;
        }

        $formatted_content .= '<li>' . esc_html( substr( $line, 2 ) ) . '</li>';
      }
    }

    // Close any remaining open <ul>
    if ( $in_list ) {
      $formatted_content .= '</ul>';
    }

    return $formatted_content;
  }

  return 'No changelog section found in README.md.';
}
