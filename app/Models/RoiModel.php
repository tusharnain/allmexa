<?php

namespace App\Models;

use App\Twebsol\Plans;




class RoiModel extends ParentModel
{
    protected $table = 'roi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['track_id', 'user_id', 'roi_amount', 'roi_total_amount', 'roi_given_amount', 'roi_type', 'salary_index', 'status', 'complete', 'topup_id_pk', 'upgrade_level', 'created_at', 'updated_at'];

    // constants
    const TRACK_ID_INIT_NUMBER = 1000000;
    const TRACK_ID_PREFIX_WORD = 'ROI';
    // constants

    //enums
    const ROI_STATUS_ACTIVE = 'active';
    const ROI_STATUS_INACTIVE = 'inactive';

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';



    //
    //
    //
    //


    public function getRoiRecord(int $roi_id_pk, string|array $columns = '*'): object|null
    {
        return $this->select($columns)->find($roi_id_pk);
    }

    public function getRoiFromTrackId(string $track_id, string|array $columns = '*'): object|null
    {
        return $this->select($columns)->where('track_id', $track_id)->get()->getRowObject();
    }








}