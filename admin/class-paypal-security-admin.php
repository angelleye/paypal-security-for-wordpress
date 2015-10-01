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
        $screen = get_current_screen();
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/paypal-security-admin.css', array(), $this->version, 'all');
        if ($screen->id == 'tools_page_paypal-security') {
            wp_enqueue_style($this->plugin_name . 'two', plugin_dir_url(__FILE__) . 'css/shCoreDefault.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name . 'three', plugin_dir_url(__FILE__) . 'css/jquery.fancybox.css', array(), $this->version, 'all');
            wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        }
    }

    /**
     * Register the JavaScript for the dashboard.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        $screen = get_current_screen();
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
        wp_enqueue_script($this->plugin_name . 'three', plugin_dir_url(__FILE__) . 'js/shCore.js', array('jquery'), $this->version, false);
        if ($screen->id == 'tools_page_paypal-security') {
            wp_enqueue_script($this->plugin_name . 'two', plugin_dir_url(__FILE__) . 'js/shBrushJScript.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name . 'four', plugin_dir_url(__FILE__) . 'js/jquery.fancybox.js', array('jquery'), $this->version, false);
        }
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
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-paypal-security-post-types.php';
    }

    public function paypal_security_scan_action_fn() {
        if (isset($_POST['data']) && !empty($_POST['data'])) {
            parse_str($_POST['data'], $post_type);
        }
        $PayPal_Security_PayPal_Helper = new AngellEYE_PayPal_Security_PayPal_Helper();
        $paypal_security_scanner_finalarrayresult = $PayPal_Security_PayPal_Helper->paypal_security_get_post_result($post_type);
    }

    public function paypal_security_scan_action_fn_scan() {
        $get_array_with_paypal = new AngellEYE_PayPal_Security_PayPal_Helper();
        $paypal_website_scan_report = array();
        $paypal_security_scanner_finalarrayresult = array();
        $paypal_security_scanner_get_all_forms = array();
        $paypal_security_scanner_finalarrayresult = $get_array_with_paypal->paypal_security_get_arraywithpaypaltext();
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
        $cnt_secure_fancy = 0;
        $cnt_un_secure_fancy = 0;
        $cnt_med_secure_fancy = 0;
        if ($int_totalpost > 0) {
            $site_score = "Site Score&nbsp;" . absint(($int_total_secure / $int_totalpost) * 100) . "%";
            $site_score_int = absint(($int_total_secure / $int_totalpost) * 100);
            if ($site_score_int >= 94 && $site_score_int <= 100) {
                $site_grade = 'A';
                $cls_color = 'clr_darkgreen';
            } elseif ($site_score_int >= 90 && $site_score_int <= 93) {
                $site_grade = 'A-';
                $cls_color = 'clr_lightgreen';
            } elseif ($site_score_int >= 87 && $site_score_int <= 89) {
                $site_grade = 'B+';
                $cls_color = 'clr_lightgreen';
            } elseif ($site_score_int >= 83 && $site_score_int <= 86) {
                $site_grade = 'B';
                $cls_color = 'clr_lightgreen';
            } elseif ($site_score_int >= 80 && $site_score_int <= 82) {
                $site_grade = 'B-';
                $cls_color = 'clr_lightgreen';
            } elseif ($site_score_int >= 77 && $site_score_int <= 79) {
                $site_grade = 'C+';
                $cls_color = 'clr_lightyellow';
            } elseif ($site_score_int >= 73 && $site_score_int <= 76) {
                $site_grade = 'C';
                $cls_color = 'clr_lightyellow';
            } elseif ($site_score_int >= 70 && $site_score_int <= 72) {
                $site_grade = 'C-';
                $cls_color = 'clr_lightyellow';
            } elseif ($site_score_int >= 67 && $site_score_int <= 69) {
                $site_grade = 'D+';
                $cls_color = 'clr_darkyellow';
            } elseif ($site_score_int >= 63 && $site_score_int <= 66) {
                $site_grade = 'D';
                $cls_color = 'clr_darkyellow';
            } elseif ($site_score_int >= 60 && $site_score_int <= 62) {
                $site_grade = 'D-';
                $cls_color = 'clr_darkyellow';
            } elseif ($site_score_int >= 0 && $site_score_int < 60) {
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
                    <?php
                    $tbl_scan_result = '<table class="tbl-scan-result form-table">';
                    $tbl_scan_result .= '<tbody>';
                    $tbl_scan_result .= '<tr class="color-note">';
                    $tbl_scan_result .= '<th><strong>Note</strong></th>';
                    $tbl_scan_result .= '<th><strong>Count</strong></th>';
                    $tbl_scan_result .= '</tr>';
                    $tbl_scan_result .= '<tr class="color-total">';
                    $tbl_scan_result .= '<td>Total Posts and Pages Scanned:</td>';
                    $tbl_scan_result .= "<td>$totalpost</td>";
                    $tbl_scan_result .= '</tr>';
                    $tbl_scan_result .= '<tr class="color-unsecure">';
                    $tbl_scan_result .= '<td>Total High Risk Buttons Found:</td>';
                    $tbl_scan_result .= "<td>$total_unsecur_count</td>";
                    $tbl_scan_result .= '</tr>';
                    $tbl_scan_result .= '<tr class="color-unsecure">';
                    $tbl_scan_result .= "<td>Total Medium Risk Buttons Found:</td>";
                    $tbl_scan_result .= "<td>$total_medium_secure_count</td>";
                    $tbl_scan_result .= "</tr>";
                    $tbl_scan_result .= '<tr class="color-secure">';
                    $tbl_scan_result .= '<td>Total Secure Buttons Found:</td>';
                    $tbl_scan_result .= "<td>$total_secure_count</td>";
                    $tbl_scan_result .= "</tr>";
                    $tbl_scan_result .= "</tbody></table>";
                    echo $tbl_scan_result;
                    ?>
                </div>
                <input type="hidden" id="txt_site_score" name="txt_site_score" value="<?php echo $site_score; ?>">
                <input type="hidden" id="txt_site_grade" name="txt_site_grade" value="<?php echo $site_grade; ?>">
                <input type="hidden" id="txt_clr_code" name="txt_site_grade" value="<?php echo $cls_color; ?>">
                <?php
                if (isset($tbl_scan_result) && !empty($tbl_scan_result)) {
                    $paypal_website_scan_report['scan_data'] = $tbl_scan_result;
                } else {
                    $paypal_website_scan_report['scan_data'] = '';
                }
                if (isset($site_score_int) && !empty($site_score_int)) {
                    $paypal_website_scan_report['txt_site_score'] = $site_score_int;
                } else {
                    $paypal_website_scan_report['txt_site_score'] = '';
                }
                if (isset($site_grade) && !empty($site_grade)) {
                    $paypal_website_scan_report['txt_site_grade'] = $site_grade;
                } else {
                    $paypal_website_scan_report['txt_site_grade'] = '';
                }
                if (isset($cls_color) && !empty($cls_color)) {
                    $paypal_website_scan_report['txt_cls_color'] = $cls_color;
                } else {
                    $paypal_website_scan_report['txt_cls_color'] = '';
                }
                ?>
                <?php if (!empty($paypal_security_scanner_finalarrayresult['unsecure']) || !empty($paypal_security_scanner_finalarrayresult['medium_risk_buttons']) || !empty($paypal_security_scanner_finalarrayresult['secure'])) { ?>
                    <table class="form-table tbl_paypal_unsecure_data" id="tbl_resultdata">
                        <thead>
                            <tr>
                                <th class="th_pageid"></th>
                                <th class="th_url" colspan="2"><strong>Button Details</strong></th>
                            </tr>
                        </thead><tbody>
                            <?php if (isset($paypal_security_scanner_finalarrayresult['unsecure']) && !empty($paypal_security_scanner_finalarrayresult['unsecure'])) { ?>
                                <?php foreach ($paypal_security_scanner_finalarrayresult['unsecure'] as $key_paypal_security_scanner_finalarrayresult_unsecure => $paypal_security_scanner_finalarrayresult_unsecure_value) { ?>
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
                                                    <a href="#un_sec_fan-<?php echo $cnt_un_secure_fancy; ?>" class="cls_dialog fancybox"><img src="<?php echo plugin_dir_url(__FILE__) ?>partials/images/view.png" id="view-icon"/></a><a href="#un_sec_fan-<?php echo $cnt_un_secure_fancy; ?>" class="cls_dialog fancybox"><span class="view_btn_code_txt">View Button Code</span></a>
                                                    <?php
                                                    $text_un_sec = trim($paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1);
                                                    $text_break_un_sec = str_replace('>', ">\n", $text_un_sec);
                                                    ?>
                                                    <div class="sec-fan" id="un_sec_fan-<?php echo $cnt_un_secure_fancy; ?>" style="width:650px;display: none;">
                                                        <div class="fan-maindiv">
                                                            <div class="fan-snippet">
                                                                <span class="spn-snippet-lable">HTML code snippet</span>
                                                                <pre class="brush: js;"><?php echo $text_break_un_sec; ?></pre>
                                                            </div>
                                                            <div class="fan-act-btndiv">
                                                                <span class="spn-act-btn-lable">Actual Button form</span>
                                                                <div class="un_sec_fan" id="un_sec_fan-<?php echo $cnt_secure_fancy; ?>">
                                                                    <?php echo $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                        $cnt_un_secure_fancy = $cnt_un_secure_fancy + 1;
                                    
                                     endforeach;
                                } 
                                ?>
                                <div id="pss_recommendation_data" style="display: none">
                                    <p><h2><?php echo __('PayPal Security Scan recommendation', 'paypal-security'); ?></h2></p>
                                    <?php echo '<p><span>' . __( 'Want to add PayPal secure Button to your site ? There is a WordPress plugin for that —', 'paypal-security' ) . ' <a href="' . esc_url( 'https://wordpress.org/plugins/paypal-wp-button-manager/' ) .'" >'.  __( 'PayPal WP Button Manager', 'paypal-security' ) . '</a></span></p>'; ?>
                                    <h3><?php echo __('PayPal WP Button Manager', 'paypal-security'); ?></h3>
                                    <div class="alert-box">
                                        <span><?php echo __('Developed by an Ace Certified PayPal Developer, official PayPal Partner, PayPal Ambassador, and 3-time PayPal Star Developer Award Winner.', 'paypal-security'); ?> </span><br>
                                        <h3><?php echo __('Introduction', 'paypal-security'); ?></h3>
                                        <span><?php echo __('Easily create and manage PayPal Standard payment buttons within WordPress, and place them on Pages / Posts using shortcodes.', 'paypal-security'); ?></span>
                                        <ul>
                                            <li><?php echo __('Buy Now Button', 'paypal-security'); ?></li>
                                            <li><?php echo __('Donation Button', 'paypal-security'); ?></li>
                                            <li><?php echo __('Subscription Button', 'paypal-security'); ?></li>
                                            <li><?php echo __('Shopping Cart Button / View Cart Button', 'paypal-security'); ?></li>
                                            <li><?php echo __('Shortcodes for easy placement of buttons on Pages / Posts', 'paypal-security'); ?></li>
                                        </ul>
                                    </div>
                                </div>
                              <?php               
                             } 
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
                                                    <a href="#med_sec_fan-<?php echo $cnt_med_secure_fancy; ?>" class="cls_dialog fancybox"><img src="<?php echo plugin_dir_url(__FILE__) ?>partials/images/view.png" id="view-icon"/></a> <a href="#med_sec_fan-<?php echo $cnt_med_secure_fancy; ?>" class="cls_dialog fancybox"><span class="view_btn_code_txt">View Button Code</span></a>
                                                    <?php
                                                    $text_med_sec = trim($paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_medium);
                                                    $text_break_med_sec = str_replace('>', ">\n", $text_med_sec);
                                                    ?>
                                                    <div class="med_sec_fan" id="med_sec_fan-<?php echo $cnt_med_secure_fancy; ?>" style="width:650px;display: none;">
                                                        <div class="fan-maindiv">
                                                            <div class="fan-snippet">
                                                                <span class="spn-snippet-lable">HTML code snippet</span>
                                                                <pre class="brush: js;"><?php echo $text_break_med_sec; ?></pre>
                                                            </div>
                                                            <div class="fan-act-btndiv">
                                                                <span class="spn-act-btn-lable">Actual Button form</span>
                                                                <div class="med_sec_fan" id="med_sec_fan-<?php echo $cnt_secure_fancy; ?>">
                                                                    <?php echo $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_medium; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                        $cnt_med_secure_fancy = $cnt_med_secure_fancy + 1;
                                        ?>
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
                                                    <a href="#sec_fan-<?php echo $cnt_secure_fancy; ?>" class="cls_dialog fancybox">
                                                        <img src="<?php echo plugin_dir_url(__FILE__) ?>partials/images/view.png" id="view-icon"/></a>
                                                    <a href="#sec_fan-<?php echo $cnt_secure_fancy; ?>" class="cls_dialog fancybox"> <span class="view_btn_code_txt">View Button Code</span></a>
                                                    <?php
                                                    $text_sec = trim($paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_secure);
                                                    $text_break_sec = str_replace('>', ">\n", $text_sec);
                                                    ?>
                                                    <div class="sec-fan" id="sec_fan-<?php echo $cnt_secure_fancy; ?>" style="width:650px;display: none;">
                                                        <div class="fan-maindiv">
                                                            <div class="fan-snippet">
                                                                <span class="spn-snippet-lable">HTML code snippet</span>
                                                                <pre class="brush: js;"><?php echo $text_break_sec; ?></pre>
                                                            </div>
                                                            <div class="fan-act-btndiv">
                                                                <span class="spn-act-btn-lable">Actual Button form</span>
                                                                <div class="sec-fan" id="sec_fan-actbtn-<?php echo $cnt_secure_fancy; ?>">
                                                                    <?php echo $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_secure; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                        $cnt_secure_fancy = $cnt_secure_fancy + 1;
                                        ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
        endif;
        unset($paypal_security_scanner_finalarrayresult);
        if (isset($paypal_security_content)) {
            unset($paypal_security_content);
        }
        if (isset($paypal_security_scanner_get_all_forms)) {
            unset($paypal_security_scanner_get_all_forms);
        }
        $this->paypal_security_add_report_history($paypal_website_scan_report);
        exit(1);
    }

    public function post_updated_remove_exclude_post_list($post_ID) {
        $paypal_security_exclude_post_list = get_option('paypal_security_exclude_post_list');
        if (isset($paypal_security_exclude_post_list) && !empty($paypal_security_exclude_post_list)) {
            if (in_array($post_ID, $paypal_security_exclude_post_list)) {
                unset($paypal_security_exclude_post_list[$post_ID]);
                update_option('paypal_security_exclude_post_list', $paypal_security_exclude_post_list);
            }
        }
    }

    public function plugin_remove_exclude_post_list() {
        delete_option('paypal_security_exclude_post_list');
    }

    public function paypal_security_add_report_history($paypal_website_scan_report) {

        $insert_report_array = array(
            'ID' => '',
            'post_type' => 'report_history', // Custom Post Type Slug
            'post_status' => 'publish',
            'post_title' => date('Y-m-d H:i:s'),
        );

        $post_id = wp_insert_post($insert_report_array);

        if (isset($paypal_website_scan_report) && !empty($paypal_website_scan_report)) {
            update_post_meta($post_id, 'paypal_website_scan_report', $paypal_website_scan_report);
        }
    }

}
