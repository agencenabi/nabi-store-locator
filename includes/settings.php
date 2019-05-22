<?php
/**
 * Store Locator Settings
 *
 * @package vdsl
 * @version 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// Create custom plugin settings menu
add_action('admin_menu', 'vdsl_create_menu');

function vdsl_create_menu() {

	//create new top-level menu
	add_menu_page('Store Locator', 'Store Locator', 'administrator', __FILE__, 'vdsl_settings_page' , plugins_url('/assets/img/icon.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'register_vdsl_settings' );
}


// register fields in WP Options (wp-admin/options.php)
function register_vdsl_settings() {
	register_setting( 'vdsl-settings-group', 'maps_api_key' );
	
	// Tab #2
	register_setting( 'vdsl-settings-group2', 'maps_pin_1' );
	register_setting( 'vdsl-settings-group2', 'maps_pin_2' );
	register_setting( 'vdsl-settings-group2', 'maps_pin_3' );
}


// Create Setting Page
function vdsl_settings_page() {
?>
<div class="wrap">
	<h1><?php _e('Localisation Settings', 'vdsl'); ?></h1>

	<div class="vdsl-tabs-nav">
		<div class="active tab1">
			<?php _e('Settings', 'vdsl'); ?>
		</div>
		<div class="tab2">
			<?php _e('Design', 'vdsl'); ?>
		</div>
	</div>
	<div class="vdsl-tabs">
		<div class="tab tab1">
			<form method="post" action="options.php">
			    <?php settings_fields( 'vdsl-settings-group' ); ?>
			    <?php do_settings_sections( 'vdsl-settings-group' ); ?>
			    <table class="form-table">
			        <tr valign="top">
			        	<th scope="row"><?php _e('Google Maps API Key', 'vdsl'); ?></th>
						<td><input type="text" name="maps_api_key" value="<?php echo esc_attr( get_option('maps_api_key') ); ?>" /></td>
			        </tr>
			    </table>
			    <?php submit_button(); ?>
			</form>
		</div>
		<div class="tab tab2" style="display: none;">
			<form method="post" action="options.php">
				<table class="form-table">
					<?php settings_fields( 'vdsl-settings-group2' ); ?>
					<?php do_settings_sections( 'vdsl-settings-group2' ); ?>
					<tr valign="top">
			        	<th scope="row"><?php _e('Default Pin', 'vdsl'); ?></th>
						<td>
							<div class="vdsl_upload_wrap">
								<img class="vdsl_upload_img" id="defaultpin_img" src="<?php echo esc_attr( get_option('maps_pin_1') ); ?>">
							</div>
							<input class="vdsl_upload_text" type="text" id="defaultpin_url" name="maps_pin_1" value="<?php echo esc_attr( get_option('maps_pin_1') ); ?>" style="display: none;" />
					        <input id="vdsl_upload_button" type="button" class="vdsl_upload_button button" value="<?php _e( 'Upload', 'vdsl' ); ?>" />
						</td>
			        </tr>
			        <tr valign="top">
			        	<th scope="row"><?php _e('Selected Pin', 'vdsl'); ?></th>
						<td>
							<div class="vdsl_upload_wrap">
								<img class="vdsl_upload_img" id="selectedpin_img" src="<?php echo esc_attr( get_option('maps_pin_2') ); ?>">
							</div>
							<input class="vdsl_upload_text" type="text" id="selectedpin_url" name="maps_pin_2" value="<?php echo esc_attr( get_option('maps_pin_2') ); ?>" style="display: none;" />
					        <input id="vdsl_upload_button2" type="button" class="vdsl_upload_button button" value="<?php _e( 'Upload', 'vdsl' ); ?>" />
						</td>
			        </tr>
			        <tr valign="top">
			        	<th scope="row"><?php _e('Cluster Pin', 'vdsl'); ?></th>
						<td>
							<div class="vdsl_upload_wrap">
								<img class="vdsl_upload_img" id="clusterpin_img" src="<?php echo esc_attr( get_option('maps_pin_3') ); ?>">
							</div>
							<input class="vdsl_upload_text" type="text" id="clusterpin_url" name="maps_pin_3" value="<?php echo esc_attr( get_option('maps_pin_3') ); ?>" style="display: none;" />
					        <input id="vdsl_upload_button3" type="button" class="vdsl_upload_button button" value="<?php _e( 'Upload', 'vdsl' ); ?>" />
						</td>
			        </tr>
			    </table>
			    <?php submit_button(); ?>
			</form>
		</div>
	</div>
</div>
<?php } ?>