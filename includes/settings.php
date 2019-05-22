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


// create custom plugin settings menu
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
	register_setting( 'vdsl-settings-group', 'logo_url' );
}

function vdsl_settings_page() {
?>
<div class="wrap">
	<h1><?php _e('Localisation Settings', 'vdsl'); ?></h1>

	<div class="vdsl-tabs-nav">
		<div class="active">
			<?php _e('Settings', 'vdsl'); ?>
		</div>
	</div>
	<div class="vdsl-tabs">
		<div class="tabcontent1">
			<form method="post" action="options.php">
			    <?php settings_fields( 'vdsl-settings-group' ); ?>
			    <?php do_settings_sections( 'vdsl-settings-group' ); ?>
			    <table class="form-table">
			        <tr valign="top">
			        	<th scope="row"><?php _e('Google Maps API Key', 'vdsl'); ?></th>
						<td><input type="text" name="maps_api_key" value="<?php echo esc_attr( get_option('maps_api_key') ); ?>" /></td>
			        </tr>
			        
			        <input type="text" id="logo_url" name="theme_wptuts_options[logo]" value="<?php echo esc_url( $wptuts_options['logo'] ); ?>" />
			        <input id="upload_logo_button" type="button" class="button" value="<?php _e( 'Upload Logo', 'wptuts' ); ?>" />
			        <span class="description"><?php _e('Upload an image for the banner.', 'wptuts' ); ?></span>
			        
			    </table>
			    <?php submit_button(); ?>
			</form>
		</div>
		<div class="tabcontent2" style="display: none;">
			<!-- Tabs #2 -->
		</div>
	</div>
</div>
<?php } ?>