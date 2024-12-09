<?php
namespace VigyapanamAffiliate\Utils;

class ClickValidator {
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function is_valid_click($ip, $freelancer_id, $program_id) {
        return !$this->is_duplicate_click($ip, $freelancer_id, $program_id) &&
               $this->is_valid_ip($ip) &&
               $this->is_valid_freelancer($freelancer_id) &&
               $this->is_valid_program($program_id);
    }

    private function is_duplicate_click($ip, $freelancer_id, $program_id) {
        $table_name = $this->wpdb->prefix . 'vigyapanam_clicks';
        
        $result = $this->wpdb->get_var($this->wpdb->prepare(
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

    private function is_valid_ip($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    private function is_valid_freelancer($freelancer_id) {
        return get_user_by('id', $freelancer_id) !== false;
    }

    private function is_valid_program($program_id) {
        return get_post_type($program_id) === 'affiliate_program';
    }
}