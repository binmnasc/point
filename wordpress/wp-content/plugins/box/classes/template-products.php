<?php

/**
*  Product Functionality for Product Handler
*
*  @since 2.4
*
*  @package dokan
*/
class Dokan_Template_Products {

    public static $errors;
    public static $product_cat;
    public static $post_content;

    /**
     *  Load autometially when class initiate
     *
     *  @since 2.4
     *
     *  @uses actions
     *  @uses filters
     */
    function __construct() {
        add_action( 'dokan_render_product_listing_template', array( $this, 'render_product_listing_template' ), 11 );
        add_action( 'template_redirect', array( $this, 'handle_all_submit' ), 11 );
        add_action( 'template_redirect', array( $this, 'handle_delete_product' ) );
        add_action( 'dokan_render_new_product_template', array( $this, 'render_new_product_template' ), 10 );
        add_action( 'dokan_render_product_edit_template', array( $this, 'load_product_edit_template' ), 11 );
    }

    /**
     * Singleton method
     *
     * @return self
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Dokan_Template_Products();
        }

        return $instance;
    }

    /**
     * Render New Product Template for only free version
     *
     * @since 2.4
     *
     * @param  array $query_vars
     *
     * @return void
     */
    public function render_new_product_template( $query_vars ) {

        if ( isset( $query_vars['new-product'] ) && !WeDevs_Dokan::init()->is_pro() ) {
            dokan_get_template_part( 'products/new-product-single' );
        }
    }

    /**
     * Load Product Edit Template
     *
     * @since 2.4
     *
     * @return void
     */
    public function load_product_edit_template() {
        if ( !WeDevs_Dokan::init()->is_pro() ) {
            dokan_get_template_part( 'products/new-product-single' );
        }
    }

    /**
     * Render Product Listing Template
     *
     * @since 2.4
     *
     * @param  string $action
     *
     * @return void
     */
    public function render_product_listing_template( $action ) {
        dokan_get_template_part( 'products/products-listing');
    }

