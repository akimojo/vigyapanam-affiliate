<?php
namespace VigyapanamAffiliate\Core\Shortcodes;

class AuthShortcodes {
    public function __construct() {
        add_shortcode('vigyapanam_login_form', [$this, 'render_login_form']);
        add_action('wp_ajax_nopriv_vigyapanam_login', [$this, 'handle_login']);
    }

    public function render_login_form() {
        if (is_user_logged_in()) {
            return __('You are already logged in.', 'vigyapanam-affiliate');
        }

        ob_start();
        ?>
        <div class="vigyapanam-form">
            <form id="vigyapanam-login-form">
                <?php wp_nonce_field('vigyapanam_auth_nonce', 'auth_nonce'); ?>
                
                <div class="form-section">
                    <h3><?php _e('Login to Your Account', 'vigyapanam-affiliate'); ?></h3>
                    
                    <div class="form-group">
                        <label for="username"><?php _e('Username or Email', 'vigyapanam-affiliate'); ?></label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password"><?php _e('Password', 'vigyapanam-affiliate'); ?></label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" value="1">
                            <?php _e('Remember me', 'vigyapanam-affiliate'); ?>
                        </label>
                    </div>
                </div>

                <button type="submit" class="submit-button">
                    <?php _e('Login', 'vigyapanam-affiliate'); ?>
                </button>

                <p class="auth-link">
                    <?php _e('Don\'t have an account?', 'vigyapanam-affiliate'); ?>
                    <a href="<?php echo esc_url(home_url('/affiliate-registration/')); ?>">
                        <?php _e('Register here', 'vigyapanam-affiliate'); ?>
                    </a>
                </p>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    public function handle_login() {
        check_ajax_referer('vigyapanam_auth_nonce', 'auth_nonce');

        $username = sanitize_user($_POST['username']);
        $password = $_POST['password'];
        $remember = isset($_POST['remember']) ? (bool) $_POST['remember'] : false;

        if (empty($username) || empty($password)) {
            wp_send_json_error(__('All fields are required', 'vigyapanam-affiliate'));
        }

        // Check if username is actually an email
        if (is_email($username)) {
            $user = get_user_by('email', $username);
            if ($user) {
                $username = $user->user_login;
            }
        }

        $credentials = [
            'user_login' => $username,
            'user_password' => $password,
            'remember' => $remember
        ];

        $user = wp_signon($credentials);

        if (is_wp_error($user)) {
            wp_send_json_error($user->get_error_message());
        }

        // Check if user has affiliate role
        if (!in_array('affiliate', $user->roles)) {
            // Log them out if they're not an affiliate
            wp_logout();
            wp_send_json_error(__('Access denied. This login is only for affiliates.', 'vigyapanam-affiliate'));
        }

        wp_send_json_success([
            'message' => __('Login successful', 'vigyapanam-affiliate'),
            'redirect_url' => home_url('/affiliate-dashboard/')
        ]);
    }
}