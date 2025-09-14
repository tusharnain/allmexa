<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWithdrawalsTable extends Migration
{
    const TABLE = 'withdrawals';
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
            'amount' => Helper::default_amount_field_attributes(default: null),
            'charges' => Helper::default_amount_field_attributes(),
            'net_amount' => Helper::default_amount_field_attributes(default: null),
            'remarks' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'status' => Helper::enum_attributes(['pending', 'cancelled', 'reject', 'complete'], default: 'pending'),
            'utr' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'admin_remarks' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'admin_resolution_at' => Helper::default_timestamp_attributes(),
            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes(),
        ]);


        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);


        $this->forge->addKey('status');
        $this->forge->addKey('created_at');

        $this->forge->createTable(self::TABLE);
    }


    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