    /**
     * Handle all the form POST submit
     *
     * @return void
     */
    function handle_all_submit() {

        if ( ! is_user_logged_in() ) {
            return;
        }

        if ( ! dokan_is_user_seller( get_current_user_id() ) ) {
            return;
        }

        $errors = array();
        self::$product_cat  = -1;
        self::$post_content = __( 'Details of your product ...', 'dokan' );

        if ( ! $_POST ) {
            return;
        }


        global $wpdb;

        if ( isset( $_POST['dokan_add_product'] ) && wp_verify_nonce( $_POST['dokan_add_new_product_nonce'], 'dokan_add_new_product' ) ) {

            $post_title              = trim( $_POST['post_title'] );
            $post_content            = trim( $_POST['post_content'] );
            $post_excerpt            = isset( $_POST['post_excerpt'] ) ? trim( $_POST['post_excerpt'] ) : '';
            $_POST['_regular_price'] = isset(  $_POST['_regular_price'] ) ?  $_POST['_regular_price'] : '';
            $price                   = floatval( $_POST['_regular_price'] );
            //$_weight                 = floatval( $_POST['_weight'] );
            $featured_image          = absint( $_POST['feat_image_id'] );
            $sku                     = isset( $_POST['_sku'] ) ? trim( $_POST['_sku'] ) : '';
            $is_lot_discount         = isset( $_POST['_is_lot_discount'] ) ? $_POST['_is_lot_discount'] : 'no';

            if ( empty( $post_title ) ) {
                $errors[] = __( 'Please enter product title', 'dokan' );
            }
            if ( empty( $price ) || $price == 0  ) {
                $errors[] = __( 'Por Favor informe o valor do item', 'dokan' );
            }

            if( dokan_get_option( 'product_category_style', 'dokan_selling', 'single' ) == 'single' ) {
                $product_cat    = intval( $_POST['product_cat'] );
                if ( $product_cat < 0 ) {
                    $errors[] = __( 'Please select a category', 'dokan' );
                }
            } else {

                if( !isset( $_POST['product_cat'] ) && empty( $_POST['product_cat'] ) ) {
                    $errors[] = __( 'Please select AT LEAST ONE category', 'dokan' );
                }else{

                  $categorias = array();
                  global $wpdb;
                  $querystr = "
                      SELECT $wpdb->terms.term_id
                      FROM $wpdb->terms
                      WHERE $wpdb->terms.slug like '%servcliente%'
                  ";

                  $not_cat_id = $wpdb->get_results($querystr);
                  //dump($not_cat_id);

                  $not_cat_array = array();
                  foreach ($not_cat_id as $key => $value) {
                      array_push($not_cat_array,$value->term_id);
                  }

                  //dump($not_cat_array);
                  //exit;
                  $not_cat_array_post = array();
                  foreach ($_POST['product_cat'] as $cat) {
                      if( !in_array($cat, $not_cat_array) ){
                         array_push($not_cat_array_post,$value->term_id);
                      }
                  }

                  //dump($not_cat_array_post);

                  if( empty( $not_cat_array_post ) ) {
                      $errors[] = __( 'Please select AT LEAST ONE category', 'dokan' );
                  }

                }

                //exit;

            }

            $_sku_post_id = $wpdb->get_var( $wpdb->prepare("
                    SELECT $wpdb->posts.ID
                    FROM $wpdb->posts
                    LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
                    WHERE $wpdb->posts.post_type = 'product'
                    AND $wpdb->posts.post_status = 'publish'
                    AND $wpdb->postmeta.meta_key = '_sku' AND $wpdb->postmeta.meta_value = '%s'
                 ", $sku ) );



            if ( isset( $_POST['dokan_product_id'] ) && empty( $_POST['dokan_product_id'] ) ) {

                if ( ! empty( $sku ) && $_sku_post_id ) {
                    $errors[] = __( 'Product SKU must be unique.', 'dokan' );
                }

                self::$errors = apply_filters( 'dokan_can_add_product', $errors );

            } else {
                 if ( ! empty( $sku ) && $_sku_post_id != (int) $_POST['dokan_product_id']  ) {
                    $errors[] = __( 'Product SKU must be unique.', 'dokan' );
                }

                self::$errors = apply_filters( 'dokan_can_edit_product', $errors );
            }

            if ( !self::$errors ) {

                $_POST['dokan_product_id'] = isset( $_POST['dokan_product_id'] ) ? $_POST['dokan_product_id'] : '';

                if( isset( $_POST['dokan_product_id'] ) && empty( $_POST['dokan_product_id'] ) ) {
                    $product_status = dokan_get_new_post_status();
                    $post_data = apply_filters( 'dokan_insert_product_post_data', array(
                        'post_type'    => 'product',
                        'post_status'  => $product_status,
                        'post_title'   => $post_title,
                        'post_content' => $post_content,
                        'post_excerpt' => $post_excerpt,
                    ) );

                    $product_id = wp_insert_post( $post_data );

                } else {
                    $post_id = (int)$_POST['dokan_product_id'];
                    $product_info = apply_filters( 'dokan_update_product_post_data', array(
                        'ID'             => $post_id,
                        'post_title'     => sanitize_text_field( $_POST['post_title'] ),
                        'post_content'   => $_POST['post_content'],
                        'post_excerpt'   => $_POST['post_excerpt'],
                        'post_status'    => isset( $_POST['post_status'] ) ? $_POST['post_status'] : 'pending',
                        'comment_status' => isset( $_POST['_enable_reviews'] ) ? 'open' : 'closed'
                    ) );

                    $product_id = wp_update_post( $product_info );
                }

                if ( $product_id ) {

                    /** set images **/
                    if ( $featured_image ) {
                        set_post_thumbnail( $product_id, $featured_image );
                    }else{

                        delete_post_thumbnail( $product_id);
                    }

                    if( isset( $_POST['product_tag'] ) && !empty( $_POST['product_tag'] ) ) {
                        $tags_ids = array_map( 'intval', (array)$_POST['product_tag'] );
                        wp_set_object_terms( $product_id, $tags_ids, 'product_tag' );
                    }

                    /** set product category * */
                    if( dokan_get_option( 'product_category_style', 'dokan_selling', 'single' ) == 'single' ) {
                        wp_set_object_terms( $product_id, (int) $_POST['product_cat'], 'product_cat' );
                    } else {
                        if( isset( $_POST['product_cat'] ) && !empty( $_POST['product_cat'] ) ) {
                            $cat_ids = array_map( 'intval', (array)$_POST['product_cat'] );
                            wp_set_object_terms( $product_id, $cat_ids, 'product_cat' );
                        }
                    }

                    if ( isset( $_POST['product-type'] ) ) {
                        wp_set_object_terms( $product_id, $_POST['product-type'], 'product_type' );
                    } else {
                        /** Set Product type by default simple */
                        if ( isset( $_POST['_create_variation'] ) && $_POST['_create_variation'] == 'yes' ) {
                            wp_set_object_terms( $product_id, 'variable', 'product_type' );
                        } else {
                            wp_set_object_terms( $product_id, 'simple', 'product_type' );
                        }
                    }

                    update_post_meta( $product_id, '_regular_price', $price );
                    update_post_meta( $product_id, '_sale_price', '' );
                    update_post_meta( $product_id, '_price', $price );
                    update_post_meta( $product_id, '_visibility', 'visible' );
                    //update_post_meta( $product_id, '_weight', $_weight );

                    dokan_new_process_product_meta( $product_id );

                    if( isset( $_POST['dokan_product_id'] ) && !empty( $_POST['dokan_product_id'] ) ) {
                        do_action( 'dokan_product_updated', $product_id );
                    }  else {
                        do_action( 'dokan_new_product_added', $product_id );
                    }

                    if( isset( $_POST['dokan_product_id'] ) && empty( $_POST['dokan_product_id'] ) ) {
                        if ( dokan_get_option( 'product_add_mail', 'dokan_general', 'on' ) == 'on' ) {
                            Dokan_Email::init()->new_product_added( $product_id, $product_status );
                        }
                    }

                    if ( $is_lot_discount == 'yes' ) {
                        $lot_discount_quantity = isset($_POST['_lot_discount_quantity']) ? $_POST['_lot_discount_quantity'] : 0;
                        $lot_discount_amount   = isset($_POST['_lot_discount_amount']) ? $_POST['_lot_discount_amount'] : 0;
                        if ( $lot_discount_quantity == '0' || $lot_discount_amount == '0' ) {
                            update_post_meta( $product_id, '_lot_discount_quantity', $lot_discount_quantity);
                            update_post_meta( $product_id, '_lot_discount_amount', $lot_discount_amount);
                            update_post_meta( $product_id, '_is_lot_discount', 'no');
                        } else {
                            update_post_meta( $product_id, '_lot_discount_quantity', $lot_discount_quantity);
                            update_post_meta( $product_id, '_lot_discount_amount', $lot_discount_amount);
                            update_post_meta( $product_id, '_is_lot_discount', $is_lot_discount);
                        }
                    } else if ( $is_lot_discount == 'no' ) {
                        update_post_meta( $product_id, '_lot_discount_quantity', 0);
                        update_post_meta( $product_id, '_lot_discount_amount', 0);
                        update_post_meta( $product_id, '_is_lot_discount', 'no');
                    }

                    if ( isset( $_POST['product-type'] ) ) {
                        wp_set_object_terms( $product_id, $_POST['product-type'], 'product_type' );
                    }
                    $redirect_url = apply_filters( 'dokan_add_new_product_redirect', dokan_edit_product_url( $product_id ), $product_id );
                    wp_redirect( add_query_arg( array( 'message' => 'success' ), $redirect_url ) );
                    exit;
                }
            }

        }

        if ( isset( $_POST['add_product'] ) && wp_verify_nonce( $_POST['dokan_add_new_product_nonce'], 'dokan_add_new_product' ) ) {
            $post_title     = trim( $_POST['post_title'] );
            $post_content   = trim( $_POST['post_content'] );
            $post_excerpt   = trim( $_POST['post_excerpt'] );
            $price          = floatval( $_POST['price'] );
            $featured_image = absint( $_POST['feat_image_id'] );

            if ( empty( $post_title ) ) {

                $errors[] = __( 'Please enter product title', 'dokan' );
            }

            dump("ITEM 2");
            dump($_POST['product_cat']);
            exit;

            if( dokan_get_option( 'product_category_style', 'dokan_selling', 'single' ) == 'single' ) {
                $product_cat    = intval( $_POST['product_cat'] );
                if ( $product_cat < 0 ) {
                    $errors[] = __( 'Please select a category', 'dokan' );
                }
            } else {
                if( !isset( $_POST['product_cat'] ) && empty( $_POST['product_cat'] ) ) {
                    $errors[] = __( 'Please select AT LEAST ONE category', 'dokan' );
                }
            }

            self::$errors = apply_filters( 'dokan_can_add_product', $errors );

            if ( !self::$errors ) {

                $product_status = dokan_get_new_post_status();
                $post_data = apply_filters( 'dokan_insert_product_post_data', array(
                        'post_type'    => 'product',
                        'post_status'  => $product_status,
                        'post_title'   => $post_title,
                        'post_content' => $post_content,
                        'post_excerpt' => $post_excerpt,
                    ) );

                $product_id = wp_insert_post( $post_data );

                if ( $product_id ) {

                    /** set images **/
                    if ( $featured_image ) {
                        set_post_thumbnail( $product_id, $featured_image );
                    }

                    if( isset( $_POST['product_tag'] ) && !empty( $_POST['product_tag'] ) ) {
                        $tags_ids = array_map( 'intval', (array)$_POST['product_tag'] );
                        wp_set_object_terms( $product_id, $tags_ids, 'product_tag' );
                    }

                    /** set product category * */
                    if( dokan_get_option( 'product_category_style', 'dokan_selling', 'single' ) == 'single' ) {
                        wp_set_object_terms( $product_id, (int) $_POST['product_cat'], 'product_cat' );
                    } else {
                        if( isset( $_POST['product_cat'] ) && !empty( $_POST['product_cat'] ) ) {
                            $cat_ids = array_map( 'intval', (array)$_POST['product_cat'] );
                            wp_set_object_terms( $product_id, $cat_ids, 'product_cat' );
                        }
                    }
                    if ( isset( $_POST['product-type'] ) ) {
                        wp_set_object_terms( $product_id, $_POST['product-type'], 'product_type' );
                    } else {
                        /** Set Product type by default simple */
                        wp_set_object_terms( $product_id, 'simple', 'product_type' );
                    }
                    update_post_meta( $product_id, '_regular_price', $price );
                    update_post_meta( $product_id, '_sale_price', '' );
                    update_post_meta( $product_id, '_price', $price );
                    update_post_meta( $product_id, '_visibility', 'visible' );

                    do_action( 'dokan_new_product_added', $product_id, $post_data );

                    if ( dokan_get_option( 'product_add_mail', 'dokan_general', 'on' ) == 'on' ) {
                        Dokan_Email::init()->new_product_added( $product_id, $product_status );
                    }

                    wp_redirect( dokan_edit_product_url( $product_id ) );
                    exit;
                }
            }
        }


        if ( isset( $_GET['product_id'] ) ) {
            $post_id = intval( $_GET['product_id'] );
        } else {
            global $post, $product;

            if ( !empty( $post ) ) {
                $post_id = $post->ID;
            }
        }


        if ( isset( $_POST['update_product'] ) && wp_verify_nonce( $_POST['dokan_edit_product_nonce'], 'dokan_edit_product' ) ) {
            $post_title     = trim( $_POST['post_title'] );
            if ( empty( $post_title ) ) {

                $errors[] = __( 'Please enter product title', 'dokan' );
            }

            dump("ITEM 3");
            dump($_POST['product_cat']);
            exit;

            if( dokan_get_option( 'product_category_style', 'dokan_selling', 'single' ) == 'single' ) {
                $product_cat    = intval( $_POST['product_cat'] );
                if ( $product_cat < 0 ) {
                    $errors[] = __( 'Please select a category', 'dokan' );
                }
            } else {
                if( !isset( $_POST['product_cat'] ) && empty( $_POST['product_cat'] ) ) {
                    $errors[] = __( 'Please select AT LEAST ONE category', 'dokan' );
                }
            }

            self::$errors = apply_filters( 'dokan_can_edit_product', $errors );

            if ( !self::$errors ) {

                $product_info = array(
                    'ID'             => $post_id,
                    'post_title'     => sanitize_text_field( $_POST['post_title'] ),
                    'post_content'   => $_POST['post_content'],
                    'post_excerpt'   => $_POST['post_excerpt'],
                    'post_status'    => isset( $_POST['post_status'] ) ? $_POST['post_status'] : 'pending',
                    'comment_status' => isset( $_POST['_enable_reviews'] ) ? 'open' : 'closed'
                );

                $is_lot_discount     = isset( $_POST['_is_lot_discount'] ) ? $_POST['_is_lot_discount'] : 'no';
                if ( $is_lot_discount == 'yes' ) {
                    $lot_discount_quantity = isset($_POST['_lot_discount_quantity']) ? $_POST['_lot_discount_quantity'] : 0;
                    $lot_discount_amount   = isset($_POST['_lot_discount_amount']) ? $_POST['_lot_discount_amount'] : 0;
                    if ( $lot_discount_quantity == '0' || $lot_discount_amount == '0' ) {
                        update_post_meta( $post_id, '_lot_discount_quantity', $lot_discount_quantity);
                        update_post_meta( $post_id, '_lot_discount_amount', $lot_discount_amount);
                        update_post_meta( $post_id, '_is_lot_discount', 'no');
                    } else {
                        update_post_meta( $post_id, '_lot_discount_quantity', $lot_discount_quantity);
                        update_post_meta( $post_id, '_lot_discount_amount', $lot_discount_amount);
                        update_post_meta( $post_id, '_is_lot_discount', $is_lot_discount);
                    }
                } else if ( $is_lot_discount == 'no' ) {
                    update_post_meta( $post_id, '_lot_discount_quantity', 0);
                    update_post_meta( $post_id, '_lot_discount_amount', 0);
                    update_post_meta( $post_id, '_is_lot_discount', 'no');
                }

                wp_update_post( $product_info );

                /** Set Product tags */
                if( isset( $_POST['product_tag'] ) ) {
                    $tags_ids = array_map( 'intval', (array)$_POST['product_tag'] );
                } else {
                    $tags_ids = array();
                }
                wp_set_object_terms( $post_id, $tags_ids, 'product_tag' );


                /** set product category * */

                if( dokan_get_option( 'product_category_style', 'dokan_selling', 'single' ) == 'single' ) {
                    wp_set_object_terms( $post_id, (int) $_POST['product_cat'], 'product_cat' );
                } else {
                    if( isset( $_POST['product_cat'] ) && !empty( $_POST['product_cat'] ) ) {
                        $cat_ids = array_map( 'intval', (array)$_POST['product_cat'] );
                        wp_set_object_terms( $post_id, $cat_ids, 'product_cat' );
                    }
                }
                if ( isset( $_POST['product-type'] ) ) {
                    wp_set_object_terms( $product_id, $_POST['product-type'], 'product_type' );
                } else {
                    wp_set_object_terms( $post_id, 'simple', 'product_type' );
                }
                /**  Process all variation products meta */
                dokan_process_product_meta( $post_id );

                /** set images **/
                $featured_image = absint( $_POST['feat_image_id'] );
                if ( $featured_image ) {
                    set_post_thumbnail( $post_id, $featured_image );
                }

                $edit_url = dokan_edit_product_url( $post_id );
                wp_redirect( add_query_arg( array( 'message' => 'success' ), $edit_url ) );
                exit;
            }
        }


    }

    /**
     * Handle delete product link
     *
     * @return void
     */
    function handle_delete_product() {

        if ( ! is_user_logged_in() ) {
            return;
        }

        if ( ! dokan_is_user_seller( get_current_user_id() ) ) {
            return;
        }

        dokan_delete_product_handler();
    }

}
