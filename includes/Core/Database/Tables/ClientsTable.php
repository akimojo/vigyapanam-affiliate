<?php
namespace VigyapanamAffiliate\Core\Database\Tables;

class ClientsTable extends BaseTable {
    protected function get_table_name() {
        return 'vigyapanam_clients';
    }

    public function get_schema() {
        return "CREATE TABLE IF NOT EXISTS {$this->table_name} (
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
        ) {$this->get_charset_collate()};";
    }
}