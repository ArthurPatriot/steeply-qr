<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://allsteeply.com
 * @since             1.0.0
 * @package           Steeply_Qr
 *
 * @wordpress-plugin
 * Plugin Name:       Steeply QR
 * Plugin URI:        https://sqr.allsteeply.com
 * Description:       Generate QR Codes for your Posts, Pages and Custom Post Types.
 * Version:           1.0.5
 * Author:            Artur Khylskyi
 * Author URI:        https://allsteeply.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       steeply-qr
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * SemVer - https://semver.org
 */
define( 'STEEPLY_QR_VERSION', '1.0.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-steeply-qr-activator.php
 */
function activate_steeply_qr() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-steeply-qr-activator.php';
	Steeply_Qr_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-steeply-qr-deactivator.php
 */
function deactivate_steeply_qr() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-steeply-qr-deactivator.php';
	Steeply_Qr_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_steeply_qr' );
register_deactivation_hook( __FILE__, 'deactivate_steeply_qr' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-steeply-qr.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_steeply_qr() {

	$plugin = new Steeply_Qr();
	$plugin->run();

}
run_steeply_qr();
