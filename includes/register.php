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
 * Enqueue admin styles and scripts
 *
 * @return void
 */
function vdsl_admin_styles_scripts() {
	wp_enqueue_style( 'vdsl-admin-styles', plugins_url() . '/nabi-store-locator/assets/css/vdsl-admin.css', array(), '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'vdsl_admin_styles_scripts' );


/**
 * Register Stores post type
 *
 */
add_action( 'init', 'vinedev_vdStores_init' );

function vinedev_vdStores_init() {
	$labels = array(
		'name'               => __( 'Stores', 'vinedev' ),
		'singular_name'      => __( 'Store', 'vinedev' ),
		'menu_name'          => __( 'Stores', 'vinedev' ),
		'name_admin_bar'     => __( 'Stores', 'vinedev' ),
		'add_new'            => __( 'Add New', 'vinedev' ),
		'add_new_item'       => __( 'Add a New Store', 'vinedev' ),
		'new_item'           => __( 'New Store', 'vinedev' ),
		'edit_item'          => __( 'Edit the Store', 'vinedev' ),
		'view_item'          => __( 'View Store', 'vinedev' ),
		'all_items'          => __( 'All Stores', 'vinedev' ),
		'search_items'       => __( 'Search a Store', 'vinedev' ),
		'parent_item_colon'  => __( 'Parent Store', 'vinedev' ),
		'not_found'          => __( 'No Store found', 'vinedev' ),
		'not_found_in_trash' => __( 'No Store found in trash', 'vinedev' )
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
		'rewrite'             => array( 'slug' => _x('stores', 'URL slug', 'vinedev'), 'with_front' => false ),
		'capability_type'     => 'post',
		'has_archive'         => false,
		'hierarchical'        => false,
		'menu_position'       => 10,
		'menu_icon'			  => 'dashicons-store',
		'supports'            => array() // 'trackbacks', 'custom-fields', 'page-attributes', 'post-formats'
	);

	register_post_type( 'vdStores', $args );
}



function vinedev_vdStores_taxonomies() {

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
add_action('init', 'vinedev_vdStores_taxonomies', 0);
