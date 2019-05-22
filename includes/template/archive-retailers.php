<?php
/**
 * The template for displaying the retailers archive
 *
 * @package WordPress
 * @subpackage vdsl
 * @since vdsl 1.0
 */
 
	$path = preg_replace('/wp-content.*$/','',__DIR__);
	$blog_title = get_bloginfo( 'name' );
	$mapsApiKey = get_option( 'maps_api_key' );
?>
	<div class="vdslMap">
		<div class="vdslLoading">
			<img src="<?php echo $GLOBALS['pluginName']; ?>/includes/template/assets/img/spinner.gif"> <!-- TODO customize: Img as SVG, so you can edit color -->
		</div>
		<div id="vdslMapCanvas"></div> <!-- TODO customize: Edit Map height in the back-end -->
	</div>

	<div class="vdslStore__extras">
		<form class="vdStores u-text-white">

			<div class="vdStore__search">
				<label for="vdslPostalCode"><?php _e( 'Search by address, city or postal code', 'vdsl' ); ?></label>
				<div class="vdStore__searchwrap">
					<input name="vdslPostalCode" id="vdslPostalCode" class="vdStores__input" type="text" />
					<button type="submit" title="<?php _e( 'Search', 'vdsl' ); ?>" id="vdslSearch" class="vdStore__btn">
						<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 18.4 19.1" style="enable-background:new 0 0 18.4 19.1;" xml:space="preserve">
							<path d="M18.1,17.4l-4.5-4.7c1.2-1.4,1.8-3.1,1.8-5C15.4,3.5,12,0,7.7,0S0,3.5,0,7.7s3.5,7.7,7.7,7.7c1.6,0,3.1-0.5,4.4-1.4l4.6,4.8 c0.2,0.2,0.4,0.3,0.7,0.3c0.3,0,0.5-0.1,0.7-0.3C18.5,18.4,18.5,17.8,18.1,17.4z M7.7,2c3.1,0,5.7,2.6,5.7,5.7s-2.6,5.7-5.7,5.7 S2,10.8,2,7.7S4.6,2,7.7,2z"/>
						</svg>
					</button>
				</div>
			</div>

			<div class="vdStore__radius">
				<label for="vdslRadius"><?php _e( 'Radius', 'vdsl' ); ?></label>
				<select id="vdslRadius" name="vdslRadius" class="vdStore__select">
					<option value="10"><?php _e( '10 km', 'vdsl' ); ?></option>
					<option value="25"><?php _e( '25 km', 'vdsl' ); ?></option>
					<option value="50"><?php _e( '50 km', 'vdsl' ); ?></option>
					<option value="100"><?php _e( '100 km', 'vdsl' ); ?></option>
					<option value="1000"><?php _e( '1000 km', 'vdsl' ); ?></option>
				</select>
			</div>

			<div class="vdStore__detect">
				<a href="#" id="detect" title="<?php _e( 'Detect your location', 'vdsl' ); ?>">
					<span><?php _e( 'Detect your location', 'vdsl' ); ?></span>
				</a>
			</div>
		</form>

		<div class="vdslResults">
			<h2><?php _e('Stores near you location', 'vdsl'); ?></h2>
			<div id="vdslRetailersList">
				<!-- Append Retailers here -->
			</div>
		</div>
	</div>
	<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['pluginName']; ?>/includes/template/assets/css/style.css" />

	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=<?php echo $mapsApiKey; ?>"></script>
	<script type="text/javascript" src="<?php echo $GLOBALS['pluginName']; ?>/includes/template/assets/js/markercluster.js"></script>