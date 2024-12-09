<?php
namespace VigyapanamAffiliate\Admin;

class AdminDashboard {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    public function add_admin_menu() {
        add_menu_page(
            __('Vigyapanam Affiliate', 'vigyapanam-affiliate'),
            __('Vigyapanam Affiliate', 'vigyapanam-affiliate'),
            'manage_options',
            'vigyapanam-affiliate',
            [$this, 'render_dashboard'],
            'dashicons-groups',
            30
        );

        add_submenu_page(
            'vigyapanam-affiliate',
            __('Dashboard', 'vigyapanam-affiliate'),
            __('Dashboard', 'vigyapanam-affiliate'),
            'manage_options',
            'vigyapanam-affiliate',
            [$this, 'render_dashboard']
        );
    }

    public function enqueue_admin_scripts($hook) {
        if (!in_array($hook, ['toplevel_page_vigyapanam-affiliate', 'vigyapanam-affiliate_page_vigyapanam-affiliate-dashboard'])) {
            return;
        }

        wp_enqueue_style(
            'vigyapanam-admin-style',
            VIGYAPANAM_AFFILIATE_PLUGIN_URL . 'assets/css/admin-style.css',
            [],
            VIGYAPANAM_AFFILIATE_VERSION
        );

        wp_enqueue_script(
            'chart-js',
            'https://cdn.jsdelivr.net/npm/chart.js',
            [],
            '3.7.0',
            true
        );

        wp_enqueue_script(
            'vigyapanam-admin-dashboard',
            VIGYAPANAM_AFFILIATE_PLUGIN_URL . 'assets/js/admin-dashboard.js',
            ['jquery', 'chart-js'],
            VIGYAPANAM_AFFILIATE_VERSION,
            true
        );

        wp_localize_script('vigyapanam-admin-dashboard', 'vigyapanamAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('vigyapanam_admin_nonce')
        ]);
    }

    public function render_dashboard() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'vigyapanam-affiliate'));
        }

        require_once VIGYAPANAM_AFFILIATE_PLUGIN_DIR . 'includes/Admin/Views/admin-dashboard.php';
    }
}