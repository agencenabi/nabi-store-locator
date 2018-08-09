<?php

	/**
	 * Store Locator Custom fields
	 *
	 * @package vdsl
	 * @version 0.01
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}


	/**
	 * Create the custom fields meta box
	 *
	 */
	function vdsl_add_meta_box() {
		//this will add the metabox for the Events post type
		$screens = array( 'vdStores' );

		foreach ( $screens as $screen ) {

		    add_meta_box(
		        'vdsl_sectionid',
		        __( 'Store Infos', 'vdsl' ),
		        'vdsl_metabox_callback',
		        $screen
		    );

		}
	}
	add_action( 'add_meta_boxes', 'vdsl_add_meta_box' );


	/**
	 * Prints the box content.
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	function vdsl_metabox_callback( $post ) {

		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'vdsl_save_meta_box_data', 'vdsl_meta_box_nonce' );

		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */

		$coordinateLatValue = get_post_meta( $post->ID, 'coordinateLat_value_key', true );
		$coordinateLongValue = get_post_meta( $post->ID, 'coordinateLong_value_key', true );

		echo '<div class="storesCF__row">';

			// Coordinates - Lat
			echo '<div class="storeCF__col--half">';
			echo '<label for="vdsl_coordinateLat">' . _e( 'Latitude', 'vdsl' ) . '</label> ';
			echo '<input type="text" id="vdsl_coordinateLat" name="vdsl_coordinateLat" value="' . esc_attr( $coordinateLatValue ) . '" size="25" />';
			echo '</div>';

			// Coordinates - Long
			echo '<div class="storeCF__col--half">';
			echo '<label for="vdsl_coordinateLong">' . _e( 'Longitude', 'vdsl' ). '</label> ';
			echo '<input type="text" id="vdsl_coordinateLong" name="vdsl_coordinateLong" value="' . esc_attr( $coordinateLongValue ) . '" size="25" />';
			echo '</div>';

		echo '</div>';


	}


	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	function vdsl_save_meta_box_data( $post_id ) {

		if ( ! isset( $_POST['vdsl_meta_box_nonce'] ) ) {
	    	return;
		}

		if ( ! wp_verify_nonce( $_POST['vdsl_meta_box_nonce'], 'vdsl_save_meta_box_data' ) ) {
	    	return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	    	return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

	    	if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
	    	}

		} else {

	    	if ( ! current_user_can( 'edit_post', $post_id ) ) {
	        	return;
	    	}
		}

		// Update Post
		$coordinateLatField = sanitize_text_field( $_POST['vdsl_coordinateLat'] );
		$coordinateLongField = sanitize_text_field( $_POST['vdsl_coordinateLong'] );

		update_post_meta( $post_id, 'coordinateLat_value_key', $coordinateLatField );
		update_post_meta( $post_id, 'coordinateLong_value_key', $coordinateLongField );

	}

	add_action( 'save_post', 'vdsl_save_meta_box_data' );



	/**
	 * Register Custom Fields for REST API
	 *
	 */
	add_action( 'rest_api_init', 'vdsl_api_posts_meta_field' );
	function vdsl_api_posts_meta_field() {

	    // register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
	    register_rest_field( 'storelocator', 'post-meta-fields', array(
	           'get_callback'    => 'vdsl_get_post_meta_for_api',
	           'schema'          => null,
	        )
	    );
	}

	function vdsl_get_post_meta_for_api( $object ) {
	    //get the id of the post object array
	    $post_id = $object['id'];

	    //return the post meta
	    return get_post_meta( $post_id );
	}


?>
