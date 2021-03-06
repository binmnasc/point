<?php

if (!defined('ABSPATH'))
    die("Can't load this file directly");

class WPIE_SCHEDULED {

    function __construct() {

        add_action('wp_ajax_wpie_save_product_scheduled', array(&$this, 'wpie_save_product_scheduled'));

        add_action('wp_ajax_wpie_save_product_cat_scheduled', array(&$this, 'wpie_save_product_cat_scheduled'));

        add_action('wp_ajax_wpie_save_coupon_scheduled', array(&$this, 'wpie_save_coupon_scheduled'));

        add_action('wp_ajax_wpie_save_order_scheduled', array(&$this, 'wpie_save_order_scheduled'));

        add_action('wp_ajax_wpie_save_coupon_import_scheduled', array(&$this, 'wpie_save_coupon_import_scheduled'));

        add_action('wp_ajax_wpie_save_product_import_scheduled', array(&$this, 'wpie_save_product_import_scheduled'));

        add_action('wp_ajax_wpie_save_product_cat_import_scheduled', array(&$this, 'wpie_save_product_cat_import_scheduled'));

        add_action('wp_ajax_wpie_save_order_import_scheduled', array(&$this, 'wpie_save_order_import_scheduled'));

        add_action('wp_ajax_wpie_save_user_import_scheduled', array(&$this, 'wpie_save_user_import_scheduled'));

        add_action('wp_ajax_wpie_save_user_scheduled', array(&$this, 'wpie_save_user_scheduled'));

        add_action('wpie_cron_scheduled_product_export', array(&$this, 'wpie_cron_scheduled_product_export_data'));

        add_action('wpie_cron_scheduled_user_export', array(&$this, 'wpie_cron_scheduled_user_export_data'));

        add_action('wp_ajax_wpie_delete_product_scheduled_cron', array(&$this, 'wpie_delete_product_scheduled_cron'));

        add_action('wp_ajax_wpie_delete_coupon_export_scheduled_cron', array(&$this, 'wpie_delete_coupon_export_scheduled_cron'));

        add_action('wp_ajax_wpie_delete_product_export_scheduled_cron', array(&$this, 'wpie_delete_product_export_scheduled_cron'));

        add_action('wp_ajax_wpie_delete_product_cat_export_scheduled_cron', array(&$this, 'wpie_delete_product_cat_export_scheduled_cron'));

        add_action('wp_ajax_wpie_delete_order_export_scheduled_cron', array(&$this, 'wpie_delete_order_export_scheduled_cron'));

        add_action('wp_ajax_wpie_delete_user_export_scheduled_cron', array(&$this, 'wpie_delete_user_export_scheduled_cron'));

        add_action('wp_ajax_wpie_delete_coupon_import_scheduled_cron', array(&$this, 'wpie_delete_coupon_import_scheduled_cron'));

        add_action('wp_ajax_wpie_delete_product_import_scheduled_cron', array(&$this, 'wpie_delete_product_import_scheduled_cron'));

        add_action('wp_ajax_wpie_delete_product_cat_import_scheduled_cron', array(&$this, 'wpie_delete_product_cat_import_scheduled_cron'));

        add_action('wp_ajax_wpie_delete_order_import_scheduled_cron', array(&$this, 'wpie_delete_order_import_scheduled_cron'));

        add_action('wp_ajax_wpie_delete_user_import_scheduled_cron', array(&$this, 'wpie_delete_user_import_scheduled_cron'));

        add_action('wpie_cron_scheduled_product_cat_export', array(&$this, 'wpie_cron_scheduled_product_cat_export_data'));

        add_action('wp_ajax_wpie_delete_product_cat_scheduled_cron', array(&$this, 'wpie_delete_product_cat_scheduled_cron'));

        add_action('wpie_cron_scheduled_coupon_export', array(&$this, 'wpie_cron_scheduled_coupon_export_data'));

        add_action('wpie_cron_scheduled_order_export', array(&$this, 'wpie_cron_scheduled_order_export_data'));

        add_action('wpie_cron_scheduled_coupon_import', array(&$this, 'wpie_cron_scheduled_coupon_import_data'));

        add_action('wpie_cron_scheduled_product_cat_import', array(&$this, 'wpie_cron_scheduled_product_cat_import_data'));

        add_action('wpie_cron_scheduled_order_import', array(&$this, 'wpie_cron_scheduled_order_import_data'));

        add_action('wpie_cron_scheduled_user_import', array(&$this, 'wpie_cron_scheduled_user_import_data'));

        add_action('wpie_cron_scheduled_product_import', array(&$this, 'wpie_cron_scheduled_product_import_data'));

        add_filter('cron_schedules', array(&$this, 'add_cron_schedules_option'));
    }

    function add_cron_schedules_option($schedules) {

        $schedules['2_minutes'] = array(
            'interval' => 120,
            'display' => __('2 minutes', WPIE_TEXTDOMAIN),
        );
        $schedules['30_minutes'] = array(
            'interval' => 1800,
            'display' => __('30 minutes', WPIE_TEXTDOMAIN),
        );
        $schedules['weekly'] = array(
            'interval' => 604800,
            'display' => __('Once Weekly', WPIE_TEXTDOMAIN)
        );
        return $schedules;
    }

    function get_product_scheduled_data() {
        $product_scheduled = @maybe_unserialize(get_option('wpie_product_scheduled_data'));

        return $product_scheduled;
    }

    function get_product_cat_scheduled_data() {
        $product_cat_scheduled = @maybe_unserialize(get_option('wpie_product_cat_scheduled_data'));

        return $product_cat_scheduled;
    }

    function get_order_scheduled_data() {
        $order_scheduled = @maybe_unserialize(get_option('wpie_order_scheduled_data'));

        return $order_scheduled;
    }

    function get_coupon_scheduled_data() {
        $coupon_scheduled = @maybe_unserialize(get_option('wpie_coupon_scheduled_data'));

        return $coupon_scheduled;
    }

    function get_coupon_import_scheduled_data() {
        $coupon_scheduled = @maybe_unserialize(get_option('wpie_coupon_import_scheduled_data'));

        return $coupon_scheduled;
    }

    function get_product_import_scheduled_data() {
        $product_scheduled = @maybe_unserialize(get_option('wpie_product_import_scheduled_data'));

        return $product_scheduled;
    }

