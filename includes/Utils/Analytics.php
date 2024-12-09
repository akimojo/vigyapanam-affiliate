<?php
namespace VigyapanamAffiliate\Utils;

class Analytics {
    public function get_freelancer_stats($freelancer_id, $period = '30') {
        global $wpdb;
        
        $end_date = current_time('Y-m-d');
        $start_date = date('Y-m-d', strtotime("-{$period} days"));

        return [
            'earnings' => $this->get_earnings($freelancer_id, $start_date, $end_date),
            'traffic' => $this->get_traffic($freelancer_id, $start_date, $end_date),
            'rpm' => $this->get_rpm($freelancer_id, $start_date, $end_date),
            'conversions' => $this->get_conversions($freelancer_id, $start_date, $end_date)
        ];
    }

    private function get_earnings($freelancer_id, $start_date, $end_date) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(date) as date, SUM(earnings) as total
            FROM {$wpdb->prefix}vigyapanam_earnings
            WHERE freelancer_id = %d
            AND date BETWEEN %s AND %s
            GROUP BY DATE(date)
            ORDER BY date ASC",
            $freelancer_id,
            $start_date,
            $end_date
        ));
    }

    private function get_traffic($freelancer_id, $start_date, $end_date) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(date_clicked) as date, COUNT(*) as total
            FROM {$wpdb->prefix}vigyapanam_clicks
            WHERE freelancer_id = %d
            AND date_clicked BETWEEN %s AND %s
            GROUP BY DATE(date_clicked)
            ORDER BY date ASC",
            $freelancer_id,
            $start_date,
            $end_date
        ));
    }

    private function get_rpm($freelancer_id, $start_date, $end_date) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(date) as date, AVG(rpm) as average
            FROM {$wpdb->prefix}vigyapanam_earnings
            WHERE freelancer_id = %d
            AND date BETWEEN %s AND %s
            GROUP BY DATE(date)
            ORDER BY date ASC",
            $freelancer_id,
            $start_date,
            $end_date
        ));
    }

    private function get_conversions($freelancer_id, $start_date, $end_date) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(conversion_date) as date, COUNT(*) as total
            FROM {$wpdb->prefix}vigyapanam_conversions
            WHERE freelancer_id = %d
            AND conversion_date BETWEEN %s AND %s
            GROUP BY DATE(conversion_date)
            ORDER BY date ASC",
            $freelancer_id,
            $start_date,
            $end_date
        ));
    }

    public function get_program_performance($program_id, $period = '30') {
        global $wpdb;
        
        $end_date = current_time('Y-m-d');
        $start_date = date('Y-m-d', strtotime("-{$period} days"));

        return [
            'clicks' => $this->get_program_clicks($program_id, $start_date, $end_date),
            'conversions' => $this->get_program_conversions($program_id, $start_date, $end_date),
            'earnings' => $this->get_program_earnings($program_id, $start_date, $end_date)
        ];
    }

    private function get_program_clicks($program_id, $start_date, $end_date) {
        global $wpdb;
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*)
            FROM {$wpdb->prefix}vigyapanam_clicks
            WHERE program_id = %d
            AND date_clicked BETWEEN %s AND %s",
            $program_id,
            $start_date,
            $end_date
        ));
    }

    private function get_program_conversions($program_id, $start_date, $end_date) {
        global $wpdb;
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*)
            FROM {$wpdb->prefix}vigyapanam_conversions
            WHERE program_id = %d
            AND conversion_date BETWEEN %s AND %s",
            $program_id,
            $start_date,
            $end_date
        ));
    }

    private function get_program_earnings($program_id, $start_date, $end_date) {
        global $wpdb;
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(earnings)
            FROM {$wpdb->prefix}vigyapanam_earnings
            WHERE program_id = %d
            AND date BETWEEN %s AND %s",
            $program_id,
            $start_date,
            $end_date
        ));
    }
}