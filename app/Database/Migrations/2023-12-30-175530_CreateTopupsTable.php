<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTopupsTable extends Migration
{
    const TABLE = 'topups';
    public function __construct()
    {
        parent::__construct();
    }
    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),

            'track_id' => Helper::track_id_attributes(),

            'user_id' => Helper::user_id_foreign_key_attributes(),

            'amount' => Helper::default_amount_field_attributes(),

            // if its a value(user_id_pk), then topup is done by user
            // but if its null, it means topup has been done by admin
            'topup_by' => Helper::user_id_foreign_key_attributes(null: true),

            'created_at' => Helper::default_timestamp_attributes(null: false),
        ]);


        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);
        Helper::make_reference_to_user_id($this->forge, field_name: 'topup_by');


        $this->forge->addKey('created_at');

        $this->forge->createTable(self::TABLE);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
