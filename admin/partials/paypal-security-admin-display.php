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
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'scanner';
        ?>
        <h2 class="nav-tab-wrapper">
            <a href="?page=paypal-security&tab=scanner" class="nav-tab <?php echo $active_tab == 'scanner' ? 'nav-tab-active' : ''; ?>"><?php echo __('Scanner', 'paypal-security'); ?></a>
            <a href="?page=paypal-security&tab=scan_history" class="nav-tab <?php echo $active_tab == 'scan_history' ? 'nav-tab-active' : ''; ?>"><?php echo __('Scan History', 'paypal-security'); ?></a>
        </h2>
        <?php if( $active_tab == 'scanner' ) { ?>
        <div id="paypal_security_scanner_fieldset">
            <fieldset>
                <legend><h2><?php _e('PayPal Security Scanner', 'paypal-security'); ?></h2></legend>
                <div class="div_frm_main">
                    <div class="frm_checkboxes">
                        <form id="frm_scan">
                            <h4><?php echo __( 'Instructions', 'woocommerce' ); ?></h4>
                            <ol>
                                <li><?php echo __( 'Disable (un-check) the boxes for the post types that you know do not contain any PayPal buttons.', 'paypal-security' ); ?></li>
                                <li><?php echo __( 'Click the Scan Now button to run the scan and return a report of PayPal button security on your site.', 'paypal-security' ); ?></li>
                            </ol>
                            <p><strong><?php echo __( 'NOTE', 'paypal-security' ); ?></strong>: <?php echo __( 'The scan may take a while depending on how many pages / posts you have on your site.', 'paypal-security' ); ?></p>
                            <?php
                            $output = 'names'; // names or objects, note names is the default
                            $operator = 'and'; // 'and' or 'or'
                            $args = array(
                                'public' => true,
                            );
                            $post_types = get_post_types($args, $output, $operator);
                            $selectboxhtml = '';
                            foreach ($post_types as $post_type) {
                                if ($post_type != "attachment") {
                                    $selectboxhtml .= '<div>';
                                    $selectboxhtml .= "<label for = " . $post_type . 'id' . ">";
                                    $selectboxhtml .= "<input type='checkbox' id=" . $post_type . 'id' . " name =" . $post_type . " checked>" . ucfirst($post_type);
                                    $selectboxhtml .= '</label>';
                                    $selectboxhtml .= '</div >';
                                }
                            }
                            echo $selectboxhtml;
                            ?>
                            <span id="btn_pswp" class="button button-primary btn_pswp"><?php echo __( 'Scan Now', 'paypal-security' ); ?></span>
                            <div id="progressbar" style="display: none"><div class="progress-label"><?php echo __( 'Loading...', 'paypal-security' ); ?></div></div>
                            <input type="hidden" value="" name="progressbar_timeout" id="progressbar_timeout">
                            <p id="notice" style="display:none;"><?php echo __( 'Please select at least one checkbox to use PayPal security scanner.', 'paypal-security' ); ?></p>
                            <img src="<?php echo plugin_dir_url(__FILE__) ?>images/ajax-loader.gif" id="loader_gifimg"/>
                        </form>
                    </div> <!-- frm_checkboxes-->
                    <div class="div_get_totalscan"></div><!--div_get_total_scan-->
                    <div class="div_site_score"></div> <!-- div_site_score -->
                    <div id="pps_recommendation" style="display: none;"></div>
                </div><!--frm_main-->
            </fieldset>
        </div>
        <?php do_action('paypal_scan_action'); ?>
        
        <div id="paypal_scan_response">
        </div>
        <?php  } elseif($active_tab == 'scan_history') {

            $type = 'report_history';
            $args = array(
                'post_type' => $type,
                'post_status' => 'publish',
                'posts_per_page' => -1,
            );
            $posts = get_posts($args);
            $tbody = '';
            if ($posts) {
                ?>
                <div class='wrap' id="report_history">
                    <h3><?php _e('PayPal Security Scan History', 'paypal-security'); ?><span class="button button-primary btn_pswp" id="delete_ps_history"><?php echo __('Delete Scan History', 'paypal-security') ?></span></h3>
                    <table class="widefat" cellspacing="0" id="report_history_table"><thead>
                            <tr>
                                <th><?php echo __('Scan Date', 'paypal-security') ?></th>
                                <th><?php echo __('Scan Data', 'paypal-security') ?></th>
                                <th><?php echo __('Site Score Percentage', 'paypal-security') ?></th>
                                <th><?php echo __('Site Grade', 'paypal-security') ?></th>

                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><?php echo __('Scan Date', 'paypal-security') ?></th>
                                <th><?php echo __('Scan Data', 'paypal-security') ?></th>
                                <th><?php echo __('Site Score Percentage', 'paypal-security') ?></th>
                                <th><?php echo __('Site Grade', 'paypal-security') ?></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach ($posts as $post):
                                $tbody .= "<tr>";
                                $paypal_website_scan_report = get_post_meta($post->ID, 'paypal_website_scan_report', true);
                                $tbody .= "<td>" . get_the_time("y-m-d g:i:s", $post->ID) . "</td>";
                                $tbody .= "<td>" . $paypal_website_scan_report['scan_data'] . "</td>";
                                if (empty($paypal_website_scan_report['txt_site_score'])) {
                                    $paypal_website_scan_report['txt_site_score'] = 0;
                                }
                                $tbody .= "<td>" . $paypal_website_scan_report['txt_site_score'] . '%' . "</td>";
                                $txt_cls_color = $paypal_website_scan_report['txt_cls_color'];
                                if ($paypal_website_scan_report['txt_site_grade'] == 'No buttons found...') {
                                    $paypal_website_scan_report['txt_site_grade'] = 'N/A';
                                    $class = '';
                                } else {
                                    $class = 'cls_site_grade';
                                }
                                $tbody .= "<td><div class=' $class $txt_cls_color'>" . $paypal_website_scan_report['txt_site_grade'] . "</div></tr>";
                                $tbody .= "</tr>";
                            endforeach;
                            echo $tbody;
                            ?>
                        </tbody></table>
                </div>
                <?php
            } else {
               echo __('No PayPal Report History found', 'paypal-security');
            }
        }
    }

}

AngellEYE_PayPal_Security_Admin_Display::init();
