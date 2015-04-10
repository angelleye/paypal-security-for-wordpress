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
        $paypal_security_for_wordpress_publisharray = $wpdb->get_results("SELECT * from $table_name where post_status='publish'");
        foreach ($paypal_security_for_wordpress_publisharray as $key_post => $paypal_security_for_wordpress_publisharray_value) {

            //$page_source_row = wp_remote_retrieve_body(wp_remote_get(get_permalink($paypal_security_for_wordpress_publisharray_value->ID)));
            //$raw_paypal_string = $page_source_row;
            $html = file_get_html(get_permalink($paypal_security_for_wordpress_publisharray_value->ID));



            if (isset($html) && !empty($html)) {
                $paypal_action_url = '';
                foreach ($html->find('form') as $e) {
                    if ($e->action == 'https://www.sandbox.paypal.com' || $e->action == 'https://www.sandbox.paypal.com/cgi-bin/webscr' || $e->action == 'https://www.paypal.com/cgi-bin/webscr' || $e->action == 'https://www.paypal.com') {
                        $paypal_action_url = $e->action;
                    }
                }
                if (isset($paypal_action_url) && !empty($paypal_action_url)) {
                    $retrive_cmd = $html->find('[name=cmd]');

                    $count_retrive_cmd = count($retrive_cmd);

                    if ($count_retrive_cmd == '1') {
                        if (isset($retrive_cmd['0']->attr['value']) && !empty($retrive_cmd['0']->attr['value'])) {
                            if ($retrive_cmd['0']->attr['value'] == '_s-xclick') {
                                $paypal_security_for_wordpress_content['secure'][$paypal_security_for_wordpress_publisharray_value->ID] = get_permalink($paypal_security_for_wordpress_publisharray_value->ID);
                            } else {
                                $paypal_security_for_wordpress_content['unsecure'][$paypal_security_for_wordpress_publisharray_value->ID] = get_permalink($paypal_security_for_wordpress_publisharray_value->ID);
                            }
                        }
                    } else {


                        foreach ($retrive_cmd as $key_retrive_cmd => $value_retrive_cmd) {
                            if (isset($value_retrive_cmd->attr['value']) && !empty($value_retrive_cmd->attr['value'])) {
                                if ($value_retrive_cmd->attr['value'] == '_s-xclick') {
                                    $paypal_security_for_wordpress_content_temp['secure'][$paypal_security_for_wordpress_publisharray_value->ID] = get_permalink($paypal_security_for_wordpress_publisharray_value->ID);
                                } else {
                                    $paypal_security_for_wordpress_content_temp['unsecure'][$paypal_security_for_wordpress_publisharray_value->ID] = get_permalink($paypal_security_for_wordpress_publisharray_value->ID);
                                }
                            }
                        }

                        if (isset($paypal_security_for_wordpress_content_temp['unsecure']) && !empty($paypal_security_for_wordpress_content_temp['unsecure'])) {
                            $paypal_security_for_wordpress_content['unsecure'][$paypal_security_for_wordpress_publisharray_value->ID] = get_permalink($paypal_security_for_wordpress_publisharray_value->ID);
                        } else {
                            $paypal_security_for_wordpress_content['secure'][$paypal_security_for_wordpress_publisharray_value->ID] = get_permalink($paypal_security_for_wordpress_publisharray_value->ID);
                        }
                    }
                }
            }
        }
        return $paypal_security_for_wordpress_content;
    }

}