<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    paypal-security
 * @subpackage paypal-security/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Security {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      AngellEYE_PayPal_Security_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the Dashboard and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'paypal-security';
        $this->version = '1.0.3';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        
        $prefix = is_network_admin() ? 'network_admin_' : '';
        add_filter("{$prefix}plugin_action_links_" . PAYPAL_SECURITY_PLUGIN_BASENAME, array($this, 'plugin_action_links'), 10, 4);
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - paypal-security-loader. Orchestrates the hooks of the plugin.
     * - paypal-security-i18n. Defines internationalization functionality.
     * - paypal-security-admin. Defines all hooks for the dashboard.
     * - paypal-security-public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-paypal-security-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-paypal-security-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the Dashboard.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-paypal-security-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-paypal-security-public.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/paypal-security-helper.php';

        /**
         * Dom php class file included.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/simple_html_dom.php';

        $this->loader = new AngellEYE_PayPal_Security_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the AngellEYE_PayPal_Security_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new AngellEYE_PayPal_Security_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the dashboard functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new AngellEYE_PayPal_Security_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('wp_ajax_paypal_scan_action', $plugin_admin, 'paypal_security_scan_action_fn');
        $this->loader->add_action('wp_ajax_paypal_scan_data', $plugin_admin, 'paypal_security_scan_action_fn_scan');
        $this->loader->add_action( 'post_updated', $plugin_admin, 'post_updated_remove_exclude_post_list', 10, 3 );
        $this->loader->add_action( 'save_post', $plugin_admin, 'post_updated_remove_exclude_post_list', 10, 1 );
        $this->loader->add_action( 'publish_post', $plugin_admin, 'post_updated_remove_exclude_post_list', 10, 1 );
        $this->loader->add_action( 'deactivate_plugin', $plugin_admin, 'plugin_remove_exclude_post_list', 10);
        $this->loader->add_action( 'activated_plugin', $plugin_admin, 'plugin_remove_exclude_post_list', 10 );
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'ps_hide_update_notice', 11 );
        $this->loader->add_action('wp_ajax_pss_delete_paypal_scan_history', $plugin_admin, 'pss_delete_paypal_scan_history');
       
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new AngellEYE_PayPal_Security_Public($this->get_plugin_name(), $this->get_version());
        
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    paypal-security-loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
    
    /**
     * @since     1.0.0
     * @param type $actions
     * @param type $plugin_file
     * @param type $plugin_data
     * @param type $context
     * @return type
     */
    public function plugin_action_links($actions, $plugin_file, $plugin_data, $context) {
        $custom_actions = array(
            'scanner' => sprintf('<a href="%s">%s</a>', admin_url('/tools.php?page=paypal-security'), __('Scanner', 'paypal-security')),
            'docs' => sprintf('<a href="%s" target="_blank">%s</a>', 'https://www.angelleye.com/category/docs/paypal-security-wordpress/', __('Docs', 'paypal-security')),
            'support' => sprintf('<a href="%s" target="_blank">%s</a>', 'https://wordpress.org/support/plugin/paypal-security/', __('Support', 'paypal-security')),
            'review' => sprintf('<a href="%s" target="_blank">%s</a>', 'http://wordpress.org/support/view/plugin-reviews/paypal-security', __('Write a Review', 'paypal-security')),
        );

        // add the links to the front of the actions list
        return array_merge($custom_actions, $actions);
    }

}
