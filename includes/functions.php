<?php
/**
 * Store Locator Function
 *
 * @package vdsl
 * @version 0.01
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * PHP to JS
 *
 * @since vdsl 1.0
 */
add_action( 'wp_enqueue_scripts', 'vdsl_php_to_js' );

function vdsl_php_to_js() {
	$vars = array(
		'site_url'      => home_url(),
		'template_url'  => get_template_directory_uri(),
		'site_title'    => get_bloginfo( 'name' ),
		'ajaxUrl' 		=> admin_url('admin-ajax.php'),
		'homeUrl'	 	=> home_url(),
	    'pluginsUrl' 	=> $GLOBALS['pluginName'],
		'post_id'      	=> get_the_ID(),
		'you'		   	=> __( 'You are here', 'vdsl' ),
		'moreinfo'		=> __( 'More info »', 'vdsl' ),
		'defaultPinImg'  => get_option( 'maps_pin_1' ),
		'selectPinImg'   => get_option( 'maps_pin_2' ),
		'clusterPinImg'  => get_option( 'maps_pin_3' ),
	);

	$vars['locate'] = ( isset( $_GET['locate'] ) || isset( $_GET['localisation'] ) ) ? true : false;

	if( isset( $_GET['address'] ) || isset( $_GET['adresse'] ) ) {
		if( isset( $_GET['address'] ) )
			$vars['address'] = $_GET['address'];
		if( isset( $_GET['adresse'] ) )
			$vars['address'] = $_GET['adresse'];

	} else {
		$vars['address'] = false;
	}

	// Map Javascript
	wp_enqueue_script('localisationmap', $GLOBALS['pluginName'] . '/includes/template/assets/js/map.js', array( 'jquery' ), '1.0.0', true );
	wp_localize_script('localisationmap', 'vdslMapScript', $vars);
	wp_enqueue_script('clustermap', $GLOBALS['pluginName'] . '/includes/template/assets/js/markercluster.js', array( 'jquery' ), '1.0.0', true );

}

function vdsl_admin_styles_scripts() {
	$vars = array(
		'mediaTitle'	 => __( 'Choose Pin', 'vdsl' ),
		'mediaBtn'	 	 => __( 'Choose Pin', 'vdsl' ),
	);

	$vars['locate'] = ( isset( $_GET['locate'] ) || isset( $_GET['localisation'] ) ) ? true : false;
	
	wp_enqueue_style( 'vdsl-admin-styles', plugins_url() . '/nabi-store-locator/includes/assets/css/vdsl-admin.css', array(), '1.0.0' );
	wp_enqueue_media();
	wp_register_script( 'vdsl-admin-scripts', plugins_url('assets/js/vdsl-admin.js' , __FILE__ ), array('jquery'));
    wp_enqueue_script('vdsl-admin-scripts');
    wp_localize_script('vdsl-admin-scripts', 'vdslMapScript', $vars);
}
add_action( 'admin_enqueue_scripts', 'vdsl_admin_styles_scripts' );


/**
 * Map points
 *
 * @since vdsl 1.0
 */
add_action( 'wp_ajax_map_points', 'vdsl_ajax_map_points' );
add_action( 'wp_ajax_nopriv_map_points', 'vdsl_ajax_map_points' );

function vdsl_ajax_map_points() {
	$points = array();

	$site_url = esc_url( home_url( '/' ) );

	$args = array(
		'post_type'      => array( 'vdStores' ),
		'status'         => 'publish',
		'order'          => 'ASC',
		'orderby'        => 'title',
		'posts_per_page' => -1
	);

	$loop = new WP_Query( $args );

	if( $loop -> have_posts() ) :
		while( $loop -> have_posts() ) : $loop -> the_post();
			$id = get_the_ID();
			$title = get_the_title();
			$slug = get_the_permalink();
			$lat = get_post_meta($id, 'coordinateLat_value_key', true);
			$long = get_post_meta($id, 'coordinateLong_value_key', true);

			array_push( $points,
			    array(
					'_lat'      => $lat,
					'_lng'      => $long,
					'_title'    => $title,
					'_address'  => $address,
					'_phone'    => $phone,
					'_email'    => $email,
					'_slug'		=> $slug
			    )
			);

		endwhile;
	endif;
	wp_reset_postdata();

	wp_send_json( $points );
}


/**
 * Flushing Rewrite on theme switching
 *
 * @since vdsl 1.0
 */
add_action( 'after_switch_theme', 'vdsl_rewrite_flush' );

function vdsl_rewrite_flush() {
    flush_rewrite_rules();
}
