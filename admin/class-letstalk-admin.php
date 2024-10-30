<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link 		https://topishare.com
 * @since 		1.0.0
 *
 * @package 	Letstalk
 * @subpackage 	Letstalk/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package 	Letstalk
 * @subpackage 	Letstalk/admin
 * @author 		topishare <dev@topishare.com>
 */
class Letstalk_Admin {

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
     * @param 		string 			$Letstalk 		The name of this plugin.
     * @param 		string 			$version 			The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->set_options();

    }

    /**
     * Adds notices for the admin to display.
     * Saves them in a temporary plugin option.
     * This method is called on plugin activation, so its needs to be static.
     */
    public static function add_admin_notices() {

        $notices 	= get_option( 'letstalk_deferred_admin_notices', array() );
        //$notices[] 	= array( 'class' => 'updated', 'notice' => esc_html__( 'Letstalk: Custom Activation Message', 'letstalk' ) );
        //$notices[] 	= array( 'class' => 'error', 'notice' => esc_html__( 'Letstalk: Problem Activation Message', 'letstalk' ) );

        apply_filters( 'letstalk_admin_notices', $notices );
        update_option( 'letstalk_deferred_admin_notices', $notices );

    } // add_admin_notices

    /**
     * Adds a settings page link to a menu
     *
     * @link 		https://codex.wordpress.org/Administration_Menus
     * @since 		1.0.0
     * @return 		void
     */
    public function add_menu() {

        // Top-level page
        // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

        add_menu_page(
            __( 'letstalk button', 'letstalk' ),
            __( 'letstalk button', 'letstalk' ),
            'manage_options',
            $this->plugin_name . '-menu',
            array( $this, 'page_settings' ),
            plugins_url( 'letstalk/admin/images/topishare_logo.png' )
        );
    } // add_menu()

    /**
     * Manages any updates or upgrades needed before displaying notices.
     * Checks plugin version against version required for displaying
     * notices.
     */
    public function admin_notices_init() {

        $current_version = '1.0.0';

        if ( $this->version !== $current_version ) {
            // Do whatever upgrades needed here.
            update_option('my_plugin_version', $current_version);
            $this->add_notice();
        }

    } // admin_notices_init()

    /**
     * Displays admin notices
     *
     * @return 	string 			Admin notices
     */
    public function display_admin_notices() {

        $notices = get_option( 'letstalk_deferred_admin_notices' );
        if ( empty( $notices ) ) {
            return;
        }

        foreach ( $notices as $notice ) {
            echo '<div class="' . esc_attr( $notice['class'] ) . '"><p>' . $notice['notice'] . '</p></div>';
        }
        delete_option( 'letstalk_deferred_admin_notices' );
    } // display_admin_notices()

    /**
     * Register the stylesheets for the Dashboard.
     *
     * @since 		1.0.0
     */
    public function enqueue_styles() {

        /*@@
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/letstalk-admin.css', array(), $this->version, 'all' );
        */

    } // enqueue_styles()

    /**
     * Register the JavaScript for the dashboard.
     *
     * @since 		1.0.0
     */
    public function enqueue_scripts( $hook_suffix ) {

        global $post_type;

        $screen = get_current_screen();

        if ( $screen->id === $hook_suffix ) {

            /*@@
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-file-uploader.min.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . '-repeater', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-repeater.min.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            */

            $localize['repeatertitle'] = __( 'File Name', 'letstalk' );

            wp_localize_script( 'letstalk', 'nhdata', $localize );
        }

    } // enqueue_scripts()

    /**
     * Creates a checkbox field
     *
     * @param 	array 		$args 			The arguments for the field
     * @return 	string 						The HTML field
     */
    public function field_checkbox( $args ) {

        $defaults['class'] 			= '';
        $defaults['description'] 	= '';
        $defaults['label'] 			= '';
        $defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['value'] 			= 0;

        apply_filters( $this->plugin_name . '-field-checkbox-options-defaults', $defaults );

        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-checkbox.php' );

    } // field_checkbox()

    /**
     * Creates an editor field
     *
     * NOTE: ID must only be lowercase letter, no spaces, dashes, or underscores.
     *
     * @param 	array 		$args 			The arguments for the field
     * @return 	string 						The HTML field
     */
    public function field_editor( $args ) {

        $defaults['description'] 	= '';
        $defaults['settings'] 		= array( 'textarea_name' => $this->plugin_name . '-options[' . $args['id'] . ']' );
        $defaults['value'] 			= '';

        apply_filters( $this->plugin_name . '-field-editor-options-defaults', $defaults );

        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-editor.php' );

    } // field_editor()

    /**
     * Creates a set of radios field
     *
     * @param 	array 		$args 			The arguments for the field
     * @return 	string 						The HTML field
     */
    public function field_radios( $args ) {

        $defaults['class'] 			= '';
        $defaults['description'] 	= '';
        $defaults['label'] 			= '';
        $defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['value'] 			= 0;

        apply_filters( $this->plugin_name . '-field-radios-options-defaults', $defaults );

        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-radios.php' );

    } // field_radios()

    public function field_repeater( $args ) {

        $defaults['class'] 			= 'repeater';
        $defaults['fields'] 		= array();
        $defaults['id'] 			= '';
        $defaults['label-add'] 		= 'Add Item';
        $defaults['label-edit'] 	= 'Edit Item';
        $defaults['label-header'] 	= 'Item Name';
        $defaults['label-remove'] 	= 'Remove Item';
        $defaults['title-field'] 	= '';

/*
        $defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
*/
        apply_filters( $this->plugin_name . '-field-repeater-options-defaults', $defaults );

        $setatts 	= wp_parse_args( $args, $defaults );
        $count 		= 1;
        $repeater 	= array();

        if ( ! empty( $this->options[$setatts['id']] ) ) {

            $repeater = maybe_unserialize( $this->options[$setatts['id']][0] );

        }

        if ( ! empty( $repeater ) ) {

            $count = count( $repeater );

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-repeater.php' );

    } // field_repeater()

    /**
     * Creates a select field
     *
     * Note: label is blank since its created in the Settings API
     *
     * @param 	array 		$args 			The arguments for the field
     * @return 	string 						The HTML field
     */
    public function field_select( $args ) {

        $defaults['aria'] 			= '';
        $defaults['blank'] 			= '';
        $defaults['class'] 			= 'widefat';
        $defaults['context'] 		= '';
        $defaults['description'] 	= '';
        $defaults['label'] 			= '';
        $defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['selections'] 	= array();
        $defaults['value'] 			= '';

        apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );

        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {

            $atts['aria'] = $atts['description'];

        } elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {

            $atts['aria'] = $atts['label'];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-select.php' );

    } // field_select()

    /**
     * Creates a text field
     *
     * @param 	array 		$args 			The arguments for the field
     * @return 	string 						The HTML field
     */
    public function field_text( $args ) {

        $defaults['class'] 			= 'text widefat';
        $defaults['description'] 	= '';
        $defaults['label'] 			= '';
        $defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['placeholder'] 	= '';
        $defaults['type'] 			= 'text';
        $defaults['value'] 			= '';

        apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );

    } // field_text()

    /**
     * Creates a textarea field
     *
     * @param 	array 		$args 			The arguments for the field
     * @return 	string 						The HTML field
     */
    public function field_textarea( $args ) {

        $defaults['class'] 			= 'large-text';
        $defaults['cols'] 			= 32;
        $defaults['context'] 		= '';
        $defaults['description'] 	= '';
        $defaults['label'] 			= '';
        $defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['rows'] 			= 5;
        $defaults['value'] 			= '';

        apply_filters( $this->plugin_name . '-field-textarea-options-defaults', $defaults );

        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-textarea.php' );

    } // field_textarea()

    /**
     * Returns an array of options names, fields types, and default values
     *
     * @return 		array 			An array of options
     */
    public static function get_options_list() {

        $options = array();

        $options[] = array( 'letstalk-button-code', 'textarea', '' );

        return $options;

    } // get_options_list()

    /**
     * Adds links to the plugin links row
     *
     * @since 		1.0.0
     * @param 		array 		$links 		The current array of row links
     * @param 		string 		$file 		The name of the file
     * @return 		array 					The modified array of row links
     */
    public function link_row( $links, $file ) {

        if ( LETSTALK_FILE === $file ) {

            $links[] = '<a href="https://topishare.com">topishare site</a>';

        }

        return $links;

    } // link_row()

    /**
     * Adds a link to the plugin settings page
     *
     * @since 		1.0.0
     * @param 		array 		$links 		The current array of links
     * @return 		array 					The modified array of links
     */
    public function link_settings( $links ) {

        $links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=letstalk-menu' ) ), esc_html__( 'Settings', 'letstalk' ) );

        return $links;

    } // link_settings()



    /**
     * Creates the options page
     *
     * @since 		1.0.0
     * @return 		void
     */
    public function page_settings() {

        include( plugin_dir_path( __FILE__ ) . 'partials/letstalk-admin-page-settings.php' );

    } // page_settings()

    /**
     * Registers settings fields with WordPress
     */
    public function register_fields() {

        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

        add_settings_field(
            'letstalk-button-code',
            apply_filters( $this->plugin_name . 'label-letstalk-button-code', esc_html__( 'Let\'s talk button code', 'letstalk' ) ),
            array( $this, 'field_textarea' ),
            $this->plugin_name,
            $this->plugin_name . '-settings',
            array(
                'description' 	=> 'Paste here the code you\'ve copied form topishare site.',
                'id' 			=> 'letstalk-button-code',
                'value' 		=> '',
            )
        );

        /*@@
        add_settings_field(
            'how-to-apply',
            apply_filters( $this->plugin_name . 'label-how-to-apply', esc_html__( 'How to Apply', 'letstalk' ) ),
            array( $this, 'field_editor' ),
            $this->plugin_name,
            $this->plugin_name . '-settings',
            array(
                'description' 	=> 'Instructions for applying (contact email, phone, fax, address, etc).',
                'id' 			=> 'howtoapply'
            )
        );

        add_settings_field(
            'repeater-test',
            apply_filters( $this->plugin_name . 'label-repeater-test', esc_html__( 'Repeater Test', 'letstalk' ) ),
            array( $this, 'field_repeater' ),
            $this->plugin_name,
            $this->plugin_name . '-settings',
            array(
                'description' 	=> 'Instructions for applying (contact email, phone, fax, address, etc).',
                'fields' 		=> array(
                    array(
                        'text' => array(
                            'class' 		=> '',
                            'description' 	=> '',
                            'id' 			=> 'test1',
                            'label' 		=> '',
                            'name' 			=> $this->plugin_name . '-options[test1]',
                            'placeholder' 	=> 'Test 1',
                            'type' 			=> 'text',
                            'value' 		=> ''
                        ),
                    ),
                    array(
                        'text' => array(
                            'class' 		=> '',
                            'description' 	=> '',
                            'id' 			=> 'test2',
                            'label' 		=> '',
                            'name' 			=> $this->plugin_name . '-options[test2]',
                            'placeholder' 	=> 'Test 2',
                            'type' 			=> 'text',
                            'value' 		=> ''
                        ),
                    ),
                    array(
                        'text' => array(
                            'class' 		=> '',
                            'description' 	=> '',
                            'id' 			=> 'test3',
                            'label' 		=> '',
                            'name' 			=> $this->plugin_name . '-options[test3]',
                            'placeholder' 	=> 'Test 3',
                            'type' 			=> 'text',
                            'value' 		=> ''
                        ),
                    ),
                ),
                'id' 			=> 'repeater-test',
                'label-add' 	=> 'Add Test',
                'label-edit' 	=> 'Edit Test',
                'label-header' 	=> 'TEST',
                'label-remove' 	=> 'Remove Test',
                'title-field' 	=> 'test1'

            )
        );
        */

    } // register_fields()

    /**
     * Registers settings sections with WordPress
     */
    public function register_sections() {

        // add_settings_section( $id, $title, $callback, $menu_slug );

        add_settings_section(
            $this->plugin_name . '-settings',
            apply_filters( $this->plugin_name . 'section-title-messages', esc_html__( 'Settings', 'letstalk' ) ),
            array( $this, 'section_messages' ),
            $this->plugin_name
        );

        add_settings_section(
            $this->plugin_name . '-help',
            apply_filters( $this->plugin_name . 'section-title-help', esc_html__( 'Help', 'letstalk' ) ),
            array( $this, 'section_help' ),
            $this->plugin_name
        );

    } // register_sections()

    /**
     * Registers plugin settings
     *
     * @since 		1.0.0
     * @return 		void
     */
    public function register_settings() {

        // register_setting( $option_group, $option_name, $sanitize_callback );

        register_setting(
            $this->plugin_name . '-options',
            $this->plugin_name . '-options',
            array( $this, 'validate_options' )
        );

    } // register_settings()

    private function sanitizer( $type, $data ) {

        if ( empty( $type ) ) { return; }
        if ( empty( $data ) ) { return; }

        $return 	= '';
        $sanitizer 	= new Letstalk_Sanitize();

        $sanitizer->set_data( $data );
        $sanitizer->set_type( $type );

        $return = $sanitizer->clean();

        unset( $sanitizer );

        return $return;

    } // sanitizer()

    /**
     * Creates a settings section
     *
     * @since 		1.0.0
     * @param 		array 		$params 		Array of parameters for the section
     * @return 		mixed 						The settings section
     */

    public function section_messages( $params ) {

        include( plugin_dir_path( __FILE__ ) . 'partials/letstalk-admin-section-messages.php' );

    } // section_messages()


    /**
     * Creates a settings section
     *
     * @since 		1.0.0
     * @param 		array 		$params 		Array of parameters for the section
     * @return 		mixed 						The settings section
     */

    public function section_help( $params ) {

        include( plugin_dir_path( __FILE__ ) . 'partials/letstalk-admin-section-help.php' );

    } // section_help()

    /**
     * Sets the class variable $options
     */
    private function set_options() {

        $this->options = get_option( $this->plugin_name . '-options' );

    } // set_options()

    /**
     * Validates saved options
     *
     * @since 		1.0.0
     * @param 		array 		$input 			array of submitted plugin options
     * @return 		array 						array of validated plugin options
     */
    public function validate_options( $input ) {

        //wp_die( print_r( $input ) );

        $valid 		= array();
        $options 	= $this->get_options_list();

        foreach ( $options as $option ) {

            $name = $option[0];
            $type = $option[1];

            if ( 'repeater' === $type && is_array( $option[2] ) ) {

                $clean = array();

                foreach ( $option[2] as $field ) {

                    foreach ( $input[$field[0]] as $data ) {

                        if ( empty( $data ) ) { continue; }

                        $clean[$field[0]][] = $this->sanitizer( $field[1], $data );

                    } // foreach

                } // foreach

                $count = letstalk_get_max( $clean );

                for ( $i = 0; $i < $count; $i++ ) {

                    foreach ( $clean as $field_name => $field ) {

                        $valid[$option[0]][$i][$field_name] = $field[$i];

                    } // foreach $clean

                } // for

            } else {

                $valid[$option[0]] = $this->sanitizer( $type, $input[$name] );

            }

        }

        return $valid;

    } // validate_options()

} // class