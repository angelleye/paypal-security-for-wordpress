<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    paypal-security
 * @subpackage paypal-security/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Security_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {

        global $wpdb;

        /* // Log activation in Angell EYE database via web service.
          $log_url = $_SERVER['HTTP_HOST'];
          $log_plugin_id = 9;
          $log_activation_status = 1;
          wp_remote_request('http://www.angelleye.com/web-services/wordpress/update-plugin-status.php?url='.$log_url.'&plugin_id='.$log_plugin_id.'&activation_status='.$log_activation_status);
         */

        $table_name = $wpdb->prefix . "paypal_security_reports";
        $charset_collate = $wpdb->get_charset_collate();

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
		`ID` mediumint(9) NOT NULL AUTO_INCREMENT,
		`report_note` text  NULL,
		`report_date` date  NULL,
		
		UNIQUE KEY ID (ID)
		) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        } else if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {

            // if already table is there then alter table code will go here
        }
    }

}
