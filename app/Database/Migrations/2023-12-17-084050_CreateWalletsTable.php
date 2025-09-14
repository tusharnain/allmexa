<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

//! if you want to add more wallets, don't change anything in this migration, instead add new wallet in Wallet Enum file, and then again run migration
//! Also don't forget to add that new wallet in labels file, and in wallet model

class CreateWalletsTable extends Migration
{
    const TABLE = 'wallets';
    public function up()
    {
        //wallets
        $wallets = \App\Services\WalletService::WALLETS;

        $walletFields = array();
        foreach ($wallets as &$wallet)
            $walletFields[$wallet] = Helper::default_amount_field_attributes(constraint: '28,8');

        $this->forge->addField([

            'id' => Helper::default_primary_key_id_attributes(),

            'user_id' => Helper::user_id_foreign_key_attributes(unique: true),

            ...$walletFields,

            'created_at' => Helper::default_timestamp_attributes(),
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
