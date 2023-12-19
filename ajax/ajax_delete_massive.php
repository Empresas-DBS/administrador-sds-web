<?php
include '../system/init.php';
$helper = new common\Helper;
$sds = new process_ilu\sds;
$sds->dbWeb1 = new database\Web1;
$sds->dbWeb1->dbConnection();

if($_POST['delete_massive']){
    $result = $sds->truncate();
    if($result){
        $resultData[] = array(
            "nro_registro" => '',
            "alu" => '',
            "canal" =>'',
            "msg" => "Registros eliminados correctamente.",
            "msg_type" => "success"
        );
    }else{
        $resultData[] = array(
            "nro_registro" => '',
            "alu" => '',
            "canal" =>'',
            "msg" => "Ocurrio un error al eliminar los registros.",
            "msg_type" => "danger"
        );
    }
    echo json_encode($resultData);
}