<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKycTable extends Migration
{
    const TABLE = 'user_kyc';
    public function up()
    {
        $this->forge->addField([

            'id' => Helper::default_primary_key_id_attributes(),

            'user_id' => Helper::user_id_foreign_key_attributes(unique: true),

            'aadhar' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'pan' => Helper::SIMPLE_NULLABLE_VARCHAR,

            'status' => Helper::enum_attributes(['approved', 'rejected', 'pending'], 'pending'),

            'status_updated_at' => Helper::default_timestamp_attributes(),

            'reject_remark' => Helper::SIMPLE_NULLABLE_VARCHAR,

            'created_at' => Helper::default_timestamp_attributes(null: false),

            'updated_at' => Helper::default_timestamp_attributes(null: true),
        ]);


        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);

        $this->forge->createTable(self::TABLE);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
