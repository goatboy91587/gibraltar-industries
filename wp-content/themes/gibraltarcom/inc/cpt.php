<?php
// Custom Post Types
// Register Custom Post Type
function custom_post_type() {

	$labels = array(
		'name'                  => _x( 'Team Members', 'Post Type General Name', 'gibraltarcom' ),
		'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'gibraltarcom' ),
		'menu_name'             => __( 'Team Members', 'gibraltarcom' ),
		'name_admin_bar'        => __( 'Team Members', 'gibraltarcom' ),
		'archives'              => __( 'Item Archives', 'gibraltarcom' ),
		'attributes'            => __( 'Item Attributes', 'gibraltarcom' ),
		'parent_item_colon'     => __( 'Parent Item:', 'gibraltarcom' ),
		'all_items'             => __( 'All Items', 'gibraltarcom' ),
		'add_new_item'          => __( 'Add New Item', 'gibraltarcom' ),
		'add_new'               => __( 'Add New Team Member', 'gibraltarcom' ),
		'new_item'              => __( 'New Item Team Member', 'gibraltarcom' ),
		'edit_item'             => __( 'Edit Item', 'gibraltarcom' ),
		'update_item'           => __( 'Update Item', 'gibraltarcom' ),
		'view_item'             => __( 'View Item', 'gibraltarcom' ),
		'view_items'            => __( 'View Items', 'gibraltarcom' ),
		'search_items'          => __( 'Search Item', 'gibraltarcom' ),
		'not_found'             => __( 'Not found', 'gibraltarcom' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'gibraltarcom' ),
		'featured_image'        => __( 'Featured Image', 'gibraltarcom' ),
		'set_featured_image'    => __( 'Set featured image', 'gibraltarcom' ),
		'remove_featured_image' => __( 'Remove featured image', 'gibraltarcom' ),
		'use_featured_image'    => __( 'Use as featured image', 'gibraltarcom' ),
		'insert_into_item'      => __( 'Insert into item', 'gibraltarcom' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'gibraltarcom' ),
		'items_list'            => __( 'Items list', 'gibraltarcom' ),
		'items_list_navigation' => __( 'Items list navigation', 'gibraltarcom' ),
		'filter_items_list'     => __( 'Filter items list', 'gibraltarcom' ),
	);
	$args = array(
		'label'                 => __( 'Team Member', 'gibraltarcom' ),
		'description'           => __( 'Post Type Description', 'gibraltarcom' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-id-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'team', $args );

}
add_action( 'init', 'custom_post_type', 0 );