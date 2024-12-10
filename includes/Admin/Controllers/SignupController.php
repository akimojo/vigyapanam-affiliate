<?php
namespace VigyapanamAffiliate\Admin\Controllers;

class SignupController {
    public function get_recent_signups($limit = 5) {
        $args = [
            'role' => 'affiliate',
            'orderby' => 'registered',
            'order' => 'DESC',
            'number' => $limit,
            'fields' => ['ID', 'user_login', 'user_email', 'user_registered']
        ];

        $users = get_users($args);
        $signups = [];

        foreach ($users as $user) {
            $signups[] = [
                'id' => $user->ID,
                'username' => $user->user_login,
                'email' => $user->user_email,
                'date' => $user->user_registered,
                'name' => get_user_meta($user->ID, 'vigyapanam_name', true),
                'mobile' => get_user_meta($user->ID, 'vigyapanam_mobile', true)
            ];
        }

        return $signups;
    }

    public function get_total_signups() {
        $args = [
            'role' => 'affiliate',
            'count_total' => true,
        ];

        return count_users()['avail_roles']['affiliate'] ?? 0;
    }

    public function get_signups_by_period($period = '30') {
        global $wpdb;
        
        $date = date('Y-m-d', strtotime("-{$period} days"));
        
        $query = $wpdb->prepare(
            "SELECT COUNT(*) as count, DATE(user_registered) as date
            FROM {$wpdb->users} u
            INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
            WHERE um.meta_key = %s
            AND um.meta_value = %s
            AND u.user_registered >= %s
            GROUP BY DATE(user_registered)
            ORDER BY date ASC",
            $wpdb->prefix . 'capabilities',
            'a:1:{s:9:"affiliate";b:1;}',
            $date
        );

        return $wpdb->get_results($query);
    }
}