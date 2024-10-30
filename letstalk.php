<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @author             topishare
 * @link               https://topishare.com
 * @since              1.0.0
 * @package            Letstalk
 *
 * @wordpress-plugin
 * Plugin Name:        Letstalk
 * Plugin URI:         https://topishare.com/plugins/letstalk/
 * Description:        Add a topishare Let's talk button on your site and create your own customized social space
 * Version:            1.0.0
 * Author:             topishare
 * Author URI:         https://topishare.com/
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:        letstalk
 * Domain Path:        /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Used for referring to the plugin file or basename
if (!defined('LETSTALK_FILE')) {
    define('LETSTALK_FILE', plugin_basename(__FILE__));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-letstalk-activator.php
 */
function activate_Letstalk()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-letstalk-activator.php';
    Letstalk_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-letstalk-deactivator.php
 */
function deactivate_Letstalk()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-letstalk-deactivator.php';
    Letstalk_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_Letstalk');
register_deactivation_hook(__FILE__, 'deactivate_Letstalk');

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-letstalk.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since        1.0.0
 */
function run_Letstalk()
{

    $plugin = new Letstalk();
    $plugin->run();

}

run_Letstalk();
