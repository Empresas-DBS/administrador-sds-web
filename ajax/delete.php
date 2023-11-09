<?php
include '../system/init.php';

$sds = new process_ilu\sds;

$sds->dbWeb1 = new database\Web1;
$sds->dbWeb1->dbConnection();

$alu = $_POST["alu"];
$canal = $_POST["canal"];
$fvalues = array(
    "alu" => strtoupper($alu),
    "canal" => $canal
);

$deleted = $sds->delete($fvalues);

$sds->dbWeb1->dbClose();

if($deleted == 1)
    echo json_encode("OK_DELETE");
else
    echo json_encode("NOK_DELETE");