    function get_product_cat_import_scheduled_data() {
        $product_cat_scheduled = @maybe_unserialize(get_option('wpie_product_cat_import_scheduled_data'));

        return $product_cat_scheduled;
    }

    function get_order_import_scheduled_data() {
        $order_scheduled = @maybe_unserialize(get_option('wpie_order_import_scheduled_data'));

        return $order_scheduled;
    }

    function get_user_scheduled_data() {
        $user_scheduled = @maybe_unserialize(get_option('wpie_user_scheduled_data'));

        return $user_scheduled;
    }

    function get_user_import_scheduled_data() {
        $user_scheduled = @maybe_unserialize(get_option('wpie_user_import_scheduled_data'));

        return $user_scheduled;
    }

    function wpie_save_product_scheduled() {

        $general_options_data = $_POST;

        $wpie_export_interval = isset($_POST['wpie_export_interval']) ? $_POST['wpie_export_interval'] : "";

        $this_export_time = isset($_POST['wpie_scheduled_export_time']) ? strtotime($_POST['wpie_scheduled_export_time']) : "";

        if ($this_export_time == "") {
            $this_export_time = time();
        }

        $return_value = array();

        if ($wpie_export_interval != "") {

            $scheduled_id = uniqid();

            $scheduled_data = $this->get_product_scheduled_data();

            $scheduled_data[$scheduled_id] = $general_options_data;

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_product_scheduled_data', $scheduled_new_data);

            wp_schedule_event($this_export_time, $wpie_export_interval, 'wpie_cron_scheduled_product_export', array($scheduled_id));

            $return_value['message'] = 'success';

            $return_value['message_content'] = __('Scheduled Saved Successfully', WPIE_TEXTDOMAIN);

            if (isset($_POST['wpie_product_scheduled_send_email']) && $_POST['wpie_product_scheduled_send_email'] == 1) {
                $send_email = __('Yes', WPIE_TEXTDOMAIN);
            } else {
                $send_email = __('No', WPIE_TEXTDOMAIN);
            }

            $new_scheduled_data = array(
                $scheduled_id,
                $get_schedules_list[$_POST['wpie_export_interval']]['display'],
                $send_email,
                $_POST['wpie_scheduled_export_email_recipients'],
                date_i18n(get_option('date_format') . ' ' . get_option('time_format'), wp_next_scheduled('wpie_cron_scheduled_product_export', array($scheduled_id))),
                '<div class="wpie-delete-cron-data wpie-delete-product-cron" cron_id=' . $scheduled_id . '>DELETE</div>'
            );

            $return_value['data'] = $new_scheduled_data;
        } else {
            $return_value['message'] = 'error';
        }

        echo json_encode($return_value);

        die();
    }

    function wpie_save_product_cat_scheduled() {

        $general_options_data = $_POST;

        $wpie_export_interval = isset($_POST['wpie_export_interval']) ? $_POST['wpie_export_interval'] : "";

        $this_export_time = isset($_POST['wpie_scheduled_export_time']) ? strtotime($_POST['wpie_scheduled_export_time']) : "";

        if ($this_export_time == "") {
            $this_export_time = time();
        }

        $return_value = array();

        if ($wpie_export_interval != "") {

            $scheduled_id = uniqid();

            $scheduled_data = $this->get_product_cat_scheduled_data();

            $scheduled_data[$scheduled_id] = $general_options_data;

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_product_cat_scheduled_data', $scheduled_new_data);

            wp_schedule_event($this_export_time, $wpie_export_interval, 'wpie_cron_scheduled_product_cat_export', array($scheduled_id));

            $return_value['message'] = 'success';

            $return_value['message_content'] = __('Scheduled Saved Successfully', WPIE_TEXTDOMAIN);
        } else {
            $return_value['message'] = 'error';
        }

        echo json_encode($return_value);

        die();
    }

    function wpie_save_coupon_scheduled() {

        $general_options_data = $_POST;

        $wpie_export_interval = isset($_POST['wpie_export_interval']) ? $_POST['wpie_export_interval'] : "";

        $this_export_time = isset($_POST['wpie_scheduled_export_time']) ? strtotime($_POST['wpie_scheduled_export_time']) : "";

        if ($this_export_time == "") {
            $this_export_time = time();
        }
        $return_value = array();

        if ($wpie_export_interval != "") {

            $scheduled_id = uniqid();

            $scheduled_data = $this->get_coupon_scheduled_data();

            $scheduled_data[$scheduled_id] = $general_options_data;

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            $get_schedules_list = wp_get_schedules();

            update_option('wpie_coupon_scheduled_data', $scheduled_new_data);

            wp_schedule_event($this_export_time, $wpie_export_interval, 'wpie_cron_scheduled_coupon_export', array($scheduled_id));

            $return_value['message'] = 'success';

            $return_value['message_content'] = __('Scheduled Saved Successfully', WPIE_TEXTDOMAIN);

            if (isset($_POST['wpie_product_scheduled_send_email']) && $_POST['wpie_product_scheduled_send_email'] == 1) {
                $send_email = __('Yes', WPIE_TEXTDOMAIN);
            } else {
                $send_email = __('No', WPIE_TEXTDOMAIN);
            }

            $new_scheduled_data = array(
                $scheduled_id,
                $get_schedules_list[$_POST['wpie_export_interval']]['display'],
                $send_email,
                $_POST['wpie_scheduled_export_email_recipients'],
                date_i18n(get_option('date_format') . ' ' . get_option('time_format'), wp_next_scheduled('wpie_cron_scheduled_coupon_export', array($scheduled_id))),
                '<div class="wpie-delete-cron-data wpie-delete-coupon-cron" cron_id=' . $scheduled_id . '>DELETE</div>'
            );

            $return_value['data'] = $new_scheduled_data;
        } else {
            $return_value['message'] = 'error';
        }

        echo json_encode($return_value);

        die();
    }

