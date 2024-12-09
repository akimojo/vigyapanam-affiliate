<?php
namespace VigyapanamAffiliate\Admin\Controllers;

class ClientController {
    public function __construct() {
        add_action('wp_ajax_add_client', [$this, 'add_client']);
        add_action('wp_ajax_edit_client', [$this, 'edit_client']);
        add_action('wp_ajax_delete_client', [$this, 'delete_client']);
    }

    public function add_client() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized access');
        }

        check_ajax_referer('client_nonce', 'nonce');

        $client_data = [
            'name' => sanitize_text_field($_POST['client_name']),
            'revenue_model' => sanitize_text_field($_POST['revenue_model']),
            'website' => esc_url_raw($_POST['website']),
            'about' => wp_kses_post($_POST['about']),
            'contact_person' => sanitize_text_field($_POST['contact_person']),
            'payment_terms' => wp_kses_post($_POST['payment_terms'])
        ];

        global $wpdb;
        $result = $wpdb->insert(
            $wpdb->prefix . 'vigyapanam_clients',
            $client_data,
            ['%s', '%s', '%s', '%s', '%s', '%s']
        );

        if ($result) {
            wp_send_json_success('Client added successfully');
        } else {
            wp_send_json_error('Failed to add client');
        }
    }

    public function edit_client() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized access');
        }

        check_ajax_referer('client_nonce', 'nonce');

        $client_id = intval($_POST['client_id']);
        $client_data = [
            'name' => sanitize_text_field($_POST['client_name']),
            'revenue_model' => sanitize_text_field($_POST['revenue_model']),
            'website' => esc_url_raw($_POST['website']),
            'about' => wp_kses_post($_POST['about']),
            'contact_person' => sanitize_text_field($_POST['contact_person']),
            'payment_terms' => wp_kses_post($_POST['payment_terms'])
        ];

        global $wpdb;
        $result = $wpdb->update(
            $wpdb->prefix . 'vigyapanam_clients',
            $client_data,
            ['id' => $client_id],
            ['%s', '%s', '%s', '%s', '%s', '%s'],
            ['%d']
        );

        if ($result !== false) {
            wp_send_json_success('Client updated successfully');
        } else {
            wp_send_json_error('Failed to update client');
        }
    }

    public function delete_client() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized access');
        }

        check_ajax_referer('client_nonce', 'nonce');

        $client_id = intval($_POST['client_id']);

        global $wpdb;
        $result = $wpdb->delete(
            $wpdb->prefix . 'vigyapanam_clients',
            ['id' => $client_id],
            ['%d']
        );

        if ($result) {
            wp_send_json_success('Client deleted successfully');
        } else {
            wp_send_json_error('Failed to delete client');
        }
    }
}