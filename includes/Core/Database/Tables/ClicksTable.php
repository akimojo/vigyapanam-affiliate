<?php
namespace VigyapanamAffiliate\Core\Database\Tables;

class ClicksTable extends BaseTable {
    protected function get_table_name() {
        return 'vigyapanam_clicks';
    }

    public function get_schema() {
        return "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            freelancer_id bigint(20) NOT NULL,
            program_id bigint(20) NOT NULL,
            page_id bigint(20) NOT NULL,
            ip_address varchar(45) NOT NULL,
            date_clicked datetime NOT NULL,
            PRIMARY KEY (id),
            KEY freelancer_id (freelancer_id),
            KEY program_id (program_id),
            KEY page_id (page_id)
        ) {$this->get_charset_collate()};";
    }
}