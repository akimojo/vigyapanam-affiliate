<?php
if (!defined('ABSPATH')) {
    exit;
}

$admin_info = wp_get_current_user();
$signup_stats = new \VigyapanamAffiliate\Admin\Views\Components\SignupStats();
$freelancer_list = new \VigyapanamAffiliate\Admin\Views\Components\FreelancerList();
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

    <!-- Signup Stats Section -->
    <?php $signup_stats->render(); ?>

    <!-- Freelancer List Section -->
    <?php $freelancer_list->render(); ?>
</div>