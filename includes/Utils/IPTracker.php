<?php
namespace VigyapanamAffiliate\Utils;

class IPTracker {
    public function __construct() {
        add_action('init', [$this, 'init_tracking']);
    }

    public function init_tracking() {
        if (!session_id()) {
            session_start();
        }
    }

    public function track_click($freelancer_id, $program_id) {
        $ip = $this->get_client_ip();
        
        if ($this->is_duplicate_click($ip, $freelancer_id, $program_id)) {
            return false;
        }

        $this->record_click($ip, $freelancer_id, $program_id);
        return true;
    }

    private function get_client_ip() {
        $ip_headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($ip_headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0';
    }

    private function is_duplicate_click($ip, $freelancer_id, $program_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vigyapanam_clicks';
        
        $result = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) 
            FROM $table_name 
            WHERE ip_address = %s 
            AND freelancer_id = %d 
            AND program_id = %d 
            AND date_clicked > DATE_SUB(NOW(), INTERVAL 24 HOUR)",
            $ip,
            $freelancer_id,
            $program_id
        ));

        return $result > 0;
    }

    private function record_click($ip, $freelancer_id, $program_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vigyapanam_clicks';
        
        $wpdb->insert(
            $table_name,
            [
                'ip_address' => $ip,
                'freelancer_id' => $freelancer_id,
                'program_id' => $program_id,
                'date_clicked' => current_time('mysql')
            ],
            ['%s', '%d', '%d', '%s']
        );
    }
}