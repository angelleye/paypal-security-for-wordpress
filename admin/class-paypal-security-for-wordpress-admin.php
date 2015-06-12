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
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-dialog');

        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/paypal-security-for-wordpress-admin.js', array('jquery'), $this->version, false);
        if (wp_script_is($this->plugin_name)) {
            wp_localize_script($this->plugin_name, 'paypal_security_plugin_url', apply_filters('paypal_security_plugin_url_filter', array(
                        'plugin_url' => plugin_dir_url(__FILE__)
                    )));
        }
    }

    public function load_dependencies() {
        /**
         * The class responsible for defining all actions that occur in the Dashboard
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/paypal-security-for-wordpress-admin-display.php';
    }

    public function paypal_security_for_wordpress_scan_action_fn() {

		if( isset($_POST['data']) && !empty($_POST['data']) ) {
			parse_str($_POST['data'], $post_type);
		} else {
			$post_type = array('all' => 'on');
		}
        $get_array_with_paypal = new AngellEYE_PayPal_Security_for_WordPress_PayPal_Helper();
        $paypal_security_scanner_finalarrayresult = array();
        $paypal_security_scanner_get_all_forms = array();
        $paypal_security_scanner_finalarrayresult = $get_array_with_paypal->paypal_security_for_wordpress_get_arraywithpaypaltext($post_type);
        $paypal_security_scanner_get_all_forms = $get_array_with_paypal->paypal_security_for_wordpress_get_total_forms($post_type);
        if (isset($paypal_security_scanner_finalarrayresult['total_post']) && !empty($paypal_security_scanner_finalarrayresult['total_post'])) {
            $totalpost = $paypal_security_scanner_finalarrayresult['total_post'];
        } else {
            $totalpost = '0';
        }
        if (isset($paypal_security_scanner_get_all_forms['unsecure_count']) && !empty($paypal_security_scanner_get_all_forms['unsecure_count'])) {
            $total_unsecur_count = $paypal_security_scanner_get_all_forms['unsecure_count'];
        } else {
            $total_unsecur_count = '0';
        }
        if (isset($paypal_security_scanner_get_all_forms['secure_count']) && !empty($paypal_security_scanner_get_all_forms['secure_count'])) {
            $total_secure_count = $paypal_security_scanner_get_all_forms['secure_count'];
        } else {
            $total_secure_count = '0';
        }


        if ((isset($paypal_security_scanner_finalarrayresult) && !empty($paypal_security_scanner_finalarrayresult))):
            ?>
            <div id="div_scan_result">
                <table class="tbl-scan-result form-table">
                    <tbody>
                        <tr class="color-note">
                            <th><strong>Note</strong></th>
                            <th><strong>Count</strong></th>
                        </tr>
                        <tr class="color-total">
                            <td>Total Posts and Pages Scanned:</td>
                            <td><?php echo $totalpost; ?></td>
                        </tr>
                        <tr class="color-unsecure">
                            <td>Total Unsecure Forms Found:</td>
                            <td><?php echo $total_unsecur_count; ?></td>
                        </tr>
                        <tr class="color-secure">
                            <td>Total Secure Forms Found:</td>
                            <td><?php echo $total_secure_count; ?></td>
                        </tr>
                    </tbody></table>
                <input type='hidden' id='current_page' /><input type='hidden' id='show_per_page' />
                <?php if (isset($paypal_security_scanner_finalarrayresult['button_type']) && !empty($paypal_security_scanner_finalarrayresult['button_type'])) { ?>
                    <table class="form-table tbl_paypal_unsecure_data">
                        <thead>
                            <tr>
                                <th class="th_pageid"><strong>Page ID</strong></th>
                                <th class="th_url"><strong>URL</strong></th>
                                <th class="th_remark_report"><strong>Remarks</strong></th>
                            </tr>
                        </thead>

                        <?php foreach ($paypal_security_scanner_finalarrayresult['button_type'] as $key_paypal_security_scanner_finalarrayresult_unsecure => $paypal_security_scanner_finalarrayresult_unsecure_value) : ?>
                            <tr>
                                <td class="td_pageid"><?php echo $key_paypal_security_scanner_finalarrayresult_unsecure; ?></td>
                                <td class="td_url">
                                    <a href='<?php echo get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure); ?>' target="_blank">
                                        <?php echo get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure); ?></a>
                                </td>
                                <td class="td_remark_report">
                                    <?php foreach ($paypal_security_scanner_finalarrayresult['button_type'][$key_paypal_security_scanner_finalarrayresult_unsecure] as $key_level2 => $value_level2) { ?>
                                        <ul class="unsecure_ul">
                                            <?php
                                            foreach ($value_level2 as $key_level3 => $value_level3) {
                                                foreach ($value_level3 as $key_level4 => $value_level4) {
                                                    ?>

                                                    <li> <?php echo $value_level4 . '&nbsp;-&nbsp;' . $key_level4; ?> - <span class="cls_dialog">View Source</span><div class="cls_dialog_source"><textarea readonly class="txt_unsecuresource"><?php echo $key_level3; ?></textarea> </div></li>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </ul>

                    <?php } ?>
                                </td>
                            </tr>      





                        <?php endforeach; ?>
            <?php } ?>
                    </tbody>
                </table>
			

            </div> 
            <?php
        endif;

        unset($paypal_security_scanner_finalarrayresult);
        if (isset($paypal_security_for_wordpress_content)) {
            unset($paypal_security_for_wordpress_content);
        }
        if (isset($paypal_security_scanner_get_all_forms)) {
            unset($paypal_security_scanner_get_all_forms);
        }
        exit(1);
    }

}