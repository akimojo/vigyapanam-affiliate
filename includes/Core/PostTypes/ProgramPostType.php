<?php
namespace VigyapanamAffiliate\Core\PostTypes;

class ProgramPostType {
    public function register() {
        register_post_type('affiliate_program', [
            'labels' => $this->get_labels(),
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'menu_icon' => 'dashicons-money-alt',
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'affiliate-programs'],
        ]);

        $this->register_meta_fields();
    }

    private function get_labels() {
        return [
            'name' => __('Affiliate Programs', 'vigyapanam-affiliate'),
            'singular_name' => __('Affiliate Program', 'vigyapanam-affiliate'),
            'add_new' => __('Add New Program', 'vigyapanam-affiliate'),
            'add_new_item' => __('Add New Affiliate Program', 'vigyapanam-affiliate'),
            'edit_item' => __('Edit Affiliate Program', 'vigyapanam-affiliate'),
            'new_item' => __('New Affiliate Program', 'vigyapanam-affiliate'),
            'view_item' => __('View Affiliate Program', 'vigyapanam-affiliate'),
            'search_items' => __('Search Affiliate Programs', 'vigyapanam-affiliate'),
            'not_found' => __('No affiliate programs found', 'vigyapanam-affiliate'),
            'not_found_in_trash' => __('No affiliate programs found in trash', 'vigyapanam-affiliate'),
        ];
    }

    private function register_meta_fields() {
        register_post_meta('affiliate_program', 'rpm', [
            'type' => 'number',
            'description' => 'Revenue Per Mille (RPM)',
            'single' => true,
            'show_in_rest' => true,
        ]);

        register_post_meta('affiliate_program', 'register_url', [
            'type' => 'string',
            'description' => 'Registration URL',
            'single' => true,
            'show_in_rest' => true,
        ]);

        register_post_meta('affiliate_program', 'terms_url', [
            'type' => 'string',
            'description' => 'Terms and Conditions URL',
            'single' => true,
            'show_in_rest' => true,
        ]);
    }
}