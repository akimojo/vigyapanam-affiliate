<?php
namespace VigyapanamAffiliate\Admin\Views;

class PluginDetails {
    public function render() {
        ?>
        <div class="wrap vigyapanam-plugin-details">
            <h1><?php _e('Vigyapanam Affiliate Manager - Usage Guide', 'vigyapanam-affiliate'); ?></h1>

            <div class="card">
                <h2><?php _e('Quick Start Guide', 'vigyapanam-affiliate'); ?></h2>
                <p><?php _e('Follow these steps to set up your affiliate program:', 'vigyapanam-affiliate'); ?></p>
                <ol>
                    <li><?php _e('Add the registration form to any page using shortcode:', 'vigyapanam-affiliate'); ?>
                        <code>[vigyapanam_combined_register]</code>
                    </li>
                    <li><?php _e('Add the affiliate dashboard to a page using shortcode:', 'vigyapanam-affiliate'); ?>
                        <code>[vigyapanam_freelancer_dashboard]</code>
                    </li>
                </ol>
            </div>

            <div class="card">
                <h2><?php _e('Available Shortcodes', 'vigyapanam-affiliate'); ?></h2>
                <table class="widefat">
                    <thead>
                        <tr>
                            <th><?php _e('Shortcode', 'vigyapanam-affiliate'); ?></th>
                            <th><?php _e('Description', 'vigyapanam-affiliate'); ?></th>
                            <th><?php _e('Usage', 'vigyapanam-affiliate'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>[vigyapanam_combined_register]</code></td>
                            <td><?php _e('Displays the combined registration form for new affiliates', 'vigyapanam-affiliate'); ?></td>
                            <td><?php _e('Add to your affiliate registration page', 'vigyapanam-affiliate'); ?></td>
                        </tr>
                        <tr>
                            <td><code>[vigyapanam_freelancer_dashboard]</code></td>
                            <td><?php _e('Displays the affiliate dashboard with analytics and earnings', 'vigyapanam-affiliate'); ?></td>
                            <td><?php _e('Add to your affiliate dashboard page', 'vigyapanam-affiliate'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h2><?php _e('Features', 'vigyapanam-affiliate'); ?></h2>
                <ul>
                    <li><?php _e('Affiliate Registration & Management', 'vigyapanam-affiliate'); ?></li>
                    <li><?php _e('Unique Affiliate Links', 'vigyapanam-affiliate'); ?></li>
                    <li><?php _e('Real-time Analytics', 'vigyapanam-affiliate'); ?></li>
                    <li><?php _e('Secure Payment Processing', 'vigyapanam-affiliate'); ?></li>
                    <li><?php _e('Performance Tracking', 'vigyapanam-affiliate'); ?></li>
                </ul>
            </div>

            <style>
                .vigyapanam-plugin-details .card {
                    padding: 20px;
                    margin: 20px 0;
                    background: #fff;
                    border: 1px solid #ccd0d4;
                    box-shadow: 0 1px 1px rgba(0,0,0,.04);
                }
                .vigyapanam-plugin-details code {
                    background: #f0f0f1;
                    padding: 3px 5px;
                    border-radius: 3px;
                }
                .vigyapanam-plugin-details table {
                    margin: 15px 0;
                }
                .vigyapanam-plugin-details th {
                    font-weight: 600;
                }
                .vigyapanam-plugin-details td, 
                .vigyapanam-plugin-details th {
                    padding: 12px;
                }
            </style>
        </div>
        <?php
    }
}