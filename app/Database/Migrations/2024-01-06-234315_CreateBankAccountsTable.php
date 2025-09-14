<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBankAccountsTable extends Migration
{
    const TABLE = 'bank_accounts';


    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),
            'user_id' => Helper::user_id_foreign_key_attributes(unique: true),

            'bank_ifsc' => Helper::varchar_attributes(constraints: 20),
            'bank_name' => Helper::varchar_attributes(constraints: 255),
            'bank_branch' => Helper::varchar_attributes(constraints: 255),
            'account_holder_name' => Helper::varchar_attributes(constraints: 255),
            'account_number' => Helper::varchar_attributes(constraints: 30),

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
