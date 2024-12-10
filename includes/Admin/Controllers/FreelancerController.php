<?php
namespace VigyapanamAffiliate\Admin\Controllers;

class FreelancerController {
    public function __construct() {
        add_action('wp_ajax_filter_freelancer_data', [$this, 'filter_freelancer_data']);
        add_action('wp_ajax_ban_freelancer', [$this, 'ban_freelancer']);
        add_action('wp_ajax_get_freelancer_details', [$this, 'get_freelancer_details']);
    }

    public function filter_freelancer_data() {
        check_ajax_referer('vigyapanam_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized access');
        }

        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date = sanitize_text_field($_POST['end_date']);
        
        $analytics = new \VigyapanamAffiliate\Utils\Analytics();
        $filtered_data = $analytics->get_filtered_data($start_date, $end_date);

        wp_send_json_success($filtered_data);
    }

    public function ban_freelancer() {
        check_ajax_referer('vigyapanam_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized access');
        }

        $freelancer_id = intval($_POST['freelancer_id']);
        $reason = sanitize_textarea_field($_POST['reason']);

        // Update user meta
        update_user_meta($freelancer_id, 'vigyapanam_banned', true);
        update_user_meta($freelancer_id, 'vigyapanam_ban_reason', $reason);
        update_user_meta($freelancer_id, 'vigyapanam_ban_date', current_time('mysql'));

        // Send email notification
        $this->send_ban_notification($freelancer_id, $reason);

        wp_send_json_success('Affiliate banned successfully');
    }

    public function get_freelancer_details() {
        check_ajax_referer('vigyapanam_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized access');
        }

        $freelancer_id = intval($_POST['freelancer_id']);
        $analytics = new \VigyapanamAffiliate\Utils\Analytics();
        
        $details = [
            'profile' => $this->get_profile_data($freelancer_id),
            'clicks' => $analytics->get_click_details($freelancer_id),
            'earnings' => $this->get_earnings_data($freelancer_id),
            'locations' => $analytics->get_location_stats($freelancer_id)
        ];

        wp_send_json_success($details);
    }

    private function get_profile_data($user_id) {
        $user = get_userdata($user_id);
        return [
            'name' => get_user_meta($user_id, 'vigyapanam_name', true),
            'email' => $user->user_email,
            'mobile' => get_user_meta($user_id, 'vigyapanam_mobile', true),
            'joined' => $user->user_registered,
            'social_media' => [
                'linkedin' => get_user_meta($user_id, 'vigyapanam_linkedin', true),
                'instagram' => get_user_meta($user_id, 'vigyapanam_instagram', true),
                'facebook' => get_user_meta($user_id, 'vigyapanam_facebook', true),
                'youtube' => get_user_meta($user_id, 'vigyapanam_youtube', true)
            ]
        ];
    }

    private function get_earnings_data($user_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT 
                DATE(date) as date,
                SUM(earnings) as amount,
                AVG(rpm) as rpm
            FROM {$wpdb->prefix}vigyapanam_earnings
            WHERE freelancer_id = %d
            GROUP BY DATE(date)
            ORDER BY date DESC
            LIMIT 30",
            $user_id
        ));
    }

    private function send_ban_notification($user_id, $reason) {
        $user = get_userdata($user_id);
        $email_manager = new \VigyapanamAffiliate\Utils\EmailManager();
        $email_manager->send_ban_notification($user->user_email, $user->display_name, $reason);
    }
}