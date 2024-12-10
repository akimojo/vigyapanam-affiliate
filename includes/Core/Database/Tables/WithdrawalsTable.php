<?php
namespace VigyapanamAffiliate\Core\Database\Tables;

class WithdrawalsTable extends BaseTable {
    protected function get_table_name() {
        return 'vigyapanam_withdrawals';
    }

    public function get_schema() {
        return "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            freelancer_id bigint(20) NOT NULL,
            amount decimal(10,2) NOT NULL,
            payment_method varchar(50) NOT NULL,
            status varchar(20) NOT NULL DEFAULT 'pending',
            request_date datetime NOT NULL,
            process_date datetime DEFAULT NULL,
            PRIMARY KEY (id),
            KEY freelancer_id (freelancer_id)
        ) {$this->get_charset_collate()};";
    }
}