<?php

namespace App\Models;

class UserSalaryModel extends ParentModel
{
    protected $table = 'user_salaries';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'structure_type', 'structure_id', 'income', 'freq', 'given_times', 'disabled_at', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
