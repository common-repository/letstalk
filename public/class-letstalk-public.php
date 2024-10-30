<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link 		https://topishare.com
 * @since 		1.0.0
 *
 * @package 	Letstalk
 * @subpackage 	Letstalk/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package 	Letstalk
 * @subpackage 	Letstalk/public
 * @author 		topishare <dev@topishare.com>
 */
class Letstalk_Public {

    private static $letstalk_inserted = false;                   // to disable multiple buttons on the same page


	/**
	 * The plugin options.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$options    The plugin options.
	 */
	private $options;

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
	 * @param 		string 			$Letstalk 		The name of the plugin.
	 * @param 		string 			$version 			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->set_options();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 		1.0.0
	 */
	public function enqueue_styles() {
        /*@@
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/letstalk-public.css', array(), $this->version, 'all' );
        */
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 		1.0.0
	 */
	public function enqueue_scripts() {
        /*@@
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/letstalk-public.js', array( 'jquery' ), $this->version, true );
        */
	}


	/**
	 * Sets the class variable $options
	 */
	private function set_options() {

		$this->options = get_option( $this->plugin_name . '-options' );

	} 



    /**
     * insert the lets talk button code after an article - if it it's a single page
     * set as a hook in define_public_hooks()
     */
    public function insert_letstalk_code_content($content) {

        if (is_single()) {
            $content .= $this->options['letstalk-button-code'];
            $this->letstalk_inserted = true;
        }
        return $content;
    }

    /**
     * insert the lets talk button code at the footer if it was not inserted before
     * set as a hook in define_public_hooks()
     */
    public function insert_letstalk_code_footer() {

        if (!$this->letstalk_inserted) {
            if (!empty($this->options['letstalk-button-code']))
                echo $this->options['letstalk-button-code'];

            $this->letstalk_inserted = true;
        }
    }






} // class
