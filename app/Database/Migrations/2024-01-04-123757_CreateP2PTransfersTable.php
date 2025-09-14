<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use App\Services\WalletService;

class CreateP2PTransfersTable extends Migration
{
    const TABLE = 'p2p_transfers';
    private array $wallets;

    public function __construct()
    {
        parent::__construct();

        $this->wallets = WalletService::WALLETS;
    }
    public function up()
    {
        $this->forge->addField([

            'id' => Helper::default_primary_key_id_attributes(),

            'sender_user_id' => Helper::user_id_foreign_key_attributes(),
            'receiver_user_id' => Helper::user_id_foreign_key_attributes(),

            'amount' => Helper::default_amount_field_attributes(default: null), // no default values

            'wallet' => Helper::enum_attributes($this->wallets),

            'sender_transaction_id' => ['type' => 'INT', 'unsigned' => true, 'unique' => true],
            'receiver_transaction_id' => ['type' => 'INT', 'unsigned' => true, 'unique' => true],

            'sender_remarks' => Helper::varchar_attributes(constraints: 255, null: true),

            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes(),
        ]);


        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge, 'sender_user_id');
        Helper::make_reference_to_user_id($this->forge, 'receiver_user_id');

        $this->forge->addForeignKey('sender_transaction_id', 'wallet_transactions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('receiver_transaction_id', 'wallet_transactions', 'id', 'CASCADE', 'CASCADE');

        $this->forge->addKey('created_at');

        $this->forge->createTable(self::TABLE);
    }


    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
