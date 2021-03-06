<?php
/*
Plugin Name: Dokan - Subscription
Plugin URI: https://wedevs.com/products/plugins/dokan/
Description: Product subscription pack add-on for Dokan sellers
Version: 1.1.4
Author: weDevs
Author URI: https://wedevs.com/
License: GPL2
*/

/**
 * Copyright (c) 2016 weDevs (email: info@wedevs.com ). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helper function for loggin
 *
 * @param string $message
 */
function dokan_dps_log( $message ) {
    $message = sprintf( "[%s] %s\n", date( 'd.m.Y h:i:s' ), $message );
    error_log( $message, 3, dirname( __FILE__ ) . '/debug.log' );
}

require_once dirname( __FILE__ ) . '/includes/classes/class-dps-paypal-standard-subscriptions.php';

if ( is_admin() ) {
    require_once dirname( __FILE__ ) . '/lib/wedevs-updater.php';

    new WeDevs_Plugin_Update_Checker( plugin_basename( __FILE__ ), 'dokan-subscription' );
}

/**
 * Dokan_Product_Subscription class
 *
 * @class Dokan_Product_Subscription The class that holds the entire Dokan_Product_Subscription plugin
 *
 * @package Dokan
 * @subpackage Subscription
 */
class Dokan_Product_Subscription {

