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
        <h2 >PayPal Security for WordPress </h2><br/>

        <?php
        $get_array_with_paypal = new AngellEYE_PayPal_Security_for_WordPress_PayPal_Helper();
        $paypal_security_scanner_finalarrayresult = array();
        ?>
        <form method="POST" action="">
            <input type="submit" name="btn_pswp" class="button button-primary" value="Scan now">
        </form>
        <?php
        if (isset($_POST['btn_pswp'])) {
            $paypal_security_scanner_finalarrayresult = $get_array_with_paypal->paypal_security_for_wordpress_get_arraywithpaypaltext();
            if (isset($paypal_security_scanner_finalarrayresult) && !empty($paypal_security_scanner_finalarrayresult)) {
                ?>
                <h3> Below pages have unsecured paypal buttons.</h3> 
                <table  class="form-table tbl_paypal_unsecure_data">
                    <tr>
                        <td colspan="2"><strong>Secure Button Pages</strong></td>

                        <?php foreach ($paypal_security_scanner_finalarrayresult['secure'] as $key_paypal_security_scanner_finalarrayresult_sercure => $paypal_security_scanner_finalarrayresult_secure_value) { ?>
                        <tr>
                            <td><?php echo $key_paypal_security_scanner_finalarrayresult_sercure; ?></td>
                            <td><a href='<?php echo $paypal_security_scanner_finalarrayresult_secure_value; ?>' target="_blank"><?php echo $paypal_security_scanner_finalarrayresult_secure_value; ?></td>

                        </tr>

                    <?php } ?>
                </table>
                <table class="form-table tbl_paypal_unsecure_data">
                    <tr>
                        <td colspan="2"><strong>Unsecure Button Pages</strong></td>

                        <?php foreach ($paypal_security_scanner_finalarrayresult['unsecure'] as $key_paypal_security_scanner_finalarrayresult_unsecure => $paypal_security_scanner_finalarrayresult_unsecure_value) { ?>
                        <tr>
                            <td><?php echo $key_paypal_security_scanner_finalarrayresult_unsecure; ?></td>
                            <td><a href='<?php echo $paypal_security_scanner_finalarrayresult_unsecure_value; ?>' target="_blank"><?php echo $paypal_security_scanner_finalarrayresult_unsecure_value; ?></td>

                        </tr>

                    <?php } ?>

                </tr>
                </table>	  
                <?php
            } else {
                echo "<h3> No unsecured button founds.</h3>";
            }
        }
        unset($paypal_security_scanner_finalarrayresult);
    }

}

AngellEYE_PayPal_Security_for_WordPress_Admin_Display::init();