    function wpie_save_order_scheduled() {

        $general_options_data = $_POST;

        $wpie_export_interval = isset($_POST['wpie_export_interval']) ? $_POST['wpie_export_interval'] : "";

        $this_export_time = isset($_POST['wpie_scheduled_export_time']) ? strtotime($_POST['wpie_scheduled_export_time']) : "";

        if ($this_export_time == "") {
            $this_export_time = time();
        }
        $return_value = array();

        if ($wpie_export_interval != "") {

            $scheduled_id = uniqid();

            $scheduled_data = $this->get_order_scheduled_data();

            $scheduled_data[$scheduled_id] = $general_options_data;

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            $get_schedules_list = wp_get_schedules();

            update_option('wpie_order_scheduled_data', $scheduled_new_data);

            wp_schedule_event($this_export_time, $wpie_export_interval, 'wpie_cron_scheduled_order_export', array($scheduled_id));

            $return_value['message'] = 'success';

            $return_value['message_content'] = __('Scheduled Saved Successfully', WPIE_TEXTDOMAIN);

            if (isset($_POST['wpie_product_scheduled_send_email']) && $_POST['wpie_product_scheduled_send_email'] == 1) {
                $send_email = __('Yes', WPIE_TEXTDOMAIN);
            } else {
                $send_email = __('No', WPIE_TEXTDOMAIN);
            }

            $new_scheduled_data = array(
                $scheduled_id,
                $get_schedules_list[$_POST['wpie_export_interval']]['display'],
                $send_email,
                $_POST['wpie_scheduled_export_email_recipients'],
                date_i18n(get_option('date_format') . ' ' . get_option('time_format'), wp_next_scheduled('wpie_cron_scheduled_order_export', array($scheduled_id))),
                '<div class="wpie-delete-cron-data wpie-delete-order-cron" cron_id=' . $scheduled_id . '>DELETE</div>'
            );

            $return_value['data'] = $new_scheduled_data;
        } else {
            $return_value['message'] = 'error';
        }

        echo json_encode($return_value);

        die();
    }

    function wpie_save_coupon_import_scheduled() {

        $general_options_data = $_POST;

        $wpie_import_interval = isset($_POST['wpie_import_interval']) ? $_POST['wpie_import_interval'] : "";

        $this_import_time = isset($_POST['wpie_scheduled_import_time']) ? strtotime($_POST['wpie_scheduled_import_time']) : "";

        if ($this_import_time == "") {
            $this_import_time = time();
        }
        $return_value = array();

        if ($wpie_import_interval != "") {

            $scheduled_id = uniqid();

            $scheduled_data = $this->get_coupon_import_scheduled_data();

            $scheduled_data[$scheduled_id] = $general_options_data;

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            $get_schedules_list = wp_get_schedules();

            update_option('wpie_coupon_import_scheduled_data', $scheduled_new_data);

            wp_schedule_event($this_import_time, $wpie_import_interval, 'wpie_cron_scheduled_coupon_import', array($scheduled_id));

            $return_value['message'] = 'success';

            $return_value['message_content'] = __('Scheduled Saved Successfully', WPIE_TEXTDOMAIN);

            if (isset($_POST['wpie_product_scheduled_send_email']) && $_POST['wpie_product_scheduled_send_email'] == 1) {
                $send_email = __('Yes', WPIE_TEXTDOMAIN);
            } else {
                $send_email = __('No', WPIE_TEXTDOMAIN);
            }

            $new_scheduled_data = array(
                $scheduled_id,
                $get_schedules_list[$_POST['wpie_import_interval']]['display'],
                $send_email,
                $_POST['wpie_scheduled_export_email_recipients'],
                date_i18n(get_option('date_format') . ' ' . get_option('time_format'), wp_next_scheduled('wpie_cron_scheduled_coupon_import', array($scheduled_id))),
                '<div class="wpie-delete-cron-data wpie-delete-coupon-import-cron" cron_id=' . $scheduled_id . '>DELETE</div>'
            );

            $return_value['data'] = $new_scheduled_data;
        } else {
            $return_value['message'] = 'error';
        }

        echo json_encode($return_value);

        die();
    }

    function wpie_save_product_cat_import_scheduled() {

        $general_options_data = $_POST;

        $wpie_import_interval = isset($_POST['wpie_import_interval']) ? $_POST['wpie_import_interval'] : "";

        $this_import_time = isset($_POST['wpie_scheduled_import_time']) ? strtotime($_POST['wpie_scheduled_import_time']) : "";

        if ($this_import_time == "") {
            $this_import_time = time();
        }
        $return_value = array();

        if ($wpie_import_interval != "") {

            $scheduled_id = uniqid();

            $scheduled_data = $this->get_product_cat_import_scheduled_data();

            $scheduled_data[$scheduled_id] = $general_options_data;

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            $get_schedules_list = wp_get_schedules();

            update_option('wpie_product_cat_import_scheduled_data', $scheduled_new_data);

            wp_schedule_event($this_import_time, $wpie_import_interval, 'wpie_cron_scheduled_product_cat_import', array($scheduled_id));

            $return_value['message'] = 'success';

            $return_value['message_content'] = __('Scheduled Saved Successfully', WPIE_TEXTDOMAIN);

            if (isset($_POST['wpie_product_scheduled_send_email']) && $_POST['wpie_product_scheduled_send_email'] == 1) {
                $send_email = __('Yes', WPIE_TEXTDOMAIN);
            } else {
                $send_email = __('No', WPIE_TEXTDOMAIN);
            }

            $new_scheduled_data = array(
                $scheduled_id,
                $get_schedules_list[$_POST['wpie_import_interval']]['display'],
                $send_email,
                $_POST['wpie_scheduled_export_email_recipients'],
                date_i18n(get_option('date_format') . ' ' . get_option('time_format'), wp_next_scheduled('wpie_cron_scheduled_product_cat_import', array($scheduled_id))),
                '<div class="wpie-delete-cron-data wpie-delete-product-cat-import-cron" cron_id=' . $scheduled_id . '>DELETE</div>'
            );

            $return_value['data'] = $new_scheduled_data;
        } else {
            $return_value['message'] = 'error';
        }

        echo json_encode($return_value);

        die();
    }

