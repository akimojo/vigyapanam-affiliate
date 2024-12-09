<?php
namespace VigyapanamAffiliate\Frontend\Controllers;

use VigyapanamAffiliate\Utils\EmailManager;

class WithdrawalController {
    private $email_manager;
    private $minimum_withdrawal = 1000; // Minimum withdrawal amount in INR

    public function __construct() {
        $this->email_manager = new EmailManager();
        add_action('wp_ajax_request_withdrawal', [$this, 'request_withdrawal']);
    }

    public function request_withdrawal() {
        if (!is_user_logged_in()) {
            wp_send_json_error('User not logged in');
        }

        check_ajax_referer('withdrawal_nonce', 'nonce');

        $user_id = get_current_user_id();
        $amount = floatval($_POST['amount']);
        $payment_method = sanitize_text_field($_POST['payment_method']);

        // Validate withdrawal amount
        if ($amount < $this->minimum_withdrawal) {
            wp_send_json_error(sprintf(
                __('Minimum withdrawal amount is ₹%s', 'vigyapanam-affiliate'),
                number_format($this->minimum_withdrawal, 2)
            ));
        }

        // Check available balance
        $available_balance = $this->get_available_balance($user_id);
        if ($amount > $available_balance) {
            wp_send_json_error(__('Insufficient balance', 'vigyapanam-affiliate'));
        }

        // Record withdrawal request
        $request_id = $this->record_withdrawal_request($user_id, $amount, $payment_method);
        if (!$request_id) {
            wp_send_json_error(__('Failed to process withdrawal request', 'vigyapanam-affiliate'));
        }

        // Send notification emails
        $this->send_withdrawal_notifications($user_id, $amount, $payment_method, $request_id);

        wp_send_json_success([
            'message' => __('Withdrawal request submitted successfully', 'vigyapanam-affiliate'),
            'request_id' => $request_id
        ]);
    }

    private function get_available_balance($user_id) {
        global $wpdb;
        
        // Get total earnings
        $total_earnings = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(earnings) 
            FROM {$wpdb->prefix}vigyapanam_earnings 
            WHERE freelancer_id = %d",
            $user_id
        ));

        // Get total withdrawals
        $total_withdrawals = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(amount) 
            FROM {$wpdb->prefix}vigyapanam_withdrawals 
            WHERE freelancer_id = %d 
            AND status IN ('completed', 'pending')",
            $user_id
        ));

        return floatval($total_earnings) - floatval($total_withdrawals);
    }

    private function record_withdrawal_request($user_id, $amount, $payment_method) {
        global $wpdb;
        
        $result = $wpdb->insert(
            $wpdb->prefix . 'vigyapanam_withdrawals',
            [
                'freelancer_id' => $user_id,
                'amount' => $amount,
                'payment_method' => $payment_method,
                'status' => 'pending',
                'request_date' => current_time('mysql')
            ],
            ['%d', '%f', '%s', '%s', '%s']
        );

        return $result ? $wpdb->insert_id : false;
    }

    private function send_withdrawal_notifications($user_id, $amount, $payment_method, $request_id) {
        $user = get_userdata($user_id);
        
        // Send email to user
        $this->email_manager->send_withdrawal_request_confirmation(
            $user->user_email,
            $user->display_name,
            $amount,
            $payment_method,
            $request_id
        );

        // Send email to admin
        $admin_email = get_option('admin_email');
        $this->email_manager->send_withdrawal_request_notification(
            $admin_email,
            $user->display_name,
            $amount,
            $payment_method,
            $request_id
        );
    }
}