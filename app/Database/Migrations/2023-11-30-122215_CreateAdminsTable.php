<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminsTable extends Migration
{
    const TABLE = 'admins';
    public function up()
    {
        $this->forge->addField([

            'id' => Helper::default_primary_key_id_attributes(),

            'full_name' => Helper::varchar_attributes(constraints: 255),
            'email' => Helper::varchar_attributes(constraints: 255),
            'phone' => Helper::varchar_attributes(constraints: 30),
            'password' => Helper::varchar_attributes(constraints: 255),
            'role' => Helper::int_attributes(unsigned: true, null: false),
            'created_at' => Helper::default_timestamp_attributes(),
            'updated_at' => Helper::default_timestamp_attributes()
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable(self::TABLE);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
