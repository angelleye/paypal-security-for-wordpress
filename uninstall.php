<?php
/**
 * Fired when the plugin is uninstalled.
  */
// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
global $wpdb;
$paypal_security_tbl = $wpdb->prefix . 'paypal_security_for_wordpress_reports';
$wpdb->query("DROP TABLE IF EXISTS $paypal_security_tbl");
