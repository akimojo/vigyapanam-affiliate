<?php
namespace VigyapanamAffiliate\Admin\Views\Components;

class SignupStats {
    private $signup_controller;

    public function __construct() {
        $this->signup_controller = new \VigyapanamAffiliate\Admin\Controllers\SignupController();
    }

    public function render() {
        $recent_signups = $this->signup_controller->get_recent_signups();
        $total_signups = $this->signup_controller->get_total_signups();
        ?>
        <div class="signup-stats-section">
            <h2><?php _e('Affiliate Signups', 'vigyapanam-affiliate'); ?></h2>
            
            <div class="metrics-grid">
                <div class="metric-box">
                    <h4><?php _e('Total Affiliates', 'vigyapanam-affiliate'); ?></h4>
                    <p><?php echo esc_html($total_signups); ?></p>
                </div>
            </div>

            <div class="recent-signups">
                <h3><?php _e('Recent Signups', 'vigyapanam-affiliate'); ?></h3>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Name', 'vigyapanam-affiliate'); ?></th>
                            <th><?php _e('Username', 'vigyapanam-affiliate'); ?></th>
                            <th><?php _e('Email', 'vigyapanam-affiliate'); ?></th>
                            <th><?php _e('Mobile', 'vigyapanam-affiliate'); ?></th>
                            <th><?php _e('Date', 'vigyapanam-affiliate'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_signups)): ?>
                            <tr>
                                <td colspan="5"><?php _e('No recent signups', 'vigyapanam-affiliate'); ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_signups as $signup): ?>
                                <tr>
                                    <td><?php echo esc_html($signup['name']); ?></td>
                                    <td><?php echo esc_html($signup['username']); ?></td>
                                    <td><?php echo esc_html($signup['email']); ?></td>
                                    <td><?php echo esc_html($signup['mobile']); ?></td>
                                    <td><?php echo date_i18n(get_option('date_format'), strtotime($signup['date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="signup-chart">
                <h3><?php _e('Signup Trends', 'vigyapanam-affiliate'); ?></h3>
                <canvas id="signup-trend-chart"></canvas>
            </div>
        </div>
        <?php
    }
}