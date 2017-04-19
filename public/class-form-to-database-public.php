<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.crandelldesign.com
 * @since      1.0.0
 *
 * @package    Form_to_Database
 * @subpackage Form_to_Database/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Form_to_Database
 * @subpackage Form_to_Database/public
 * @author     Matt Crandell <matt@crandelldesign.com>
 */
class Form_to_Database_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $form_to_database    The ID of this plugin.
	 */
	private $form_to_database;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $form_to_database       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $form_to_database, $version ) {

		$this->form_to_database = $form_to_database;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Form_to_Database_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Form_to_Database_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->form_to_database, plugin_dir_url( __FILE__ ) . 'css/form-to-database-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Form_to_Database_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Form_to_Database_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->form_to_database, plugin_dir_url( __FILE__ ) . 'js/form-to-database-public.js', array( 'jquery' ), $this->version, false );

	}

}
