<?php
namespace VigyapanamAffiliate\Admin\Controllers;

use VigyapanamAffiliate\Utils\EmailManager;

class FreelancerController {
    private $email_manager;

    public function __construct() {
        $this->email_manager = new EmailManager();
        add_action('wp_ajax_ban_freelancer', [$this, 'ban_freelancer']);
        add_action('wp_ajax_unban_freelancer', [$this, 'unban_freelancer']);
        add_action('wp_ajax_get_top_freelancers', [$this, 'get_top_freelancers']);
    }

    public function ban_freelancer() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized access');
        }

        check_ajax_referer('freelancer_nonce', 'nonce');

        $freelancer_id = intval($_POST['freelancer_id']);
        $reason = sanitize_textarea_field($_POST['reason']);

        update_user_meta($freelancer_id, 'vigyapanam_banned', true);
        update_user_meta($freelancer_id, 'vigyapanam_ban_reason', $reason);
        update_user_meta($freelancer_id, 'vigyapanam_ban_date', current_time('mysql'));

        $user = get_userdata($freelancer_id);
        $this->email_manager->send_ban_notification($user->user_email, $user->display_name, $reason);

        wp_send_json_success('Freelancer banned successfully');
    }

    public function unban_freelancer() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized access');
        }

        check_ajax_referer('freelancer_nonce', 'nonce');

        $freelancer_id = intval($_POST['freelancer_id']);

        delete_user_meta($freelancer_id, 'vigyapanam_banned');
        delete_user_meta($freelancer_id, 'vigyapanam_ban_reason');
        delete_user_meta($freelancer_id, 'vigyapanam_ban_date');

        wp_send_json_success('Freelancer unbanned successfully');
    }

    public function get_top_freelancers() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized access');
        }

        global $wpdb;
        $results = $wpdb->get_results(
            "SELECT 
                u.ID,
                u.display_name,
                SUM(e.earnings) as total_earnings,
                COUNT(DISTINCT c.id) as total_clicks
            FROM {$wpdb->users} u
            LEFT JOIN {$wpdb->prefix}vigyapanam_earnings e ON u.ID = e.freelancer_id
            LEFT JOIN {$wpdb->prefix}vigyapanam_clicks c ON u.ID = c.freelancer_id
            WHERE u.ID IN (
                SELECT user_id 
                FROM {$wpdb->usermeta} 
                WHERE meta_key = 'wp_capabilities' 
                AND meta_value LIKE '%freelancer%'
            )
            AND MONTH(e.date) = MONTH(CURRENT_DATE())
            GROUP BY u.ID
            ORDER BY total_earnings DESC
            LIMIT 5"
        );

        wp_send_json_success($results);
    }
}