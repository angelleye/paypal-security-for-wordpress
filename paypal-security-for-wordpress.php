<?php

/**
 * @wordpress-plugin
 * Plugin Name:       PayPal Security for WordPress
 * Plugin URI:        http://www.angelleye.com/
 * Description:       A security scanner for WordPress that looks for PayPal concerns and provides feedback.
 * Version:           1.0.0
 * Author:            Angell EYE
 * Author URI:        http://www.angelleye.com/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       paypal-security-for-wordpress
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-paypal-security-for-wordpress-activator.php
 */
function activate_plugin_name() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-paypal-security-for-wordpress-activator.php';
    AngellEYE_PayPal_Security_for_WordPress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-paypal-security-for-wordpress-deactivator.php
 */
function deactivate_plugin_name() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-paypal-security-for-wordpress-deactivator.php';
    AngellEYE_PayPal_Security_for_WordPress_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_plugin_name');
register_deactivation_hook(__FILE__, 'deactivate_plugin_name');

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-paypal-security-for-wordpress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

    $plugin = new AngellEYE_PayPal_Security_for_WordPress();
    $plugin->run();
}

run_plugin_name();