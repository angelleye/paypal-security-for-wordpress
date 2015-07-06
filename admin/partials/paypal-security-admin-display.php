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
                <legend><h2><?php _e('PayPal Security Scanner', 'paypal-security'); ?></h2></legend>
                <div class="div_frm_main">
                    <div class="frm_checkboxes">
                        <form id="frm_scan">
                            <h4>Instructions</h4>
                            <ol>
                                <li>Disable (un-check) the boxes for the post types that you know do not contain any PayPal buttons.</li>
                                <li>Click the Scan Now button to run the scan and return a report of PayPal button security on your site.</li>
                            </ol>
                            <p><strong>NOTE</strong>: The scan may take a while depending on how many pages / posts you have on your site.</p>
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
                                $selectboxhtml .= "<label for = " . $post_type .'id'. ">";
                                $selectboxhtml .= "<input type='checkbox' id=" . $post_type .'id'." name =" . $post_type . " checked>" . ucfirst($post_type);
                                $selectboxhtml .= '</label>';
                                $selectboxhtml .= '</div >';
                            }
                            echo $selectboxhtml;
                            ?>
                            <span id="btn_pswp" class="button button-primary btn_pswp">Scan Now</span>
                            <p id="notice" style="display:none;">Please select at least one checkbox to use PayPal security scanner.</p>
                            <img src="<?php echo plugin_dir_url(__FILE__) ?>images/ajax-loader.gif" id="loader_gifimg"/>
                        </form>

                    </div> <!-- frm_checkboxes-->

                    <div class="div_get_totalscan">


                    </div><!--div_get_total_scan-->

                    <div class="div_site_score">


                    </div> <!-- div_site_score -->



                </div><!--frm_main-->

            </fieldset>

        </div>
        <?php do_action('paypal_scan_action'); ?>
        <div id="paypal_scan_response">
        </div>
        <?php
    }

}

AngellEYE_PayPal_Security_Admin_Display::init();