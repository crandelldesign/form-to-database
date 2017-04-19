<?php

/**
 * Form to Database
 *
 * A WordPress plgugin to add form data to the database.
 * Also viewable from the admin area.
 *
 * @link              http://www.crandelldesign.com
 * @since             1.0.0
 * @package           Form_to_Database
 *
 * @wordpress-plugin
 * Plugin Name:       Form to Database
 * Plugin URI:        https://github.com/crandelldesign/form-to-database
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Crandell Design
 * Author URI:        http://www.crandelldesign.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       form-to-database
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in inc/class-form-to-database-activator.php
 */
function activate_form_to_database() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/class-form-to-database-activator.php';
	Form_to_Database_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in inc/class-form-to-database-deactivator.php
 */
function deactivate_form_to_database() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/class-form-to-database-deactivator.php';
	Form_to_Database_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_form_to_database' );
register_deactivation_hook( __FILE__, 'deactivate_form_to_database' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'inc/class-form-to-database.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_form_to_database() {

	$plugin = new Form_to_Database();
	$plugin->run();

}
run_form_to_database();
