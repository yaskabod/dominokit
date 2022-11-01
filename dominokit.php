<?php
/**
 * Plugin Name: dominokit
 * Description: Customize your WooCommerce
 * Plugin URI:  https://dominodev.com/sandbox/dominokit-landing/dominokit
 * Version:     1.0.5
 * Author:      dominodev
 * Author URI:  https://www.zhaket.com/store/web/dominodev
 * Text Domain: dominokit
 */

if (!defined('ABSPATH')) {
    exit;
}

define('DOMKIT_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('DOMKIT_INCLUDE', DOMKIT_DIR . 'includes');
define('DOMKIT_APP', DOMKIT_DIR . 'app');
define('DOMKIT_TEMPLATE', DOMKIT_DIR . 'templates');
define('DOMKIT_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('DOMKIT_ASSETS', DOMKIT_URL . 'assets');
define('DOMKIT_IMAGES', DOMKIT_URL . 'assets/images');
define('DOMKIT_DEBUG', false);


final class dominokit
{
    /**
     * Minimum PHP Version
     *
     * @since 1.2.0
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.4';

    /**
     * @var null
     * instance in class dominokit
     */
    private static $_instance = null;

    /**
     * dominokit constructor.
     */
    public function __construct()
    {
        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', array($this, 'admin_notice_minimum_php_version'));
            return;
        }

        require_once('vendor/autoload.php');

        // Load translation
        add_action('init', array($this, 'i18n'));

        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $get_plugin_data = get_plugin_data(__FILE__);
        $GLOBALS['version'] = $get_plugin_data["Version"];

        if (!class_exists('DominoKitController')) {
            require_once DOMKIT_APP . '/DominoKitController.php';
        }
    }

    /**
     * add language in plugin dominokit
     */
    public function i18n()
    {
        load_plugin_textdomain('dominokit', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Notice minimum php version
     */
    public function admin_notice_minimum_php_version()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'dominokit'),
            '<strong>' . esc_html__('woocommerce toolkit', 'dominokit') . '</strong>',
            '<strong>' . esc_html__('PHP', 'dominokit') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        $html_message = sprintf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

        echo wp_kses_post($html_message);
    }

    /**
     * @return dominokit|null
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}

dominokit::instance();
