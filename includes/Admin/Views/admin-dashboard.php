<?php
if (!defined('ABSPATH')) {
    exit;
}

$admin_info = wp_get_current_user();
?>

<div class="wrap vigyapanam-admin-dashboard">
    <h1><?php _e('Vigyapanam Affiliate Dashboard', 'vigyapanam-affiliate'); ?></h1>

    <!-- Admin Information Section -->
    <div class="admin-info-section">
        <h2><?php _e('Admin Information', 'vigyapanam-affiliate'); ?></h2>
        <div class="metrics-grid">
            <div class="metric-box">
                <h4><?php _e('Admin Name', 'vigyapanam-affiliate'); ?></h4>
                <p><?php echo esc_html($admin_info->display_name); ?></p>
            </div>
            <div class="metric-box">
                <h4><?php _e('Admin ID', 'vigyapanam-affiliate'); ?></h4>
                <p><?php echo esc_html($admin_info->ID); ?></p>
            </div>
            <div class="metric-box">
                <h4><?php _e('Access Level', 'vigyapanam-affiliate'); ?></h4>
                <p><?php echo implode(', ', array_map('translate_user_role', $admin_info->roles)); ?></p>
            </div>
        </div>
    </div>

    <!-- Client Management Section -->
    <?php 
    $client_management = new \VigyapanamAffiliate\Admin\Views\ClientManagement();
    $client_management->render(); 
    ?>

    <!-- Performance Tracking Section -->
    <div class="performance-tracking-section">
        <h2><?php _e('Performance Tracking', 'vigyapanam-affiliate'); ?></h2>
        <div class="metrics-grid">
            <div class="metric-box">
                <h4><?php _e('Total Sign-ups', 'vigyapanam-affiliate'); ?></h4>
                <p id="total-signups">0</p>
            </div>
            <div class="metric-box">
                <h4><?php _e('Traffic Clicks', 'vigyapanam-affiliate'); ?></h4>
                <p id="traffic-clicks">0</p>
            </div>
            <div class="metric-box">
                <h4><?php _e('Conversion Rate', 'vigyapanam-affiliate'); ?></h4>
                <p id="conversion-rate">0%</p>
            </div>
        </div>

        <form class="tracking-form">
            <?php wp_nonce_field('tracking_nonce', 'tracking_nonce'); ?>
            <h3><?php _e('Add Client Site Tracking', 'vigyapanam-affiliate'); ?></h3>
            <input type="url" name="site_url" placeholder="<?php esc_attr_e('Client Site URL', 'vigyapanam-affiliate'); ?>" required>
            <input type="text" name="tracking_id" placeholder="<?php esc_attr_e('Tracking ID', 'vigyapanam-affiliate'); ?>" required>
            <button type="submit" class="button button-primary">
                <?php _e('Add Tracking', 'vigyapanam-affiliate'); ?>
            </button>
        </form>
    </div>

    <!-- Freelancer Management Section -->
    <div class="freelancer-management-section">
        <h2><?php _e('Freelancer Management', 'vigyapanam-affiliate'); ?></h2>
        
        <!-- Top 5 Freelancers -->
        <div class="top-freelancers">
            <h3><?php _e('Top 5 Freelancers by Monthly Revenue', 'vigyapanam-affiliate'); ?></h3>
            <table>
                <thead>
                    <tr>
                        <th><?php _e('Name', 'vigyapanam-affiliate'); ?></th>
                        <th><?php _e('Total Earnings', 'vigyapanam-affiliate'); ?></th>
                        <th><?php _e('Total Clicks', 'vigyapanam-affiliate'); ?></th>
                    </tr>
                </thead>
                <tbody id="top-freelancers-list">
                    <!-- Populated via JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Ban Freelancer Form -->
        <form class="ban-form">
            <?php wp_nonce_field('freelancer_nonce', 'freelancer_nonce'); ?>
            <h3><?php _e('Ban Freelancer', 'vigyapanam-affiliate'); ?></h3>
            <select name="freelancer_id" required>
                <option value=""><?php _e('Select Freelancer', 'vigyapanam-affiliate'); ?></option>
                <?php
                $freelancers = get_users(['role' => 'freelancer']);
                foreach ($freelancers as $freelancer) {
                    echo sprintf(
                        '<option value="%d">%s</option>',
                        esc_attr($freelancer->ID),
                        esc_html($freelancer->display_name)
                    );
                }
                ?>
            </select>
            <textarea name="ban_reason" placeholder="<?php esc_attr_e('Reason for ban', 'vigyapanam-affiliate'); ?>" required></textarea>
            <button type="submit" class="button button-primary">
                <?php _e('Ban Freelancer', 'vigyapanam-affiliate'); ?>
            </button>
        </form>
    </div>
</div>