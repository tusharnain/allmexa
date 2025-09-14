<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRewardsTable extends Migration
{
    const TABLE = 'user_rewards';
    public function up()
    {
        $this->forge->addField([

            'id' => Helper::default_primary_key_id_attributes(),

            'user_id' => Helper::user_id_foreign_key_attributes(),

            'reward_id' => Helper::int_attributes(type: 'TINYINT', unsigned: true),

            'reward_name' => Helper::SIMPLE_NULLABLE_VARCHAR,

            'created_at' => Helper::default_timestamp_attributes(null: false),

            'updated_at' => Helper::default_timestamp_attributes(null: true),
        ]);


        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);

        $this->forge->addUniqueKey(['user_id', 'reward_id']);


        $this->forge->createTable(self::TABLE);
    }


    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
