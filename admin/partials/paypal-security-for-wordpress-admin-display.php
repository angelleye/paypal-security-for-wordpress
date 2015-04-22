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
        ?>
        <div id="paypal_security_scanner_fieldset">
        <fieldset>
        <legend>PayPal security scanner for WordPress</legend>


        <form id="frm_scan">
        <h4>Click Scan Now button for scan all unsecure PayPal buttons.</h4>
            <span id="btn_pswp" class="button button-primary btn_pswp">Scan Now</span>
            <img src="<?php echo plugin_dir_url(__FILE__) ?>images/ajax-loader.gif" id="loader_gifimg"/>
            <div id="div_progress">
			 <div id="progressbar"><div class="progress-label"></div></div>
			</div>
        </form>
	</fieldset>
	</div>
        <?php do_action('paypal_scan_action'); ?>
        <div id="paypal_scan_response">

        </div>
        <?php
    }

}

AngellEYE_PayPal_Security_for_WordPress_Admin_Display::init();