<?php

/**
 * This class defines all paypal custom functions
 * @class       AngellEYE_PayPal_Security_PayPal_Helper
 * @version	1.0.0
 * @package		paypal-security/partials
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Security_PayPal_Helper {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public function __construct() {
        
    }

    public function paypal_security_get_arraywithpaypaltext() {
         global $post, $wpdb;
        $table_name = $wpdb->prefix . "posts";
        $paypal_security_content = array();
        $paypal_security_publisharray = array();
        $paypal_action_url = array();
        $retrive_cmd = array();
        $current_form_html = array();
        $retrive_item_name = array();
        $button_name = '';
        $paypal_security_paypal_form_html = array();
        if( isset($_POST['data']['count']) && !empty( $_POST['data']['count']) ) {
            $paypal_security_content['total_post'] = $_POST['data']['count'];
            unset($_POST['data']['count']);
        } else {
            $paypal_security_content['total_post'] = 0;
        }       
        
        $post_type = $_POST['data'];
        foreach ($post_type as $key_post => $post_id) {
            $html = file_get_html(get_permalink($post_id));
            if (isset($html) && !empty($html)) {
                $paypal_action_url = '';
                foreach ($html->find('form') as $e) {
                    if (!preg_match("~\bpaypal.com\b~", $e->action)) {
                        continue;
                    } else {
                        $paypal_action_url = $e->action;
                        if (isset($paypal_action_url) && !empty($paypal_action_url)) {
                            $retrive_cmd = $html->find('[name=cmd]');
                            foreach ($retrive_cmd as $key_retrive_cmd => $value_retrive_cmd) {
                                $viewcart_str_html = str_get_html($value_retrive_cmd->parent()->outertext());
                                //$check_is_viewcart = $viewcart_str_html->find('[name=item_name]');
                                //$check_is_shoppingbutton = $viewcart_str_html->find('[name=shopping_url]');
                                if (isset($retrive_cmd[$key_retrive_cmd]->attr['value']) && !empty($retrive_cmd[$key_retrive_cmd]->attr['value'])) {
                                    if (($retrive_cmd[$key_retrive_cmd]->attr['value'] != '_s-xclick')) {
                                        $current_form_html = str_get_html($value_retrive_cmd->parent()->outertext());
                                        if ($retrive_cmd[$key_retrive_cmd]->attr['value'] == '_xclick') {
                                            $button_name = 'Buy Now button';
                                        } else if ($retrive_cmd[$key_retrive_cmd]->attr['value'] == '_cart') {
                                            $button_name = 'Shopping Cart button';
                                        } else if ($retrive_cmd[$key_retrive_cmd]->attr['value'] == '_xclick-subscriptions') {
                                            $button_name = 'Subscribe button';
                                        } else if ($retrive_cmd[$key_retrive_cmd]->attr['value'] == '_donations') {
                                            $button_name = 'Donate button';
                                        } else if ($retrive_cmd[$key_retrive_cmd]->attr['value'] == '_oe-gift-certificate') {
                                            $button_name = 'Buy Gift Certificate button';
                                        }
                                        if ($retrive_cmd[$key_retrive_cmd]->attr['value'] == '_oe-gift-certificate') {
                                            $retrive_item_name = $current_form_html->find('[name=shopping_url]');
                                        } else {
                                            $retrive_item_name = $current_form_html->find('[name=item_name]');
                                        }
                                        if (isset($retrive_item_name[0]->attr['value']) && !empty($retrive_item_name[0]->attr['value'])) {
                                            $itemname = $retrive_item_name[0]->attr['value'];
                                        } else {
                                            $itemname = 'Not Available';
                                        }
                                        $retrive_business = $current_form_html->find('[name=business]');
                                        $retrive_price_amount = $current_form_html->find('[name=amount]');
                                        if (isset($retrive_price_amount[0]->attr['value']) && !empty($retrive_price_amount[0]->attr['value'])) {
                                            $paypal_security_content['unsecure'][$post_id][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()]['button_details'][$itemname] = $button_name;
                                            if (strpos($retrive_business[0]->attr['value'], '@') != false) {
                                                $paypal_security_content['unsecure'][$post_id][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()]['button_remark']['pricing_concern'] = "Vulnerable to pricing adjustments prior to submitting payment.";
                                                $paypal_security_content['unsecure'][$post_id][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()]['button_remark']['privacy_concern'] = "Email address displayed in the button code.";
                                            } else if (strpos($retrive_business[0]->attr['value'], '@') == false) {
                                                $paypal_security_content['unsecure'][$post_id][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()]['button_remark']['pricing_concern'] = "Vulnerable to pricing adjustments prior to submitting payment.";
                                                $paypal_security_content['unsecure'][$post_id][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()]['button_remark']['privacy_concern'] = "None";
                                            }
                                        }
                                        if ((isset($retrive_business[0]->attr['value']) && !empty($retrive_business[0]->attr['value']) ) && (empty($retrive_price_amount[0]->attr['value']))) {
                                            if (strpos($retrive_business[0]->attr['value'], '@') != false) {
                                                $paypal_security_content['medium_risk_buttons'][$post_id][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()]['button_remark']['pricing_concern'] = "None";
                                                $paypal_security_content['medium_risk_buttons'][$post_id][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()]['button_remark']['privacy_concern'] = "Email address displayed in the button code.";
                                            } else {
                                                $paypal_security_content['secure'][$post_id][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()] = "View Cart";
                                            }
                                        }
                                    } else if (($retrive_cmd[$key_retrive_cmd]->attr['value'] == '_s-xclick')) {
                                        $paypal_security_content['secure'][$post_id][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()] = 'Secure Button';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $paypal_security_content;
    }

    public function paypal_security_get_total_forms($paypal_security_scanner_finalarrayresult) {
        $paypal_total_forms = array();
        $paypal_total_forms = $paypal_security_scanner_finalarrayresult;
        $paypal_total_forms_unsecure = array();
        $paypal_total_forms_unsecure_count = array();
        $paypal_total_forms_secure = array();
        $paypal_total_forms_secure_count = array();
        $paypal_total_all_forms_count = array();
        $paypal_total_forms_medium_secure = array();
        $paypal_total_forms_medium_secure_count = array();
        if (isset($paypal_total_forms) && !empty($paypal_total_forms)) {
            if (isset($paypal_total_forms['unsecure']) && !empty($paypal_total_forms['unsecure'])) {
                foreach ($paypal_total_forms['unsecure'] as $paypal_total_forms_key => $paypal_total_forms_value) {
                    $paypal_total_forms_unsecure['unsecure_count'][$paypal_total_forms_key] = count($paypal_total_forms['unsecure'][$paypal_total_forms_key]);
                }
                $paypal_total_forms_unsecure_count = array_sum($paypal_total_forms_unsecure['unsecure_count']);
                $paypal_total_all_forms_count['unsecure_count'] = $paypal_total_forms_unsecure_count;
            }
            if (isset($paypal_total_forms['secure']) && !empty($paypal_total_forms['secure'])) {
                foreach ($paypal_total_forms['secure'] as $paypal_total_forms_key => $paypal_total_forms_value) {
                    $paypal_total_forms_secure['secure_count'][$paypal_total_forms_key] = count($paypal_total_forms['secure'][$paypal_total_forms_key]);
                }
                $paypal_total_forms_secure_count = array_sum($paypal_total_forms_secure['secure_count']);
                $paypal_total_all_forms_count['secure_count'] = $paypal_total_forms_secure_count;
            }
            if (isset($paypal_total_forms['medium_risk_buttons']) && !empty($paypal_total_forms['medium_risk_buttons'])) {
                foreach ($paypal_total_forms['medium_risk_buttons'] as $paypal_total_forms_key => $paypal_total_forms_value) {
                    $paypal_total_forms_medium_secure['medium_secure_count'][$paypal_total_forms_key] = count($paypal_total_forms['medium_risk_buttons'][$paypal_total_forms_key]);
                }
                $paypal_total_forms_medium_secure_count = array_sum($paypal_total_forms_medium_secure['medium_secure_count']);
                $paypal_total_all_forms_count['medium_secure_count'] = $paypal_total_forms_medium_secure_count;
            }
        }
        return $paypal_total_all_forms_count;
    }

    public function paypal_security_get_post_result($post_type) {
        global $post, $wpdb;
        $table_name = $wpdb->prefix . "posts";
        $paypal_security_content = array();
        $paypal_security_publisharray = array();
        $paypal_action_url = array();
        $retrive_cmd = array();
        $current_form_html = array();
        $retrive_item_name = array();
        $button_name = '';
        $paypal_security_paypal_form_html = array();
        $post_id_list = array();

        $find_post = array();
        foreach ($post_type as $key => $value) {
            $find_post[] = "'" . $key . "'";
        }
        $selected_post_types = join(',', $find_post);
        $paypal_security_publisharray = $wpdb->get_results("SELECT ID from $table_name where post_status='publish' and post_type IN ($selected_post_types) AND $table_name.post_status = 'publish'", ARRAY_A);
        foreach ($paypal_security_publisharray as $key => $value) {
            $post_id_list[$key] = $value['ID'];
        }
        $post_id_list['count'] = $key + 1;
        echo json_encode($post_id_list, true);
        exit();
    }

}
