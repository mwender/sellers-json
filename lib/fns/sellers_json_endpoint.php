<?php

// Register the API endpoint
add_action('init', function() {
  add_rewrite_rule('^sellers\.json$', 'index.php?json=sellers', 'top');
});

// Add the 'json' query variable
add_filter('query_vars', function($vars) {
  $vars[] = 'json';
  return $vars;
});

// Define the callback function for the API endpoint
add_action('template_redirect', function() {
  $json = get_query_var('json');
  if ( $json == 'sellers' ) {
    // Set the content type to JSON
    header('Content-Type: application/json');

    // Get the sellers page content from the wp_options table
    $sellers = get_posts([
      'post_type'       => 'seller',
      'order'           => 'ASC',
      'orderby'         => 'title',
      'posts_per_page'  => -1,
    ]);

    $json = [];
    $sellers_json_options_options = get_option( 'sellers_json_options_option_name' );

    $contact_email = $sellers_json_options_options['contact_email_0'];
    if( $contact_email )
      $json['contact_email'] = $contact_email;
    $contact_address = $sellers_json_options_options['contact_address_1'];
    if( $contact_address )
      $json['contact_address'] = $contact_address;

    $json['version'] = '1.0';
    if( $sellers ){
      foreach ($sellers as $seller ) {
        $seller_id = get_post_meta( $seller->ID, 'seller_id', true );
        $seller_type = get_post_meta( $seller->ID, 'seller_type', true );
        $domain = get_post_meta( $seller->ID, 'domain', true );
        $name = get_post_meta( $seller->ID, 'name', true );
        if( empty( $name ) )
          $name = get_the_title( $seller->ID );

        // Only add Sellers with seller_id, seller_type, domain, and name to avoid syntax errors:
        if( ! empty( $seller_id ) && ! empty( $seller_type ) && ! empty( $domain ) && ! empty( $name ) )
          $json['sellers'][] = [ 'seller_id' => $seller_id, 'seller_type' => $seller_type, 'domain' => $domain, 'name' => $name ];
      }
    }

    wp_send_json( $json, 200 );
  }
});
