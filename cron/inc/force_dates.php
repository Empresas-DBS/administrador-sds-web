<?php
/*               2 días      1 día       1 día       1 día       1 día */
$hours = array("00:00:00", "13:00:00", "15:00:00", "17:00:00", "21:00:00");
$dates = array();

$t_hour = $hours[0]; //0-1-2-3-4
$t_date = "2022-04-05";

if($t_hour == "00:00:00")
{
    $exec_hour = "23:59:59";
    $datetime = $t_date . " " . $t_hour;
    $dates[] = date('Y-m-d 00:i:s',strtotime($datetime . "-2 days"));
}
else
{
    $exec_hour = $t_hour;
    $datetime = $t_date . " " . $t_hour;
    $dates[] = date('Y-m-d H:i:s',strtotime($datetime . "-1 days"));
}

// echo "<pre>";
// var_dump($exec_hour);
// var_dump($dates);
// echo "</pre>";
// die();