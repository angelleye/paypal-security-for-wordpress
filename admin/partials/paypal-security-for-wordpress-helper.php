<?php

/**
 * This class defines all paypal custom functions
 * @class       AngellEYE_PayPal_Security_for_WordPress_PayPal_Helper
 * @version	1.0.0
 * @package		paypal-security-for-wordpress/partials
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Security_for_WordPress_PayPal_Helper {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public function __construct() {
        //$this->paypal_security_for_wordpress_get_arraywithpaypaltext();
    }

    public function paypal_security_for_wordpress_get_arraywithpaypaltext() {

        global $post, $wpdb;
        $table_name = $wpdb->prefix . "posts";
        $paypal_security_for_wordpress_content = array();
        $paypal_security_for_wordpress_publisharray = array();
        $paypal_action_url = array();
        $retrive_cmd = array();
        $current_form_html = array();
        $retrive_item_name = array();
        $button_name = '';
        $paypal_security_for_wordpress_paypal_form_html = array();


        $paypal_security_for_wordpress_publisharray = $wpdb->get_results("SELECT * from $table_name where post_status='publish'");
        $paypal_security_for_wordpress_count = $wpdb->get_row("SELECT count(*) as cnt_total from $table_name where post_status='publish'");
        $paypal_security_for_wordpress_content['total_post'] = $paypal_security_for_wordpress_count->cnt_total;



        foreach ($paypal_security_for_wordpress_publisharray as $key_post => $paypal_security_for_wordpress_publisharray_value) {
            $html = file_get_html(get_permalink($paypal_security_for_wordpress_publisharray_value->ID));
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
                                $check_is_viewcart = $viewcart_str_html->find('[name=item_name]');
                                $check_is_shoppingbutton = $viewcart_str_html->find('[name=shopping_url]');
                         //    if(!empty($check_is_viewcart) || $retrive_cmd[$key_retrive_cmd]->attr['value'] == '_oe-gift-certificate') {
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
										}else {
											$itemname = 'Not Available';
										}
										
										$retrive_business = $current_form_html->find('[name=business]');
										
										if (isset($retrive_business[0]->attr['value']) && !empty($retrive_business[0]->attr['value'])) {
											if(strpos($retrive_business[0]->attr['value'],'@') !== false) {
												$paypal_security_for_wordpress_content['unsecure'][$paypal_security_for_wordpress_publisharray_value->ID][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()][$itemname] = $button_name;								
											} else {
												$paypal_security_for_wordpress_content['medium_risk_buttons'][$paypal_security_for_wordpress_publisharray_value->ID][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()][$itemname] = $button_name;
											}
										} else {
											$paypal_security_for_wordpress_content['unsecure'][$paypal_security_for_wordpress_publisharray_value->ID][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()][$itemname] = $button_name;								
										}
                                    	$paypal_security_for_wordpress_content['button_type'][$paypal_security_for_wordpress_publisharray_value->ID][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()][$itemname] = $button_name;
                                    } else {
                                        $paypal_security_for_wordpress_content['secure'][$paypal_security_for_wordpress_publisharray_value->ID][$key_retrive_cmd][$value_retrive_cmd->parent()->outertext()][$itemname] = $button_name;
                                    }
                                }
                               //} 
                            }
                        }
                    }
                }
            }
        }
        return $paypal_security_for_wordpress_content;
    }

    public function paypal_security_for_wordpress_get_total_forms($paypal_security_scanner_finalarrayresult) {
        $paypal_total_forms = array();
        $paypal_total_forms = $paypal_security_scanner_finalarrayresult;

        $paypal_total_forms_unsecure = array();
        $paypal_total_forms_unsecure_count = array();

        $paypal_total_forms_secure = array();
        $paypal_total_forms_secure_count = array();
        $paypal_total_all_forms_count = array();


        if (isset($paypal_total_forms) && !empty($paypal_total_forms)) {
            if (isset($paypal_total_forms['unsecure']) && !empty($paypal_total_forms['unsecure'])) {
                foreach ($paypal_total_forms['unsecure'] as $paypal_total_forms_key => $paypal_total_forms_value) {
                    $paypal_total_forms_unsecure['unsecure_count'][$paypal_total_forms_key] = count($paypal_total_forms_value);
                }
                $paypal_total_forms_unsecure_count = array_sum($paypal_total_forms_unsecure['unsecure_count']);
                $paypal_total_all_forms_count['unsecure_count'] = $paypal_total_forms_unsecure_count;
            }
            if (isset($paypal_total_forms['secure']) && !empty($paypal_total_forms['secure'])) {
                foreach ($paypal_total_forms['secure'] as $paypal_total_forms_key => $paypal_total_forms_value) {
                    $paypal_total_forms_secure['secure_count'][$paypal_total_forms_key] = count($paypal_total_forms_value);
                }
                $paypal_total_forms_secure_count = array_sum($paypal_total_forms_secure['secure_count']);
                $paypal_total_all_forms_count['secure_count'] = $paypal_total_forms_secure_count;
            }
        }


        return $paypal_total_all_forms_count;
    }

}