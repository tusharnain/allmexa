<?php

namespace App\Database\Migrations;

use App\Enums\NomineeRelation;
use CodeIgniter\Database\Migration;

class CreateUserDetailsTable extends Migration
{
    const TABLE = 'user_details';
    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),
            'user_id' => Helper::user_id_foreign_key_attributes(unique: true),
            'date_of_birth' => ['type' => 'DATE', 'null' => true],
            'gender' => Helper::enum_attributes(options: ['male', 'female', 'other'], null: true),
            'address' => Helper::varchar_attributes(constraints: 1024, null: true),
            'postal_code' => Helper::varchar_attributes(constraints: 20, null: true),
            'city' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'state' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'country' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'father_name' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'mother_name' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'employment_status' => Helper::enum_attributes(options: ['employed', 'unemployed', 'student'], null: true),
            'occupation' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'nominee' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'nominee_relation' => Helper::enum_attributes(options: NomineeRelation::RELATIONS, null: true),
            'nominee_phone' => Helper::varchar_attributes(constraints: 20, null: true),
            'nominee_email' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'nominee_address' => Helper::varchar_attributes(constraints: 1024, null: true),
            'nominee_aadhar' => Helper::SIMPLE_NULLABLE_VARCHAR,
            'last_password_change_at' => Helper::default_timestamp_attributes(),
            'last_tpin_change_at' => Helper::default_timestamp_attributes(),

            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes()
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
