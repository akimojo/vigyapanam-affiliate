<?php
namespace VigyapanamAffiliate\Utils;

class EmailManager {
    public function __construct() {
        add_filter('wp_mail_content_type', [$this, 'set_html_content_type']);
    }

    public function set_html_content_type() {
        return 'text/html';
    }

    public function send_welcome_email($user_email, $user_name) {
        $subject = __('Welcome to Vigyapanam Affiliate Program', 'vigyapanam-affiliate');
        $message = $this->get_welcome_email_template($user_name);
        
        return wp_mail($user_email, $subject, $message);
    }

    public function send_ban_notification($user_email, $user_name, $reason) {
        $subject = __('Account Status Update - Vigyapanam Affiliate Program', 'vigyapanam-affiliate');
        $message = $this->get_ban_email_template($user_name, $reason);
        
        return wp_mail($user_email, $subject, $message);
    }

    private function get_welcome_email_template($user_name) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #f8f9fa; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { text-align: center; padding: 20px; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1><?php _e('Welcome to Vigyapanam!', 'vigyapanam-affiliate'); ?></h1>
                </div>
                <div class="content">
                    <p><?php printf(__('Dear %s,', 'vigyapanam-affiliate'), esc_html($user_name)); ?></p>
                    <p><?php _e('Welcome to the Vigyapanam Affiliate Program! We\'re excited to have you on board.', 'vigyapanam-affiliate'); ?></p>
                    <p><?php _e('Here\'s what you can do next:', 'vigyapanam-affiliate'); ?></p>
                    <ul>
                        <li><?php _e('Complete your profile', 'vigyapanam-affiliate'); ?></li>
                        <li><?php _e('Browse available affiliate programs', 'vigyapanam-affiliate'); ?></li>
                        <li><?php _e('Start promoting and earning', 'vigyapanam-affiliate'); ?></li>
                    </ul>
                    <p><?php _e('If you have any questions, feel free to contact our support team.', 'vigyapanam-affiliate'); ?></p>
                </div>
                <div class="footer">
                    <p><?php _e('Best regards,', 'vigyapanam-affiliate'); ?><br>Vigyapanam Team</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    private function get_ban_email_template($user_name, $reason) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #f8f9fa; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { text-align: center; padding: 20px; font-size: 12px; }
                .reason { background: #fff3cd; padding: 15px; margin: 15px 0; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1><?php _e('Account Status Update', 'vigyapanam-affiliate'); ?></h1>
                </div>
                <div class="content">
                    <p><?php printf(__('Dear %s,', 'vigyapanam-affiliate'), esc_html($user_name)); ?></p>
                    <p><?php _e('We regret to inform you that your affiliate account has been temporarily suspended.', 'vigyapanam-affiliate'); ?></p>
                    <div class="reason">
                        <strong><?php _e('Reason:', 'vigyapanam-affiliate'); ?></strong>
                        <p><?php echo esc_html($reason); ?></p>
                    </div>
                    <p><?php _e('If you believe this is a mistake or would like to appeal this decision, please contact our support team.', 'vigyapanam-affiliate'); ?></p>
                </div>
                <div class="footer">
                    <p><?php _e('Best regards,', 'vigyapanam-affiliate'); ?><br>Vigyapanam Team</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}