<?php

/**
 * @class       AngellEYE_PayPal_Security_for_WordPress
 * @version		1.0.0
 * @package		paypal-security-for-wordpress
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Security_for_WordPress_Admin_Display {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_settings_menu'));
    }
    
     public static function add_settings_menu() {
        add_options_page('PayPal Security for WordPress', 'PayPal Security for WordPress', 'manage_options', 'paypal-security-for-wordpress-option', array(__CLASS__, 'paypal_security_for_wordpress_options'));
    }
    
    public static function paypal_security_for_wordpress_options() {
    	  $get_array_with_paypal = new AngellEYE_PayPal_Security_for_WordPress_PayPal_Helper();
    	  $test = $get_array_with_paypal->paypal_security_for_wordpress_get_arraywithpaypaltext();
    	  echo $test;
    }

}

AngellEYE_PayPal_Security_for_WordPress_Admin_Display::init();