    function wpie_save_order_import_scheduled() {

        $general_options_data = $_POST;

        $wpie_import_interval = isset($_POST['wpie_import_interval']) ? $_POST['wpie_import_interval'] : "";

        $this_import_time = isset($_POST['wpie_scheduled_import_time']) ? strtotime($_POST['wpie_scheduled_import_time']) : "";

        if ($this_import_time == "") {
            $this_import_time = time();
        }
        $return_value = array();

        if ($wpie_import_interval != "") {

            $scheduled_id = uniqid();

            $scheduled_data = $this->get_order_import_scheduled_data();

            $scheduled_data[$scheduled_id] = $general_options_data;

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            $get_schedules_list = wp_get_schedules();

            update_option('wpie_order_import_scheduled_data', $scheduled_new_data);

            wp_schedule_event($this_import_time, $wpie_import_interval, 'wpie_cron_scheduled_order_import', array($scheduled_id));

            $return_value['message'] = 'success';

            $return_value['message_content'] = __('Scheduled Saved Successfully', WPIE_TEXTDOMAIN);

            if (isset($_POST['wpie_product_scheduled_send_email']) && $_POST['wpie_product_scheduled_send_email'] == 1) {
                $send_email = __('Yes', WPIE_TEXTDOMAIN);
            } else {
                $send_email = __('No', WPIE_TEXTDOMAIN);
            }

            $new_scheduled_data = array(
                $scheduled_id,
                $get_schedules_list[$_POST['wpie_import_interval']]['display'],
                $send_email,
                $_POST['wpie_scheduled_export_email_recipients'],
                date_i18n(get_option('date_format') . ' ' . get_option('time_format'), wp_next_scheduled('wpie_cron_scheduled_order_import', array($scheduled_id))),
                '<div class="wpie-delete-cron-data wpie-delete-order-import-cron" cron_id=' . $scheduled_id . '>DELETE</div>'
            );

            $return_value['data'] = $new_scheduled_data;
        } else {
            $return_value['message'] = 'error';
        }

        echo json_encode($return_value);

        die();
    }

    function wpie_save_user_import_scheduled() {

        $general_options_data = $_POST;

        $wpie_import_interval = isset($_POST['wpie_import_interval']) ? $_POST['wpie_import_interval'] : "";

        $this_import_time = isset($_POST['wpie_scheduled_import_time']) ? strtotime($_POST['wpie_scheduled_import_time']) : "";

        if ($this_import_time == "") {
            $this_import_time = time();
        }
        $return_value = array();

        if ($wpie_import_interval != "") {

            $scheduled_id = uniqid();

            $scheduled_data = $this->get_user_import_scheduled_data();

            $scheduled_data[$scheduled_id] = $general_options_data;

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            $get_schedules_list = wp_get_schedules();

            update_option('wpie_user_import_scheduled_data', $scheduled_new_data);

            wp_schedule_event($this_import_time, $wpie_import_interval, 'wpie_cron_scheduled_user_import', array($scheduled_id));

            $return_value['message'] = 'success';

            $return_value['message_content'] = __('Scheduled Saved Successfully', WPIE_TEXTDOMAIN);

            if (isset($_POST['wpie_product_scheduled_send_email']) && $_POST['wpie_product_scheduled_send_email'] == 1) {
                $send_email = __('Yes', WPIE_TEXTDOMAIN);
            } else {
                $send_email = __('No', WPIE_TEXTDOMAIN);
            }

            $new_scheduled_data = array(
                $scheduled_id,
                $get_schedules_list[$_POST['wpie_import_interval']]['display'],
                $send_email,
                $_POST['wpie_scheduled_export_email_recipients'],
                date_i18n(get_option('date_format') . ' ' . get_option('time_format'), wp_next_scheduled('wpie_cron_scheduled_user_import', array($scheduled_id))),
                '<div class="wpie-delete-cron-data wpie-delete-user-import-cron" cron_id=' . $scheduled_id . '>DELETE</div>'
            );

            $return_value['data'] = $new_scheduled_data;
        } else {
            $return_value['message'] = 'error';
        }

        echo json_encode($return_value);

        die();
    }

    function wpie_save_user_scheduled() {

        $general_options_data = $_POST;

        $wpie_export_interval = isset($_POST['wpie_export_interval']) ? $_POST['wpie_export_interval'] : "";

        $this_export_time = isset($_POST['wpie_scheduled_export_time']) ? strtotime($_POST['wpie_scheduled_export_time']) : "";

        if ($this_export_time == "") {
            $this_export_time = time();
        }


        $return_value = array();

        if ($wpie_export_interval != "") {

            $scheduled_id = uniqid();

            $get_schedules_list = wp_get_schedules();

            $scheduled_data = $this->get_user_scheduled_data();

            $scheduled_data[$scheduled_id] = $general_options_data;

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_user_scheduled_data', $scheduled_new_data);

            wp_schedule_event($this_export_time, $wpie_export_interval, 'wpie_cron_scheduled_user_export', array($scheduled_id));

            $return_value['message'] = 'success';

            $return_value['message_content'] = __('Scheduled Saved Successfully', WPIE_TEXTDOMAIN);

            if (isset($_POST['wpie_product_scheduled_send_email']) && $_POST['wpie_product_scheduled_send_email'] == 1) {
                $send_email = __('Yes', WPIE_TEXTDOMAIN);
            } else {
                $send_email = __('No', WPIE_TEXTDOMAIN);
            }

            $new_scheduled_data = array(
                $scheduled_id,
                $get_schedules_list[$_POST['wpie_export_interval']]['display'],
                $send_email,
                $_POST['wpie_scheduled_export_email_recipients'],
                date_i18n(get_option('date_format') . ' ' . get_option('time_format'), wp_next_scheduled('wpie_cron_scheduled_user_export', array($scheduled_id))),
                '<div class="wpie-delete-cron-data wpie-delete-user-cron" cron_id=' . $scheduled_id . '>DELETE</div>'
            );

            $return_value['data'] = $new_scheduled_data;
        } else {
            $return_value['message'] = 'error';
        }

        echo json_encode($return_value);

        die();
    }