    /**
     * Constructor for the Dokan_Product_Subscription class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses register_activation_hook()
     * @uses register_deactivation_hook()
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct() {

        $this->response = '';

        $this->define_constants();
        $this->file_includes();

        // enable the settings only when the subscription is ON
        $enable_option = get_option( 'dokan_product_subscription', array( 'enable_pricing' => 'off' ) );

        if ( !isset( $enable_option['enable_pricing'] ) || $enable_option['enable_pricing'] != 'on' ) {
            return;
        }

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );

        // Loads frontend scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );

        // Loads all actions
        add_filter( 'dokan_can_add_product', array( $this, 'seller_add_products' ), 1, 1 );
        add_action( 'dokan_can_post_notice', array( $this, 'display_product_pack' ) );
        add_filter( 'dokan_can_post', array( $this, 'can_post_product' ) );

        add_action( 'dps_schedule_pack_update', array( $this, 'schedule_task' ) );
        add_action( 'dokan_before_listing_product', array( $this, 'show_custom_subscription_info' ) );

        // add_action( 'dokan_after_delete_product_item', array( $this, 'update_meta_for_delete_product' ) );
        // add_action( 'valid-paypal-standard-ipn-request', array( $this, 'process_paypal_ipn_request' ), 9 );

        add_filter( 'dokan_get_dashboard_nav', array( $this, 'add_new_page' ), 11, 1 );
        add_action( 'dokan_load_custom_template', array( $this, 'load_template_from_plugin') );

        add_filter( 'woocommerce_order_item_needs_processing', array( $this, 'order_needs_processing' ), 10, 2 );
        add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'add_to_cart_redirect' ) );
        add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'maybe_empty_cart' ), 10, 3 );
        add_action( 'woocommerce_order_status_changed', array( $this, 'process_order_pack_product' ), 10, 3 );

        add_filter( 'template_redirect', array( $this, 'user_subscription_cancel' ) );

        add_filter( 'dokan_query_var_filter', array( $this, 'add_subscription_endpoint' ) );

        // Load Shortcodes
        add_shortcode( 'dps_product_pack', array( $this, 'shortcode_handler' ) );


    }

    /**
     * Initializes the Dokan_Product_Subscription() class
     *
     * Checks for an existing Dokan_Product_Subscription() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Dokan_Product_Subscription();
        }

        return $instance;
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public static function activate() {

        do_action('dps_schedule_pack_update');

        if ( false == wp_next_scheduled( 'dps_schedule_pack_update' ) ) {
            wp_schedule_event( time(), 'daily', 'dps_schedule_pack_update' );
        }

        if( !self::is_dokan_plugin() ) {

            if ( ! get_page_by_title( __( 'Product Subscription', 'dps' ) ) ) {

                $dasboard_page = get_page_by_title( 'Dashboard' );

                $page_id = wp_insert_post( array(
                    'post_title'   => wp_strip_all_tags( __( 'Product Subscription', 'dps' ) ),
                    'post_content' => '[dps_product_pack]',
                    'post_status'  => 'publish',
                    'post_parent'  => $dasboard_page->ID,
                    'post_type'    => 'page'
                ) );
            }
        }
    }

    /**
     * Placeholder for deactivation function
     *
     */
    public static function deactivate() {
        wp_clear_scheduled_hook( 'dps_schedule_pack_update' );
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'dps', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Check is Dokan is plugin or nor
     * @return boolean true|false
     */
    public static function is_dokan_plugin() {
        return defined('DOKAN_PLUGIN_VERSION');
    }

    /**
     * Define constants
     *
     * @return void
     */
    function define_constants() {
        define( 'DPS_PATH', dirname( __FILE__ ) );
        define( 'DPS_URL', plugins_url( '', __FILE__ ) );
    }

    /**
     * Includes required files
     *
     * @return void
     */
    function file_includes() {
        if ( is_admin() ) {
            require_once DPS_PATH . '/includes/admin/admin.php';
        }

        require_once DPS_PATH . '/includes/functions.php';
        require_once DPS_PATH . '/includes/classes/class-dps-manager.php';
        require_once DPS_PATH . '/includes/classes/class-registration-sub.php';
    }

    /**
     * Enqueue admin scripts
     *
     * Allows plugin assets to be loaded.
     *
     * @uses wp_enqueue_script()
     * @uses wp_localize_script()
     * @uses wp_enqueue_style
     */
    public function enqueue_scripts() {
        /**
         * All styles goes here
         */
        wp_enqueue_style( 'dps-custom-style', DPS_URL . '/assets/css/style.css', false, date( 'Ymd' ) );

        /**
         * All scripts goes here
         */
        wp_enqueue_script( 'dps-custom-js', DPS_URL . '/assets/js/script.js', array( 'jquery' ), false, true );
    }

    /**
     * Show_custom_subscription_info in Listing products
     */
    function show_custom_subscription_info() {

        if ( dokan_is_seller_enabled( get_current_user_id() ) ) {

            $remaining_product = dps_user_remaining_product( get_current_user_id() );

            if ( $remaining_product == 0 ) {
                if( self::is_dokan_plugin() ) {
                    $permalink = dokan_get_navigation_url('subscription');
                } else {
                    $page_id = dokan_get_option( 'subscription_pack', 'dokan_product_subscription' );
                    $permalink = get_permalink( $page_id );
                }
                // $page_id = dokan_get_option( 'subscription_pack', 'dokan_product_subscription' );
                $info    = sprintf( __( 'Sorry! You can not add or publish any more product. Please <a href="%s">update your package</a>.', 'dps' ), $permalink );
                echo "<p class='dokan-info'>" . $info . "</p>";
                echo "<style>.dokan-add-product-link{display : none !important}</style>";
            } else {
                echo "<p class='dokan-info'>". sprintf( __( 'You can add %d more product(s).', 'dps' ), $remaining_product ) . "</p>";
            }
        }
    }

    /**
     * Update(add) Product number when seller delete product
     *
     */
    function update_meta_for_delete_product() {
        $user_id         = get_current_user_id();
        $user_pack_id    = get_user_meta( $user_id, 'product_package_id', true );
        $pack_product_no = get_post_meta( $user_pack_id, '_no_of_product', true );

        $remaining_product = dps_user_remaining_product( $user_id );

        if ( $remaining_product != $pack_product_no ) {
            update_user_meta( $user_id, 'product_no_with_pack', $remaining_product + 1 );
        }
    }

    /**
     * Add Subscription endpoint to the end of Dashboard
     * @param array $query_var
     */
    function add_subscription_endpoint( $query_var ) {

        $query_var[] = 'subscription';

        return $query_var;
    }

    /**
     * Load template for the dashboard
     *
     * @param  array $query_vars
     *
     * @return void
     */
    function load_template_from_plugin( $query_vars ) {

        if ( isset( $query_vars['subscription'] ) ) {
            $installed_version = get_option( 'dokan_theme_version' );

            if ( $installed_version > '2.3' ) {
                $template = dirname( __FILE__ ) . '/templates/product_subscription_plugin_new.php';
            } else {
                $template = dirname( __FILE__ ) . '/templates/product_subscription_plugin.php';
            }

            include $template;
        }
    }

    /**
     * Add new menu in seller dashboard
     *
     * @param array   $urls
     * @return array
     */
    function add_new_page( $urls ) {

        if( self::is_dokan_plugin() ) {
            $permalink = dokan_get_navigation_url('subscription');
        } else {
            $page_id = dokan_get_option( 'subscription_pack', 'dokan_product_subscription' );
            $permalink = get_permalink( $page_id );
        }

        if ( dokan_is_seller_enabled( get_current_user_id() ) ) {
            $installed_version = get_option( 'dokan_theme_version' );

            if ( $installed_version > '2.3' ) {
                $urls['subscription'] = array(
                    'title' => __( 'Subscription', 'dps' ),
                    'icon'  => '<i class="fa fa-book"></i>',
                    'url'   => $permalink,
                    'pos'   => 180
                );
            } else {
                $urls['subscription'] = array(
                    'title' => __( 'Subscription', 'dps' ),
                    'icon'  => '<i class="fa fa-book"></i>',
                    'url'   => $permalink
                );
            }
        }

        return $urls;
    }

    /**
     * Restriction for adding product for seller
     *
     * @param array   $errors
     * @return string
     */
    function seller_add_products( $errors ) {
        $user_id = get_current_user_id();
        if ( dokan_is_user_seller( $user_id ) ) {

            $user_pack_id      = get_user_meta( $user_id, 'product_package_id', true );
            $pack_product_no   = get_post_meta( $user_pack_id, '_no_of_product', true );
            $remaining_product = dps_user_remaining_product( $user_id );

            if ( $remaining_product <= 0 ) {
                $errors[] = __( "Sorry your subscription exceeds your package limits please update your package subscription", 'dps' );
                return $errors;
            } else {
                update_user_meta( $user_id, 'product_no_with_pack', (int) $remaining_product - 1 );

                return $errors;
            }
        }
    }


    /**
     * Get number of product by seller
     *
     * @param integer $user_id
     * @return integer
     */
    function get_number_of_product_by_seller( $user_id ) {
        global $wpdb;

        $query = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = $user_id AND post_type = 'product' AND post_status = 'publish'";
        $count = $wpdb->get_var( $query );

        return $count;
    }

    /**
     * Returns a readable recurring period
     *
     * @param  string $period
     * @return string
     */
    function recurring_period( $period ) {
        switch ($period) {
            case 'day':
                return __( 'days', 'dps' );

            case 'week':
                return __( 'week', 'dps' );

            case 'month':
                return __( 'mo', 'dps' );

            case 'year':
                return __( 'yr', 'dps' );

            default:
                return apply_filters( 'dps_recurring_text', $period );
        }
    }

    /**
     * Get all product pack
     *
     */
    function shortcode_handler() {
        global $post;

        $checkout_url = WC()->cart->get_checkout_url();
        $user_id      = get_current_user_id();
        $product      = get_product( get_user_meta( $user_id, 'product_package_id', true ) );
        $order_id     = get_user_meta( $user_id, 'product_order_id', true );

        $args = array(
            'post_type' => 'product',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'product_pack'
                )
            ),
            'posts_per_page' => -1,
        );

