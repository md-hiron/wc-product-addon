<?php

class Addon_Tab_pabel{
    public function __construct(){
        //Product data tab for addon
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'product_data_tab_for_addon' ) );
        add_action( 'woocommerce_product_data_panels', array( $this, 'wc_product_addon_data_tab_content' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_wc_product_addon_meta_data' ) );
    }

    /**
     * Product data tab for addon
     */
    public function product_data_tab_for_addon( $tabs ){
        $tabs['product_addon'] = array(
            'label'    => __( 'Product Addons', 'wc-product-addon' ),
            'target'   => 'wc_product_addon_tab',
            'class'    => array( 'show_if_simple', 'show_if_variable' ),
        );

        return $tabs;
    }

    /**
     * Product addon tab data
     */
    public function wc_product_addon_data_tab_content(){
        global $post;
        $addon_items = get_post_meta( $post->ID, 'addon_items', true );
        ?>
            <div id="wc_product_addon_tab" class="panel woocommerce_options_panel">
                <div class="addon-head">
                    <h2><?php _e( 'Extra Items', 'wc-product-addon' ); ?></h2>
                </div>
                <div class="wc-product-addons">
                <?php
                    if( $addon_items ){
                        foreach( $addon_items as $item ){
                            ?>
                            <div class="addon-form-fields-wrap">
                                <div class="wc-addon-field">
                                    <label for="addon_name"><?php _e( 'Item title', 'wc-product-addon' ); ?></label>
                                    <input type="text" id="addon_name" name="addon_name[]" value="<?php echo esc_attr( $item['addon_name'] )?>">
                                </div>
                                <div class="wc-addon-field">
                                    <label for="addon_price"><?php _e( 'Item Price', 'wc-product-addon' ); ?></label>
                                    <input type="text" id="addon_price" name="addon_price[]" value="<?php echo esc_attr( $item['addon_price'] )?>">
                                </div>
                                <button type="button" class="button remove-product-addon"><?php echo __( 'Remove', 'wc-product-addon' );?></button>
                            </div>
                            <?php
                        }
                    }else{
                        
                    ?>
                        <div class="addon-form-fields-wrap">
                            <div class="wc-addon-field">
                                <label for="addon_name"><?php _e( 'Item title', 'wc-product-addon' ); ?></label>
                                <input type="text" id="addon_name" name="addon_name[]">
                            </div>
                            <div class="wc-addon-field">
                                <label for="addon_price"><?php _e( 'Item Price', 'wc-product-addon' ); ?></label>
                                <input type="text" id="addon_price" name="addon_price[]">
                            </div>
                            <button type="button" class="button remove-product-addon"><?php echo __( 'Remove', 'wc-product-addon' );?></button>
                        </div>
                    <?php }?> 
                </div>
                <button type="button" class="button add-product-addon"><?php echo __( 'Add New Addon Item', 'wc-product-addon' );?></button>
            </div>
        <?php

    }

    /**
     * Save product addon meta data
     */
    public function save_wc_product_addon_meta_data( $post_id ){

        if ( isset( $_POST['addon_name'] ) && isset( $_POST['addon_price'] ) ) {
            $addons = array();
            $addon_names = $_POST['addon_name'];
            $addon_prices = $_POST['addon_price'];
            $count = count( $addon_names );

            for ( $i = 0; $i < $count; $i++ ) {
                $addons[] = array(
                    'addon_name' => sanitize_text_field( $addon_names[$i] ),
                    'addon_price' => sanitize_text_field( $addon_prices[$i] ),
                );
            }
            
            update_post_meta( $post_id, 'addon_items', $addons );
        }
    }
}

$addon_tab_panel = new Addon_Tab_pabel();