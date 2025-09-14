<?php

namespace App\Database\Migrations;

use App\Enums\UserTypes;
use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    const TABLE = 'users';
    private array $userTypes;

    public function __construct()
    {
        parent::__construct();
        $this->userTypes = UserTypes::getArray();
    }

    public function up()
    {
        $this->forge->addField([


            'id' => Helper::default_primary_key_id_attributes(),

            'user_id' => Helper::varchar_attributes(constraints: 100, unique: true),

            'sponsor_id' => Helper::int_attributes(unsigned: true, null: true),

            //
            // 'parent_id' => Helper::int_attributes(unsigned: true, null: true),


            // 'placement' => Helper::enum_attributes(['l', 'r'], null: true),
            // 'left_child_id' => Helper::int_attributes(unsigned: true, null: true),
            // 'right_child_id' => Helper::int_attributes(unsigned: true, null: true),




            'full_name' => Helper::varchar_attributes(constraints: 255),
            'email' => Helper::varchar_attributes(constraints: 255),
            'phone' => Helper::varchar_attributes(constraints: 30),
            'status' => Helper::bool_attributes(),
            // 'user_type' => Helper::enum_attributes(options: $this->userTypes, null: true),
            'password' => Helper::varchar_attributes(constraints: 255),
            'is_password_hashed' => Helper::bool_attributes(),
            'tpin' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'is_tpin_hashed' => Helper::bool_attributes(),
            'roi_start_date' => Helper::default_timestamp_attributes(),
            'email_verified' => Helper::bool_attributes(),
            'login_suspend' => Helper::bool_attributes(),
            'profile_picture' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'activated_at' => Helper::default_timestamp_attributes(),
            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes(),

        ]);

        $this->forge->addPrimaryKey('id');


        $this->make_foriegn_key_to_self_user_id('sponsor_id');

        // $this->make_foriegn_key_to_self_user_id('parent_id');

        // $this->make_foriegn_key_to_self_user_id('left_child_id');
        // $this->make_foriegn_key_to_self_user_id('right_child_id');



        $this->forge->addKey('created_at');

        $this->forge->createTable(self::TABLE);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }



    private function make_foriegn_key_to_self_user_id(string $field)
    {
        $this->forge->addForeignKey($field, self::TABLE, 'id', 'RESTRICT', 'RESTRICT');
    }
}
