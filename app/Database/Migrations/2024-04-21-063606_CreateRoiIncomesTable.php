<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoiIncomesTable extends Migration
{
    const TABLE = 'roi_incomes';


    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->forge->addField([

            'id' => Helper::default_primary_key_id_attributes(),

            'user_id' => Helper::user_id_foreign_key_attributes(),

            'amount' => Helper::default_amount_field_attributes(default: null), // no default values

            'roi_id' => Helper::int_attributes(unsigned: true),

            'upgrade_level' => Helper::int_attributes(type: 'TINYINT', unsigned: true, default: 0),

            'transaction_id_pk' => Helper::int_attributes(unsigned: true),

            'created_at' => Helper::default_timestamp_attributes(null: false),
        ]);


        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);

        $this->forge->addForeignKey('roi_id', 'roi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('transaction_id_pk', 'wallet_transactions', 'id', 'CASCADE', 'CASCADE');

        $this->forge->addKey('created_at');

        $this->forge->createTable(self::TABLE);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
