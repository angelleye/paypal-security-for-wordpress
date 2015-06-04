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
        add_management_page('PayPal Security', 'PayPal Security', 'manage_options', 'paypal-security-for-wordpress', array(__CLASS__, 'paypal_security_for_wordpress_options'));
    }

    public static function paypal_security_for_wordpress_options() {
        ?>
        <div id="paypal_security_scanner_fieldset">
            <fieldset>
                <legend><h2>PayPal security scanner for WordPress</h2></legend>


                <form id="frm_scan">
                    <h4>Click Scan Now button for scan all insecure PayPal buttons.</h4>
                    <h5>This could take a while depending on how many Pages / Posts you have.</h5>
                    <span id="btn_pswp" class="button button-primary btn_pswp">Scan Now</span>
                    <img src="<?php echo plugin_dir_url(__FILE__) ?>images/ajax-loader.gif" id="loader_gifimg"/>



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