<?php
namespace VigyapanamAffiliate\Frontend;

class FreelancerDashboard {
    public function __construct() {
        add_action('init', [$this, 'register_shortcodes']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function register_shortcodes() {
        add_shortcode('vigyapanam_freelancer_dashboard', [$this, 'render_dashboard']);
    }

    public function enqueue_scripts() {
        wp_enqueue_style('vigyapanam-affiliate-style');
        wp_enqueue_script('chart-js');
        wp_enqueue_script('vigyapanam-affiliate-dashboard');
    }

    public function render_dashboard() {
        if (!is_user_logged_in()) {
            return __('Please log in to view your dashboard.', 'vigyapanam-affiliate');
        }

        $user_id = get_current_user_id();
        $profile_data = $this->get_profile_data($user_id);
        $analytics_data = $this->get_analytics_data($user_id);

        ob_start();
        ?>
        <div class="freelancer-dashboard">
            <div class="profile-section">
                <h2><?php _e('Profile Details', 'vigyapanam-affiliate'); ?></h2>
                <div class="profile-info">
                    <p><strong><?php _e('Name:', 'vigyapanam-affiliate'); ?></strong> <?php echo esc_html($profile_data['name']); ?></p>
                    <p><strong><?php _e('ID:', 'vigyapanam-affiliate'); ?></strong> <?php echo esc_html($profile_data['id']); ?></p>
                    <p><strong><?php _e('Company:', 'vigyapanam-affiliate'); ?></strong> <?php echo esc_html($profile_data['company']); ?></p>
                    <p><strong><?php _e('Revenue Model:', 'vigyapanam-affiliate'); ?></strong> <?php echo esc_html($profile_data['revenue_model']); ?></p>
                </div>
            </div>

            <div class="analytics-section">
                <h2><?php _e('Analytics', 'vigyapanam-affiliate'); ?></h2>
                <div class="analytics-grid">
                    <div class="stat-box">
                        <h3><?php _e('Total Traffic', 'vigyapanam-affiliate'); ?></h3>
                        <p><?php echo esc_html($analytics_data['total_traffic']); ?></p>
                    </div>
                    <div class="stat-box">
                        <h3><?php _e('Average RPM', 'vigyapanam-affiliate'); ?></h3>
                        <p><?php echo esc_html($analytics_data['avg_rpm']); ?></p>
                    </div>
                    <div class="stat-box">
                        <h3><?php _e('Total Earnings', 'vigyapanam-affiliate'); ?></h3>
                        <p><?php echo esc_html($analytics_data['total_earnings']); ?></p>
                    </div>
                </div>

                <div class="analytics-charts">
                    <canvas id="revenue-chart"></canvas>
                    <canvas id="traffic-chart"></canvas>
                    <canvas id="rpm-chart"></canvas>
                </div>
            </div>

            <div class="withdrawal-section">
                <h2><?php _e('Withdrawal Options', 'vigyapanam-affiliate'); ?></h2>
                <?php $this->render_withdrawal_form(); ?>
            </div>

            <div class="terms-section">
                <h2><?php _e('Terms and Conditions', 'vigyapanam-affiliate'); ?></h2>
                <?php $this->render_terms_and_conditions(); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_profile_data($user_id) {
        return [
            'name' => get_user_meta($user_id, 'vigyapanam_name', true),
            'id' => $user_id,
            'company' => get_user_meta($user_id, 'vigyapanam_company', true),
            'revenue_model' => get_user_meta($user_id, 'vigyapanam_revenue_model', true),
        ];
    }

    private function get_analytics_data($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vigyapanam_earnings';
        
        $results = $wpdb->get_row($wpdb->prepare(
            "SELECT 
                SUM(traffic) as total_traffic,
                AVG(rpm) as avg_rpm,
                SUM(earnings) as total_earnings
            FROM $table_name
            WHERE freelancer_id = %d",
            $user_id
        ));

        return [
            'total_traffic' => $results->total_traffic ?? 0,
            'avg_rpm' => number_format($results->avg_rpm ?? 0, 2),
            'total_earnings' => number_format($results->total_earnings ?? 0, 2),
        ];
    }

    private function render_withdrawal_form() {
        ?>
        <form id="withdrawal-form" class="withdrawal-form">
            <input type="number" name="amount" placeholder="<?php _e('Amount to withdraw', 'vigyapanam-affiliate'); ?>" required>
            <select name="payment_method" required>
                <option value="upi"><?php _e('UPI', 'vigyapanam-affiliate'); ?></option>
                <option value="bank"><?php _e('Bank Transfer', 'vigyapanam-affiliate'); ?></option>
            </select>
            <button type="submit"><?php _e('Request Withdrawal', 'vigyapanam-affiliate'); ?></button>
        </form>
        <?php
    }

    private function render_terms_and_conditions() {
        $terms = get_option('vigyapanam_affiliate_terms');
        echo wp_kses_post($terms);
    }
}