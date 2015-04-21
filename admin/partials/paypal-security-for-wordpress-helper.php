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
        $paypal_security_for_wordpress_matches = array();
        $paypal_security_for_wordpress_content_final = array();

        $paypal_security_for_wordpress_content_filtered_unsecure = array();
        $paypal_security_for_wordpress_content_filtered_secure = array();
        $paypal_action_url = array();
        $retrive_cmd = array();
        $paypal_security_for_wordpress_content_temp = array();
        $paypal_security_for_wordpress_paypal_form_html = array();
        $paypal_security_for_wordpress_publisharray = $wpdb->get_results("SELECT * from $table_name where post_status='publish'");
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

                                if (isset($retrive_cmd[$key_retrive_cmd]->attr['value']) && !empty($retrive_cmd[$key_retrive_cmd]->attr['value'])) {
                                    if ($retrive_cmd[$key_retrive_cmd]->attr['value'] != '_s-xclick') {
                                        $paypal_security_for_wordpress_content['unsecure'][$paypal_security_for_wordpress_publisharray_value->ID][$key_retrive_cmd] = $value_retrive_cmd->parent()->outertext();
                                    } else {
                                        $paypal_security_for_wordpress_content['secure'][$paypal_security_for_wordpress_publisharray_value->ID][$key_retrive_cmd] = $value_retrive_cmd->parent()->outertext();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $paypal_security_for_wordpress_content;
    }

}