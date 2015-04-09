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

        $paypal_security_for_wordpress_publisharray = $wpdb->get_results("SELECT * from $table_name where post_status='publish'");
        foreach ($paypal_security_for_wordpress_publisharray as $key_post => $paypal_security_for_wordpress_publisharray_value) {

            $page_source_row = wp_remote_retrieve_body(wp_remote_get(get_permalink($paypal_security_for_wordpress_publisharray_value->ID)));
            $raw_paypal_string = $page_source_row;
            if (preg_match("~\bpaypal.com\b~", $raw_paypal_string)) {
                $paypal_security_for_wordpress_content[$paypal_security_for_wordpress_publisharray_value->ID] = $paypal_security_for_wordpress_publisharray_value->post_content;
            }
        }
        foreach ($paypal_security_for_wordpress_content as $key_paypal_security_for_wordpress_content => $paypal_security_for_wordpress_content_value) {

            $page_source_first_filter = wp_remote_retrieve_body(wp_remote_get(get_permalink($key_paypal_security_for_wordpress_content)));
            $first_filter_paypal_string = $page_source_first_filter;

            if (((preg_match('~\b_cart\b~', $first_filter_paypal_string)) && (preg_match("~\bamount\b~", $first_filter_paypal_string))) && (preg_match('~\b_s-xclick\b~', $first_filter_paypal_string))) {

                $paypal_security_for_wordpress_content_final['unsecure'][$key_paypal_security_for_wordpress_content] = get_permalink($key_paypal_security_for_wordpress_content);
            } else if ((!preg_match('~\b_cart\b~', $first_filter_paypal_string) && (!preg_match('~\bamount\b~', $first_filter_paypal_string))) && (preg_match('~\b_s-xclick\b~', $first_filter_paypal_string))) {
                $paypal_security_for_wordpress_content_final['secure'][$key_paypal_security_for_wordpress_content] = get_permalink($key_paypal_security_for_wordpress_content);
            }
        }


        return $paypal_security_for_wordpress_content_final;
    }

}