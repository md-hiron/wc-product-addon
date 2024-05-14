<?php
/*
Plugin Name: WC Product Addon
Plugin URI: http://hirondev.com
Description: WC Product addon
Version: 1.0.0
Author: Md Hiron Mia
Author URI: http://hirondev.com
Text Domain: wc-product-addon
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'WCP_ADDON_VERSION', '1.0.0' );
define( 'WCP_ADDON_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'WCP_ADDON_URI', rtrim( plugin_dir_path( __FILE__ ), '/' ) );

class WC_Product_Addon{
    /**
	 * Static property to hold our singleton instance
	 *
	 */
	static $instance = false;

    /**
	 * This is our constructor
	 *
	 * @return void
	 */
    private function __construct(){
        
        add_action('init', array($this, 'check_woocommerce_activation'));

        add_action('plugins_loaded', array( $this, 'wcp_addon_textdomain' ));

        add_action( 'admin_enqueue_scripts', array( $this, 'wcp_admin_register_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'wcp_register_scripts' ) );

        //file includes
        $this->file_includes();

    }

    /**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * retuns it.
	 *
	 * @return Order_Hub
	 */
    public static function getInstance(){
        if( !self::$instance ){
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
	 * load textdomain
	 *
	 * @return void
	 */
    public function wcp_addon_textdomain(){
        load_plugin_textdomain( 'wc-product-addon', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Woocommerce installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function check_woocommerce_activation() {
        if ( ! class_exists( 'WooCommerce' ) ) {
			printf('<div class="notice notice-error is-dismissible"><p style="padding: 13px 0">%s</p></div>', __('Woocommerce plugin is required', 'wc-product-addon') );
			return;
		}
		
	}

    /**
	 * Register scripts
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function wcp_admin_register_scripts(){
        wp_enqueue_style( 'wcp_admin_style', WCP_ADDON_URL . '/assets/css/product-addon.css', array(), time() );
        wp_enqueue_script('jquery');
        wp_enqueue_script( 'wcp_script', WCP_ADDON_URL . '/assets/js/product-addon.js', array('jquery'), time(), true );
  
    }

    public function wcp_register_scripts(){
        wp_enqueue_style( 'wcp_style', WCP_ADDON_URL . '/assets/css/style.css', array(), time() );
    }

     /**
     * file includes
     * 
     * @return void
     */
    public function file_includes(){
        require_once WCP_ADDON_URI . '/includes/class-addon-calculation.php';
        require_once WCP_ADDON_URI . '/includes/class-addon-tab-panel.php';
       
    }
}

WC_Product_Addon::getInstance();