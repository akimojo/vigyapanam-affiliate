<?php
namespace VigyapanamAffiliate\Core\Shortcodes;

class CombinedRegistrationShortcode {
    public function __construct() {
        add_shortcode('vigyapanam_combined_register', [$this, 'render_combined_form']);
    }

    public function render_combined_form() {
        if (is_user_logged_in()) {
            return __('You are already registered and logged in.', 'vigyapanam-affiliate');
        }

        ob_start();
        ?>
        <div class="vigyapanam-form">
            <form id="vigyapanam-combined-register-form">
                <?php wp_nonce_field('vigyapanam_combined_nonce', 'combined_nonce'); ?>

                <div class="form-section">
                    <h3><?php _e('Account Information', 'vigyapanam-affiliate'); ?></h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="username"><?php _e('Username', 'vigyapanam-affiliate'); ?></label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email"><?php _e('Email Address', 'vigyapanam-affiliate'); ?></label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password"><?php _e('Password', 'vigyapanam-affiliate'); ?></label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password"><?php _e('Confirm Password', 'vigyapanam-affiliate'); ?></label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><?php _e('Personal Information', 'vigyapanam-affiliate'); ?></h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name"><?php _e('Full Name', 'vigyapanam-affiliate'); ?></label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile"><?php _e('Mobile Number', 'vigyapanam-affiliate'); ?></label>
                            <input type="tel" id="mobile" name="mobile" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="followers"><?php _e('Number of Followers', 'vigyapanam-affiliate'); ?></label>
                            <input type="number" id="followers" name="followers" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><?php _e('Social Media Links', 'vigyapanam-affiliate'); ?></h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="linkedin"><?php _e('LinkedIn Profile URL', 'vigyapanam-affiliate'); ?></label>
                            <input type="url" id="linkedin" name="linkedin" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="instagram"><?php _e('Instagram Profile URL', 'vigyapanam-affiliate'); ?></label>
                            <input type="url" id="instagram" name="instagram" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="facebook"><?php _e('Facebook Profile URL', 'vigyapanam-affiliate'); ?></label>
                            <input type="url" id="facebook" name="facebook" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="youtube"><?php _e('YouTube Channel URL', 'vigyapanam-affiliate'); ?></label>
                            <input type="url" id="youtube" name="youtube" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><?php _e('Payment Information', 'vigyapanam-affiliate'); ?></h3>
                    <div class="form-group">
                        <label for="upi"><?php _e('UPI ID', 'vigyapanam-affiliate'); ?></label>
                        <input type="text" id="upi" name="upi" class="form-control" required>
                    </div>
                </div>

                <div class="form-section">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        <?php _e('I agree to the Terms and Conditions', 'vigyapanam-affiliate'); ?>
                    </label>
                </div>

                <button type="submit" class="submit-button">
                    <?php _e('Register as Affiliate', 'vigyapanam-affiliate'); ?>
                </button>

                <p class="auth-link">
                    <?php _e('Already have an account?', 'vigyapanam-affiliate'); ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                        <?php _e('Login here', 'vigyapanam-affiliate'); ?>
                    </a>
                </p>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
}