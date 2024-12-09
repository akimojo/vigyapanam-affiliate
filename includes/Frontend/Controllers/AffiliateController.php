<?php
namespace VigyapanamAffiliate\Frontend\Controllers;

use VigyapanamAffiliate\Utils\IPTracker;

class AffiliateController {
    private $ip_tracker;

    public function __construct() {
        $this->ip_tracker = new IPTracker();
        add_action('wp_ajax_track_affiliate_click', [$this, 'track_affiliate_click']);
        add_action('wp_ajax_nopriv_track_affiliate_click', [$this, 'track_affiliate_click']);
    }

    public function track_affiliate_click() {
        check_ajax_referer('affiliate_nonce', 'nonce');

        $freelancer_id = intval($_POST['freelancer_id']);
        $program_id = intval($_POST['program_id']);

        if ($this->ip_tracker->track_click($freelancer_id, $program_id)) {
            $this->record_earnings($freelancer_id, $program_id);
            wp_send_json_success('Click tracked successfully');
        } else {
            wp_send_json_error('Click already recorded');
        }
    }

    private function record_earnings($freelancer_id, $program_id) {
        global $wpdb;
        
        // Get program details
        $program = get_post($program_id);
        $rpm = get_post_meta($program_id, 'vigyapanam_rpm', true);

        // Calculate earnings based on RPM
        $earnings = $rpm / 1000; // RPM is per 1000 clicks

        // Record earnings
        $wpdb->insert(
            $wpdb->prefix . 'vigyapanam_earnings',
            [
                'freelancer_id' => $freelancer_id,
                'program_id' => $program_id,
                'date' => current_time('mysql'),
                'earnings' => $earnings,
                'rpm' => $rpm
            ],
            ['%d', '%d', '%s', '%f', '%f']
        );
    }
}