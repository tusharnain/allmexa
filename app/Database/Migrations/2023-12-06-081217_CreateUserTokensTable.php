<?php

namespace App\Database\Migrations;

use App\Enums\UserToken\UserTokenStatus;
use App\Enums\UserToken\UserTokenType;
use CodeIgniter\Database\Migration;

class UserTokens extends Migration
{
    const TABLE = 'user_tokens';
    private array $tokenTypeArray;
    private array $tokenStatusArray;

    public function __construct()
    {
        parent::__construct();

        $this->tokenTypeArray = UserTokenType::getTypeArray();
        $this->tokenStatusArray = UserTokenStatus::getTypeArray();
    }

    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),
            'user_id' => Helper::user_id_foreign_key_attributes(unique: false),
            'token_type' => Helper::enum_attributes(options: $this->tokenTypeArray),
            'token' => Helper::varchar_attributes(constraints: 255),
            'status' => Helper::enum_attributes(options: $this->tokenStatusArray, default: UserTokenStatus::UNUSED),
            'expire_at' => Helper::default_timestamp_attributes(null: false),
            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes(),
        ]);

        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);

        // Add unique constraint for combination of token_type and token
        $this->forge->addUniqueKey(['token_type', 'token']);

        $this->forge->addKey('expire_at');

        $this->forge->createTable(self::TABLE, false);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE, false);
    }
}
