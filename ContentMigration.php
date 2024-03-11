<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.wordpress.org/
 * @since             1.0.0
 * @package           Content Migration Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Content Migration Plugin
 * Plugin URI:        www.wordpress.org/
 * Description:       A plugin to move pages from one wordpress site to another.
 * Version:           1.0.0
 * Author:            Ramandeep Singh
 * Author URI:        http://insteptechnologies.com/
 * License:           License: GPL2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ContentMigration_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ContentMigration-activator.php
 */
function activate_ContentMigration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ContentMigration-activator.php';
	ContentMigration_Activator :: activate();
}
add_action('admin_menu', 'ContentMigration_setup_menu');
function test_init(){
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ContentMigration-activator.php';
	ContentMigration_Activator :: mainMenuContentLoad();
}

function loadSubMenu_Content(){
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ContentMigration-activator.php';
	ContentMigration_Activator :: subMenuContentLoad();
}

function ContentMigration_setup_menu(){
	add_menu_page( 'Content Migration', 'Content Migration', 'manage_options', 'content-migration', 'test_init' );
	add_submenu_page('content-migration', 'Settings', 'Settings', 'manage_options', 'settings_options', 'loadSubMenu_Content' );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ContentMigration-deactivator.php
 */
function deactivate_ContentMigration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ContentMigration-deactivator.php';
	ContentMigration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ContentMigration' );
register_deactivation_hook( __FILE__, 'deactivate_ContentMigration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ContentMigration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ContentMigration() {

	$plugin = new ContentMigration();
	$plugin->run();

}
run_ContentMigration();
