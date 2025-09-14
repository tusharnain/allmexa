<?php

namespace App\Models;

use CodeIgniter\Model;


class ParentModel extends Model
{


    public function dbDate(int $timestamp = null)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }
    public function getTimestamps(int $type = 1): array
    {

        $time = $this->dbDate();

        if ($type === 1) {
            $arr = [
                'created_at' => $time,
                'updated_at' => $time
            ];
        } else if ($type === 2) {
            $arr = ['created_at' => $time];
        } else {
            $arr = ['updated_at' => $time];
        }
        return $arr;
    }

}