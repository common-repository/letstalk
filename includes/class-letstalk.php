<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link 		https://topishare.com
 * @since 		1.0.0
 *
 * @package 	Letstalk
 * @subpackage 	Letstalk/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since 		1.0.0
 * @package 	Letstalk
 * @subpackage 	Letstalk/includes
 * @author 		topishare <dev@topishare.com>
 */
class Letstalk {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since 		1.0.0
	 * @access 		protected
	 * @var 		Letstalk_Loader 		$loader 		Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 		1.0.0
	 * @access 		protected
	 * @var 		string 			$plugin_name 		The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Sanitizer for cleaning user input
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Letstalk_Sanitize    $sanitizer    Sanitizes data
	 */
	private $sanitizer;

	/**
	 * The current version of the plugin.
	 *
	 * @since 		1.0.0
	 * @access 		protected
	 * @var 		string 			$version 		The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since 		1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'letstalk';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		/*@@ if we want a widget (latest comments from topishare) $this->define_widget_hooks();  */
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Letstalk_Loader. Orchestrates the hooks of the plugin.
	 * - Letstalk_i18n. Defines internationalization functionality.
	 * - Letstalk_Admin. Defines all hooks for the dashboard.
	 * - Letstalk_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-letstalk-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-letstalk-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-letstalk-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-letstalk-public.php';

		/**
		 * The class responsible for all global functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/letstalk-global-functions.php';

		/**
		 * The class responsible for defining all actions shared by the Dashboard and public-facing sides.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-letstalk-shared.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-letstalk-widget.php';

		/**
		 * The class responsible for sanitizing user input
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-letstalk-sanitize.php';

		$this->loader = new Letstalk_Loader();
		$this->sanitizer = new Letstalk_Sanitize();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Letstalk_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 */
	private function set_locale() {

		$plugin_i18n = new Letstalk_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Letstalk_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'plugin_action_links_' . LETSTALK_FILE, $plugin_admin, 'link_settings' );
		//$this->loader->add_action( 'plugin_row_meta', $plugin_admin, 'link_row', 10, 2 );         // show a link to topishare site
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_fields' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_notices_init' );
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'display_admin_notices' );

	} // define_admin_hooks()

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 */
	private function define_public_hooks() {

		$plugin_public = new Letstalk_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_filter('the_content', $plugin_public, 'insert_letstalk_code_content' );
        $this->loader->add_action('wp_footer',   $plugin_public, 'insert_letstalk_code_footer');

        /*@@
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', $this->get_version(), TRUE );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', $this->get_version(), TRUE );
        */

	} // define_public_hooks()


	/**
	 * Register all of the hooks shared between public-facing and admin functionality
	 * of the plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 */
	private function define_shared_hooks() {

		$plugin_shared = new Letstalk_Shared( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'widgets_init', $plugin_shared, 'widgets_init' );
		$this->loader->add_action( 'deleted_post', $plugin_shared, 'flush_widget_cache' );
		$this->loader->add_action( 'switch_theme', $plugin_shared, 'flush_widget_cache' );

	} // define_shared_hooks()


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 		1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since 		1.0.0
	 * @return 		string 					The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since 		1.0.0
	 * @return 		Letstalk_Loader 		Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since 		1.0.0
	 * @return 		string 					The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}




	// Option 2

	/**
	 * Register all of the hooks shared between public-facing and admin functionality
	 * of the plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 */
	private function define_widget_hooks() {

		$this->loader->add_action( 'widgets_init', $this, 'widgets_init' );
		$this->loader->add_action( 'deleted_post', $this, 'flush_widget_cache' );
		$this->loader->add_action( 'switch_theme', $this, 'flush_widget_cache' );

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
	}



	/**
	 * Registers widgets with WordPress
	 *
	 * @since 		1.0.0
	 * @access 		public
	 */
	public function widgets_init() {

		register_widget( 'letstalk_widget' );

	} // widgets_init()

}
