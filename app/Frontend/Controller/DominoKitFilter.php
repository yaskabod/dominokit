<?php

namespace DominoKitApp\Frontend\Controller;

defined('ABSPATH') || exit;

class DominoKitFilter
{
    /**
     * @var null
     */
    private static $instance = null;

    public $WooCart_product_url;

    public $WooCart_product_txt;

    public $WooCart_btn_hide_price;

    public $replace_text_zero;

    public function __construct()
    {
        $unavailable_products = get_option('woo_unavailable_products') === 'true';
        if ($unavailable_products) {
            add_filter('posts_clauses', array($this, 'wookit_order_by_stock_status'), 2000);
        }

        $this->WooCart_product_txt = get_option('dominokit_cart_button_product_txt');
        if ($this->WooCart_product_txt !== false) {
            add_filter('woocommerce_product_single_add_to_cart_text', array($this, 'wookit_custom_add_to_cart_text_callback'));
            add_filter('woocommerce_product_add_to_cart_text', array($this, 'wookit_custom_add_to_cart_text_callback'));
        }

        $this->WooCart_product_url = get_option('dominokit_cart_button_product_url');
        if ($this->WooCart_product_url !== false) {
            add_filter('woocommerce_add_to_cart_redirect', array($this, 'dominokit_woocommerce_product_add_to_cart_url_filter'), 99);
            add_filter('woocommerce_loop_add_to_cart_link', array($this, 'dominokit_view_cart_button'), 10, 3);
        }

        $this->WooCart_btn_hide_price = get_option('dominokit_price_hide_enabled');
        if ($this->WooCart_btn_hide_price !== false) {
            add_action('init', array($this, 'dominokit_price_add_cart_not_logged_in_callback'));
        }

        $this->replace_text_zero = get_option('dominokit_replace_text_zero');
        if ($this->replace_text_zero !== false) {
            add_filter('woocommerce_get_price_html', array($this, 'dominokit_price_free_zero_empty_callback'), 100, 2);
        }

    }

    public function dominokit_price_free_zero_empty_callback($price, $product)
    {
        if ($product->get_price() === '' || $product->get_price() === '0' || $product->get_price() === 0) {
            $price = $this->replace_text_zero;
        }

        return $price;
    }

    public function dominokit_price_add_cart_not_logged_in_callback()
    {
        if (!is_user_logged_in()) {
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
            remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

            add_action('woocommerce_single_product_summary', array($this, 'dominokit_print_login_to_see_callback'), 31);
            add_action('woocommerce_after_shop_loop_item', array($this, 'dominokit_print_login_to_see_callback'), 11);
        }
    }

    public function dominokit_print_login_to_see_callback()
    {
        $price_hide_text = get_option('dominokit_price_hide_text') !== false ? get_option('dominokit_price_hide_text') : __('Log in to see the price', 'wookit');
        $price_hide_url = get_option('dominokit_price_hide_url') !== false ? get_option('dominokit_price_hide_url') : get_permalink(wc_get_page_id('myaccount'));

        echo '<a href="' . $price_hide_url . '">' . $price_hide_text . '</a>';
    }

    public function dominokit_woocommerce_product_add_to_cart_url_filter($url)
    {
        $url = $this->WooCart_product_url; // URL to redirect to (1 is the page ID here)
        return $url;
    }

    public function dominokit_view_cart_button($sprintf, $product, $args)
    {
        global $product;

        $product_txt = $this->WooCart_product_txt !== false ? $this->WooCart_product_txt : $product->add_to_cart_text();
        $product_url = $this->WooCart_product_url !== false ? $this->WooCart_product_url : $product->add_to_cart_url();

        $sprintf = sprintf(
            '<a href="%s" data-quantity="%s" class="%s">%s</a>',
            esc_url($product_url),
            esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
            esc_attr('button'),
            esc_html($product_txt));

        return wp_kses_post($sprintf);

    }

    public function wookit_custom_add_to_cart_text_callback()
    {
        if ($this->WooCart_product_txt === false) return false;
        return $this->WooCart_product_txt;
    }

    public function wookit_order_by_stock_status($posts_clauses)
    {
        global $wpdb;

        if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag())) {
            $posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
            $posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
            $posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
        }

        return $posts_clauses;
    }

    /**
     * @return DominoKitFilter|null
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
