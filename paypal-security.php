<?php

/**
 * @wordpress-plugin
 * Plugin Name:       PayPal Security
 * Plugin URI:        http://www.angelleye.com/
 * Description:       A security scanner that looks for PayPal concerns and provides feedback.
 * Version:           1.0.5
 * Author:            Angell EYE
 * Author URI:        http://www.angelleye.com/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       paypal-security
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
/**
 * define plugin basename
 */
if (!defined('PAYPAL_SECURITY_PLUGIN_BASENAME')) {
    define('PAYPAL_SECURITY_PLUGIN_BASENAME', plugin_basename(__FILE__));
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-paypal-security-activator.php
 */
function activate_paypal_security() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-paypal-security-activator.php';
    AngellEYE_PayPal_Security_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-paypal-security-deactivator.php
 */
function deactivate_paypal_security() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-paypal-security-deactivator.php';
    AngellEYE_PayPal_Security_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_paypal_security');
register_deactivation_hook(__FILE__, 'deactivate_paypal_security');

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-paypal-security.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_paypal_security() {

    $plugin = new AngellEYE_PayPal_Security();
    $plugin->run();
}

run_paypal_security();