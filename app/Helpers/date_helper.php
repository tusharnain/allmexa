<?php

if (!function_exists('chart_get_last_n_days')) {
    function chart_get_last_n_days($startDate = null, $N = 10): array
    {
        $dates = array();
        if ($startDate === null) {
            $today = strtotime('today');
        } else {
            $today = strtotime($startDate);
        }

        for ($i = 0; $i < $N; $i++) {
            $dates[] = date('Y-m-d\TH:i:s', strtotime("-$i days", $today));
        }

        return $dates;
    }
}
