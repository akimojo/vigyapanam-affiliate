<?php
namespace VigyapanamAffiliate\Admin\Views\Components;

class FreelancerStats {
    public function render() {
        $stats = $this->get_freelancer_stats();
        ?>
        <div class="freelancer-stats-section">
            <h3><?php _e('Affiliate Performance Overview', 'vigyapanam-affiliate'); ?></h3>
            
            <div class="metrics-grid">
                <div class="metric-box">
                    <h4><?php _e('Total Clicks', 'vigyapanam-affiliate'); ?></h4>
                    <p><?php echo number_format($stats['total_clicks']); ?></p>
                </div>
                <div class="metric-box">
                    <h4><?php _e('Total Earnings', 'vigyapanam-affiliate'); ?></h4>
                    <p>₹<?php echo number_format($stats['total_earnings'], 2); ?></p>
                </div>
                <div class="metric-box">
                    <h4><?php _e('Average RPM', 'vigyapanam-affiliate'); ?></h4>
                    <p>₹<?php echo number_format($stats['avg_rpm'], 2); ?></p>
                </div>
            </div>

            <div class="performance-chart">
                <canvas id="performance-trend-chart"></canvas>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            const ctx = document.getElementById('performance-trend-chart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($stats['dates']); ?>,
                    datasets: [{
                        label: '<?php _e('Clicks', 'vigyapanam-affiliate'); ?>',
                        data: <?php echo json_encode($stats['daily_clicks']); ?>,
                        borderColor: '#0073aa',
                        yAxisID: 'y-clicks'
                    }, {
                        label: '<?php _e('Earnings (₹)', 'vigyapanam-affiliate'); ?>',
                        data: <?php echo json_encode($stats['daily_earnings']); ?>,
                        borderColor: '#28a745',
                        yAxisID: 'y-earnings'
                    }]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        'y-clicks': {
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: '<?php _e('Clicks', 'vigyapanam-affiliate'); ?>'
                            }
                        },
                        'y-earnings': {
                            type: 'linear',
                            position: 'right',
                            title: {
                                display: true,
                                text: '<?php _e('Earnings (₹)', 'vigyapanam-affiliate'); ?>'
                            }
                        }
                    }
                }
            });
        });
        </script>
        <?php
    }

    private function get_freelancer_stats() {
        global $wpdb;
        
        // Get last 30 days of data
        $start_date = date('Y-m-d', strtotime('-30 days'));
        $end_date = date('Y-m-d');

        // Get total stats
        $total_stats = $wpdb->get_row($wpdb->prepare(
            "SELECT 
                COUNT(*) as total_clicks,
                SUM(earnings) as total_earnings,
                AVG(rpm) as avg_rpm
            FROM {$wpdb->prefix}vigyapanam_earnings
            WHERE date >= %s",
            $start_date
        ));

        // Get daily stats
        $daily_stats = $wpdb->get_results($wpdb->prepare(
            "SELECT 
                DATE(date) as date,
                COUNT(*) as clicks,
                SUM(earnings) as earnings
            FROM {$wpdb->prefix}vigyapanam_earnings
            WHERE date >= %s
            GROUP BY DATE(date)
            ORDER BY date ASC",
            $start_date
        ));

        // Prepare daily data
        $dates = [];
        $daily_clicks = [];
        $daily_earnings = [];

        foreach ($daily_stats as $stat) {
            $dates[] = date('M j', strtotime($stat->date));
            $daily_clicks[] = (int)$stat->clicks;
            $daily_earnings[] = round((float)$stat->earnings, 2);
        }

        return [
            'total_clicks' => (int)$total_stats->total_clicks,
            'total_earnings' => round((float)$total_stats->total_earnings, 2),
            'avg_rpm' => round((float)$total_stats->avg_rpm, 2),
            'dates' => $dates,
            'daily_clicks' => $daily_clicks,
            'daily_earnings' => $daily_earnings
        ];
    }
}