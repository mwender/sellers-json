<?php

function cptui_register_my_cpts() {

  /**
   * Post Type: Sellers.
   */

  $labels = [
    "name" => esc_html__( "Sellers", "twentytwentythree" ),
    "singular_name" => esc_html__( "Seller", "twentytwentythree" ),
    "menu_name" => esc_html__( "My Sellers", "twentytwentythree" ),
    "all_items" => esc_html__( "All Sellers", "twentytwentythree" ),
    "add_new" => esc_html__( "Add new", "twentytwentythree" ),
    "add_new_item" => esc_html__( "Add new Seller", "twentytwentythree" ),
    "edit_item" => esc_html__( "Edit Seller", "twentytwentythree" ),
    "new_item" => esc_html__( "New Seller", "twentytwentythree" ),
    "view_item" => esc_html__( "View Seller", "twentytwentythree" ),
    "view_items" => esc_html__( "View Sellers", "twentytwentythree" ),
    "search_items" => esc_html__( "Search Sellers", "twentytwentythree" ),
    "not_found" => esc_html__( "No Sellers found", "twentytwentythree" ),
    "not_found_in_trash" => esc_html__( "No Sellers found in trash", "twentytwentythree" ),
    "parent" => esc_html__( "Parent Seller:", "twentytwentythree" ),
    "featured_image" => esc_html__( "Featured image for this Seller", "twentytwentythree" ),
    "set_featured_image" => esc_html__( "Set featured image for this Seller", "twentytwentythree" ),
    "remove_featured_image" => esc_html__( "Remove featured image for this Seller", "twentytwentythree" ),
    "use_featured_image" => esc_html__( "Use as featured image for this Seller", "twentytwentythree" ),
    "archives" => esc_html__( "Seller archives", "twentytwentythree" ),
    "insert_into_item" => esc_html__( "Insert into Seller", "twentytwentythree" ),
    "uploaded_to_this_item" => esc_html__( "Upload to this Seller", "twentytwentythree" ),
    "filter_items_list" => esc_html__( "Filter Sellers list", "twentytwentythree" ),
    "items_list_navigation" => esc_html__( "Sellers list navigation", "twentytwentythree" ),
    "items_list" => esc_html__( "Sellers list", "twentytwentythree" ),
    "attributes" => esc_html__( "Sellers attributes", "twentytwentythree" ),
    "name_admin_bar" => esc_html__( "Seller", "twentytwentythree" ),
    "item_published" => esc_html__( "Seller published", "twentytwentythree" ),
    "item_published_privately" => esc_html__( "Seller published privately.", "twentytwentythree" ),
    "item_reverted_to_draft" => esc_html__( "Seller reverted to draft.", "twentytwentythree" ),
    "item_scheduled" => esc_html__( "Seller scheduled", "twentytwentythree" ),
    "item_updated" => esc_html__( "Seller updated.", "twentytwentythree" ),
    "parent_item_colon" => esc_html__( "Parent Seller:", "twentytwentythree" ),
  ];

  $args = [
    "label" => esc_html__( "Sellers", "twentytwentythree" ),
    "labels" => $labels,
    "description" => "Defines an entity that is either direct seller of or intermediary in the selling of digital advertising.",
    "public" => true,
    "publicly_queryable" => false,
    "show_ui" => true,
    "show_in_rest" => true,
    "rest_base" => "",
    "rest_controller_class" => "WP_REST_Posts_Controller",
    "rest_namespace" => "wp/v2",
    "has_archive" => false,
    "show_in_menu" => true,
    "show_in_nav_menus" => true,
    "delete_with_user" => false,
    "exclude_from_search" => false,
    "capability_type" => "post",
    "map_meta_cap" => true,
    "hierarchical" => false,
    "can_export" => false,
    "rewrite" => [ "slug" => "seller", "with_front" => true ],
    "query_var" => true,
    "menu_icon" => "dashicons-media-spreadsheet",
    "supports" => [ "title" ],
    "show_in_graphql" => false,
  ];

  register_post_type( "seller", $args );
}
add_action( 'init', 'cptui_register_my_cpts' );
