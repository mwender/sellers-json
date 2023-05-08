<?php
namespace SellersJson\admincolumns;

/**
 * Adds columns to admin donation custom post_type listings.
 *
 * @since 1.0.1
 *
 * @param array $defaults Array of default columns for the CPT.
 * @return array Modified array of columns.
 */
function columns_for_seller( $defaults ){
    $defaults = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Name',
        'seller_id' => 'Seller ID',
        'seller_type' => 'Seller Type',
        'domain' => 'Domain',
        'date' => 'Date',
    );
    return $defaults;
}
add_filter( 'manage_seller_posts_columns', __NAMESPACE__ . '\\columns_for_seller' );

function custom_column_content( $column ){
  global $post;

  switch ( $column ) {
    case 'seller_id':
    case 'seller_type':
    case 'domain':
      $content = get_post_meta( $post->ID, $column, true );
      if( ! empty( $content ) )
        echo $content;
      break;

    default:
      // code...
      break;
  }
}
add_action( 'manage_seller_posts_custom_column', __NAMESPACE__ . '\\custom_column_content', 10, 2 );