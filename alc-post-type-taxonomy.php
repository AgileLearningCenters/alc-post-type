<?php

// Register Custom Taxonomy
function alc_taxonomy_standing() {

  $labels = array(
    'name'                       => _x( 'ALC Standings', 'Taxonomy General Name', 'alc_text' ),
    'singular_name'              => _x( 'ALC Standing', 'Taxonomy Singular Name', 'alc_text' ),
    'menu_name'                  => __( 'Standing', 'alc_text' ),
    'all_items'                  => __( 'All Standings', 'alc_text' ),
    'parent_item'                => __( 'Parent Standing', 'alc_text' ),
    'parent_item_colon'          => __( 'Parent Standing:', 'alc_text' ),
    'new_item_name'              => __( 'New Standing', 'alc_text' ),
    'add_new_item'               => __( 'Add New Standing', 'alc_text' ),
    'edit_item'                  => __( 'Edit Standing', 'alc_text' ),
    'update_item'                => __( 'Update Standing', 'alc_text' ),
    'view_item'                  => __( 'View Standing', 'alc_text' ),
    'separate_items_with_commas' => __( 'Separate Standings with commas', 'alc_text' ),
    'add_or_remove_items'        => __( 'Add or remove Standing', 'alc_text' ),
    'choose_from_most_used'      => __( 'Choose from the most used', 'alc_text' ),
    'popular_items'              => __( 'Popular Standings', 'alc_text' ),
    'search_items'               => __( 'Search Standings', 'alc_text' ),
    'not_found'                  => __( 'Not Found', 'alc_text' ),
    'no_terms'                   => __( 'No Standings', 'alc_text' ),
    'items_list'                 => __( 'Standings list', 'alc_text' ),
    'items_list_navigation'      => __( 'Standings list navigation', 'alc_text' ),
  );
  $capabilities = array(
    'manage_terms'               => 'manage_alc_standing',
    'edit_terms'                 => 'manage_alc_standing',
    'delete_terms'               => 'manage_alc_standing',
    'assign_terms'               => 'edit_alc_standing',
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => false,
    'show_tagcloud'              => false,
    'capabilities'               => $capabilities,
  );
  register_taxonomy( 'alc-standing', array( 'alc' ), $args );

}
add_action( 'init', 'alc_taxonomy_standing', 0 );
// Register Custom Taxonomy
function alc_taxonomy_type() {

  $labels = array(
    'name'                       => _x( 'ALC types', 'Taxonomy General Name', 'alc_text' ),
    'singular_name'              => _x( 'ALC type', 'Taxonomy Singular Name', 'alc_text' ),
    'menu_name'                  => __( 'type', 'alc_text' ),
    'all_items'                  => __( 'All types', 'alc_text' ),
    'parent_item'                => __( 'Parent type', 'alc_text' ),
    'parent_item_colon'          => __( 'Parent type:', 'alc_text' ),
    'new_item_name'              => __( 'New type', 'alc_text' ),
    'add_new_item'               => __( 'Add New type', 'alc_text' ),
    'edit_item'                  => __( 'Edit type', 'alc_text' ),
    'update_item'                => __( 'Update type', 'alc_text' ),
    'view_item'                  => __( 'View type', 'alc_text' ),
    'separate_items_with_commas' => __( 'Separate types with commas', 'alc_text' ),
    'add_or_remove_items'        => __( 'Add or remove type', 'alc_text' ),
    'choose_from_most_used'      => __( 'Choose from the most used', 'alc_text' ),
    'popular_items'              => __( 'Popular types', 'alc_text' ),
    'search_items'               => __( 'Search types', 'alc_text' ),
    'not_found'                  => __( 'Not Found', 'alc_text' ),
    'no_terms'                   => __( 'No types', 'alc_text' ),
    'items_list'                 => __( 'types list', 'alc_text' ),
    'items_list_navigation'      => __( 'types list navigation', 'alc_text' ),
  );
  $capabilities = array(
    'manage_terms'               => 'manage_alc_type',
    'edit_terms'                 => 'manage_alc_type',
    'delete_terms'               => 'manage_alc_type',
    'assign_terms'               => 'edit_alc_type',
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => false,
    'show_tagcloud'              => false,
    'capabilities'               => $capabilities,
  );
  register_taxonomy( 'alc-type', array( 'alc' ), $args );

}
add_action( 'init', 'alc_taxonomy_type', 0 );