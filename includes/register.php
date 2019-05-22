<?php
/**
 * Store Locator register styles, scripts and Custom post
 *
 * @package vdsl
 * @version 0.01
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Register Stores post type
 *
 */
add_action( 'init', 'vdsl_vdStores_init' );

function vdsl_vdStores_init() {
	$labels = array(
		'name'               => __( 'Stores', 'vdsl' ),
		'singular_name'      => __( 'Store', 'vdsl' ),
		'menu_name'          => __( 'Stores', 'vdsl' ),
		'name_admin_bar'     => __( 'Stores', 'vdsl' ),
		'add_new'            => __( 'Add New', 'vdsl' ),
		'add_new_item'       => __( 'Add a New Store', 'vdsl' ),
		'new_item'           => __( 'New Store', 'vdsl' ),
		'edit_item'          => __( 'Edit the Store', 'vdsl' ),
		'view_item'          => __( 'View Store', 'vdsl' ),
		'all_items'          => __( 'All Stores', 'vdsl' ),
		'search_items'       => __( 'Search a Store', 'vdsl' ),
		'parent_item_colon'  => __( 'Parent Store', 'vdsl' ),
		'not_found'          => __( 'No Store found', 'vdsl' ),
		'not_found_in_trash' => __( 'No Store found in trash', 'vdsl' )
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => _x('stores', 'URL slug', 'vdsl'), 'with_front' => false ),
		'capability_type'     => 'post',
		'has_archive'         => false,
		'hierarchical'        => false,
		'menu_position'       => 10,
		'menu_icon'			  => 'dashicons-store',
		'supports'            => array() // 'trackbacks', 'custom-fields', 'page-attributes', 'post-formats'
	);

	register_post_type( 'vdStores', $args );
}



function vdsl_vdStores_taxonomies() {

	// Retailer Type
	$labels = array(
		'name'			=> 'Type',
		'singular_name'	=> 'Type',
		'menu_name'		=> 'Type',
	);

	$args = array(
		'hierarchical'	=> true,
		'labels'		=> $labels,
		'show_admin_column' => true
	);

	register_taxonomy('type', array('vdStores'), $args);


	// Retailer Spécialités
	$labels = array(
		'name'			=> 'Spécialités',
		'singular_name'	=> 'Spécialité',
		'menu_name'		=> 'Spécialités',
	);

	$args = array(
		'hierarchical'	=> true,
		'labels'		=> $labels,
		'show_admin_column' => true
	);

	register_taxonomy('specialites', array('vdStores'), $args);

}
add_action('init', 'vdsl_vdStores_taxonomies', 0);
