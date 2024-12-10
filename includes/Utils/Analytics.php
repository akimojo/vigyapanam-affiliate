<?php
namespace VigyapanamAffiliate\Utils;

class Analytics {
    public function get_click_details($freelancer_id, $start_date = null, $end_date = null) {
        global $wpdb;
        
        $query = "SELECT 
            c.ip_address,
            c.date_clicked,
            COALESCE(l.city, 'Unknown') as city,
            COALESCE(l.country, 'Unknown') as country,
            COALESCE(l.browser, 'Unknown') as browser,
            p.post_title as page_title,
            p.guid as page_url
        FROM {$wpdb->prefix}vigyapanam_clicks c
        LEFT JOIN {$wpdb->prefix}vigyapanam_locations l ON c.ip_address = l.ip_address
        LEFT JOIN {$wpdb->posts} p ON c.page_id = p.ID
        WHERE c.freelancer_id = %d";
        
        $params = [$freelancer_id];
        
        if ($start_date && $end_date) {
            $query .= " AND c.date_clicked BETWEEN %s AND %s";
            $params[] = $start_date;
            $params[] = $end_date;
        }
        
        $query .= " ORDER BY c.date_clicked DESC";
        
        return $wpdb->get_results($wpdb->prepare($query, $params));
    }

    public function get_location_stats($freelancer_id) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT 
                COALESCE(l.country, 'Unknown') as country,
                COUNT(*) as visits
            FROM {$wpdb->prefix}vigyapanam_clicks c
            LEFT JOIN {$wpdb->prefix}vigyapanam_locations l ON c.ip_address = l.ip_address
            WHERE c.freelancer_id = %d
            GROUP BY l.country
            ORDER BY visits DESC
            LIMIT 10",
            $freelancer_id
        ));
    }

    public function get_page_stats($freelancer_id) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT 
                p.post_title as page_title,
                COUNT(*) as visits,
                AVG(TIMESTAMPDIFF(SECOND, c.date_clicked, 
                    COALESCE(LEAD(c.date_clicked) OVER (PARTITION BY c.ip_address ORDER BY c.date_clicked), 
                    DATE_ADD(c.date_clicked, INTERVAL 5 MINUTE)))) as avg_time
            FROM {$wpdb->prefix}vigyapanam_clicks c
            LEFT JOIN {$wpdb->posts} p ON c.page_id = p.ID
            WHERE c.freelancer_id = %d
            GROUP BY p.ID
            ORDER BY visits DESC",
            $freelancer_id
        ));
    }

    public function get_time_stats($freelancer_id) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT 
                HOUR(date_clicked) as hour,
                COUNT(*) as visits
            FROM {$wpdb->prefix}vigyapanam_clicks
            WHERE freelancer_id = %d
            GROUP BY HOUR(date_clicked)
            ORDER BY hour",
            $freelancer_id
        ));
    }
}