<?php
function format_datetime(String $i = null) {
    if (is_null($i)) return $i;
    $month = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];
    $split = explode(" ", $i);
    $date = explode("-", $split[0]);
    $format_date = $date[2].' '.$month[$date[1]].' '.$date[0];
    return $format_date.', '.$split[1];
}

function format_date(String $i = null) {
    if (is_null($i)) return $i;
    $month = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];
    $split = explode(" ", $i);
    $date = explode("-", $split[0]);
    $format_date = $date[2].' '.$month[$date[1]].' '.$date[0];
    return $format_date;
}

function get_day_indonesia(string $day){
    $day = strtolower($day);
    $today_arr = [
        'monday' => 'Senin',
        'tuesday' => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday' => 'Kamis',
        'friday' => 'Jum\'at',
        'saturday' => 'Sabtu',
        'sunday' => 'Minggu'
    ];
    return isset($today_arr[$day]) ? $today_arr[$day] : $day;
}