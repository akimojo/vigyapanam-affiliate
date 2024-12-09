<?php
namespace VigyapanamAffiliate\Core;

class Plugin {
    public function __construct() {
        add_action('plugins_loaded', [$this, 'load_plugin_textdomain']);
        add_action('init', [$this, 'init_plugin']);
        register_activation_hook(VIGYAPANAM_AFFILIATE_PLUGIN_FILE, [$this, 'activate']);
        register_deactivation_hook(VIGYAPANAM_AFFILIATE_PLUGIN_FILE, [$this, 'deactivate']);
    }

    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'vigyapanam-affiliate',
            false,
            dirname(plugin_basename(VIGYAPANAM_AFFILIATE_PLUGIN_FILE)) . '/languages/'
        );
    }

    public function init_plugin() {
        $this->init_hooks();
        $this->load_dependencies();
    }

    private function init_hooks() {
        add_action('init', [$this, 'register_post_types']);
        add_action('admin_menu', [$this, 'register_admin_menu']);
    }

    private function load_dependencies() {
        // Load admin classes
        new \VigyapanamAffiliate\Admin\AdminDashboard();
        
        // Load frontend classes
        new \VigyapanamAffiliate\Frontend\FreelancerDashboard();
        new \VigyapanamAffiliate\Frontend\FreelancerProfile();
        
        // Load utility classes
        new \VigyapanamAffiliate\Utils\IPTracker();
        new \VigyapanamAffiliate\Utils\EmailManager();
    }

    public function register_post_types() {
        // Register custom post types
        register_post_type('affiliate_program', [
            'labels' => [
                'name' => __('Affiliate Programs', 'vigyapanam-affiliate'),
                'singular_name' => __('Affiliate Program', 'vigyapanam-affiliate'),
                'add_new' => __('Add New Program', 'vigyapanam-affiliate'),
                'add_new_item' => __('Add New Affiliate Program', 'vigyapanam-affiliate'),
                'edit_item' => __('Edit Affiliate Program', 'vigyapanam-affiliate'),
                'new_item' => __('New Affiliate Program', 'vigyapanam-affiliate'),
                'view_item' => __('View Affiliate Program', 'vigyapanam-affiliate'),
                'search_items' => __('Search Affiliate Programs', 'vigyapanam-affiliate'),
                'not_found' => __('No affiliate programs found', 'vigyapanam-affiliate'),
                'not_found_in_trash' => __('No affiliate programs found in trash', 'vigyapanam-affiliate'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'menu_icon' => 'dashicons-money-alt',
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'affiliate-programs'],
        ]);
    }

    public function register_admin_menu() {
        add_menu_page(
            __('Vigyapanam Affiliate', 'vigyapanam-affiliate'),
            __('Vigyapanam Affiliate', 'vigyapanam-affiliate'),
            'manage_options',
            'vigyapanam-affiliate',
            [$this, 'admin_dashboard_page'],
            'dashicons-groups',
            30
        );
    }

    public function activate() {
        // Create necessary database tables
        $this->create_tables();
        
        // Set up roles and capabilities
        $this->setup_roles();
        
        // Clear permalinks
        flush_rewrite_rules();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }

    private function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Clients table
        $sql_clients = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}vigyapanam_clients (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            revenue_model varchar(100) NOT NULL,
            website varchar(255) NOT NULL,
            about text NOT NULL,
            contact_person varchar(255) NOT NULL,
            payment_terms text NOT NULL,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta($sql_clients);

        // Earnings table
        $sql_earnings = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}vigyapanam_earnings (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            freelancer_id bigint(20) NOT NULL,
            program_id bigint(20) NOT NULL,
            date datetime NOT NULL,
            traffic int(11) NOT NULL DEFAULT 0,
            rpm decimal(10,2) NOT NULL DEFAULT 0.00,
            earnings decimal(10,2) NOT NULL DEFAULT 0.00,
            PRIMARY KEY (id),
            KEY freelancer_id (freelancer_id),
            KEY program_id (program_id)
        ) $charset_collate;";
        dbDelta($sql_earnings);

        // Clicks table
        $sql_clicks = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}vigyapanam_clicks (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            freelancer_id bigint(20) NOT NULL,
            program_id bigint(20) NOT NULL,
            ip_address varchar(45) NOT NULL,
            date_clicked datetime NOT NULL,
            PRIMARY KEY (id),
            KEY freelancer_id (freelancer_id),
            KEY program_id (program_id)
        ) $charset_collate;";
        dbDelta($sql_clicks);

        // Withdrawals table
        $sql_withdrawals = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}vigyapanam_withdrawals (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            freelancer_id bigint(20) NOT NULL,
            amount decimal(10,2) NOT NULL,
            payment_method varchar(50) NOT NULL,
            status varchar(20) NOT NULL DEFAULT 'pending',
            request_date datetime NOT NULL,
            process_date datetime DEFAULT NULL,
            PRIMARY KEY (id),
            KEY freelancer_id (freelancer_id)
        ) $charset_collate;";
        dbDelta($sql_withdrawals);

        // Conversions table
        $sql_conversions = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}vigyapanam_conversions (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            freelancer_id bigint(20) NOT NULL,
            program_id bigint(20) NOT NULL,
            conversion_date datetime NOT NULL,
            commission decimal(10,2) NOT NULL DEFAULT 0.00,
            status varchar(20) NOT NULL DEFAULT 'pending',
            PRIMARY KEY (id),
            KEY freelancer_id (freelancer_id),
            KEY program_id (program_id)
        ) $charset_collate;";
        dbDelta($sql_conversions);

        // Store the current database version
        update_option('vigyapanam_affiliate_db_version', '1.0.0');
    }

    private function setup_roles() {
        // Add Freelancer role if it doesn't exist
        if (!get_role('freelancer')) {
            add_role('freelancer', __('Freelancer', 'vigyapanam-affiliate'), [
                'read' => true,
                'edit_posts' => false,
                'delete_posts' => false,
                'upload_files' => true,
                'vigyapanam_view_dashboard' => true,
                'vigyapanam_withdraw_earnings' => true,
                'vigyapanam_view_programs' => true,
            ]);
        }

        // Update administrator capabilities
        $admin = get_role('administrator');
        $admin_caps = [
            'vigyapanam_manage_settings',
            'vigyapanam_manage_clients',
            'vigyapanam_manage_freelancers',
            'vigyapanam_view_reports',
            'vigyapanam_manage_withdrawals',
            'vigyapanam_manage_programs',
        ];

        foreach ($admin_caps as $cap) {
            $admin->add_cap($cap);
        }
    }

    public function admin_dashboard_page() {
        require_once VIGYAPANAM_AFFILIATE_PLUGIN_DIR . 'includes/Admin/Views/admin-dashboard.php';
    }
}