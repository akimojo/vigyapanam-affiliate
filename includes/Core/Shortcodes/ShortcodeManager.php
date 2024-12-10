<?php
namespace VigyapanamAffiliate\Core\Shortcodes;

class ShortcodeManager {
    private $freelancer_dashboard;
    private $freelancer_profile;

    public function __construct() {
        $this->freelancer_dashboard = new \VigyapanamAffiliate\Frontend\FreelancerDashboard();
        $this->freelancer_profile = new \VigyapanamAffiliate\Frontend\FreelancerProfile();
        $this->init_shortcodes();
    }

    private function init_shortcodes() {
        add_shortcode('vigyapanam_freelancer_dashboard', [$this->freelancer_dashboard, 'render_dashboard']);
        add_shortcode('vigyapanam_freelancer_profile', [$this->freelancer_profile, 'render_profile_form']);
        add_shortcode('vigyapanam_affiliate_programs', [$this, 'render_affiliate_programs']);
    }

    public function render_affiliate_programs($atts) {
        $args = shortcode_atts([
            'limit' => -1,
            'columns' => 3
        ], $atts);

        $programs = get_posts([
            'post_type' => 'affiliate_program',
            'posts_per_page' => $args['limit'],
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ]);

        ob_start();
        ?>
        <div class="vigyapanam-affiliate-programs">
            <div class="programs-grid" style="display: grid; grid-template-columns: repeat(<?php echo esc_attr($args['columns']); ?>, 1fr); gap: 20px;">
                <?php foreach ($programs as $program): ?>
                    <div class="program-card">
                        <?php if (has_post_thumbnail($program->ID)): ?>
                            <div class="program-image">
                                <?php echo get_the_post_thumbnail($program->ID, 'medium'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <h3><?php echo esc_html($program->post_title); ?></h3>
                        
                        <div class="program-excerpt">
                            <?php echo wp_trim_words($program->post_excerpt, 20); ?>
                        </div>
                        
                        <div class="program-actions">
                            <a href="<?php echo esc_url(get_permalink($program->ID)); ?>" class="button">
                                <?php _e('Learn More', 'vigyapanam-affiliate'); ?>
                            </a>
                            <a href="<?php echo esc_url(get_post_meta($program->ID, 'register_url', true)); ?>" class="button button-primary">
                                <?php _e('Register Now', 'vigyapanam-affiliate'); ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}