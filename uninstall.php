<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://allsteeply.com
 * @since      1.0.0
 *
 * @package    Steeply_Qr
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

//Delete SQR Directory from uploads
$sqr_directory = escapeshellarg(WP_CONTENT_DIR.'/uploads/sqr/');
exec("rmdir /s /q $sqr_directory");