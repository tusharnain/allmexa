<?php

namespace App\Models;



class UserKycModel extends ParentModel
{
    protected $table = 'user_kyc';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'aadhar', 'aadhar_back', 'pan', 'status', 'status_updated_at', 'reject_remark'];

    // Dates

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';





}