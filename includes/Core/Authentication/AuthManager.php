<?php
namespace VigyapanamAffiliate\Core\Authentication;

class AuthManager {
    public function __construct() {
        add_action('init', [$this, 'redirect_to_myaccount']);
        add_filter('woocommerce_login_redirect', [$this, 'affiliate_login_redirect'], 10, 2);
        add_action('wp_ajax_nopriv_vigyapanam_register', [$this, 'handle_registration']);
        add_action('woocommerce_login_form_end', [$this, 'add_affiliate_register_link']);
    }

    public function redirect_to_myaccount() {
        global $pagenow;
        
        // Redirect affiliate login page to WooCommerce My Account
        if (is_page('affiliate-login')) {
            wp_safe_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }
    }

    public function add_affiliate_register_link() {
        echo '<div class="affiliate-register-link">';
        echo __('Want to become an affiliate? ', 'vigyapanam-affiliate');
        echo '<a href="' . esc_url(home_url('/affiliate-registration/')) . '">';
        echo __('Register here', 'vigyapanam-affiliate');
        echo '</a></div>';
    }

    
    public function handle_registration() {
        check_ajax_referer('vigyapanam_auth_nonce', 'nonce');

        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];

        if (empty($username) || empty($email) || empty($password)) {
            wp_send_json_error(__('All fields are required', 'vigyapanam-affiliate'));
        }

        if (!is_email($email)) {
            wp_send_json_error(__('Invalid email address', 'vigyapanam-affiliate'));
        }

        if (username_exists($username)) {
            wp_send_json_error(__('Username already exists', 'vigyapanam-affiliate'));
        }

        if (email_exists($email)) {
            wp_send_json_error(__('Email already exists', 'vigyapanam-affiliate'));
        }

        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            wp_send_json_error($user_id->get_error_message());
        }

        // Set user role to affiliate
        $user = new \WP_User($user_id);
        $user->set_role('affiliate');

        // Save additional user meta
        $meta_fields = [
            'name' => sanitize_text_field($_POST['name']),
            'mobile' => sanitize_text_field($_POST['mobile']),
            'followers' => intval($_POST['followers']),
            'linkedin' => esc_url_raw($_POST['linkedin']),
            'instagram' => esc_url_raw($_POST['instagram']),
            'facebook' => esc_url_raw($_POST['facebook']),
            'youtube' => esc_url_raw($_POST['youtube']),
            'upi' => sanitize_text_field($_POST['upi'])
        ];

        foreach ($meta_fields as $key => $value) {
            update_user_meta($user_id, 'vigyapanam_' . $key, $value);
        }

        // Auto login after registration
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);

        wp_send_json_success([
            'message' => __('Registration successful', 'vigyapanam-affiliate'),
            'redirect_url' => wc_get_account_endpoint_url('affiliate-dashboard')
        ]);
    }
}