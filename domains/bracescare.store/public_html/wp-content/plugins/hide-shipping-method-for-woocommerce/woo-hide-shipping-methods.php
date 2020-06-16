<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.thedotstore.com/
 * @since             1.0.0
 * @package           Woo_Hide_Shipping_Methods
 *
 * @wordpress-plugin
 * Plugin Name: Hide Shipping Method For WooCommerce
 * Plugin URI:        https://www.thedotstore.com/hide-shipping-method-for-woocommerce
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.1
 * Author:            theDotstore
 * Author URI:        https://www.thedotstore.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-hide-shipping-methods
 * Domain Path:       /languages
 *
 * WC requires at least: 3.3.0
 * WC tested up to: 4.0
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'whsm_fs' ) ) {
    whsm_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'whsm_fs' ) ) {
        // Create a helper function for easy SDK access.
        function whsm_fs()
        {
            global  $whsm_fs ;
            
            if ( !isset( $whsm_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_4743_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_4743_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $whsm_fs = fs_dynamic_init( array(
                    'id'             => '4743',
                    'slug'           => 'woo-hide-shipping-methods',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_71be4de10d0508098c1b7ca85e591',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'slug'           => 'whsm-start-page',
                    'override_exact' => true,
                    'contact'        => false,
                    'support'        => false,
                    'network'        => true,
                    'parent'         => array(
                    'slug' => 'woocommerce',
                ),
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $whsm_fs;
        }
        
        // Init Freemius.
        whsm_fs();
        // Signal that SDK was initiated.
        do_action( 'whsm_fs_loaded' );
        function whsm_fs_settings_url()
        {
            return admin_url( 'admin.php?page=whsm-start-page' );
        }
        
        whsm_fs()->add_filter( 'connect_url', 'whsm_fs_settings_url' );
        whsm_fs()->add_filter( 'after_skip_url', 'whsm_fs_settings_url' );
        whsm_fs()->add_filter( 'after_connect_url', 'whsm_fs_settings_url' );
        whsm_fs()->add_filter( 'after_pending_connect_url', 'whsm_fs_settings_url' );
        whsm_fs()->get_upgrade_url();
    }

}

if ( !defined( 'WOO_HIDE_SHIPPING_METHODS_VERSION' ) ) {
    define( 'WOO_HIDE_SHIPPING_METHODS_VERSION', '1.0.1' );
}
if ( !defined( 'WHSM_PLUGIN_URL' ) ) {
    define( 'WHSM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'WHSM_PLUGIN_DIR' ) ) {
    define( 'WHSM_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( !defined( 'WHSM_PLUGIN_DIR_PATH' ) ) {
    define( 'WHSM_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'WHSM_SLUG' ) ) {
    define( 'WHSM_SLUG', 'woo-hide-shipping-methods' );
}
if ( !defined( 'WHSM_PLUGIN_BASENAME' ) ) {
    define( 'WHSM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( !defined( 'WHSM_PLUGIN_NAME' ) ) {
    define( 'WHSM_PLUGIN_NAME', 'Hide Shipping Method For WooCommerce' );
}
if ( !defined( 'WHSM_TEXT_DOMAIN' ) ) {
    define( 'WHSM_TEXT_DOMAIN', 'woo-hide-shipping-methods' );
}
if ( !defined( 'WHSM_FEE_AMOUNT_NOTICE' ) ) {
    define( 'WHSM_FEE_AMOUNT_NOTICE', 'If entered fee amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) <b>OR</b> If entered fee amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0)' );
}
if ( !defined( 'WHSM_PERTICULAR_FEE_AMOUNT_NOTICE' ) ) {
    define( 'WHSM_PERTICULAR_FEE_AMOUNT_NOTICE', 'Enable or Disable hide shipping rule using this checkbox.' );
}
add_action( 'plugins_loaded', 'whsm__initialize_plugin' );
/**
 * Check Initialize plugin in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
function whsm__initialize_plugin()
{
    $wc_active = in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true );
    if ( current_user_can( 'activate_plugins' ) && $wc_active !== true || $wc_active !== true ) {
        add_action( 'admin_notices', 'whsm_plugin_admin_notice_required_plugin' );
    }
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-hide-shipping-methods-activator.php
 */
function activate_woo_hide_shipping_methods()
{
    set_transient( 'whsm-admin-notice', true );
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-hide-shipping-methods-activator.php';
    Woo_Hide_Shipping_Methods_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-hide-shipping-methods-deactivator.php
 */
function deactivate_woo_hide_shipping_methods()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-hide-shipping-methods-deactivator.php';
    Woo_Hide_Shipping_Methods_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_hide_shipping_methods' );
register_deactivation_hook( __FILE__, 'deactivate_woo_hide_shipping_methods' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-hide-shipping-methods.php';
/**
 * User feedback admin notice
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-hide-shipping-methods-user-feedback.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_hide_shipping_methods()
{
    $plugin = new Woo_Hide_Shipping_Methods();
    $plugin->run();
}

run_woo_hide_shipping_methods();
function woo_hide_shipping_methods_pro_plugin_path()
{
    return untrailingslashit( plugin_dir_path( __FILE__ ) );
}

/**
 * Helper function for logging
 *
 * For valid levels, see `WC_Log_Levels` class
 *
 * Description of levels:
 *     'emergency': System is unusable.
 *     'alert': Action must be taken immediately.
 *     'critical': Critical conditions.
 *     'error': Error conditions.
 *     'warning': Warning conditions.
 *     'notice': Normal but significant condition.
 *     'info': Informational messages.
 *     'debug': Debug-level messages.
 *
 * @since    1.0.0
 *
 * @param string $message
 *
 * @return mixed log
 */
function whsm_main_log( $message, $level = 'debug' )
{
    $chk_enable_logging = get_option( 'chk_enable_logging' );
    if ( 'off' === $chk_enable_logging ) {
        return;
    }
    $logger = wc_get_logger();
    $context = array(
        'source' => 'woo-hide-shipping-methods',
    );
    return $logger->log( $level, $message, $context );
}

add_action( 'admin_notices', 'whsm_main_notice_function' );
/**
 * Notice function: When activate plugin then notice will display.
 *
 * @since    1.0.0
 *
 */
function whsm_main_notice_function()
{
    $screen = get_current_screen();
    $screen_id = ( $screen ? $screen->id : '' );
    $screens = array( 'plugins', 'woocommerce_page_whsm-start-page' );
    if ( !in_array( $screen_id, $screens, true ) ) {
        return;
    }
    $whsm_admin = filter_input( INPUT_GET, 'whsm-hide-notice', FILTER_SANITIZE_STRING );
    $wc_notice_nonce = filter_input( INPUT_GET, '_whsm_notice_nonce', FILTER_SANITIZE_STRING );
    if ( isset( $whsm_admin ) && 'whsm_admin' === $whsm_admin && wp_verify_nonce( sanitize_text_field( $wc_notice_nonce ), 'whsm_hide_notices_nonce' ) ) {
        delete_transient( 'whsm-admin-notice' );
    }
    /* Check transient, if available display notice */
    
    if ( get_transient( 'whsm-admin-notice' ) ) {
        ?>
        <div id="message" class="updated woocommerce-message woocommerce-admin-promo-messages welcome-panel whsm-panel">
            <a class="woocommerce-message-close notice-dismiss" href="<?php 
        echo  esc_url( wp_nonce_url( add_query_arg( 'whsm-hide-notice', 'whsm_admin' ), 'whsm_hide_notices_nonce', '_whsm_notice_nonce' ) ) ;
        ?>"></a>
            <p>
                <?php 
        echo  sprintf( wp_kses( __( '<strong>Hide Shipping Method For WooCommerce is successfully installed and ready to go.</strong>', 'woo-hide-shipping-methods' ), array(
            'strong' => array(),
        ), esc_url( admin_url( 'options-general.php' ) ) ) ) ;
        ?>
            </p>
            <p>
                <?php 
        echo  wp_kses_post( __( 'Click on settings button and create your shipping method with multiple rules', 'woo-hide-shipping-methods' ) ) ;
        ?>
            </p>
            <?php 
        $url = add_query_arg( array(
            'page' => 'whsm-start-page',
        ), admin_url( 'admin.php' ) );
        ?>
            <p>
                <a href="<?php 
        echo  esc_url( $url ) ;
        ?>" class="button button-primary"><?php 
        esc_html_e( 'Settings', 'woo-hide-shipping-methods' );
        ?></a>
            </p>
        </div>
        <?php 
    }

}

/**
 * Show admin notice in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
function whsm_plugin_admin_notice_required_plugin()
{
    $vpe_plugin = esc_html__( 'Hide Shipping Method For WooCommerce', 'woocommerce-product-attachment' );
    $wc_plugin = esc_html__( 'WooCommerce', 'woocommerce-product-attachment' );
    ?>
    <div class="error">
        <p>
            <?php 
    echo  sprintf( esc_html__( '%1$s requires %2$s to be installed & activated!', 'woocommerce-product-attachment' ), '<strong>' . esc_html( $vpe_plugin ) . '</strong>', '<a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '" target="_blank"><strong>' . esc_html( $wc_plugin ) . '</strong></a>' ) ;
    ?>
        </p>
    </div>
    <?php 
}