        $query = new WP_Query( $args );

        ob_start();

        ?>

        <?php if ( Dokan_Product_Subscription::can_post_product() ): ?>
            <div class="seller_subs_info">
                <p>
                    <?php printf( __( 'Your are using <span>%s</span> package.', 'dps' ), $product->post->post_title ); ?>
                </p>
                <p>
                    <?php printf( __( 'You can add <span>%s</span> product(s) for <span>%s</span> days.', 'dps' ), get_post_meta( $product->id, '_no_of_product', true ), get_post_meta( $product->id, '_pack_validity', true ) ) ?>
                </p>
                <p>
                    <?php printf( __( 'Your package will expire on <span>%s</span>', 'dps' ), date_i18n( get_option( 'date_format' ), strtotime( get_user_meta( $user_id, 'product_pack_enddate', true ) ) ) ); ?>
                </p>

                <p>
                    <form action="" method="post">
                        <label><?php _e( 'To cancel your subscription click here &rarr;', 'dps' ); ?></label>

                        <?php wp_nonce_field( 'dps-sub-cancel' ); ?>
                        <input type="submit" name="dps_cancel_subscription" class="btn btn-sm btn-danger" value="<?php _e( 'Cancel', 'dps' ); ?>">
                    </form>
                </p>
                <p>
                    <?php _e( 'Please cancel your running package to switch another subscription', 'dps' ); ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if ( $query->have_posts() ) {
            ?>

            <?php if ( isset( $_GET['msg'] ) && $_GET['msg'] == 'dps_sub_cancelled' ) { ?>
                <div class="dokan-message">
                    <p><?php _e( 'Your subscription has been cancelled!', 'dps' ); ?></p>
                </div>
            <?php } ?>

            <div class="pack_content_wrapper">

            <?php
            while ( $query->have_posts() ) {
                $query->the_post();

                $is_recurring       = ( get_post_meta( $post->ID, '_enable_recurring_payment', true ) == 'yes' ) ? true : false;
                $recurring_interval = (int) get_post_meta( $post->ID, '_subscription_period_interval', true );
                $recurring_period   = get_post_meta( $post->ID, '_subscription_period', true );
                ?>

                    <div class="product_pack_item <?php echo ( $this->has_pack_validity_seller( get_the_ID() ) || $this->pack_renew_seller( get_the_ID() ) ) ? 'current_pack ' : ''; ?><?php echo ( ( get_post_meta( get_the_ID(), '_regular_price', true ) == '0' ) && $this->has_used_free_pack( get_current_user_id(), get_the_id() ) ) ? 'fp_already_taken' : ''; ?>">
                        <div class="pack_price">

                            <span class="dps-amount">
                                <?php if ( get_post_meta( get_the_ID(), '_regular_price', true ) == '0' ): ?>
                                    <?php _e( 'Free', 'dps' ); ?>
                                <?php else: ?>
                                    <?php if (get_post_meta( get_the_ID(), '_sale_price', true )): ?>
                                       <strike><?php echo get_woocommerce_currency_symbol() . get_post_meta( get_the_ID(), '_regular_price', true ); ?></strike> <?php echo get_woocommerce_currency_symbol() . get_post_meta( get_the_ID(), '_sale_price', true ); ?>
                                    <?php else: ?>
                                        <?php echo get_woocommerce_currency_symbol() . get_post_meta( get_the_ID(), '_regular_price', true ); ?>
                                    <?php endif ?>
                                <?php endif; ?>
                            </span>

                            <?php if ( $is_recurring && $recurring_interval === 1 ) { ?>
                                <span class="dps-rec-period">
                                    <span class="sep">/</span><?php echo $this->recurring_period( $recurring_period ); ?>
                                </span>
                            <?php } ?>
                        </div><!-- .pack_price -->

                        <div class="pack_content">
                            <h2><?php the_title(); ?></h2>

                            <?php the_content();

                            $no_of_product = get_post_meta( get_the_id(), '_no_of_product', true );
                            printf( __( '<strong>%d</strong> Products <br />', 'dps' ), $no_of_product );
                            ?>

                            <?php if ( $is_recurring && $recurring_interval > 1 ) { ?>
                                <span class="dps-rec-period">
                                    <?php printf( __( 'In every %d %s(s)', 'dps' ), $recurring_interval, $this->recurring_period( $recurring_period ) ); ?>
                                </span>
                            <?php } else {
                                $pack_validity = get_post_meta( get_the_id(), '_pack_validity', true );
                                printf( __( 'For<br /><strong>%s</strong> Days', 'dps' ), $pack_validity );
                            } ?>
                        </div>

                        <div class="buy_pack_button">
                            <?php if ( $this->has_pack_validity_seller( get_the_ID() ) ): ?>

                                <a href="<?php echo get_permalink( get_the_ID() ); ?>" class="buy_product_pack"><?php _e( 'Your Pack', 'dps' ); ?></a>

                            <?php elseif ( $this->pack_renew_seller( get_the_ID() ) ): ?>

                                <a href="<?php echo do_shortcode( '[add_to_cart_url id="' . get_the_ID() . '"]' ); ?>" class="buy_product_pack"><?php _e( 'Renew', 'dps' ); ?></a>

                            <?php else: ?>

                                <?php if ( ( get_post_meta( get_the_ID(), '_regular_price', true ) == '0' ) && $this->has_used_free_pack( get_current_user_id(), get_the_id() ) ): ?>
                                    <p><?php _e( 'Subscribed', 'dps' ); ?></p>

                                <?php elseif ( ! get_user_meta( get_current_user_id(), 'product_package_id', true ) ) : ?>

                                    <a href="<?php echo do_shortcode( '[add_to_cart_url id="' . get_the_ID() . '"]' ); ?>" class="buy_product_pack"><?php _e( 'Buy Now', 'dps' ); ?></a>

                                <?php else:

                                    $btn_link = sprintf('<a href="%s" class="buy_product_pack">%s</a>', get_permalink( get_the_ID() ), __( 'View Details', 'dps' ) ) ;

                                    echo apply_filters( 'dokan_notsubscribed_pack_button_text', $btn_link );

                                endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
            }
        } else {
            echo '<h3>' . __( 'No subscription pack has been found!', 'dps' ) . '</h3>';
        }

