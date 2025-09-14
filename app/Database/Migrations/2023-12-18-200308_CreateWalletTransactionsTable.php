<?php

namespace App\Database\Migrations;

use App\Enums\WalletTransactionCategory as TransactionEnum;
use App\Services\WalletService;
use CodeIgniter\Database\Migration;

class CreateWalletTransactionsTable extends Migration
{
    const TABLE = 'wallet_transactions';
    private array $wallets;
    private array $categories;
    public function __construct()
    {
        parent::__construct();

        $this->wallets = WalletService::WALLETS;
        $this->categories = TransactionEnum::getArray();
    }
    public function up()
    {

        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),
            'track_id' => Helper::track_id_attributes(),
            'wallet' => Helper::enum_attributes($this->wallets),
            'user_id' => Helper::user_id_foreign_key_attributes(),
            'type' => Helper::enum_attributes(['credit', 'debit']),
            'amount' => Helper::default_amount_field_attributes(default: null), // no default values
            'balance' => Helper::default_amount_field_attributes(default: null), // no default values
            'details' => Helper::json_attributes(null: true),
            'category' => Helper::enum_attributes($this->categories),
            'status' => Helper::enum_attributes(['success', 'pending', 'failed']),
            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes(),
        ]);

        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);


        $this->forge->addKey('created_at');
        $this->forge->addKey('wallet');

        $this->forge->createTable(self::TABLE);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
