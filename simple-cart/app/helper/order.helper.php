<?php
function get_discount(int $amount)
{
    global $get_day_indonesia;
    $get_day_indonesia = strtolower($get_day_indonesia);
    if ($amount >= 300000 && $amount <= 500000) return ($get_day_indonesia == 'minggu') ? (10 + 5) : 10;
    elseif ($amount > 500000 && $amount <= 700000) return ($get_day_indonesia == 'minggu') ? (12 + 5) : 12;
    elseif ($amount > 700000 && $amount <= 900000) return ($get_day_indonesia == 'minggu') ? (14 + 5) : 14;
    else return ($get_day_indonesia == 'minggu') ? (0 + 5) : 0;
}

function get_amount_discount(int $amount)
{
    return ceil($amount * (get_discount($amount) / 100));
}