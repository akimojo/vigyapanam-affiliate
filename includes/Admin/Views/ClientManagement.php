<?php
namespace VigyapanamAffiliate\Admin\Views;

class ClientManagement {
    public function render() {
        ?>
        <div class="client-management-section">
            <h2><?php _e('Client Management', 'vigyapanam-affiliate'); ?></h2>
            
            <?php $this->render_add_client_form(); ?>
            <?php $this->render_client_list(); ?>
        </div>
        <?php
    }

    private function render_add_client_form() {
        ?>
        <div class="add-client-form-container">
            <h3><?php _e('Add New Client', 'vigyapanam-affiliate'); ?></h3>
            <form id="add-client-form" class="client-form">
                <?php wp_nonce_field('client_nonce', 'client_nonce'); ?>
                
                <div class="form-group">
                    <label for="client_name"><?php _e('Client Name', 'vigyapanam-affiliate'); ?></label>
                    <input type="text" id="client_name" name="client_name" required>
                </div>

                <div class="form-group">
                    <label for="revenue_model"><?php _e('Revenue Model', 'vigyapanam-affiliate'); ?></label>
                    <input type="text" id="revenue_model" name="revenue_model" required>
                </div>

                <div class="form-group">
                    <label for="website"><?php _e('Website', 'vigyapanam-affiliate'); ?></label>
                    <input type="url" id="website" name="website" required>
                </div>

                <div class="form-group">
                    <label for="about"><?php _e('About', 'vigyapanam-affiliate'); ?></label>
                    <textarea id="about" name="about" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="contact_person"><?php _e('Contact Person', 'vigyapanam-affiliate'); ?></label>
                    <input type="text" id="contact_person" name="contact_person" required>
                </div>

                <div class="form-group">
                    <label for="payment_terms"><?php _e('Payment Terms', 'vigyapanam-affiliate'); ?></label>
                    <textarea id="payment_terms" name="payment_terms" rows="4" required></textarea>
                </div>

                <button type="submit" class="button button-primary">
                    <?php _e('Add Client', 'vigyapanam-affiliate'); ?>
                </button>
            </form>
        </div>
        <?php
    }

    private function render_client_list() {
        global $wpdb;
        $clients = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vigyapanam_clients ORDER BY name ASC");
        ?>
        <div class="client-list-container">
            <h3><?php _e('Existing Clients', 'vigyapanam-affiliate'); ?></h3>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Client Name', 'vigyapanam-affiliate'); ?></th>
                        <th><?php _e('Revenue Model', 'vigyapanam-affiliate'); ?></th>
                        <th><?php _e('Website', 'vigyapanam-affiliate'); ?></th>
                        <th><?php _e('Contact Person', 'vigyapanam-affiliate'); ?></th>
                        <th><?php _e('Actions', 'vigyapanam-affiliate'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo esc_html($client->name); ?></td>
                        <td><?php echo esc_html($client->revenue_model); ?></td>
                        <td>
                            <a href="<?php echo esc_url($client->website); ?>" target="_blank">
                                <?php echo esc_html($client->website); ?>
                            </a>
                        </td>
                        <td><?php echo esc_html($client->contact_person); ?></td>
                        <td>
                            <button class="button edit-client" data-id="<?php echo esc_attr($client->id); ?>">
                                <?php _e('Edit', 'vigyapanam-affiliate'); ?>
                            </button>
                            <button class="button delete-client" data-id="<?php echo esc_attr($client->id); ?>">
                                <?php _e('Delete', 'vigyapanam-affiliate'); ?>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}