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
        if ($screen->id == 'tools_page_paypal-security') {
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-progressbar');
            wp_enqueue_script('thickbox');
            wp_enqueue_script('run_prettify', 'https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js?autoload=true&amp;skin=sunburst&amp;lang=css', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/paypal-security-admin.js', array('jquery'), $this->version, false);
            if (wp_script_is($this->plugin_name)) {
                wp_localize_script($this->plugin_name, 'paypal_security_plugin_url', apply_filters('paypal_security_plugin_url_filter', array(
                    'plugin_url' => plugin_dir_url(__FILE__)
                )));
            }
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
        $thinkbox_inline = "#TB_inline?height=1200&width=600&inlineId=";
        $get_array_with_paypal = new AngellEYE_PayPal_Security_PayPal_Helper();
        $paypal_website_scan_report = array();
        $paypal_button_security_details = '';
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
            $paypal_button_security_details .= '<div id="div_scan_result">';
                $paypal_button_security_details .= '<div class="div_tbl_total_count">';
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
                    $paypal_button_security_details .= $tbl_scan_result;

                 $paypal_button_security_details .= "</div>";
                $paypal_button_security_details .= "<input type='hidden' id='txt_site_score' name='txt_site_score' value='$site_score'>";
                $paypal_button_security_details .= "<input type='hidden' id='txt_site_grade' name='txt_site_grade' value='$site_grade'>";
                $paypal_button_security_details .= "<input type='hidden' id='txt_clr_code' name='txt_site_grade' value='$cls_color'>";
                
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
                
                if (!empty($paypal_security_scanner_finalarrayresult['unsecure']) || !empty($paypal_security_scanner_finalarrayresult['medium_risk_buttons']) || !empty($paypal_security_scanner_finalarrayresult['secure'])) { 
                    $paypal_button_security_details .= "<table class='form-table tbl_paypal_unsecure_data' id='tbl_resultdata'>";
                    $paypal_button_security_details .= "<thead>";
                    $paypal_button_security_details .= "<tr>";
                    $paypal_button_security_details .= "<th class='th_pageid'></th>";
                    $paypal_button_security_details .= "<th class='th_url' colspan='2'><strong>PayPal Button Security Details</strong></th>";
                    $paypal_button_security_details .= "</tr>";
                    $paypal_button_security_details .= "</thead><tbody>";
                    if (isset($paypal_security_scanner_finalarrayresult['unsecure']) && !empty($paypal_security_scanner_finalarrayresult['unsecure'])) { 
                         foreach ($paypal_security_scanner_finalarrayresult['unsecure'] as $key_paypal_security_scanner_finalarrayresult_unsecure => $paypal_security_scanner_finalarrayresult_unsecure_value) { 
                              foreach ($paypal_security_scanner_finalarrayresult_unsecure_value as $paypal_security_scanner_finalarrayresult_unsecure_value_key => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value) {
                                 foreach ($paypal_security_scanner_finalarrayresult_unsecure_value_key_value as $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1 => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value) {
                                      $cnt_un_secure_think = $this->angelleye_generator_thinkbox_key();
                                      $paypal_button_security_details .= "<tr>";
                                      $paypal_button_security_details .= '<td><img src="' . plugin_dir_url(__FILE__) . 'partials/images/insecure-high-risk-icon.png" id="insecure-high-risk-icon"/></td>';
                                      $paypal_button_security_details .= '<td class="td_viewremark"><strong>Page URL:&nbsp;</strong> <a href="' . get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure) .'" target="_blank">';
                                      $paypal_button_security_details .= get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure) . '</a>';
                                      $paypal_button_security_details .= "<br/>";
                                      $paypal_button_security_details .= '<strong>Button Security Status:&nbsp;</strong>High Risk<br/>';
                                      $paypal_button_security_details .= "<strong>Pricing Concern:&nbsp;</strong>" . $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value['button_remark']['pricing_concern'] . "<br/>";
                                      $paypal_button_security_details .= "<strong>Privacy Concern:&nbsp;</strong>" . $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value['button_remark']['privacy_concern'] . "<br/>";
                                      $paypal_button_security_details .= "</td>";
                                      $paypal_button_security_details .= '<td class="td_viewsource_img">';
                                      $paypal_button_security_details .= '<a href="'.$thinkbox_inline . $cnt_un_secure_think . '" class="cls_dialog thickbox"><img src="' . plugin_dir_url(__FILE__) . 'partials/images/view.png" id="view-icon"/></a><a href="'.$thinkbox_inline . $cnt_un_secure_think . '" class="cls_dialog thickbox"><span class="view_btn_code_txt">View Button Code</span></a>';

                                      $text_un_sec = trim($paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1);
                                      $text_break_un_sec = str_replace('>', ">\n", $text_un_sec);

                                      $paypal_button_security_details .= '<div class="sec-fan" id="'. $cnt_un_secure_think . '" style="width:650px;display: none;">';
                                      $paypal_button_security_details .= '<div class="fan-maindiv">';
                                      $paypal_button_security_details .= '<div class="fan-snippet">';
                                      $paypal_button_security_details .= '<span class="spn-snippet-lable">HTML code snippet</span>';
                                      $paypal_button_security_details .= '<pre class="prettyprint lang-html">' . $text_break_un_sec . '</pre>';
                                      $paypal_button_security_details .= '</div>';
                                      $paypal_button_security_details .= '<div class="fan-act-btndiv">';
                                      $paypal_button_security_details .= '<span class="spn-act-btn-lable">Actual Button form</span>';
                                      $paypal_button_security_details .= '<div class="un_sec_fan" id="'. $cnt_un_secure_think . '">';
                                      $paypal_button_security_details .= $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1;
                                      $paypal_button_security_details .= '</div>';
                                      $paypal_button_security_details .= '</div>';
                                      $paypal_button_security_details .= '</div>';
                                      $paypal_button_security_details .= '</div>';
                                      $paypal_button_security_details .= '</td>';
                                      $paypal_button_security_details .= '</tr>';                                            
                         }
                    }
               }
                    $paypal_button_security_details .= '<div id="pss_recommendation_data" style="display: none">';
                    $paypal_button_security_details .= '<p><h2>'.__('PayPal Security Scan Recommendation', 'paypal-security').'</h2></p>';
                    $paypal_button_security_details .= '<ul>';
                    $paypal_button_security_details .= '<li>'.__('Scroll down to see details about the security of the individual buttons found on your site.', 'paypal-security').'</li>';
                    $paypal_button_security_details .= '<li>'.__('The insecure buttons on your site should be replaced with PayPal hosted buttons to ensure that they are secure.', 'paypal-security').'</li>';
                    $paypal_button_security_details .= '<li>'.__('This can be done', 'paypal-security') . ' <a href="' . esc_url('https://www.angelleye.com/how-to-create-a-paypal-button/') . '" target="_blank">' . __('manually through your PayPal account profile', 'paypal-security') . '</a>' . __(', or you can use our free plugin', 'paypal-security') . ', <a href="https://www.angelleye.com/product/wordpress-paypal-button-manager/" target="_blank">PayPal WP Button Manager</a>, ' . __('to build and manage securely hosted PayPal buttons within WordPress.', 'paypal-security').'</li>';

                    if ($this->ps_is_plugin_active('PayPal WP Button Manager'))
                    {
                        $paypal_button_security_details .= '<li>' . __('We see that you already have our PayPal WP Button Manager plugin installed and activated.', 'paypal-security') . ' <a href="' . esc_url('https://www.angelleye.com/paypal-wp-button-manager-user-guide/') . '" target="_blank"> ' . __('Click here to view documentation', 'paypal-security') . '</a> ' . __('on how you can use it to create secure PayPal buttons.', 'paypal-security') . '</a></li>';
                    }
                    elseif($this->ps_is_plugin_installed('PayPal WP Button Manager'))
                    {
                        $paypal_button_security_details .= '<li>' . __('We see that you already have our PayPal WP Button Manager plugin installed, but it is not currently active.  We recommend that you activate it and use it to', 'paypal-security') . ' <a target="_blank" href="' . esc_url('https://www.angelleye.com/paypal-wp-button-manager-user-guide/') . '">' . __('build hosted, secure buttons', 'paypal-security') . '</a> ' . __('to replace your current buttons.', 'paypal-security') . '</li>';
                        $this->ps_active_plugin_using_name('PayPal WP Button Manager');
                    }
                    else
                    {
                        $paypal_button_security_details .= '<li>' . __('Click the button below to automatically install the PayPal WP Button Manager plugin.', 'paypal-security') . '</li>';
                        $this->install_paypal_wp_button_manager_plugin();
                    }

                    $paypal_button_security_details .= '</ul>';
                    $paypal_button_security_details .= '</div>';                            
                  }
                    if (isset($paypal_security_scanner_finalarrayresult['medium_risk_buttons']) && !empty($paypal_security_scanner_finalarrayresult['medium_risk_buttons'])) {
                            foreach ($paypal_security_scanner_finalarrayresult['medium_risk_buttons'] as $key_paypal_security_scanner_finalarrayresult_unsecure_medium => $paypal_security_scanner_finalarrayresult_unsecure_value_medium) :                               
                                 foreach ($paypal_security_scanner_finalarrayresult_unsecure_value_medium as $paypal_security_scanner_finalarrayresult_unsecure_value_key_medium => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_medium) : 
                                    foreach ($paypal_security_scanner_finalarrayresult_unsecure_value_key_value_medium as $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_medium => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value_medium):
                                        $cnt_med_secure_think = $this->angelleye_generator_thinkbox_key();
                                        $paypal_button_security_details .= '<tr>';
                                        $paypal_button_security_details .= '<td><img src="' . plugin_dir_url(__FILE__) . 'partials/images/insecure-mediaum-risk-icon.png" id="insecure-mediaum-risk-icon"/></td>';
                                        $paypal_button_security_details .= '<td class="td_viewremark"><strong>Page URL:&nbsp;</strong> <a href="' . get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure_medium) . '" target="_blank">';
                                        $paypal_button_security_details .= get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure_medium) . '</a>';
                                        $paypal_button_security_details .= '<br/>';
                                        $paypal_button_security_details .= '<strong>Button Security Status:&nbsp;</strong>Medium Risk<br/>';
                                        $paypal_button_security_details .= '<strong>Pricing Concern:&nbsp;</strong>' . $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value_medium['button_remark']['pricing_concern'] . '<br/>';
                                        $paypal_button_security_details .= '<strong>Privacy Concern:&nbsp;</strong>' . $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value_medium['button_remark']['privacy_concern'] . '<br/>';
                                        $paypal_button_security_details .= '</td>';
                                        $paypal_button_security_details .= '<td class="td_viewsource_img">';
                                        $paypal_button_security_details .= '<a href="'. $thinkbox_inline . $cnt_med_secure_think . '" class="cls_dialog thickbox"><img src="' . plugin_dir_url(__FILE__) . 'partials/images/view.png" id="view-icon"/></a> <a href="'. $thinkbox_inline . $cnt_med_secure_think . '" class="cls_dialog thickbox"><span class="view_btn_code_txt">View Button Code</span></a>';
                                            
                                        $text_med_sec = trim($paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_medium);
                                        $text_break_med_sec = str_replace('>', ">\n", $text_med_sec);
                                            
                                        $paypal_button_security_details .= '<div class="med_sec_fan" id="' . $cnt_med_secure_think . '" style="width:650px;display: none;">';
                                        $paypal_button_security_details .= '<div class="fan-maindiv">';
                                        $paypal_button_security_details .= '<div class="fan-snippet">';
                                        $paypal_button_security_details .= '<span class="spn-snippet-lable">HTML code snippet</span>';
                                        $paypal_button_security_details .= '<pre class="prettyprint lang-html">' . $text_break_med_sec . '</pre>';
                                        $paypal_button_security_details .= '</div>';
                                        $paypal_button_security_details .= '<div class="fan-act-btndiv">';
                                        $paypal_button_security_details .= '<span class="spn-act-btn-lable">Actual Button form</span>';
                                        $paypal_button_security_details .= '<div class="med_sec_fan" id="' . $cnt_med_secure_think . '">';
                                        $paypal_button_security_details .= $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_medium;
                                        $paypal_button_security_details .= '</div>';
                                        $paypal_button_security_details .= '</div>';
                                        $paypal_button_security_details .= '</div>';
                                        $paypal_button_security_details .= '</div>';
                                        $paypal_button_security_details .= '</td>';
                                        $paypal_button_security_details .= '</tr>';                                       
                                    endforeach;
                               endforeach;
                      endforeach;
                   }                        
                    if (isset($paypal_security_scanner_finalarrayresult['secure']) && !empty($paypal_security_scanner_finalarrayresult['secure'])) {
                            foreach ($paypal_security_scanner_finalarrayresult['secure'] as $key_paypal_security_scanner_finalarrayresult_unsecure_secure => $paypal_security_scanner_finalarrayresult_unsecure_value_secure) :                               
                                foreach ($paypal_security_scanner_finalarrayresult_unsecure_value_secure as $paypal_security_scanner_finalarrayresult_unsecure_value_key_secure => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_secure) :
                                    foreach ($paypal_security_scanner_finalarrayresult_unsecure_value_key_value_secure as $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_secure => $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_value_secure):
                                        $cnt_secure_think = $this->angelleye_generator_thinkbox_key();
                                        $paypal_button_security_details .= '<tr>';
                                        $paypal_button_security_details .= '<td><img src="' . plugin_dir_url(__FILE__) . 'partials/images/secure-button-icon.png" id="secure-button-icon"/></td>';
                                        $paypal_button_security_details .= '<td class="td_viewremark"><strong>Page URL:&nbsp;</strong> <a href="' . get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure_secure) . '" target="_blank">';
                                        $paypal_button_security_details .= get_permalink($key_paypal_security_scanner_finalarrayresult_unsecure_secure) . '</a>';
                                        $paypal_button_security_details .= '<br/>';
                                        $paypal_button_security_details .= '<strong>Button Security Status:&nbsp;</strong>Secure<br/>';
                                        $paypal_button_security_details .= '<strong>Pricing Concern:&nbsp;</strong>None<br/>';
                                        $paypal_button_security_details .= '<strong>Privacy Concern:&nbsp;</strong>None<br/>';
                                        $paypal_button_security_details .= '</td>';
                                        $paypal_button_security_details .= '<td class="td_viewsource_img">';
                                        $paypal_button_security_details .= '<a href="' . $thinkbox_inline . $cnt_secure_think . '" class="cls_dialog thickbox">';
                                        $paypal_button_security_details .= '<img src="' . plugin_dir_url(__FILE__) . 'partials/images/view.png" id="view-icon"/></a>';
                                        $paypal_button_security_details .= '<a href="' . $thinkbox_inline . $cnt_secure_think . '" class="cls_dialog thickbox"> <span class="view_btn_code_txt">View Button Code</span></a>';
                                                
                                        $text_sec = trim($paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_secure);
                                        $text_break_sec = str_replace('>', ">\n", $text_sec);
                                               
                                        $paypal_button_security_details .= '<div class="sec-fan" id="' . $cnt_secure_think . '" style="width:650px;display: none;">';
                                        $paypal_button_security_details .= '<div class="fan-maindiv">';
                                        $paypal_button_security_details .= '<div class="fan-snippet">';
                                        $paypal_button_security_details .= '<span class="spn-snippet-lable">HTML code snippet</span>';
                                        $paypal_button_security_details .= '<pre class="prettyprint lang-html">' . $text_break_sec . '</pre>';
                                        $paypal_button_security_details .= '</div>';
                                        $paypal_button_security_details .= '<div class="fan-act-btndiv">';
                                        $paypal_button_security_details .= '<span class="spn-act-btn-lable">Actual Button form</span>';
                                        $paypal_button_security_details .= '<div class="sec-fan" id="' . $cnt_secure_think . '">';
                                        $paypal_button_security_details .= $paypal_security_scanner_finalarrayresult_unsecure_value_key_value_key1_secure;
                                        $paypal_button_security_details .= '</div>';
                                        $paypal_button_security_details .= '</div>';
                                        $paypal_button_security_details .= '</div>';
                                        $paypal_button_security_details .= '</div>';
                                        $paypal_button_security_details .= '</td>';
                                        $paypal_button_security_details .= '</tr>';                                      
                                    endforeach;
                             endforeach;
                         endforeach;
                        } 
                    $paypal_button_security_details .= '</tbody>';
                    $paypal_button_security_details .= '</table>';
                $paypal_button_security_details .= '</div>'; 
                echo $paypal_button_security_details;
            }
        endif;
        unset($paypal_security_scanner_finalarrayresult);
        if (isset($paypal_security_content)) {
            unset($paypal_security_content);
        }
        if (isset($paypal_security_scanner_get_all_forms)) {
            unset($paypal_security_scanner_get_all_forms);
        }
        $this->paypal_security_add_report_history($paypal_website_scan_report, $paypal_button_security_details);
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

    public function paypal_security_add_report_history($paypal_website_scan_report, $paypal_button_security_details) {

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
        
        if (isset($paypal_button_security_details) && !empty($paypal_button_security_details)) {
            update_post_meta($post_id, 'paypal_button_security_details', $paypal_button_security_details);
        }
        
    }

    public function install_paypal_wp_button_manager_plugin() {
        $plugin_slug = "paypal-wp-button-manager";
        $plugin_name = "PayPal WP Button Manager";
        if ($this->ps_is_plugin_installed('PayPal WP Button Manager') == false) {
            if (current_user_can('install_plugins')) {
                $plugin_install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug), 'install-plugin_' . $plugin_slug);
                echo '<p><span><a class="install-now button" data-slug="' . esc_attr($plugin_slug) . '" href="' . esc_url($plugin_install_url) . '" aria-label="' . esc_attr(sprintf(__('Install %s now'), $plugin_name)) . '" data-name="' . esc_attr($plugin_name) . '">' . __('Install Now') . '</a></span></p>';
            }
        }
    }

    public function ps_hide_update_notice() {
        $screen = get_current_screen();

        if ($screen->id == "tools_page_paypal-security") {
            ?>
            <style>
                .updated.fade {
                    display: none;
                }
            </style>
            <?php
        }
    }

    public function pss_delete_paypal_scan_history() {
        global $wpdb;
        if( isset($_POST['value']) && $_POST['value'] == 'yes') {
            $wpdb->query( $wpdb->prepare( "DELETE p, pm FROM $wpdb->posts p INNER JOIN $wpdb->postmeta pm ON pm.post_id = p.ID WHERE p.post_type = %s", 'report_history'));
            echo json_encode(array("statusmsg" => 'success'));
            exit();
        }
    }

    public function ps_is_plugin_installed($plugin_name = null) {
        if (!empty($plugin_name)) {
            if (!function_exists('get_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $all_plugins = get_plugins();
            foreach ($all_plugins as $plugin_slug => $plugin_item) {
                if ($plugin_item['Title'] == $plugin_name) {
                    return true;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    public function ps_is_plugin_active($plugin_name = null) {
        if (!empty($plugin_name)) {
            if (!function_exists('get_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $all_plugins = get_plugins();
            foreach ($all_plugins as $plugin_slug => $plugin_item) {
                if ($plugin_item['Title'] == $plugin_name) {
                    $return = is_plugin_active($plugin_slug) ? true : false;
                    return $return;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    public function ps_active_plugin_using_name($plugin_name = null) {
        $plugin_slug = '';
        $s = '';
        $page = 1;
        $context = 'all';
        if (!empty($plugin_name)) {
            if (!function_exists('get_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $all_plugins = get_plugins();
            foreach ($all_plugins as $plugin_slug => $plugin_item) {
                if ($plugin_item['Title'] == $plugin_name) {
                    break;
                }
            }
            
        } 
        if( !empty($plugin_slug) ) {
            $activeate_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin_slug . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'activate-plugin_' . $plugin_slug );
            if (current_user_can('install_plugins')) {
                $plugin_install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug), 'install-plugin_' . $plugin_slug);
                echo '<p><span><a class="install-now button" data-slug="' . esc_attr($plugin_slug) . '" href="' . esc_url($activeate_url) . '" aria-label="' . esc_attr(sprintf(__('Install %s now'), $plugin_name)) . '" data-name="' . esc_attr($plugin_name) . '">' . __('Activate Now') . '</a></span></p>';
            }
        }
    }
    
    /**
     * @return type
     */
    public function angelleye_generator_thinkbox_key() {
        $key = md5(microtime());
        $new_key = '';
        for ($i = 1; $i <= 10; $i ++) {
            $new_key .= $key[$i];
		}
        return strtoupper($new_key);
    }
}
