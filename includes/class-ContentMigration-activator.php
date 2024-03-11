<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 * @package    Content Migration Plugin
 * @subpackage ContentMigration/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    ContentMigration
 * @subpackage ContentMigration/includes
 * @author     Ramandeep Singh <raman@insteptechnologies.com>
 */
class ContentMigration_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $table_prefix, $wpdb;

		$tblname = 'content_migration_settings';
		$wp_track_table = $table_prefix . "$tblname ";

		#Check to see if the table exists already, if not, then create it
		$table = $wpdb->get_var( "SHOW TABLES LIKE 'wp_content_migration_settings'" );
		if(!$table) 
		{
			$sql = "CREATE TABLE $wp_track_table (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				`auth_email`  varchar(255) NOT NULL,
				`db_host`  varchar(255) NOT NULL,
				`db_name`  varchar(255) NOT NULL,
				`db_username`  varchar(255) NOT NULL,
				`db_passworsd`  varchar(255) NOT NULL,
				`site_url`  varchar(255) NOT NULL,
				reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
				)";
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			dbDelta($sql);
		}
	}

	public static function mainMenuContentLoad() {
		if(current_user_can('administrator')) {  
			// Stuff here for administrators//
			require_once (dirname(dirname( __FILE__ )) . '/admin/html/index.php');
		}
		else {
			require_once (dirname(dirname( __FILE__ )) . '/admin/class-ContentMigration-admin.php');
			ContentMigration_Admin :: showError("You Are Not Allowed To View This Page");
		}
	}


	public static function subMenuContentLoad() {
		if(current_user_can('administrator')) {  
			// Stuff here for administrators//
			require_once (dirname(dirname( __FILE__ )) . '/admin/html/settings.php');
		}
		else {
			require_once (dirname(dirname( __FILE__ )) . '/admin/class-ContentMigration-admin.php');
			ContentMigration_Admin :: showError("You Are Not Allowed To View This Page");
		}
	}
}
?>
