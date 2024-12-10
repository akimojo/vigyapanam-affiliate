<?php
namespace VigyapanamAffiliate\Core\Database\Tables;

class ConversionsTable extends BaseTable {
    protected function get_table_name() {
        return 'vigyapanam_conversions';
    }

    public function get_schema() {
        return "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            freelancer_id bigint(20) NOT NULL,
            program_id bigint(20) NOT NULL,
            conversion_date datetime NOT NULL,
            commission decimal(10,2) NOT NULL DEFAULT 0.00,
            status varchar(20) NOT NULL DEFAULT 'pending',
            PRIMARY KEY (id),
            KEY freelancer_id (freelancer_id),
            KEY program_id (program_id)
        ) {$this->get_charset_collate()};";
    }
}