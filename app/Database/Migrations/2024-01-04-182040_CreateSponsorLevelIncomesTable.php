<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSponsorLevelIncomesTable extends Migration
{
    const TABLE = 'sponsor_level_incomes';
    private array $wallets;


    public function up()
    {
        $this->forge->addField([

            'id' => Helper::default_primary_key_id_attributes(),

            'user_id' => Helper::user_id_foreign_key_attributes(),

            'amount' => Helper::default_amount_field_attributes(default: null), // no default values


            'level' => Helper::int_attributes(type: 'TINYINT'), // tiny int coz, level should not be very higher, 10,20,30 max to max can go 40 or 50

            'level_user_id' => Helper::user_id_foreign_key_attributes(),

            'percent' => Helper::int_attributes(type: 'TINYINT', unsigned: true, null: true),
            'bv' => Helper::default_amount_field_attributes(default: null),

            'topup_id_pk' => Helper::int_attributes(unsigned: true),

            'transaction_id_pk' => Helper::int_attributes(unsigned: true),

            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes(),
        ]);


        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);
        Helper::make_reference_to_user_id($this->forge, 'level_user_id');


        $this->forge->addForeignKey('topup_id_pk', 'topups', 'id', 'CASCADE', 'CASCADE');


        $this->forge->addForeignKey('transaction_id_pk', 'wallet_transactions', 'id', 'CASCADE', 'CASCADE');


        $this->forge->addKey('created_at');

        $this->forge->createTable(self::TABLE);
    }


    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
