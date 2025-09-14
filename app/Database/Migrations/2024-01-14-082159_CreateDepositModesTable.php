<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDepositModesTable extends Migration
{
    const TABLE = 'deposit_modes';


    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),

            'name' => Helper::varchar_attributes(constraints: 255, unique: true),

            'title' => Helper::varchar_attributes(constraints: 255),

            'data' => [...Helper::json_attributes(null: false), 'comment' => 'Stores JSON'],

            'visibility' => Helper::bool_attributes(default: true),

            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes(),
        ]);


        $this->forge->addPrimaryKey('id');

        $this->forge->createTable(self::TABLE);
    }


    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
