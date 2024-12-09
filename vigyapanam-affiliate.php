<?php
/**
 * Plugin Name: Vigyapanam Affiliate Manager
 * Description: A comprehensive affiliate management system for Vigyapanam
 * Version: 1.0.0
 * Author: Vigyapanam
 * Text Domain: vigyapanam-affiliate
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('VIGYAPANAM_AFFILIATE_VERSION', '1.0.0');
define('VIGYAPANAM_AFFILIATE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VIGYAPANAM_AFFILIATE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VIGYAPANAM_AFFILIATE_PLUGIN_FILE', __FILE__);

// Autoloader for classes
spl_autoload_register(function ($class) {
    $prefix = 'VigyapanamAffiliate\\';
    $base_dir = VIGYAPANAM_AFFILIATE_PLUGIN_DIR . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the plugin
function vigyapanam_affiliate_init() {
    new VigyapanamAffiliate\Core\Plugin();
}

// Hook into plugins_loaded to ensure proper initialization
add_action('plugins_loaded', 'vigyapanam_affiliate_init');