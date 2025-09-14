<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserSessionsTable extends Migration
{
    const TABLE = 'user_sessions';
    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),
            'user_id' => Helper::user_id_foreign_key_attributes(unique: true),
            'session_id' => Helper::varchar_attributes(constraints: 255),
            'remember_token' => Helper::varchar_attributes(constraints: 255, unique: true, null: true),
            'remember_token_expire' => Helper::default_timestamp_attributes(),
            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes(),
        ]);

        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);

        $this->forge->addKey('session_id');
        $this->forge->addKey('remember_token_expire');

        $this->forge->createTable(self::TABLE);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
