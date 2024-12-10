<?php
namespace VigyapanamAffiliate\Core\Database\Tables;

abstract class BaseTable {
    protected $wpdb;
    protected $table_name;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . $this->get_table_name();
    }

    abstract protected function get_table_name();
    abstract public function get_schema();

    protected function get_charset_collate() {
        return $this->wpdb->get_charset_collate();
    }
}