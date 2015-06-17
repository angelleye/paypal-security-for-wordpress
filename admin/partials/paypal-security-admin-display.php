<?php

/**
 * @class       AngellEYE_PayPal_Security
 * @version		1.0.0
 * @package		paypal-security
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Security_Admin_Display {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_settings_menu'));
    }

    public static function add_settings_menu() {
        add_management_page('PayPal Security', 'PayPal Security', 'manage_options', 'paypal-security', array(__CLASS__, 'paypal_security_options'));
    }

    public static function paypal_security_options() {
        ?>
        <div id="paypal_security_scanner_fieldset">

            <fieldset>
                <legend><h2><?php _e('PayPal security scanner', 'paypal-security'); ?></h2></legend>
                <form id="frm_scan">
                    <?php
                    $output = 'names'; // names or objects, note names is the default
                    $operator = 'and'; // 'and' or 'or'
                    $args = array(
                        'public' => true,
                    );
                    $post_types = get_post_types($args, $output, $operator);
                    $selectboxhtml = '';
                    foreach ($post_types as $post_type) {
                        $selectboxhtml .= '<div>';
                        $selectboxhtml .= "<label for = " . $post_type . ">";
                        $selectboxhtml .= "<input type='checkbox' name =" . $post_type . " checked>" . ucfirst($post_type);
                        $selectboxhtml .= '</label>';
                        $selectboxhtml .= '</div >';
                    }
                    echo $selectboxhtml;
                    ?>
                    <h4>Click Scan Now button for scan all insecure PayPal buttons.</h4>
                    <h5>This could take a while depending on how many Pages / Posts you have.</h5>
                    <span id="btn_pswp" class="button button-primary btn_pswp">Scan Now</span>
                    <span id="notice" style="display:none;">Please select atleast one checkbox to use PayPal security scanner.</span>
                    <img src="<?php echo plugin_dir_url(__FILE__) ?>images/ajax-loader.gif" id="loader_gifimg"/>
                </form>
            </fieldset>
        </div>
        <?php do_action('paypal_scan_action'); ?>
        <div id="paypal_scan_response">
        </div>
        <?php
    }

}

AngellEYE_PayPal_Security_Admin_Display::init();