    function wpie_cron_scheduled_product_export_data($wpie_cron_data) {

        global $wpie_product, $wpdb;

        $scheduled_data = $this->get_product_scheduled_data();

        $wpie_data = $scheduled_data[$wpie_cron_data];

        $filename = 'product_' . date('Y_m_d_H_i_s') . '.csv';

        $fh = @fopen(WPIE_UPLOAD_DIR . '/' . $filename, 'w+');

        $wpie_data = @maybe_unserialize($wpie_data);

        $product_export_data = $wpie_product->get_product_export_data($wpie_data);

        $wpie_export_separator = (isset($wpie_data['wpie_export_separator']) && $wpie_data['wpie_export_separator'] != "") ? $wpie_data['wpie_export_separator'] : ",";

        foreach ($product_export_data as $new_data) {
            @fputcsv($fh, $new_data, $wpie_export_separator);
        }

        @fclose($fh);

        $new_values = array();

        $new_values['export_log_file_type'] = 'export';
        $new_values['export_log_file_name'] = $filename;
        $new_values['export_log_data'] = 'Product';
        $new_values['create_date'] = current_time('mysql');

        $res = $wpdb->insert($wpdb->prefix . "wpie_export_log", $new_values);


        if (isset($wpie_data['wpie_product_scheduled_send_email']) && $wpie_data['wpie_product_scheduled_send_email'] == 1 && isset($wpie_data['wpie_scheduled_export_email_recipients']) && $wpie_data['wpie_scheduled_export_email_recipients'] != "") {

            $attachments = array(WPIE_UPLOAD_DIR . '/' . $filename);

            $recipient = explode(',', $wpie_data['wpie_scheduled_export_email_recipients']);

            $subject = $wpie_data['wpie_scheduled_export_email_subject'];

            $message = $wpie_data['wpie_scheduled_export_email_content'];

            $admin_email = get_option('admin_email');

            $headers = array();

            $headers[] = 'From: "' . get_option('blogname') . '" <' . $admin_email . '>';

            $headers[] = 'Reply-To: ' . $admin_email;

            $headers[] = 'Content-Type:text/html; charset="' . get_option('blog_charset') . '"';

            $this->wpie_send_mail($recipient, $subject, $message, $header, $attachments);
        }
    }

    function wpie_send_mail($recipient, $subject, $message, $header, $attachments) {
        if (!wp_mail($recipient, $subject, $message, $header, $attachments)) {

            $semi_rand = md5(time());

            $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

            $headers = 'From: ' . get_option('blogname') . ' <' . $admin_email . '>' . '\n';

            $date = date("Y-m-d H:i:s");

            $headers .= "\n" . "Date:$date " . "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

            $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";

            $message .= "--{$mime_boundary}\n";

            if (count($attachments) > 0) {

                foreach ($attachments as $filename) {

                    $attachmnt = @chunk_split(base64_encode(file_get_contents($filename)));

                    $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"" . basename($filename) . "\"\n" . "Content-Disposition: attachment;\n" . " filename=\"" . basename($filename) . "\"\n" . "Content-Transfer-Encoding: base64\n\n" . $attachmnt . "\n\n";

                    $message .= "--{$mime_boundary}\n";
                }
            }

            @mail($recipient, $subject, $message, $headers);
        }
    }

    function wpie_delete_product_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_product_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_product_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_product_export', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_delete_coupon_export_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_coupon_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_coupon_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_coupon_export', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_delete_product_export_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_product_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_product_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_product_export', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_delete_product_cat_export_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_product_cat_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_product_cat_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_product_cat_export', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_delete_order_export_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_order_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_order_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_order_export', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_delete_user_export_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_user_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_user_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_user_export', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_delete_coupon_import_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_coupon_import_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_coupon_import_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_coupon_import', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_delete_product_import_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_product_import_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_product_import_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_product_import', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_delete_product_cat_import_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_product_cat_import_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_product_cat_import_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_product_cat_import', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_delete_order_import_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_order_import_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_order_import_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_order_import', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_delete_user_import_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_user_import_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_user_import_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_user_import', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_cron_scheduled_product_cat_export_data($wpie_cron_data) {
        global $wpie_product, $wpdb, $wpie_product_category;

        $scheduled_data = $this->get_product_cat_scheduled_data();

        $wpie_data = $scheduled_data[$wpie_cron_data];

        $filename = 'product_category_' . date('Y_m_d_H_i_s') . '.csv';

        $fh = @fopen(WPIE_UPLOAD_DIR . '/' . $filename, 'w+');

        $wpie_data = @maybe_unserialize($wpie_data);

        $product_cat_export_data = $wpie_product_category->get_product_cat_export_data($wpie_data);

        $wpie_export_separator = (isset($wpie_data['wpie_export_separator']) && $wpie_data['wpie_export_separator'] != "") ? $wpie_data['wpie_export_separator'] : ",";

        foreach ($product_cat_export_data as $new_data) {
            @fputcsv($fh, $new_data, $wpie_export_separator);
        }

        @fclose($fh);

        $new_values = array();

        $new_values['export_log_file_type'] = 'export';
        $new_values['export_log_file_name'] = $filename;
        $new_values['export_log_data'] = 'Product Category';
        $new_values['create_date'] = current_time('mysql');

        $res = $wpdb->insert($wpdb->prefix . "wpie_export_log", $new_values);


