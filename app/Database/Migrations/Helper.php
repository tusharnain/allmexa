<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;


// migration helper class
class Helper
{
    const SIMPLE_NULLABLE_VARCHAR = [
        'type' => 'VARCHAR',
        'constraint' => 255,
        'null' => true
    ];



    // For Primary Key (id) column
    public static function default_primary_key_id_attributes(): array
    {
        return [
            'type' => 'INT',
            'unsigned' => true,
            'auto_increment' => true,
        ];
    }

    // Default Timestamp Field
    public static function default_timestamp_attributes(bool $null = true): array
    {
        return [
            'type' => 'TIMESTAMP',
            'null' => $null
        ];
    }

    // default attributes for amount fields, its smaller than balance fields, coz amount numbers will be less, the balance can store higer numbers
    public static function default_amount_field_attributes(?float $default = 0, $null = false, string $constraint = '28,8'): array
    {
        $arr = [
            'type' => 'DECIMAL',
            'constraint' => $constraint,
        ];

        if (!is_null($default))
            $arr['default'] = $default;

        if ($null) {
            unset($arr['default']);
            $arr['null'] = true;
        }
        return $arr;
    }


    // Attribute for reference to id(in users table)
    public static function user_id_foreign_key_attributes(bool $unique = false, bool $null = false): array
    {
        return [
            'type' => 'INT',
            'unsigned' => true,
            'unique' => $unique,
            'null' => $null
        ];
    }

    public static function track_id_attributes(): array
    {
        return Helper::varchar_attributes(constraints: 64, unique: true, null: true);
    }


    public static function make_reference_to_user_id(Forge &$forge, string $field_name = 'user_id')
    {
        $forge->addForeignKey($field_name, 'users', 'id', 'RESTRICT', 'RESTRICT');
    }




    // simple fields
    public static function varchar_attributes(int $constraints = 255, $unique = false, $null = false): array
    {
        return [
            'type' => 'VARCHAR',
            'constraint' => $constraints,
            'unique' => $unique,
            'null' => $null
        ];
    }
    public static function int_attributes(string $type = 'INT', bool $unsigned = false, bool $null = false, int $default = null): array
    {
        $arr = [
            'type' => $type,
            'unsigned' => $unsigned,
            'null' => $null
        ];

        if (!is_null($default))
            $arr['default'] = $default;

        return $arr;
    }

    public static function bool_attributes(bool $default = false): array
    {
        return [
            'type' => 'BOOLEAN',
            'default' => $default
        ];
    }

    public static function json_attributes(bool $null = true): array
    {
        return ['type' => 'JSON', 'null' => $null];
    }

    public static function enum_attributes(array $options, $default = null, bool $null = false): array
    {
        $data = [
            'type' => 'ENUM',
            'constraint' => $options,
            'null' => $null
        ];

        if ($default)
            $data['default'] = $default;

        return $data;
    }
}
