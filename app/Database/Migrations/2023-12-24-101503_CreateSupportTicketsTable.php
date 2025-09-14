<?php

namespace App\Database\Migrations;


use CodeIgniter\Database\Migration;

class CreateSupportTicketsTable extends Migration
{
    const TABLE = 'support_tickets';

    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),
            'ticket_id' => Helper::track_id_attributes(),
            'user_id' => Helper::user_id_foreign_key_attributes(),

            // user
            'subject' => Helper::varchar_attributes(constraints: 155), // 155 coz, subject should be shorter
            'message' => Helper::varchar_attributes(constraints: 2048),

            //admin
            'admin_reply' => Helper::varchar_attributes(constraints: 2048, null: true),

            'status' => Helper::bool_attributes(default: false), // false->open, true->closed

            'closed_at' => Helper::default_timestamp_attributes(),
            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes()
        ]);

        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);


        $this->forge->addKey('created_at');
        $this->forge->addKey('status');

        $this->forge->createTable(self::TABLE);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
