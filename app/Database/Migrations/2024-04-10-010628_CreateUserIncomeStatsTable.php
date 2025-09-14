<?php

namespace App\Database\Migrations;

use App\Enums\UserIncomeStats;
use CodeIgniter\Database\Migration;

class CreateUserIncomeStatsTable extends Migration
{
    const TABLE = 'user_income_stats';
    private ?array $stats;

    public function __construct()
    {
        parent::__construct();
        $this->stats = UserIncomeStats::getArray();
    }

    public function up()
    {

        $columns = [
            'id' => Helper::default_primary_key_id_attributes(),
            'user_id' => Helper::user_id_foreign_key_attributes(unique: true),
        ];

        foreach ($this->stats as &$stat)
            $columns[$stat] = Helper::default_amount_field_attributes(constraint: '28,16');

        $columns['created_at'] = Helper::default_timestamp_attributes(null: false);


        $this->forge->addField($columns);

        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);

        $this->forge->createTable(self::TABLE);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
