<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    paypal-security
 * @subpackage paypal-security/admin
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Security_Admin {

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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/paypal-security-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, 'two', plugin_dir_url(__FILE__) . 'css/jquery.dataTables.css', array(), $this->version, 'all');
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
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/paypal-security-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . 'two', plugin_dir_url(__FILE__) . 'js/jquery.dataTables.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . 'three', plugin_dir_url(__FILE__) . 'js/jquery.form.js', array('jquery'), $this->version, false);


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
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/paypal-security-admin-display.php';
    }

    public function paypal_security_scan_action_fn() {

        if (isset($_POST['data']) && !empty($_POST['data'])) {
            parse_str($_POST['data'], $post_type);
        } else {
            $post_type = array('all' => 'on');
        }
        $get_array_with_paypal = new AngellEYE_PayPal_Security_PayPal_Helper();
        $paypal_security_scanner_finalarrayresult = array();
        $paypal_security_scanner_get_all_forms = array();


        //$paypal_security_scanner_finalarrayresult = $get_array_with_paypal->paypal_security_get_arraywithpaypaltext($post_type);
        $paypal_security_scanner_finalarrayresult = $get_array_with_paypal->paypal_security_get_arraywithpaypaltext($post_type);
        // $paypal_security_scanner_get_all_forms = $get_array_with_paypal->paypal_security_get_total_forms($paypal_security_scanner_finalarrayresult);
        $paypal_security_scanner_get_all_forms = $get_array_with_paypal->paypal_security_get_total_forms($paypal_security_scanner_finalarrayresult);
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

        if (isset($paypal_security_scanner_get_all_forms['medium_secure_count']) && !empty($paypal_security_scanner_get_all_forms['medium_secure_count'])) {
            $total_medium_secure_count = $paypal_security_scanner_get_all_forms['medium_secure_count'];
        } else {
            $total_medium_secure_count = '0';
        }

        $int_totalpost = intval($total_unsecur_count) + intval($total_secure_count) + intval($total_medium_secure_count);
        $int_total_secure = intval($total_secure_count);
        $site_score = '';
        $site_grade = '';
        $cls_color = '';
        if ($int_totalpost > 0) {


            $site_score = "Site Score&nbsp;" . absint(($int_total_secure / $int_totalpost) * 100) . "%";

            if ($site_score >= 94 && $site_score <= 100) {
                $site_grade = 'A';
                $cls_color = 'clr_darkgreen';
            } elseif ($site_score >= 90 && $site_score <= 93) {
                $site_grade = 'A-';
                $cls_color = 'clr_lightgreen';
            } elseif ($site_score >= 87 && $site_score <= 89) {
                $site_grade = 'B+';
                $cls_color = 'clr_lightgreen';
            } elseif ($site_score >= 83 && $site_score <= 86) {
                $site_grade = 'B';
                $cls_color = 'clr_lightgreen';
            } elseif ($site_score >= 80 && $site_score <= 82) {
                $site_grade = 'B-';
                $cls_color = 'clr_lightgreen';
            } elseif ($site_score >= 77 && $site_score <= 79) {
                $site_grade = 'C+';
                $cls_color = 'clr_lightyellow';
            } elseif ($site_score >= 73 && $site_score <= 76) {
                $site_grade = 'C';
                $cls_color = 'clr_lightyellow';
            } elseif ($site_score >= 70 && $site_score <= 72) {
                $site_grade = 'C-';
                $cls_color = 'clr_lightyellow';
            } elseif ($site_score >= 67 && $site_score <= 69) {
                $site_grade = 'D+';
                $cls_color = 'clr_darkyellow';
            } elseif ($site_score >= 63 && $site_score <= 66) {
                $site_grade = 'D';
                $cls_color = 'clr_darkyellow';
            } elseif ($site_score >= 60 && $site_score <= 62) {
                $site_grade = 'D-';
                $cls_color = 'clr_darkyellow';
            } elseif ($site_score >= 0 && $site_score <= 60) {
                $site_grade = 'F';
                $cls_color = 'clr_darkred';
            }
        } elseif ($int_totalpost <= 0) {
            $site_grade = 'No buttons found...';
            $site_score = '';
        }

        if ((isset($paypal_security_scanner_finalarrayresult) && !empty($paypal_security_scanner_finalarrayresult))):
            ?>
            <div id="div_scan_result">
                <div class="div_tbl_total_count">
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
                                <td>Total High Risk Buttons Found:</td>
                                <td><?php echo $total_unsecur_count; ?></td>
                            </tr>
                            <tr class="color-unsecure">
                                <td>Total Medium Risk Buttons Found:</td>
                                <td><?php echo $total_medium_secure_count; ?></td>
                            </tr>
                            <tr class="color-secure">
                                <td>Total Secure Buttons Found:</td>
                                <td><?php echo $total_secure_count; ?></td>
                            </tr>
                        </tbody></table>
                </div>
                <input type="hidden" id="txt_site_score" name="txt_site_score" value="<?php echo $site_score; ?>">
                <input type="hidden" id="txt_site_grade" name="txt_site_grade" value="<?php echo $site_grade; ?>">
                <input type="hidden" id="txt_clr_code" name="txt_site_grade" value="<?php echo $cls_color; ?>">

                <?php if (isset($paypal_security_scanner_finalarrayresult['unsecure']) && !empty($paypal_security_scanner_finalarrayresult['unsecure'])) { ?>

                    <table class="form-table tbl_paypal_unsecure_data" id="tbl_resultdata">
                        <thead>
                            <tr>
                                <th class="th_pageid"></th>
                                <th class="th_url" colspan="2"><strong>Button Details</strong></th>

                            </tr>
                        </thead>

                        <?php foreach ($paypal_security_scanner_finalarrayresult['unsecure'] as $key_paypal_security_scanner_finalarrayresult_unsecure => $paypal_security_scanner_finalarrayresult_unsecure_value) : ?>
                            <?php foreach ($paypal_security_scanner_finalarrayresult_unsecure_value as $paypal_security_scanner_finalarrayresult_unsecure_value_key => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value) : ?>
                                <?php foreach ($paypal_security_scanner_finalarrayresult_unsecure_value_key_value as $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1 => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value): ?>
                                    <tr>
                                        <td><img src="<?php echo plugin_dir_url(__FILE__) ?>partials/images/insecure-high-risk-icon.png" id="insecure-high-risk-icon"/></td>
                                        <td class="td_viewremark"><strong>Page URL:&nbsp;</strong> <a href='<?php echo get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure); ?>' target="_blank">
                                                <?php echo get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure); ?></a>
                                            <br/>
                                            <strong>Button Security Status:&nbsp;</strong>High Risk<br/>  
                                            <strong>Pricing Concern:&nbsp;</strong><?php echo $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value['button_remark']['pricing_concern']; ?><br/>  
                                            <strong>Privacy Concern:&nbsp;</strong><?php echo $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value['button_remark']['privacy_concern']; ?><br/>  
                                        </td>
                                        <td class="td_viewsource_img">
                                            <span class="cls_dialog"><img src="<?php echo plugin_dir_url(__FILE__) ?>partials/images/view.png" id="view-icon"/></span><span class="view_btn_code_txt">View Button Code</span><div class="cls_dialog_source"><textarea readonly class="txt_unsecuresource"><?php echo $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1; ?></textarea></div>

                                        </td>

                                    </tr>      
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>

                        <?php /// medium risk start ?>
                        <?php
                        if (isset($paypal_security_scanner_finalarrayresult['medium_risk_buttons']) && !empty($paypal_security_scanner_finalarrayresult['medium_risk_buttons'])) {
                            foreach ($paypal_security_scanner_finalarrayresult['medium_risk_buttons'] as $key_paypal_security_scanner_finalarrayresult_unsecure_medium => $paypal_security_scanner_finalarrayresult_unsecure_value_medium) :
                                ?>
                                <?php foreach ($paypal_security_scanner_finalarrayresult_unsecure_value_medium as $paypal_security_scanner_finalarrayresult_unsecure_value_key_medium => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_medium) : ?>
                            <?php foreach ($paypal_security_scanner_finalarrayresult_unsecure_value_key_value_medium as $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_medium => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value_medium): ?>
                                        <tr>
                                            <td><img src="<?php echo plugin_dir_url(__FILE__) ?>partials/images/insecure-mediaum-risk-icon.png" id="insecure-mediaum-risk-icon"/></td>
                                            <td class="td_viewremark"><strong>Page URL:&nbsp;</strong> <a href='<?php echo get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure_medium); ?>' target="_blank">
                                <?php echo get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure_medium); ?></a>
                                                <br/>
                                                <strong>Button Security Status:&nbsp;</strong>Medium Risk<br/>  
                                                <strong>Pricing Concern:&nbsp;</strong><?php echo $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value_medium['button_remark']['pricing_concern']; ?><br/>  
                                                <strong>Privacy Concern:&nbsp;</strong><?php echo $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value_medium['button_remark']['privacy_concern']; ?><br/>  
                                            </td>
                                            <td class="td_viewsource_img">
                                                <span class="cls_dialog"><img src="<?php echo plugin_dir_url(__FILE__) ?>partials/images/view.png" id="view-icon"/></span><span class="view_btn_code_txt">View Button Code</span><div class="cls_dialog_source"><textarea readonly class="txt_unsecuresource"><?php echo $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_medium; ?></textarea></div>

                                            </td>

                                        </tr>      
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php } ?>
                        <?php /// secure start ?>
                        <?php
                        if (isset($paypal_security_scanner_finalarrayresult['secure']) && !empty($paypal_security_scanner_finalarrayresult['secure'])) {
                            foreach ($paypal_security_scanner_finalarrayresult['secure'] as $key_paypal_security_scanner_finalarrayresult_unsecure_secure => $paypal_security_scanner_finalarrayresult_unsecure_value_secure) :
                                ?>
                        <?php foreach ($paypal_security_scanner_finalarrayresult_unsecure_value_secure as $paypal_security_scanner_finalarrayresult_unsecure_value_key_secure => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_secure) : ?>
                            <?php foreach ($paypal_security_scanner_finalarrayresult_unsecure_value_key_value_secure as $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_secure => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value_secure): ?>
                                        <tr>
                                            <td><img src="<?php echo plugin_dir_url(__FILE__) ?>partials/images/secure-button-icon.png" id="secure-button-icon"/></td>
                                            <td class="td_viewremark"><strong>Page URL:&nbsp;</strong> <a href='<?php echo get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure_secure); ?>' target="_blank">
                                <?php echo get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure_secure); ?></a>
                                                <br/>
                                                <strong>Button Security Status:&nbsp;</strong>Secure<br/>  
                                                <strong>Pricing Concern:&nbsp;</strong>None<br/>  
                                                <strong>Privacy Concern:&nbsp;</strong>None<br/>  
                                            </td>
                                            <td class="td_viewsource_img">
                                                <span class="cls_dialog"><img src="<?php echo plugin_dir_url(__FILE__) ?>partials/images/view.png" id="view-icon"/></span><span class="view_btn_code_txt">View Button Code</span><div class="cls_dialog_source"><textarea readonly class="txt_unsecuresource"><?php echo $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_secure; ?></textarea></div>

                                            </td>

                                        </tr>      
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php } ?>

            <?php } ?>


                    </tbody>
                </table>
            </div> 
            <?php
        endif;

        unset($paypal_security_scanner_finalarrayresult);
        if (isset($paypal_security_content)) {
            unset($paypal_security_content);
        }
        if (isset($paypal_security_scanner_get_all_forms)) {
            unset($paypal_security_scanner_get_all_forms);
        }
        exit(1);
    }

}