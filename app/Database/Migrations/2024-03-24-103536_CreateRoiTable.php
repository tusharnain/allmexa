<?php

namespace App\Database\Migrations;

use App\Enums\RoiTypes;
use App\Twebsol\Plans;
use CodeIgniter\Database\Migration;

class CreateRoiTable extends Migration
{
    const TABLE = 'roi';
    private array $roiTypes;
    private array $salaryIndexArray;
    public function __construct()
    {
        parent::__construct();

        $this->roiTypes = RoiTypes::getArray();
        $this->salaryIndexArray = array_map(fn(int $index) => (string) $index, array_keys(Plans::SALARY_ROI_STRUCTURE));
    }

    public function up()
    {
        $this->forge->addField([
            'id' => Helper::default_primary_key_id_attributes(),
            'track_id' => Helper::track_id_attributes(),
            'user_id' => Helper::user_id_foreign_key_attributes(),
            'roi_amount' => Helper::default_amount_field_attributes(),
            'roi_total_amount' => Helper::default_amount_field_attributes(),
            'roi_given_amount' => Helper::default_amount_field_attributes(),
            'roi_type' => Helper::enum_attributes($this->roiTypes),
            'salary_index' => Helper::enum_attributes($this->salaryIndexArray, null: true),
            'status' => Helper::enum_attributes(['active', 'inactive'], default: 'active'),
            'complete' => Helper::bool_attributes(default: 0),
            'topup_id_pk' => Helper::int_attributes(unsigned: true, null: true),
            'upgrade_level' => Helper::int_attributes(type: 'TINYINT', unsigned: true, default: 0),
            'created_at' => Helper::default_timestamp_attributes(null: false),
            'updated_at' => Helper::default_timestamp_attributes(),
        ]);


        $this->forge->addPrimaryKey('id');

        Helper::make_reference_to_user_id($this->forge);

        $this->forge->addForeignKey('topup_id_pk', 'topups', 'id', 'CASCADE', 'CASCADE');


        $this->forge->createTable(self::TABLE);
    }

    public function down()
    {
        $this->forge->dropTable(self::TABLE);
    }
}
