<?php

/**
 * Fired during plugin activation
 *
 * @link 		https://topishare.com
 * @since 		1.0.0
 *
 * @package 	Letstalk
 * @subpackage 	Letstalk/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 		1.0.0
 * @package 	Letstalk
 * @subpackage 	Letstalk/includes
 * @author 		topishare <dev@topishare.com>
 */
class Letstalk_Activator {

	/**
	 * Declare custom post types, taxonomies, and plugin settings
	 * Flushes rewrite rules afterwards
	 *
	 * @since 		1.0.0
	 */
	public static function activate() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-letstalk-admin.php';

		flush_rewrite_rules();

		$opts 		= array();
		$options 	= Letstalk_Admin::get_options_list();
		foreach ( $options as $option ) {
			$opts[ $option[0] ] = $option[2];
		}

		update_option( 'letstalk-options', $opts );

		Letstalk_Admin::add_admin_notices();

	} // activate()
} // class
