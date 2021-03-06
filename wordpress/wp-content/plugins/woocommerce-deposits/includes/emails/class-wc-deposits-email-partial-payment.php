<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if (!class_exists('WC_Deposits_Email_Partial_Payment')):

/**
 * @brief Partial Payment Email
 *
 * An email sent to the admin when a new order is partially paid.
 *
 */
class WC_Deposits_Email_Partial_Payment extends WC_Email {

  /**
   * Constructor
   */
  function __construct() {

    $this->id         = 'partial_payment';
    $this->title      = __('Partial Payment', 'woocommerce-deposits');
    $this->description    = __('Partial payment emails are sent when an order is partially paid.', 'woocommerce-deposits');

    $this->heading      = __('Order partially paid', 'woocommerce-deposits');
    $this->subject        = __('[{site_title}] Order partially paid ({order_number}) - {order_date}', 'woocommerce-deposits');

    $this->template_html  = 'emails/admin-order-partially-paid.php';
    $this->template_plain   = 'emails/plain/admin-order-partially-paid.php';

    // Triggers for this email
    add_action('woocommerce_order_status_on-hold_to_partially-paid_notification', array($this, 'trigger'));
    add_action('woocommerce_order_status_pending_to_partially-paid_notification', array($this, 'trigger'));
    add_action('woocommerce_order_status_failed_to_partially-paid_notification', array($this, 'trigger'));
    add_action('woocommerce_order_status_draft_to_partially-paid_notification', array($this, 'trigger'));

    // Call parent constructor
    parent::__construct();

    $this->template_base = WC_DEPOSITS_TEMPLATE_PATH;

    // Other settings
    $this->recipient = $this->get_option('recipient');

    if (! $this->recipient)
      $this->recipient = get_option('admin_email');
  }

  /**
   * trigger function.
   *
   * @access public
   * @return void
   */
  function trigger($order_id) {

    if ($order_id) {
      $this->object     = wc_get_order($order_id);

      $this->find['order-date']      = '{order_date}';
      $this->find['order-number']    = '{order_number}';

      $this->replace['order-date']   = date_i18n(wc_date_format(), strtotime($this->object->order_date));
      $this->replace['order-number'] = $this->object->get_order_number();
    }

    if (! $this->is_enabled() || ! $this->get_recipient()) {
      return;
    }

    $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
  }

  /**
   * get_content_html function.
   *
   * @access public
   * @return string
   */
  function get_content_html() {
    ob_start();
    wc_get_template($this->template_html, array(
      'order'     => $this->object,
      'email_heading' => $this->get_heading(),
      'sent_to_admin' => true,
      'plain_text'    => false
    ), '', $this->template_base );
    return ob_get_clean();
  }

  /**
   * get_content_plain function.
   *
   * @access public
   * @return string
   */
  function get_content_plain() {
    ob_start();
    wc_get_template($this->template_plain, array(
      'order'     => $this->object,
      'email_heading' => $this->get_heading(),
      'sent_to_admin' => true,
      'plain_text'    => true
    ), '', $this->template_base );
    return ob_get_clean();
  }

  /**
    * Initialise Settings Form Fields
    *
    * @access public
    * @return void
    */
  function init_form_fields() {
    $this->form_fields = array(
      'enabled' => array(
        'title'     => __('Enable/Disable', 'woocommerce-deposits'),
        'type'      => 'checkbox',
        'label'     => __('Enable this email notification', 'woocommerce-deposits'),
        'default'     => 'yes'
      ),
      'recipient' => array(
        'title'     => __('Recipient(s)', 'woocommerce-deposits'),
        'type'      => 'text',
        'description'   => sprintf(__('Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', 'woocommerce-deposits'), esc_attr(get_option('admin_email'))),
        'placeholder'   => '',
        'default'     => ''
      ),
      'subject' => array(
        'title'     => __('Subject', 'woocommerce-deposits'),
        'type'      => 'text',
        'description'   => sprintf(__('This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'woocommerce-deposits'), $this->subject),
        'placeholder'   => '',
        'default'     => ''
      ),
      'heading' => array(
        'title'     => __('Email Heading', 'woocommerce-deposits'),
        'type'      => 'text',
        'description'   => sprintf(__('This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'woocommerce-deposits'), $this->heading),
        'placeholder'   => '',
        'default'     => ''
      ),
      'email_type' => array(
        'title'     => __('Email type', 'woocommerce-deposits'),
        'type'      => 'select',
        'description'   => __('Choose which format of email to send.', 'woocommerce-deposits'),
        'default'     => 'html',
        'class'     => 'email_type',
        'options'   => array(
          'plain'     => __('Plain text', 'woocommerce-deposits'),
          'html'      => __('HTML', 'woocommerce-deposits'),
          'multipart'   => __('Multipart', 'woocommerce-deposits'),
        )
      )
    );
  }
}

endif;

return new WC_Deposits_Email_Partial_Payment();
