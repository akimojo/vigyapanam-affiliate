<?php
namespace VigyapanamAffiliate\Admin\Views\Components;

class FreelancerList {
    public function render() {
        $freelancers = $this->get_freelancers();
        ?>
        <div class="freelancer-list-section">
            <h3><?php _e('Affiliate Performance', 'vigyapanam-affiliate'); ?></h3>
            
            <!-- Date Range Filter -->
            <div class="date-range-filter">
                <input type="date" id="start_date" name="start_date">
                <input type="date" id="end_date" name="end_date">
                <button class="button" onclick="filterFreelancerData()"><?php _e('Filter', 'vigyapanam-affiliate'); ?></button>
            </div>

            <!-- Freelancer Accordion -->
            <div class="freelancer-accordion">
                <?php foreach ($freelancers as $freelancer): ?>
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <span class="name"><?php echo esc_html($freelancer['name']); ?></span>
                            <span class="stats">
                                <?php echo sprintf(
                                    __('Clicks: %d | Earnings: ₹%s', 'vigyapanam-affiliate'),
                                    $freelancer['clicks'],
                                    number_format($freelancer['earnings'], 2)
                                ); ?>
                            </span>
                        </div>
                        <div class="accordion-content">
                            <!-- Traffic Details -->
                            <div class="traffic-details">
                                <h4><?php _e('Traffic Details', 'vigyapanam-affiliate'); ?></h4>
                                <table class="widefat">
                                    <thead>
                                        <tr>
                                            <th><?php _e('Page', 'vigyapanam-affiliate'); ?></th>
                                            <th><?php _e('Visits', 'vigyapanam-affiliate'); ?></th>
                                            <th><?php _e('Location', 'vigyapanam-affiliate'); ?></th>
                                            <th><?php _e('Date', 'vigyapanam-affiliate'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($freelancer['traffic'] as $visit): ?>
                                            <tr>
                                                <td><?php echo esc_html($visit->page_title); ?></td>
                                                <td><?php echo esc_html($visit->visits); ?></td>
                                                <td><?php echo esc_html($visit->location); ?></td>
                                                <td><?php echo esc_html($visit->date); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Charts -->
                            <div class="analytics-charts">
                                <canvas id="traffic-chart-<?php echo esc_attr($freelancer['id']); ?>"></canvas>
                                <canvas id="location-chart-<?php echo esc_attr($freelancer['id']); ?>"></canvas>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <style>
            .freelancer-accordion {
                margin-top: 20px;
            }
            .accordion-item {
                border: 1px solid #ddd;
                margin-bottom: 10px;
            }
            .accordion-header {
                padding: 15px;
                background: #f8f9fa;
                cursor: pointer;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .accordion-content {
                padding: 20px;
                display: none;
            }
            .accordion-content.active {
                display: block;
            }
            .date-range-filter {
                margin-bottom: 20px;
            }
            .analytics-charts {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-top: 20px;
            }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Accordion functionality
            $('.accordion-header').click(function() {
                $(this).next('.accordion-content').slideToggle();
            });

            // Initialize charts for each freelancer
            <?php foreach ($freelancers as $freelancer): ?>
                initializeFreelancerCharts(<?php echo json_encode($freelancer); ?>);
            <?php endforeach; ?>
        });

        function initializeFreelancerCharts(freelancer) {
            // Traffic chart
            new Chart(document.getElementById('traffic-chart-' + freelancer.id), {
                type: 'line',
                data: {
                    labels: freelancer.traffic_data.dates,
                    datasets: [{
                        label: 'Traffic',
                        data: freelancer.traffic_data.visits,
                        borderColor: '#0073aa'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Location chart
            new Chart(document.getElementById('location-chart-' + freelancer.id), {
                type: 'pie',
                data: {
                    labels: freelancer.location_data.labels,
                    datasets: [{
                        data: freelancer.location_data.values,
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        function filterFreelancerData() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            // AJAX call to get filtered data
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'filter_freelancer_data',
                    start_date: startDate,
                    end_date: endDate,
                    nonce: vigyapanamAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update the UI with filtered data
                        updateFreelancerList(response.data);
                    }
                }
            });
        }
        </script>
        <?php
    }

    private function get_freelancers() {
        $args = [
            'role' => 'affiliate',
            'orderby' => 'registered',
            'order' => 'DESC'
        ];

        $users = get_users($args);
        $analytics = new \VigyapanamAffiliate\Utils\Analytics();
        $freelancers = [];

        foreach ($users as $user) {
            $freelancers[] = [
                'id' => $user->ID,
                'name' => get_user_meta($user->ID, 'vigyapanam_name', true),
                'clicks' => $this->get_total_clicks($user->ID),
                'earnings' => $this->get_total_earnings($user->ID),
                'traffic' => $analytics->get_click_details($user->ID),
                'traffic_data' => $this->get_traffic_data($user->ID),
                'location_data' => $this->get_location_data($user->ID)
            ];
        }

        return $freelancers;
    }

    private function get_total_clicks($user_id) {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}vigyapanam_clicks WHERE freelancer_id = %d",
            $user_id
        ));
    }

    private function get_total_earnings($user_id) {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(earnings) FROM {$wpdb->prefix}vigyapanam_earnings WHERE freelancer_id = %d",
            $user_id
        )) ?? 0;
    }

    private function get_traffic_data($user_id) {
        $analytics = new \VigyapanamAffiliate\Utils\Analytics();
        $clicks = $analytics->get_click_details($user_id);
        
        $dates = [];
        $visits = [];
        
        foreach ($clicks as $click) {
            $date = date('Y-m-d', strtotime($click->date_clicked));
            if (!isset($visits[$date])) {
                $visits[$date] = 0;
            }
            $visits[$date]++;
        }

        return [
            'dates' => array_keys($visits),
            'visits' => array_values($visits)
        ];
    }

    private function get_location_data($user_id) {
        $analytics = new \VigyapanamAffiliate\Utils\Analytics();
        $locations = $analytics->get_location_stats($user_id);
        
        return [
            'labels' => array_column($locations, 'country'),
            'values' => array_column($locations, 'visits')
        ];
    }
}