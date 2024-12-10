<?php
namespace VigyapanamAffiliate\Core\Database;

class DatabaseManager {
    private $tables;

    public function __construct() {
        $this->tables = [
            new Tables\ClientsTable(),
            new Tables\EarningsTable(),
            new Tables\ClicksTable(),
            new Tables\WithdrawalsTable(),
            new Tables\ConversionsTable(),
            new Tables\LocationsTable()
        ];
    }

    public function create_tables() {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        foreach ($this->tables as $table) {
            $sql = $table->get_schema();
            dbDelta($sql);
        }

        update_option('vigyapanam_affiliate_db_version', VIGYAPANAM_AFFILIATE_VERSION);
    }

    public function optimize_tables() {
        global $wpdb;
        foreach ($this->tables as $table) {
            $table_name = $wpdb->prefix . $table->get_table_name();
            $wpdb->query("OPTIMIZE TABLE $table_name");
        }
    }

    public function cleanup_old_data() {
        global $wpdb;
        
        // Keep only last 90 days of data
        $cutoff_date = date('Y-m-d', strtotime('-90 days'));
        
        $tables = [
            'vigyapanam_clicks' => 'date_clicked',
            'vigyapanam_earnings' => 'date',
            'vigyapanam_locations' => 'created_at'
        ];

        foreach ($tables as $table => $date_column) {
            $wpdb->query($wpdb->prepare(
                "DELETE FROM {$wpdb->prefix}{$table} WHERE {$date_column} < %s",
                $cutoff_date
            ));
        }
    }

    public function table_exists($table_name) {
        global $wpdb;
        $table = $wpdb->prefix . $table_name;
        return $wpdb->get_var("SHOW TABLES LIKE '$table'") === $table;
    }
}