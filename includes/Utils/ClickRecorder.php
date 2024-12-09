<?php
namespace VigyapanamAffiliate\Utils;

class ClickRecorder {
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function record_click($ip, $freelancer_id, $program_id) {
        $table_name = $this->wpdb->prefix . 'vigyapanam_clicks';
        
        return $this->wpdb->insert(
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

    public function get_click_count($freelancer_id, $program_id = null, $period = '24 HOUR') {
        $table_name = $this->wpdb->prefix . 'vigyapanam_clicks';
        
        $query = "SELECT COUNT(*) FROM $table_name WHERE freelancer_id = %d";
        $params = [$freelancer_id];

        if ($program_id !== null) {
            $query .= " AND program_id = %d";
            $params[] = $program_id;
        }

        if ($period) {
            $query .= " AND date_clicked > DATE_SUB(NOW(), INTERVAL $period)";
        }

        return $this->wpdb->get_var($this->wpdb->prepare($query, $params));
    }
}