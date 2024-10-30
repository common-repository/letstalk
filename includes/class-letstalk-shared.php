<?php

/**
 * The public & admin-facing shared functionality of the plugin.
 *
 * @link 		https://topishare.com
 * @since 		1.0.0
 *
 * @package 	Letstalk
 * @subpackage 	Letstalk/includes
 */

/**
 * The public & admin-facing shared functionality of the plugin.
 *
 * @package 	Letstalk
 * @subpackage 	Letstalk/includes
 * @author 		topishare <dev@topishare.com>
 */

 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) { exit; }

class Letstalk_Shared {

	/**
	 * The ID of this plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$plugin_name 		The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$version 			The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		1.0.0
	 * @param 		string 			$Letstalk 		The name of this plugin.
	 * @param 		string 			$version 			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Flushes widget cache
	 *
	 * @since 		1.0.0
	 * @access 		public
	 * @param 		int 		$post_id 		The post ID
	 * @return 		void
	 */
	public function flush_widget_cache( $post_id ) {

		if ( wp_is_post_revision( $post_id ) ) { return; }

		$post = get_post( $post_id );

    	wp_cache_delete( $this->plugin_name, 'widget' );

	} // flush_widget_cache()



	/**
	 * Registers widgets with WordPress
	 *
	 * @since 		1.0.0
	 * @access 		public
	 */
	public function widgets_init() {

		register_widget( 'letstalk_widget' );

	} // widgets_init()

} // class