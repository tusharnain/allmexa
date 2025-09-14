<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCronTestTable extends Migration
{
    const TABLE = 'cron_test';

    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),
            'data' => ['type' => 'TEXT', 'null' => true],
            'created_at' => Helper::default_timestamp_attributes(null: false),
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable(self::TABLE);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