            wp_reset_postdata();
            ?>
            <div class="clearfix"></div>
        </div>
        <?php

        $contents = ob_get_clean();

        return apply_filters( 'dokan_sub_shortcode', $contents, $query, $args );
    }


    /**
     * Display Product Pack
     *
     */
    function display_product_pack() {

        if ( dokan_is_seller_enabled( get_current_user_id() ) ) {
            echo do_shortcode( '[dps_product_pack]' );
        } else {
            dokan_seller_not_enabled_notice();
        }
    }


    /**
     * Check is Seller has any subscription
     *
     * @return boolean
     */
    public static function can_post_product() {
        if ( get_user_meta( get_current_user_id(), 'can_post_product', true ) == '1' ) {
            return true;
        }

        return false;
    }

    /**
     * Shedule task daliy update this functions
     *
     */
    public function schedule_task() {
        $users = get_users( 'role=seller' );

        foreach ( $users as $user ) {

            $is_seller_enabled = dokan_is_seller_enabled( $user->ID );
            $can_post_product  = get_user_meta( $user->ID, 'can_post_product', true );
            $recurring_status  = get_user_meta( $user->ID, '_customer_recurring_subscription', true );
            $has_subscription  = get_user_meta( $user->ID, 'product_package_id', true );

            if ( $has_subscription && $is_seller_enabled && ( $can_post_product == '1' ) && ( $recurring_status != 'active' ) ) {

                if ( $this->alert_before_two_days( $user->ID ) ) {

                    $subject = ( dokan_get_option( 'email_subject', 'dokan_product_subscription' ) ) ? dokan_get_option( 'email_subject', 'dokan_product_subscription' ) : __( 'Package End notification alert', 'dps' );
                    $message = ( dokan_get_option( 'email_body', 'dokan_product_subscription' ) ) ? dokan_get_option( 'email_body', 'dokan_product_subscription' ) : __( 'Your Package validation remaining some days please confirm it', 'dps' );
                    $headers = 'From: ' . get_option( 'blogname' ) . ' <' . get_option( 'admin_email' ) . '>' . "\r\n";
                    wp_mail( $user->user_email, $subject, $message, $headers );
                }

                if ( $this->end_of_pack_validity( $user->ID ) ) {

                    $subject = ( dokan_get_option( 'email_subject', 'dokan_product_subscription' ) ) ? dokan_get_option( 'email_subject', 'dokan_product_subscription' ) : __( 'Subscription Package Cancel notification', 'dps' );
                    $message = ( dokan_get_option( 'email_body', 'dokan_product_subscription' ) ) ? dokan_get_option( 'email_body', 'dokan_product_subscription' ) : __( 'Due to finish your Package validation we are canceling your Subscription Package', 'dps' );
                    $headers = 'From: ' . get_option( 'blogname' ) . ' <' . get_option( 'admin_email' ) . '>' . "\r\n";
                    wp_mail( $user->user_email, $subject, $message, $headers );

                    if ( $this->check_seller_product_exist( $user->ID ) ) {
                        $this->update_product_status( $user->ID );
                    }
                    $order_id = get_user_meta( $user->ID, 'product_order_id', true );
                    dokan_dps_log ( 'Subscription cancel check: As the package has expired for order #' . $order_id . ', we are cancelling the Subscription Package of user #' . $user->ID );
                    self::delete_subscription_pack( $user->ID, $order_id );
                }
            }
        }
    }

    /**
     * Check Seller product exist or not
     *
     * @param nteger  $user_id
     * @return boolean
     */
    function check_seller_product_exist( $user_id ) {
        $query = get_posts( "post_type=product&author=$user_id&post_status=any" );
        dokan_dps_log ( 'Product exist check: As the package has expired of user #' . $user_id . ' we are checking if he has any product' );
        if ( $query ) {
            return true;
        }

        return false;
    }

    /**
     * Upadate Product Status
     *
     * @param integer $user_id
     */
    function update_product_status( $user_id ) {
        global $wpdb;

        $status = dokan_get_option( 'product_status_after_end', 'dokan_product_subscription', 'draft' );
       dokan_dps_log ( 'Product status check: As the package has expired of user #' . $user_id . ', we are changing his existing product status to ' . $status );
        $wpdb->query( "UPDATE $wpdb->posts SET post_status = '$status' WHERE post_author = '$user_id' AND post_type = 'product' AND post_status='publish'" );
    }

    /**
     * Process order for specipic package
     *
     * @param integer $order_id
     * @param string  $old_status
     * @param string  $new_status
     */
    function process_order_pack_product( $order_id, $old_status, $new_status ) {

        $customer_id = get_post_meta( $order_id, '_customer_user', true );

        if ( $new_status == 'completed' ) {
            $order = new WC_Order( $order_id );

            $product_items = $order->get_items();

            $product = reset( $product_items );

            if ( self::is_subscription_product( $product['product_id'] ) ) {

                if ( !self::has_used_free_pack( $customer_id, $product['product_id'] ) ) {
                    $this->add_used_free_pack( $customer_id, $product['product_id'] );
                }

                if ( get_post_meta( $product['product_id'], '_enable_recurring_payment', true ) == 'yes' ) {
                    return;
                }

                $pack_validity = get_post_meta( $product['product_id'], '_pack_validity', true );
                update_user_meta( $customer_id, 'product_package_id', $product['product_id'] );
                update_user_meta( $customer_id, 'product_order_id', $order_id );
                update_user_meta( $customer_id, 'product_no_with_pack', get_post_meta( $product['product_id'], '_no_of_product', true ) );
                update_user_meta( $customer_id, 'product_pack_startdate', date( 'Y-m-d H:i:s' ) );
                update_user_meta( $customer_id, 'product_pack_enddate', date( 'Y-m-d H:i:s', strtotime( "+$pack_validity days" ) ) );
                update_user_meta( $customer_id, 'can_post_product', '1' );
                update_user_meta( $customer_id, '_customer_recurring_subscription', '' );
            }
        }
    }

    /**
     * Redirect after add product into cart
     *
     * @param string  $url url
     */
    public static function add_to_cart_redirect( $url ) {

        $product_id = isset( $_REQUEST['add-to-cart'] ) ?  (int) $_REQUEST['add-to-cart'] : 0;

        if ( !$product_id ) {
            return $url;
        }

        // If product is of the subscription type
        if ( self::is_subscription_product( $product_id ) ) {
            $url = WC()->cart->get_checkout_url();
        }

        return $url;
    }


    /**
     * When a subscription is added to the cart, remove other products/subscriptions to
     * work with PayPal Standard, which only accept one subscription per checkout.
     */
    public static function maybe_empty_cart( $valid, $product_id, $quantity ) {

        if ( self::is_subscription_product( $product_id ) ) {

            WC()->cart->empty_cart();

        } elseif ( self::cart_contains_subscription() ) {

            self::remove_subscriptions_from_cart();

            wc_add_notice( __( 'A subscription has been removed from your cart. Due to payment gateway restrictions, products and subscriptions can not be purchased at the same time.', 'dps' ) );

            // Redirect to cart page to remove subscription & notify shopper
            // add_filter( 'add_to_cart_fragments', __CLASS__ . '::redirect_ajax_add_to_cart' );
        }
        return $valid;
    }

    /**
     * Removes all subscription products from the shopping cart.
     */
    public static function remove_subscriptions_from_cart() {

        foreach ( WC()->cart->cart_contents as $cart_item_key => $cart_item ) {
            if ( self::is_subscription_product( $cart_item['product_id'] ) ) {
                WC()->cart->set_quantity( $cart_item_key, 0 );
            }
        }
    }

    /**
     * Check is product is subscription or not
     *
     * @param integer $product_id
     * @return boolean
     */
    public static function is_subscription_product( $product_id ) {

        $product = get_product( $product_id );

        if ( $product && $product->product_type == 'product_pack' ) {
            return true;
        }

        return false;
    }


    /**
     * Checks the cart to see if it contains a subscription product.
     */
    public static function cart_contains_subscription() {
        global $woocommerce;

        $contains_subscription = false;

        if ( self::cart_contains_subscription_renewal( 'child' ) ) {

            $contains_subscription = false;

        } else if ( !empty( WC()->cart->cart_contents ) ) {
            foreach ( WC()->cart->cart_contents as $cart_item ) {
                if ( self::is_subscription_product( $cart_item['product_id'] ) ) {
                    $contains_subscription = true;
                    break;
                }
            }
        }

        return $contains_subscription;
    }

    /**
     * Checks the cart to see if it contains a subscription product renewal.
     *
     * Returns the cart_item containing the product renewal, else false.
     */
    public static function cart_contains_subscription_renewal( $role = '' ) {

        $contains_renewal = false;

        if ( !empty( WC()->cart->cart_contents ) ) {
            foreach ( WC()->cart->cart_contents as $cart_item ) {
                if ( isset( $cart_item['subscription_renewal'] ) && ( empty( $role ) || $role === $cart_item['subscription_renewal']['role'] ) ) {
                    $contains_renewal = $cart_item;
                    break;
                }
            }
        }

        return $contains_renewal;
    }


    /**
     * Check package validity for seller
     *
     * @param integer $product_id
     * @return boolean
     */
    function has_pack_validity_seller( $product_id ) {

        $date = date( 'Y-m-d', strtotime( current_time( 'mysql' ) ) );
        $validation_date = date( 'Y-m-d', strtotime( get_user_meta( get_current_user_id(), 'product_pack_enddate', true ) ) );

        if ( ( $date < $validation_date ) && ( get_user_meta( get_current_user_id(), 'product_package_id', true ) == $product_id ) ) {
            return true;
        }

        return false;
    }

    /**
     * Check package renew for seller
     *
     * @param integer $product_id
     * @return boolean
     */
    function pack_renew_seller( $product_id ) {

        $date = date( 'Y-m-d', strtotime( current_time( 'mysql' ) ) );
        $validation_date = date( 'Y-m-d', strtotime( get_user_meta( get_current_user_id(), 'product_pack_enddate', true ) ) );

        $datetime1 = new DateTime( $date );
        $datetime2 = new DateTime( $validation_date );

        $interval = $datetime1->diff( $datetime2 );

        $interval = $interval->format( '%r%d' );

        if ( (int) $interval <= 3 && (int) $interval >= 0 && ( get_user_meta( get_current_user_id(), 'product_package_id', true ) == $product_id ) ) {
            return true;
        }

        return false;
    }


    /**
     * Alert before 2 days end of subscription
     *
     * @return boolean
     */
    function alert_before_two_days( $user_id ) {

        $alert_days = dokan_get_option( 'no_of_days_before_mail', 'dokan_product_subscription' );

        if ( $alert_days == 0 ) {
            $alert_days = 2;
        }

        $date = new DateTime( date( 'Y-m-d h:i:s', strtotime( current_time( 'mysql', 1 ) . '+' . $alert_days . ' days' ) ) );
        $prv_two_date = $date->format( 'Y-m-d H:i:s' );

        // return $prv_two_date;

        if ( $prv_two_date == get_user_meta( $user_id, 'product_pack_enddate', true ) ) {
            return true;
        }

        return false;
    }


    /**
     * End Pack Validity for update can_post_product flag
     *
     * @return boolean
     */
    function end_of_pack_validity( $user_id ) {

        $date = date( 'Y-m-d', strtotime( current_time( 'mysql' ) ) );
        $validation_date = date( 'Y-m-d', strtotime( get_user_meta( $user_id, 'product_pack_enddate', true ) ) );

        if ( $date > $validation_date ) {

            dokan_dps_log ( 'Subscription validity check ( ' . $date . ' ): checking subscription pack validity of user #' . $user_id . '. This users subscription pack will expire on ' . $validation_date );

            return true;
        }

        return false;
    }


    /**
     * Determine if the user has used a free pack before
     *
     * @param int     $user_id
     * @param int     $pack_id
     * @return boolean
     */
    public static function has_used_free_pack( $user_id, $pack_id ) {

        $has_used = get_user_meta( $user_id, 'dps_fp_used', true );

        if ( $has_used == '' ) {
            return false;
        }

        if ( is_array( $has_used ) && isset( $has_used[$pack_id] ) ) {
            return true;
        }

        return false;
    }


    /**
     * Add a free used pack to the user account
     *
     * @param int     $user_id
     * @param int     $pack_id
     */
    public function add_used_free_pack( $user_id, $pack_id ) {

        $has_used = get_user_meta( $user_id, 'dps_fp_used', true );
        $has_used = is_array( $has_used ) ? $has_used : array();

        $has_used[$pack_id] = $pack_id;
        update_user_meta( $user_id, 'dps_fp_used', $has_used );
    }

    /**
     * Delete Subscription pack
     *
     * @param integer $customer_id (customer user id)
     */
    public static function delete_subscription_pack( $customer_id, $order_id ) {

        if ( $order_id != get_user_meta( $customer_id, 'product_order_id', true ) ) {
            return;
        }

        delete_user_meta( $customer_id, 'product_package_id' );
        delete_user_meta( $customer_id, 'product_order_id' );
        delete_user_meta( $customer_id, 'product_no_with_pack' );
        delete_user_meta( $customer_id, 'product_pack_startdate' );
        delete_user_meta( $customer_id, 'product_pack_enddate' );
        delete_user_meta( $customer_id, 'can_post_product' );
        delete_user_meta( $customer_id, '_customer_recurring_subscription' );
    }

    /**
     * Log some infor using this function
     *
     * @param text    $message
     *
     */
    public static function dokan_log( $message ) {
        $message = sprintf( "[%s] %s\n", date( 'd.m.Y h:i:s' ), $message );
        error_log( $message, 3, WP_PLUGIN_DIR . '/debug.log' );
    }

    /**
     * Tell WC that we don't need any processing
     *
     * @param  bool $needs_processing
     * @param  array $product
     * @return bool
     */
    function order_needs_processing( $needs_processing, $product ) {

        if ( $product->product_type == 'product_pack' ) {
            $needs_processing = false;
        }

        return $needs_processing;
    }

    /**
     * Handle subscription cancel request from the user
     *
     * @return void
     */
    function user_subscription_cancel() {
        if ( isset( $_POST['dps_cancel_subscription'] ) ) {

            if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'dps-sub-cancel' ) ) {
                wp_die( __( 'Nonce failure', 'dps' ) );
            }

            $user_id  = get_current_user_id();
            $order_id = get_user_meta( $user_id, 'product_order_id', true );


            if ( self::is_dokan_plugin() ) {
                $page_url = dokan_get_navigation_url( 'subscription' );
            } else {
                $page_url = get_permalink( dokan_get_option( 'subscription_pack', 'dokan_product_subscription' ) );
            }

            if ( $order_id && get_user_meta( $user_id, '_customer_recurring_subscription', true ) == 'active' ) {
                dokan_dps_log( 'Subscription cancel check: User #' . $user_id . ' has canceled his Subscription of order #' . $order_id );
                DPS_PayPal_Standard_Subscriptions::cancel_subscription_with_paypal( $order_id, $user_id );
                wp_redirect( add_query_arg( array( 'msg' => 'dps_sub_cancelled' ), $page_url ) );
                exit();
            } else {
                dokan_dps_log( 'Subscription cancel check: User #' . $user_id . ' has canceled his Subscription of order #' . $order_id );
                Dokan_Product_Subscription::delete_subscription_pack( $user_id, $order_id );
                wp_redirect( add_query_arg( array( 'msg' => 'dps_sub_cancelled' ), $page_url ) );
                exit();
            }
        }
    }

} // Dokan_Product_Subscription

// Ativation and Deactivation hook
register_activation_hook( __FILE__, array( 'Dokan_Product_Subscription', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Dokan_Product_Subscription' , 'deactivate' ) );

/**
 * Loaded after load all plugins
 *
 * @return void
 */
function dps_load_plugin() {

    if ( !class_exists( 'WeDevs_Dokan' ) ) {
        return;
    }

    if ( !class_exists( 'WooCommerce' ) ) {
        return;
    }

    require_once dirname( __FILE__ ). '/includes/classes/class-dps-product-pack.php';

    $dps = Dokan_Product_Subscription::init();
}

add_action( 'plugins_loaded', 'dps_load_plugin', 99 );
