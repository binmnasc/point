<?php

class RSOfflineOnlineRewards{
    
    public function __construct(){
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_settings'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_offline_online_rewards', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
                
        add_action('woocommerce_update_options_rewardsystem_offline_online_rewards', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
     /*
     * Function to Define Name of the Tab
     */
    
    public static function reward_system_tab_settings($setting_tab){
        $setting_tab['rewardsystem_offline_online_rewards'] = __('Gift Voucher','rewardsystem');
        return  $setting_tab;
    }
    
    /*
     * Function for Admin Settings
     * 
     */
    
    public static function reward_system_admin_fields(){
        
        return apply_filters('woocommerce_rewardsystem_offline_online_rewards_settings',array(
            array(
                'name' => __('Gift Voucher Reward Settings', 'rewardsystem'),                
                'type' => 'title',                          
                'id' => '_rs_offline_to_online_rewards_settings'
            ),  
             array(
                'name' => __(''),
                'type' => 'title',
                'desc' => '<h3>[sumo_current_balance] - Use this Shortcode for displaying the User Current Reward Points <br><br></h3>'
               
            ),
            array(
                'type' => 'rs_offline_online_rewards_voucher_settings',
            ),   
            
            array(
                'type' => 'rs_offline_online_rewards_display_table_settings',
            ),
            array(
                'name' => __('Gift Voucher Message settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_gift_voucher_message_settings',
            ),
            array(
                'name' => __('Error Message when Redeem Voucher Field is empty', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when Redeem Voucher Button is clicked without entering the voucher code ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_voucher_redeem_empty_error',
                'css' => 'min-width:550px;',
                'std' => 'Please Enter your Voucher Code',
                'type' => 'text',
                'newids' => 'rs_voucher_redeem_empty_error',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Success Message when Gift Voucher is Redeemed', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when the Gift Voucher has been Successfully Redeemed', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_voucher_redeem_success_message',
                'css' => 'min-width:550px;',
                'std' => '[giftvoucherpoints] Reward points has been added to your Account',
                'type' => 'text',
                'newids' => 'rs_voucher_redeem_success_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Messgae when Voucher has Expired', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when the Gift Voucher has been Successfully Redeemed', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_voucher_code_expired_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Voucher has been Expired',
                'type' => 'text',
                'newids' => 'rs_voucher_code_expired_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Invalid Voucher Code Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when a Invalid Voucher is used for Redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_invalid_voucher_code_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Sorry, Voucher not found in list',
                'type' => 'text',
                'newids' => 'rs_invalid_voucher_code_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Login link for Guest Label', 'rewardsystem'),
                'desc' => __('Please Enter Login link for Guest Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_redeem_voucher_login_link_label',
                'css' => 'min-width:200px;',
                'std' => 'Login',
                'type' => 'text',
                'newids' => 'rs_redeem_voucher_login_link_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message Displayed for Guest', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed for Guest when Gift Voucher Shortcode is used', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_voucher_redeem_guest_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Please [rs_login_link] to View this Page',
                'type' => 'text',
                'newids' => 'rs_voucher_redeem_guest_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Voucher Already Used Error Mesage', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed when User tries to Redeem a Voucher code that has already been Used', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_voucher_code_used_error_message',
                'css' => 'min-width:200px;',
                'std' => 'Voucher has been used',
                'type' => 'text',
                'newids' => 'rs_voucher_code_used_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message Displayed for Banned Users', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed when a Banned User tries to Redeem the Gift Voucher', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_banned_user_redeem_voucher_error',
                'css' => 'min-width:400px;',
                'std' => 'You have Earned 0 Points',
                'type' => 'textarea',
                'newids' => 'rs_banned_user_redeem_voucher_error',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_gift_voucher_message_settings'),
            array(
                'name' => __('Voucher Code Form Customization', 'rewardsystem'),                
                'type' => 'title',                          
                'id' => '_rs_offline_to_online_form_customize_settings'
            ),
            array(
                'name' => __('Voucher Code Field Caption', 'rewardsystem'),
                'desc' => __(' ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_code_field_caption',
                'css' => 'min-width:350px;',
                'std' => 'Enter your Voucher Code below to Claim',
                'type' => 'text',
                'newids' => 'rs_reward_code_field_caption',                
            ),
            array(
                'name' => __('Placeholder for Voucher Code Field', 'rewardsystem'),
                'desc' => __(' ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_code_field_placeholder',
                'css' => 'min-width:350px;',
                'std' => 'Voucher Code',
                'type' => 'text',
                'newids' => 'rs_reward_code_field_placeholder',                
            ),
             
            array(
                'name' => __('Submit Button Field Caption', 'rewardsystem'),
                'desc' => __(' ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_code_submit_field_caption',
                'css' => 'min-width:350px;',
                'std' => 'Submit',
                'type' => 'text',
                'newids' => 'rs_reward_code_submit_field_caption',                
            ),            
            array('type'=>'sectionend', 'id'=>'_rs_offline_to_online_form_customize_settings'),            
            array(
                'name' => __('Current Balance message Custmization', 'rewardsystem'),                
                'type' => 'title',                          
                'id' => '_rs_current_balance_shortcode_customization'
            ),
            array(
                'name' => __('Current Balance Caption', 'rewardsystem'),
                'desc' => __(' ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_current_available_balance_caption',
                'css' => 'min-width:350px;',
                'std' => 'Current Balance:',
                'type' => 'text',
                'newids' => 'rs_current_available_balance_caption',                
            ),
            array('type'=>'sectionend', 'id'=>'_rs_current_balance_shortcode_customization'),
            array('type'=>'sectionend', 'id'=>'_rs_offline_to_online_rewards_settings'),
        ));
        
    }
    
    /*
     * Register  the Admin Field Settings
     * 
     */
    
    public static function reward_system_register_admin_settings(){
        
        woocommerce_admin_fields(RSOfflineOnlineRewards::reward_system_admin_fields());
    }
    
    /*
     * Update Settings for Offline Online Rewards tab    
     * 
     */
    
    public static function reward_system_update_settings(){
        woocommerce_update_options(RSOfflineOnlineRewards::reward_system_admin_fields());
    }
    
    
     /**
     * Initialize the Default Settings by looping this function
     */
    
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSOfflineOnlineRewards::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }   
    
}

new RSOfflineOnlineRewards();

