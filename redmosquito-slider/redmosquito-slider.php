<?php
/**
 * Plugin Name: Redmosquito Slider
 * Description: A slider for the header of the Redmosquito website
 * Plugin URI: https://github.com/KiOui/redmosquito-theme
 * Version: 0.0.1
 * Author: Lars van Rhijn
 * Author URI: https://larsvanrhijn.nl/
 * Text Domain: redmosquito-slider
 * Domain Path: /languages/
 *
 * @package redmosquito-slider
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'REDSLI_PLUGIN_FILE' ) ) {
	define( 'REDSLI_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'REDSLI_PLUGIN_URI' ) ) {
	define( 'REDSLI_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
}

include_once dirname( __FILE__ ) . '/includes/class-redslicore.php';

RedSliCore::instance();
