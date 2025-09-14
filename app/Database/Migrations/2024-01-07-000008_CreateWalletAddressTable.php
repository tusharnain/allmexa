<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWalletAddressTable extends Migration
{
    const TABLE = 'wallet_address';


    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),
            'user_id' => Helper::user_id_foreign_key_attributes(unique: true),

            'trc20_address' => Helper::varchar_attributes(constraints: 64),
            // 'bep20_address' => Helper::varchar_attributes(constraints: 64),

            'locked' => Helper::bool_attributes(default: false),

            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes(),
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
