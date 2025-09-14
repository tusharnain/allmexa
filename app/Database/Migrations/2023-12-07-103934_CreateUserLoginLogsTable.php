<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserLoginLogsTable extends Migration
{
    const TABLE = 'user_login_logs';
    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),
            'user_id' => Helper::user_id_foreign_key_attributes(unique: false),
            'ip_address' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'os' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'browser' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'user_agent' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'status' => Helper::enum_attributes(['success', 'fail'], default: 'success'),
            'message' => Helper::SIMPLE_NULLABLE_VARCHAR,
            // it means that the login is done by remember me cookie 
            'remember_login' => Helper::bool_attributes(),
            'created_at' => Helper::default_timestamp_attributes(null: false)
        ]);

        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);

        $this->forge->createTable(self::TABLE, false);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE, false);
    }
}
