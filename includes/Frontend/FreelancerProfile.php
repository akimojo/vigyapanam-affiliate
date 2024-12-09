<?php
namespace VigyapanamAffiliate\Frontend;

class FreelancerProfile {
    public function __construct() {
        add_action('init', [$this, 'register_shortcodes']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_save_freelancer_profile', [$this, 'save_freelancer_profile']);
    }

    public function register_shortcodes() {
        add_shortcode('vigyapanam_freelancer_profile', [$this, 'render_profile_form']);
    }

    public function enqueue_scripts() {
        wp_enqueue_style('vigyapanam-affiliate-style');
        wp_enqueue_script('vigyapanam-affiliate-profile');
    }

    public function render_profile_form() {
        ob_start();
        ?>
        <form id="freelancer-profile-form" class="vigyapanam-form">
            <div class="form-section">
                <h3><?php _e('Personal Information', 'vigyapanam-affiliate'); ?></h3>
                <input type="text" name="name" placeholder="<?php _e('Full Name', 'vigyapanam-affiliate'); ?>" required>
                <input type="email" name="email" placeholder="<?php _e('Email Address', 'vigyapanam-affiliate'); ?>" required>
                <input type="tel" name="mobile" placeholder="<?php _e('Mobile Number', 'vigyapanam-affiliate'); ?>" required>
                <input type="number" name="followers" placeholder="<?php _e('Number of Followers', 'vigyapanam-affiliate'); ?>" required>
            </div>

            <div class="form-section">
                <h3><?php _e('Social Media Links', 'vigyapanam-affiliate'); ?></h3>
                <input type="url" name="linkedin" placeholder="<?php _e('LinkedIn Profile URL', 'vigyapanam-affiliate'); ?>">
                <input type="url" name="instagram" placeholder="<?php _e('Instagram Profile URL', 'vigyapanam-affiliate'); ?>">
                <input type="url" name="facebook" placeholder="<?php _e('Facebook Profile URL', 'vigyapanam-affiliate'); ?>">
                <input type="url" name="youtube" placeholder="<?php _e('YouTube Channel URL', 'vigyapanam-affiliate'); ?>">
            </div>

            <div class="form-section">
                <h3><?php _e('Payment Information', 'vigyapanam-affiliate'); ?></h3>
                <input type="text" name="upi" placeholder="<?php _e('UPI ID', 'vigyapanam-affiliate'); ?>" required>
            </div>

            <div class="form-section">
                <label>
                    <input type="checkbox" name="terms" required>
                    <?php _e('I agree to the Terms and Conditions', 'vigyapanam-affiliate'); ?>
                </label>
            </div>

            <button type="submit" class="submit-button">
                <?php _e('Register as Freelancer', 'vigyapanam-affiliate'); ?>
            </button>
        </form>
        <?php
        return ob_get_clean();
    }

    public function save_freelancer_profile() {
        if (!wp_verify_nonce($_POST['nonce'], 'freelancer_profile_nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error('User not logged in');
        }

        // Sanitize and save user data
        $data = [
            'name' => sanitize_text_field($_POST['name']),
            'email' => sanitize_email($_POST['email']),
            'mobile' => sanitize_text_field($_POST['mobile']),
            'followers' => intval($_POST['followers']),
            'linkedin' => esc_url_raw($_POST['linkedin']),
            'instagram' => esc_url_raw($_POST['instagram']),
            'facebook' => esc_url_raw($_POST['facebook']),
            'youtube' => esc_url_raw($_POST['youtube']),
            'upi' => sanitize_text_field($_POST['upi']),
        ];

        // Save to user meta
        foreach ($data as $key => $value) {
            update_user_meta($user_id, 'vigyapanam_' . $key, $value);
        }

        // Send welcome email
        $this->send_welcome_email($data['email'], $data['name']);

        wp_send_json_success('Profile saved successfully');
    }

    private function send_welcome_email($email, $name) {
        $subject = __('Welcome to Vigyapanam Affiliate Program', 'vigyapanam-affiliate');
        $message = sprintf(
            __('Dear %s,

Welcome to the Vigyapanam Affiliate Program! We\'re excited to have you on board.

Your account has been successfully created, and you can now start promoting our affiliate programs.

Best regards,
Vigyapanam Team', 'vigyapanam-affiliate'),
            $name
        );

        wp_mail($email, $subject, $message);
    }
}