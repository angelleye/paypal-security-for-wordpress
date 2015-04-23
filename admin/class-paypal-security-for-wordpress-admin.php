<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    paypal-security-for-wordpress
 * @subpackage paypal-security-for-wordpress/admin
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Security_for_WordPress_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();
    }

    /**
     * Register the stylesheets for the Dashboard.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/paypal-security-for-wordpress-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the dashboard.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-progressbar');

        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_script($this->plugin_name . 'one', plugin_dir_url(__FILE__) . 'js/paypal-security-for-wordpress-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . 'three', plugin_dir_url(__FILE__) . 'js/paypal-security-for-wordpress-jquery.form.min.js', array('jquery'), $this->version, false);
    }

    public function load_dependencies() {
        /**
         * The class responsible for defining all actions that occur in the Dashboard
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/paypal-security-for-wordpress-admin-display.php';
    }

    public function paypal_security_for_wordpress_scan_action_fn() {


        $get_array_with_paypal = new AngellEYE_PayPal_Security_for_WordPress_PayPal_Helper();
        $paypal_security_scanner_finalarrayresult = array();

        $paypal_security_scanner_finalarrayresult = $get_array_with_paypal->paypal_security_for_wordpress_get_arraywithpaypaltext();
        if (isset($paypal_security_scanner_finalarrayresult) && !empty($paypal_security_scanner_finalarrayresult)) {
            ?>
            <h3> Below pages have unsecured paypal buttons.</h3> 

            <table class="form-table tbl_paypal_unsecure_data">
                <thead>
                    <tr>
                        <td><strong>Page Id</strong></td>
                        <td><strong>Page Url</strong></td>
                        <td><strong>Unsecure Note</strong></td>
                    </tr>
                    <?php foreach ($paypal_security_scanner_finalarrayresult['unsecure'] as $key_paypal_security_scanner_finalarrayresult_unsecure => $paypal_security_scanner_finalarrayresult_unsecure_value) { ?>
                        <tr>
                            <td><?php echo $key_paypal_security_scanner_finalarrayresult_unsecure; ?></td>
                            <td><a href='<?php echo get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure); ?>' target="_blank"><?php echo get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure); ?></td>
                            <td><?php
                if (count($paypal_security_scanner_finalarrayresult_unsecure_value) > 1) {

                    foreach ($paypal_security_scanner_finalarrayresult_unsecure_value as $paypal_security_scanner_finalarrayresult_unsecure_value_key => $paypal_security_scanner_finalarrayresult_unsecure_value_value) {

                        echo '<textarea readonly="readonly" class="txt_unsecurenote">' . $paypal_security_scanner_finalarrayresult_unsecure_value_value . '</textarea><br/>';
                    }
                } else {
                    $key_single = array_keys($paypal_security_scanner_finalarrayresult_unsecure_value);
                    echo '<textarea readonly="readonly" class="txt_unsecurenote">' . $paypal_security_scanner_finalarrayresult_unsecure_value[$key_single['0']] . '</textarea>';
                }
                        ?>

                            </td>


                        </tr>

                    <?php } ?>

                    </tr>
            </table>	  
            <?php
        } else {
            echo "<h3> No unsecured button founds.</h3>";
        }
        unset($paypal_security_scanner_finalarrayresult);
        if (isset($paypal_security_for_wordpress_content)) {
            unset($paypal_security_for_wordpress_content);
        }
        exit(1);
    }

}
