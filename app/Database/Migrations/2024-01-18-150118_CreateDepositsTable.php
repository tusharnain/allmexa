<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDepositsTable extends Migration
{
    const TABLE = 'deposits';

    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),
            'track_id' => Helper::track_id_attributes(),
            'user_id' => Helper::user_id_foreign_key_attributes(),
            'amount' => Helper::default_amount_field_attributes(default: null),
            'deposit_mode_id' => Helper::int_attributes(unsigned: true),
            'utr' => Helper::varchar_attributes(constraints: 255),
            'receipt_file' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'remarks' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'status' => Helper::enum_attributes(['pending', 'reject', 'complete'], default: 'pending'),
            'admin_remarks' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'admin_resolution_at' => Helper::default_timestamp_attributes(),
            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes(),
        ]);


        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);

        $this->forge->addForeignKey('deposit_mode_id', 'deposit_modes', 'id', 'CASCADE', 'CASCADE');

        $this->forge->addKey('created_at');

        $this->forge->createTable(self::TABLE);
    }


    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
