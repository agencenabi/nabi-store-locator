<?php
/**
* Plugin Name: Nabi Store Locator
* Plugin URI: https://agencenabi.com
* Description: A simple and elegant store locator.
* Version: 1.0
* Author: Marc-André Lavigne
* Author URI: https://agencenabi.com
* License: GPLv2 or later
**/

// Setup global variable for dynamic plugin directory name
global $pluginName;
$pluginName = plugins_url() . '/' . dirname( plugin_basename( __FILE__ ) );

// Include all that beautiful code!
include_once( 'includes/settings.php' );
include_once( 'includes/register.php' );
include_once( 'includes/functions.php' );
include_once( 'includes/locate-template.php' );
include_once( 'includes/shortcodes.php' );
include_once( 'includes/fields.php' );

?>