<?php
namespace VigyapanamAffiliate\Core\Database\Tables;

class LocationsTable extends BaseTable {
    protected function get_table_name() {
        return 'vigyapanam_locations';
    }

    public function get_schema() {
        return "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            ip_address varchar(45) NOT NULL,
            country varchar(100) DEFAULT 'Unknown',
            city varchar(100) DEFAULT 'Unknown',
            browser varchar(255) DEFAULT 'Unknown',
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY ip_address (ip_address),
            KEY country (country),
            KEY city (city)
        ) {$this->get_charset_collate()};";
    }
}