<?php
namespace VigyapanamAffiliate\Core\WooCommerce;

class MyAccountIntegration {
    public function __construct() {
        add_action('init', [$this, 'add_endpoints']);
        add_filter('woocommerce_account_menu_items', [$this, 'add_affiliate_dashboard_tab']);
        add_action('woocommerce_account_affiliate-dashboard_endpoint', [$this, 'affiliate_dashboard_content']);
        add_action('template_redirect', [$this, 'check_affiliate_access']);
        add_filter('woocommerce_get_endpoint_url', [$this, 'get_affiliate_endpoint_url'], 10, 4);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('woocommerce_login_form_end', [$this, 'add_affiliate_register_link']);
    }

    public function add_affiliate_register_link() {
        echo '<p class="affiliate-register-link">';
        echo __('Want to become an affiliate? ', 'vigyapanam-affiliate');
        echo '<a href="' . esc_url(home_url('/affiliate-registration/')) . '">';
        echo __('Register here', 'vigyapanam-affiliate');
        echo '</a></p>';
    }

    public function add_endpoints() {
        add_rewrite_endpoint('affiliate-dashboard', EP_ROOT | EP_PAGES);
        if (!get_option('vigyapanam_flushed_rewrite')) {
            flush_rewrite_rules();
            update_option('vigyapanam_flushed_rewrite', true);
        }
    }

    public function add_affiliate_dashboard_tab($items) {
        if (is_user_logged_in() && in_array('affiliate', wp_get_current_user()->roles)) {
            $new_items = [];
            foreach ($items as $key => $item) {
                $new_items[$key] = $item;
                if ($key === 'dashboard') {
                    $new_items['affiliate-dashboard'] = __('Affiliate Dashboard', 'vigyapanam-affiliate');
                }
            }
            return $new_items;
        }
        return $items;
    }

    public function affiliate_dashboard_content() {
        if (!is_user_logged_in() || !in_array('affiliate', wp_get_current_user()->roles)) {
            return;
        }
        echo do_shortcode('[vigyapanam_freelancer_dashboard]');
    }

    public function check_affiliate_access() {
        if (is_account_page() && is_wc_endpoint_url('affiliate-dashboard')) {
            if (!is_user_logged_in() || !in_array('affiliate', wp_get_current_user()->roles)) {
                wp_safe_redirect(wc_get_account_endpoint_url('dashboard'));
                exit;
            }
        }
    }

    public function get_affiliate_endpoint_url($url, $endpoint, $value, $permalink) {
        if ($endpoint === 'affiliate-dashboard') {
            return wc_get_account_endpoint_url('affiliate-dashboard');
        }
        return $url;
    }

    public function enqueue_scripts() {
        if (is_account_page()) {
            wp_enqueue_style('vigyapanam-frontend-style');
            wp_enqueue_script('chart-js');
            wp_enqueue_script('vigyapanam-dashboard');
        }
    }
}