<?php

class Addon_Calculation{
    public function __construct(){
        // display addon on single product
        add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'display_addons_on_product' ) );

        //save addon data to cart data
        add_filter( 'woocommerce_add_cart_item_data', array( $this, 'wcp_addon_data_add_to_cart' ), 10, 4 );

        // update price with addon price
        add_action('woocommerce_before_calculate_totals', array($this, 'wcp_addon_price_add_to_total'));

        //cart item name update with addon name on cart and checkout
        add_filter('woocommerce_cart_item_name', array( $this, 'add_addon_name_under_product_title' ), 10, 3);
        add_filter('woocommerce_order_item_name', array( $this, 'add_addon_name_under_product_title' ), 10, 3);

        //save addon data during order
        add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_addon_name_to_order' ), 10, 4 );

        //Show addon data on single order from admin panel
        add_action('woocommerce_admin_order_item_values', array($this, 'display_addon_name_on_order_page'), 10, 3);

    }

    /**
     * Display Addon data on single product
     */
    public function display_addons_on_product(){
        global $product;
        // Retrieve addon data for the product
        $addons = get_post_meta( $product->get_id(), 'addon_items', true );

        // Output addon options as radio buttons
        if ( $addons ) {
            echo '<div class="addons-options">';
            echo '<h3>'.__('Extra items', 'wc-product-addon').'</h3>';
            foreach ( $addons as $addon ) {
                echo '<input type="radio" name="addon_option" value="' . esc_attr( $addon['addon_name'] ) . '"> ' . esc_html( $addon['addon_name'] ) . ' - $' . esc_html( $addon['addon_price'] ) . '<br>';
            }
            echo '</div>';
        }
    }

    /**
     * Save selected addon to cart
     */
    public function wcp_addon_data_add_to_cart($cart_item_data, $product_id, $variation_id, $quantity){
        if (isset($_POST['addon_option']) && !empty($_POST['addon_option'])) {
            $addon_name = sanitize_text_field($_POST['addon_option']);

            $addons = get_post_meta( $product_id, 'addon_items', true );
            // Find the selected addon
            $selected_addon = null;
            foreach ($addons as $addon) {
                if ($addon['addon_name'] === $addon_name) {
                    $selected_addon = $addon;
                    break;
                }
            }

            $cart_item_data = array_merge($cart_item_data, $selected_addon);
            
        }
        return $cart_item_data;
    }

    /**
     * Calculate price with addon price
     */
    public function wcp_addon_price_add_to_total($cart_object){
        foreach ($cart_object->cart_contents as $key => $value) {
           // Access cart item data
        $product = $value['data'];
        
        // Get the product price
        $product_price = $product->get_price();
        $addon_price = isset( $value['addon_price'] ) ? $value['addon_price'] : 0;
        $value['data']->set_price( $product_price + $addon_price );

        }
    }

    /**
     * Addon Name Uner product title
     */
    public function add_addon_name_under_product_title( $product_name, $cart_item, $cart_item_key ){
    
        // Retrieve your addon from the cart item
        $addon_name = isset($cart_item['addon_name']) ? $cart_item['addon_name'] : '';

        // Output addon data under the product title
        if (!empty($addon_name)) {
            $product_name .= '<br/><small>' . esc_html__('Addon: ', 'wc-product-addon') . $addon_name . '</small>';
        }

        return $product_name;
    }

    /**
     * Save addon data to order meta
     */
    public function save_addon_name_to_order( $item, $cart_item_key, $values, $order ){
            // Get addon Name from the cart item
            $addon_name = isset($values['addon_name']) ? $values['addon_name'] : '';

            // Save addon name as order item meta
            if (!empty($addon_name)) {
                $item->add_meta_data(__('Addon ', 'wc-product-addon'), $addon_name);
            }
    }

    /**
     * Display addon data on order
     */
    public function display_addon_name_on_order_page( $product, $item, $item_id ){
        
        $addon_name = $item->get_meta('addon_name');
        echo $addon_name;
    }

}

$addon_calculation = new Addon_Calculation();

