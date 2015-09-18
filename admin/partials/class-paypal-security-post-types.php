<?php

/**
 * @class       AngellEYE_PayPal_Security
 * @version		1.0.0
 * @package		paypal-security
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Security_Admin_Post_type {

    public static function init() {
        if (  is_admin() ) {
            add_action('init', array(__CLASS__, 'paypal_security_register_post_types'), 5);
        }
    }

    public static function paypal_security_register_post_types() {
        global $wpdb;
        if (post_type_exists('report_history')) {
            return;
        }
        do_action('paypal_security_register_post_type');
        register_post_type('report_history', apply_filters('paypal_security_register_post_type', array(
            'labels' => array(
                'name' => __('PayPal Report History', 'paypal-security'),
                'singular_name' => __('PayPal Report History', 'paypal-security'),
                'menu_name' => _x('PayPal Report History', 'Admin menu name', 'paypal-security'),
                'add_new' => __('Add PayPal Report History', 'paypal-security'),
                'add_new_item' => __('Add New PayPal Report History', 'paypal-security'),
                'edit' => __('Edit', 'paypal-security'),
                'edit_item' => __('View PayPal Report History', 'paypal-security'),
                'new_item' => __('New PayPal Report History', 'paypal-security'),
                'view' => __('View PayPal Report History', 'paypal-security'),
                'view_item' => __('View PayPal Report History', 'paypal-security'),
                'search_items' => __('Search PayPal Report History', 'paypal-security'),
                'not_found' => __('No PayPal Report History found', 'paypal-security'),
                'not_found_in_trash' => __('No PayPal Report History found in trash', 'paypal-security'),
                'parent' => __('Parent PayPal Report History', 'paypal-security')
            ),
            'description' => __('', 'paypal-security'),
            'public' => false,
            'show_ui' => false,
            'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => false, 
            ),
            'map_meta_cap' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'hierarchical' => false, 
            'rewrite' => array('slug' => 'report_history'),
            'query_var' => true,
            'menu_icon' => '',
            'supports' => array('', ''),
            'has_archive' => true,
            'show_in_nav_menus' => true
                        )
                )
        );
    }
}

AngellEYE_PayPal_Security_Admin_Post_type::init();