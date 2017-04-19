<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.crandelldesign.com
 * @since      1.0.0
 *
 * @package    Form_to_Database
 * @subpackage Form_to_Database/inc
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Form_to_Database
 * @subpackage Form_to_Database/inc
 * @author     Matt Crandell <matt@crandelldesign.com>
 */
class Form_to_Database_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'form_to_database';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            id bigint(20) NOT NULL AUTO_INCREMENT,
            email varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
            name varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
            data text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
            form_name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

	}

}