        if (isset($wpie_data['wpie_product_scheduled_send_email']) && $wpie_data['wpie_product_scheduled_send_email'] == 1 && isset($wpie_data['wpie_scheduled_export_email_recipients']) && $wpie_data['wpie_scheduled_export_email_recipients'] != "") {

            $attachments = array(WPIE_UPLOAD_DIR . '/' . $filename);

            $recipient = explode(',', $wpie_data['wpie_scheduled_export_email_recipients']);

            $subject = $wpie_data['wpie_scheduled_export_email_subject'];

            $message = $wpie_data['wpie_scheduled_export_email_content'];

            $admin_email = get_option('admin_email');

            $headers = array();

            $headers[] = 'From: "' . get_option('blogname') . '" <' . $admin_email . '>';

            $headers[] = 'Reply-To: ' . $admin_email;

            $headers[] = 'Content-Type:text/html; charset="' . get_option('blog_charset') . '"';

            $this->wpie_send_mail($recipient, $subject, $message, $header, $attachments);
        }
    }

    function wpie_delete_product_cat_scheduled_cron() {

        $cron_id = isset($_POST['cron_id']) ? $_POST['cron_id'] : "";

        if ($cron_id != "") {
            $scheduled_data = $this->get_product_cat_scheduled_data();

            unset($scheduled_data[$cron_id]);

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            update_option('wpie_product_cat_scheduled_data', $scheduled_new_data);

            wp_clear_scheduled_hook('wpie_cron_scheduled_product_cat_export', array($cron_id));
        }

        $return_value = array();

        $return_value['message'] = 'success';

        $return_value['message_content'] = __('Scheduled Deleted Successfully', WPIE_TEXTDOMAIN);

        echo json_encode($return_value);

        die();
    }

    function wpie_cron_scheduled_coupon_export_data($wpie_cron_data) {
        global $wpie_product, $wpdb, $wpie_coupon;

        $scheduled_data = $this->get_coupon_scheduled_data();

        $wpie_data = $scheduled_data[$wpie_cron_data];

        $filename = 'coupon_' . date('Y_m_d_H_i_s') . '.csv';

        $fh = @fopen(WPIE_UPLOAD_DIR . '/' . $filename, 'w+');

        $wpie_data = @maybe_unserialize($wpie_data);

        $coupon_export_data = $wpie_coupon->get_coupon_export_data($wpie_data);

        $wpie_export_separator = (isset($wpie_data['wpie_export_separator']) && $wpie_data['wpie_export_separator'] != "") ? $wpie_data['wpie_export_separator'] : ",";

        foreach ($coupon_export_data as $new_data) {
            @fputcsv($fh, $new_data, $wpie_export_separator);
        }

        @fclose($fh);

        $new_values = array();

        $new_values['export_log_file_type'] = 'export';
        $new_values['export_log_file_name'] = $filename;
        $new_values['export_log_data'] = 'Coupon';
        $new_values['create_date'] = current_time('mysql');

        $res = $wpdb->insert($wpdb->prefix . "wpie_export_log", $new_values);


        if (isset($wpie_data['wpie_product_scheduled_send_email']) && $wpie_data['wpie_product_scheduled_send_email'] == 1 && isset($wpie_data['wpie_scheduled_export_email_recipients']) && $wpie_data['wpie_scheduled_export_email_recipients'] != "") {

            $attachments = array(WPIE_UPLOAD_DIR . '/' . $filename);

            $recipient = explode(',', $wpie_data['wpie_scheduled_export_email_recipients']);

            $subject = $wpie_data['wpie_scheduled_export_email_subject'];

            $message = $wpie_data['wpie_scheduled_export_email_content'];

            $admin_email = get_option('admin_email');

            $headers = array();

            $headers[] = 'From: "' . get_option('blogname') . '" <' . $admin_email . '>';

            $headers[] = 'Reply-To: ' . $admin_email;

            $headers[] = 'Content-Type:text/html; charset="' . get_option('blog_charset') . '"';

            $this->wpie_send_mail($recipient, $subject, $message, $header, $attachments);
        }
    }

    function wpie_cron_scheduled_order_export_data($wpie_cron_data) {

        global $wpdb, $wpie_order;

        $scheduled_data = $this->get_order_scheduled_data();

        $wpie_data = $scheduled_data[$wpie_cron_data];

        $wpie_data = @maybe_unserialize($wpie_data);

        $order_export_data = $wpie_order->get_order_export_data($wpie_data);

        if (isset($wpie_data['wpie_product_scheduled_send_email']) && $wpie_data['wpie_product_scheduled_send_email'] == 1 && isset($wpie_data['wpie_scheduled_export_email_recipients']) && $wpie_data['wpie_scheduled_export_email_recipients'] != "") {

            $attachments = array(WPIE_UPLOAD_DIR . '/' . $filename);

            $recipient = explode(',', $wpie_data['wpie_scheduled_export_email_recipients']);

            $subject = $wpie_data['wpie_scheduled_export_email_subject'];

            $message = $wpie_data['wpie_scheduled_export_email_content'];

            $admin_email = get_option('admin_email');

            $headers = array();

            $headers[] = 'From: "' . get_option('blogname') . '" <' . $admin_email . '>';

            $headers[] = 'Reply-To: ' . $admin_email;

            $headers[] = 'Content-Type:text/html; charset="' . get_option('blog_charset') . '"';

            $this->wpie_send_mail($recipient, $subject, $message, $header, $attachments);
        }
    }

    function wpie_cron_scheduled_coupon_import_data($wpie_cron_data) {
        global $wpie_product, $wpdb, $wpie_coupon;

        $scheduled_data = $this->get_coupon_import_scheduled_data();

        $wpie_data = $scheduled_data[$wpie_cron_data];

        $wpie_data = @maybe_unserialize($wpie_data);

        $coupon_export_data = $wpie_coupon->wpie_set_coupon_import_data($wpie_data);


        if (isset($wpie_data['wpie_product_scheduled_send_email']) && $wpie_data['wpie_product_scheduled_send_email'] == 1 && isset($wpie_data['wpie_scheduled_export_email_recipients']) && $wpie_data['wpie_scheduled_export_email_recipients'] != "") {

            $attachments = array(WPIE_UPLOAD_DIR . '/' . $filename);

            $recipient = explode(',', $wpie_data['wpie_scheduled_export_email_recipients']);

            $subject = $wpie_data['wpie_scheduled_export_email_subject'];

            $message = $wpie_data['wpie_scheduled_export_email_content'];

            $admin_email = get_option('admin_email');

            $headers = array();

            $headers[] = 'From: "' . get_option('blogname') . '" <' . $admin_email . '>';

            $headers[] = 'Reply-To: ' . $admin_email;

            $headers[] = 'Content-Type:text/html; charset="' . get_option('blog_charset') . '"';

            $this->wpie_send_mail($recipient, $subject, $message, $header, $attachments);
        }
    }

    function wpie_cron_scheduled_product_import_data($wpie_cron_data) {
        global $wpie_product, $wpdb;

        $scheduled_data = $this->get_product_import_scheduled_data();

        $wpie_data = $scheduled_data[$wpie_cron_data];

        $wpie_data = @maybe_unserialize($wpie_data);

        $product_export_data = $wpie_product->wpie_set_product_import_data($wpie_data);

        if (isset($wpie_data['wpie_product_scheduled_send_email']) && $wpie_data['wpie_product_scheduled_send_email'] == 1 && isset($wpie_data['wpie_scheduled_export_email_recipients']) && $wpie_data['wpie_scheduled_export_email_recipients'] != "") {

            $attachments = array(WPIE_UPLOAD_DIR . '/' . $filename);

            $recipient = explode(',', $wpie_data['wpie_scheduled_export_email_recipients']);

            $subject = $wpie_data['wpie_scheduled_export_email_subject'];

            $message = $wpie_data['wpie_scheduled_export_email_content'];

            $admin_email = get_option('admin_email');

            $headers = array();

            $headers[] = 'From: "' . get_option('blogname') . '" <' . $admin_email . '>';

            $headers[] = 'Reply-To: ' . $admin_email;

            $headers[] = 'Content-Type:text/html; charset="' . get_option('blog_charset') . '"';

            $this->wpie_send_mail($recipient, $subject, $message, $header, $attachments);
        }
    }

    function wpie_cron_scheduled_product_cat_import_data($wpie_cron_data) {
        global $wpie_product, $wpdb, $wpie_product_category;

        $scheduled_data = $this->get_product_cat_import_scheduled_data();

        $wpie_data = $scheduled_data[$wpie_cron_data];

        $wpie_data = @maybe_unserialize($wpie_data);

        $product_cat_export_data = $wpie_product_category->wpie_set_product_cat_import_data($wpie_data);

        if (isset($wpie_data['wpie_product_scheduled_send_email']) && $wpie_data['wpie_product_scheduled_send_email'] == 1 && isset($wpie_data['wpie_scheduled_export_email_recipients']) && $wpie_data['wpie_scheduled_export_email_recipients'] != "") {

            $attachments = array(WPIE_UPLOAD_DIR . '/' . $filename);

            $recipient = explode(',', $wpie_data['wpie_scheduled_export_email_recipients']);

            $subject = $wpie_data['wpie_scheduled_export_email_subject'];

            $message = $wpie_data['wpie_scheduled_export_email_content'];

            $admin_email = get_option('admin_email');

            $headers = array();

            $headers[] = 'From: "' . get_option('blogname') . '" <' . $admin_email . '>';

            $headers[] = 'Reply-To: ' . $admin_email;

            $headers[] = 'Content-Type:text/html; charset="' . get_option('blog_charset') . '"';

            $this->wpie_send_mail($recipient, $subject, $message, $header, $attachments);
        }
    }

    function wpie_cron_scheduled_user_import_data($wpie_cron_data) {
        global $wpie_product, $wpdb, $wpie_user;

        $scheduled_data = $this->get_user_import_scheduled_data();

        $wpie_data = $scheduled_data[$wpie_cron_data];

        $wpie_data = @maybe_unserialize($wpie_data);

        $user_export_data = $wpie_user->wpie_set_user_import_data($wpie_data);

        if (isset($wpie_data['wpie_product_scheduled_send_email']) && $wpie_data['wpie_product_scheduled_send_email'] == 1 && isset($wpie_data['wpie_scheduled_export_email_recipients']) && $wpie_data['wpie_scheduled_export_email_recipients'] != "") {

            $attachments = array(WPIE_UPLOAD_DIR . '/' . $filename);

            $recipient = explode(',', $wpie_data['wpie_scheduled_export_email_recipients']);

            $subject = $wpie_data['wpie_scheduled_export_email_subject'];

            $message = $wpie_data['wpie_scheduled_export_email_content'];

            $admin_email = get_option('admin_email');

            $headers = array();

            $headers[] = 'From: "' . get_option('blogname') . '" <' . $admin_email . '>';

            $headers[] = 'Reply-To: ' . $admin_email;

            $headers[] = 'Content-Type:text/html; charset="' . get_option('blog_charset') . '"';

            $this->wpie_send_mail($recipient, $subject, $message, $header, $attachments);
        }
    }

    function wpie_cron_scheduled_user_export_data($wpie_cron_data) {

        global $wpie_user, $wpdb;

        $scheduled_data = $this->get_user_scheduled_data();

        $wpie_data = $scheduled_data[$wpie_cron_data];

        $filename = 'user_' . date('Y_m_d_H_i_s') . '.csv';

        $fh = @fopen(WPIE_UPLOAD_DIR . '/' . $filename, 'w+');

        $wpie_data = @maybe_unserialize($wpie_data);

        $user_export_data = $wpie_user->get_user_csv_data($wpie_data);

        $wpie_export_separator = (isset($wpie_data['wpie_export_separator']) && $wpie_data['wpie_export_separator'] != "") ? $wpie_data['wpie_export_separator'] : ",";

        foreach ($user_export_data as $new_data) {
            @fputcsv($fh, $new_data, $wpie_export_separator);
        }

        @fclose($fh);

        $new_values = array();

        $new_values['export_log_file_type'] = 'export';
        $new_values['export_log_file_name'] = $filename;
        $new_values['export_log_data'] = 'User';
        $new_values['create_date'] = current_time('mysql');

        $res = $wpdb->insert($wpdb->prefix . "wpie_export_log", $new_values);

        if (isset($wpie_data['wpie_user_scheduled_send_email']) && $wpie_data['wpie_user_scheduled_send_email'] == 1 && isset($wpie_data['wpie_scheduled_export_email_recipients']) && $wpie_data['wpie_scheduled_export_email_recipients'] != "") {

            $attachments = array(WPIE_UPLOAD_DIR . '/' . $filename);

            $recipient = explode(',', $wpie_data['wpie_scheduled_export_email_recipients']);

            $subject = $wpie_data['wpie_scheduled_export_email_subject'];

            $message = $wpie_data['wpie_scheduled_export_email_content'];

            $admin_email = get_option('admin_email');

            $headers = array();

            $headers[] = 'From: "' . get_option('blogname') . '" <' . $admin_email . '>';

            $headers[] = 'Reply-To: ' . $admin_email;

            $headers[] = 'Content-Type:text/html; charset="' . get_option('blog_charset') . '"';

            $this->wpie_send_mail($recipient, $subject, $message, $header, $attachments);
        }
    }

    function wpie_cron_scheduled_order_import_data($wpie_cron_data) {
        global $wpie_product, $wpdb, $wpie_order;

        $scheduled_data = $this->get_order_import_scheduled_data();

        $wpie_data = $scheduled_data[$wpie_cron_data];

        $wpie_data = @maybe_unserialize($wpie_data);

        $order_export_data = $wpie_order->wpie_set_order_import_data($wpie_data);

        if (isset($wpie_data['wpie_product_scheduled_send_email']) && $wpie_data['wpie_product_scheduled_send_email'] == 1 && isset($wpie_data['wpie_scheduled_export_email_recipients']) && $wpie_data['wpie_scheduled_export_email_recipients'] != "") {

            $attachments = array(WPIE_UPLOAD_DIR . '/' . $filename);

            $recipient = explode(',', $wpie_data['wpie_scheduled_export_email_recipients']);

            $subject = $wpie_data['wpie_scheduled_export_email_subject'];

            $message = $wpie_data['wpie_scheduled_export_email_content'];

            $admin_email = get_option('admin_email');

            $headers = array();

            $headers[] = 'From: "' . get_option('blogname') . '" <' . $admin_email . '>';

            $headers[] = 'Reply-To: ' . $admin_email;

            $headers[] = 'Content-Type:text/html; charset="' . get_option('blog_charset') . '"';

            $this->wpie_send_mail($recipient, $subject, $message, $header, $attachments);
        }
    }

    function wpie_save_product_import_scheduled() {

        $general_options_data = $_POST;

        $wpie_import_interval = isset($_POST['wpie_import_interval']) ? $_POST['wpie_import_interval'] : "";

        $this_import_time = isset($_POST['wpie_scheduled_import_time']) ? strtotime($_POST['wpie_scheduled_import_time']) : "";

        if ($this_import_time == "") {
            $this_import_time = time();
        }
        $return_value = array();

        if ($wpie_import_interval != "") {

            $scheduled_id = uniqid();

            $scheduled_data = $this->get_product_import_scheduled_data();

            $scheduled_data[$scheduled_id] = $general_options_data;

            $scheduled_new_data = @maybe_serialize($scheduled_data);

            $get_schedules_list = wp_get_schedules();

            update_option('wpie_product_import_scheduled_data', $scheduled_new_data);

            wp_schedule_event($this_import_time, $wpie_import_interval, 'wpie_cron_scheduled_product_import', array($scheduled_id));

            $return_value['message'] = 'success';

            $return_value['message_content'] = __('Scheduled Saved Successfully', WPIE_TEXTDOMAIN);

            if (isset($_POST['wpie_product_scheduled_send_email']) && $_POST['wpie_product_scheduled_send_email'] == 1) {
                $send_email = __('Yes', WPIE_TEXTDOMAIN);
            } else {
                $send_email = __('No', WPIE_TEXTDOMAIN);
            }

            $new_scheduled_data = array(
                $scheduled_id,
                $get_schedules_list[$_POST['wpie_import_interval']]['display'],
                $send_email,
                $_POST['wpie_scheduled_export_email_recipients'],
                date_i18n(get_option('date_format') . ' ' . get_option('time_format'), wp_next_scheduled('wpie_cron_scheduled_product_import', array($scheduled_id))),
                '<div class="wpie-delete-cron-data wpie-delete-product-import-cron" cron_id=' . $scheduled_id . '>DELETE</div>'
            );

            $return_value['data'] = $new_scheduled_data;
        } else {
            $return_value['message'] = 'error';
        }

        echo json_encode($return_value);

        die();
    }

    function wpie_delete_all_cron() {

        //delete order scheduled
        $order_scheduled_data = $this->get_order_scheduled_data();

        if (!empty($order_scheduled_data)) {
            foreach ($order_scheduled_data as $cron_id => $value) {
                wp_clear_scheduled_hook('wpie_cron_scheduled_order_export', array($cron_id));
            }
        }

        //delete product scheduled
        $product_scheduled_data = $this->get_product_scheduled_data();

        if (!empty($product_scheduled_data)) {
            foreach ($product_scheduled_data as $cron_id => $value) {
                wp_clear_scheduled_hook('wpie_cron_scheduled_product_export', array($cron_id));
            }
        }

        //delete user scheduled
        $user_scheduled_data = $this->get_user_scheduled_data();

        if (!empty($user_scheduled_data)) {
            foreach ($user_scheduled_data as $cron_id => $value) {
                wp_clear_scheduled_hook('wpie_cron_scheduled_user_export', array($cron_id));
            }
        }

        //delete product category scheduled
        $product_cat_scheduled_data = $this->get_product_cat_scheduled_data();

        if (!empty($product_cat_scheduled_data)) {
            foreach ($product_cat_scheduled_data as $cron_id => $value) {
                wp_clear_scheduled_hook('wpie_cron_scheduled_product_cat_export', array($cron_id));
            }
        }

        //delete coupon scheduled
        $coupon_scheduled_data = $this->get_coupon_scheduled_data();

        if (!empty($coupon_scheduled_data)) {
            foreach ($coupon_scheduled_data as $cron_id => $value) {
                wp_clear_scheduled_hook('wpie_cron_scheduled_coupon_export', array($cron_id));
            }
        }


        //delete coupon import scheduled
        $coupon_import_scheduled_data = $this->get_coupon_import_scheduled_data();

        if (!empty($coupon_import_scheduled_data)) {
            foreach ($coupon_import_scheduled_data as $cron_id => $value) {
                wp_clear_scheduled_hook('wpie_cron_scheduled_coupon_import', array($cron_id));
            }
        }

        //delete product import scheduled
        $product_import_scheduled_data = $this->get_product_import_scheduled_data();

        if (!empty($product_import_scheduled_data)) {
            foreach ($product_import_scheduled_data as $cron_id => $value) {
                wp_clear_scheduled_hook('wpie_cron_scheduled_product_import', array($cron_id));
            }
        }

        //delete product cat import scheduled
        $product_cat_import_scheduled_data = $this->get_product_cat_import_scheduled_data();

        if (!empty($product_cat_import_scheduled_data)) {
            foreach ($product_cat_import_scheduled_data as $cron_id => $value) {
                wp_clear_scheduled_hook('wpie_cron_scheduled_product_cat_import', array($cron_id));
            }
        }

        //delete order import scheduled
        $order_import_scheduled_data = $this->get_order_import_scheduled_data();

        if (!empty($order_import_scheduled_data)) {
            foreach ($order_import_scheduled_data as $cron_id => $value) {
                wp_clear_scheduled_hook('wpie_cron_scheduled_order_import', array($cron_id));
            }
        }

        //delete user import scheduled
        $user_import_scheduled_data = $this->get_user_import_scheduled_data();

        if (!empty($user_import_scheduled_data)) {
            foreach ($user_import_scheduled_data as $cron_id => $value) {
                wp_clear_scheduled_hook('wpie_cron_scheduled_user_import', array($cron_id));
            }
        }
    }

}
