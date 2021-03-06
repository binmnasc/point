<?php

/**
 * Dokan Store Support Widget
 *
 * @since 1.3
 *
 * @package dokan
 */
class Dokan_Store_Support_Widget extends WP_Widget {
    
    private $text_domain = 'dokan-store-support';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        $widget_ops = array( 'classname' => 'dokan-store-support-widget', 'description' => __( 'Show Dokan Store Support button', $this->text_domain ) );
        parent::__construct( 'dokan-store-support-widget', __( 'Dokan: Store Support Area', $this->text_domain ), $widget_ops );
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     *
     * @return void Echoes it's output
     */
    function widget( $args, $instance ) {

        if ( ! dokan_is_store_page() ) {
            return;
        }

        extract( $args, EXTR_SKIP );

        $title     = apply_filters( 'dokan_store_support_widget_title', $instance['title'] );
        $desc      = $instance['description'];
        
        echo $before_widget;
        
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }  
        if ( ! empty( $desc ) ) {
            echo '<p class="store-support-widget-desc">' . $desc . '</p>';
        } 
     
        $this->generate_support_button_on_widget();

        echo $after_widget;
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     *
     * @return array The validated and (if necessary) amended settings
     */
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $updated_instance = $new_instance;
        return $updated_instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     *
     * @return void Echoes it's output
     */
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array(
            'title'       => __( 'Store Support' , $this->text_domain ),
            'description' => '',           
        ) );

        $title = $instance['title'];
        $description = $instance['description'];
        
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', $this->text_domain ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description: ', $this->text_domain ); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo esc_attr( $description ); ?></textarea>
        </p>
        
        <?php
    }
    
    
    /**
     * prints Get support button on store page
     *
     * @since 1.3
     * @param int store_id
     */
    function generate_support_button_on_widget() {
        
        $store_user   = get_userdata( get_query_var( 'author' ) );
        $store_id     = $store_user->ID;
        
        if ( is_user_logged_in() ) {
            $user_logged_in = 'user_logged';
        } else {
            $user_logged_in = 'user_logged_out';
        }
        $disabled = '';
//        if ( get_current_user_id() == $store_id ) {
//            $disabled = 'disabled';
//        }

        $store_info = dokan_get_store_info( $store_id );

        if ( isset( $store_info['show_support_btn'] ) && $store_info['show_support_btn'] == 'no' ) {
            return;
        }

        $support_text = isset( $store_info['support_btn_name'] ) && !empty( $store_info['support_btn_name'] ) ? $store_info['support_btn_name'] : __( 'Get Support', $this->text_domain );
        ?>
        <button data-store_id="<?php echo $store_id; ?>" class="dokan-store-support-btn dokan-btn dokan-btn-theme dokan-btn-sm <?php echo $user_logged_in ?>">
            <?php echo esc_html( $support_text ); ?>
        </button>
        <?php
    }
}

add_action( 'widgets_init', create_function( '', "register_widget( 'Dokan_Store_Support_Widget' );" ) );