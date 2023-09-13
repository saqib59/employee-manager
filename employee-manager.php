<?php
/**
 * Plugin Name: Employee Manager
 * Description: A plugin to manage employees and display them using a Gutenberg block.
 * Version: 1.0.0
 * Author: Saqib Ali
 * 
 * @package employee-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'EM_PLUGIN_DIR' ) ) {
	define( 'EM_PLUGIN_DIR', __DIR__ );
}
if ( ! defined( 'EM_PLUGIN_DIR_URL' ) ) {
	define( 'EM_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}

include_once EM_PLUGIN_DIR . '/includes/class-em-loader.php';
