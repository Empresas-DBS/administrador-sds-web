<?php
include "../extras/connStores.php";
include "../extras/connCubos.php";

$dbStores = new conn\Stores;
$dbCubos = new conn\Cubos;

$dbStores->dbConnection();
$dbCubos->dbConnection();

$store_code = $_POST["store_code"];
$complete_date = $_POST["complete_date"];
$company = $_POST["company"];
$new_stock = $_POST["new_stock"];

$sql = "select 
            id_stock_dia_tienda,
            codigo_tienda,
            total,
            fecha,
            fecha_registro,
            numero_tienda,
            empresa
        from
            stock_dia_tienda
        where
            codigo_tienda = '{$store_code}' and
            fecha = '{$complete_date}' and
            empresa = '{$company}' and
            total = {$new_stock}";
$dbCubos->dbQuery($sql);
$resultCubos = $dbCubos->dbGetResult();

$sql2 = "select
            count(*) as cantidad
        from
            stock_dia_tienda
        where
            codigo_tienda = '{$store_code}' and
            fecha = '{$complete_date}' and
            empresa = '{$company}'";
$dbStores->dbQuery($sql2);
$resultStores = $dbStores->dbGetResult();

if($resultStores[0]["cantidad"] == 0)
{
    $sqlInsert = "insert into stock_dia_tienda (codigo_tienda, total, fecha, fecha_registro, numero_tienda, empresa) values ('{$store_code}', {$new_stock}, '{$complete_date}', '{$resultCubos[0]["fecha_registro"]}', {$resultCubos[0]["numero_tienda"]}, '{$company}')";
    $dbStores->dbQuery($sqlInsert);

    $insertedId = $dbStores->dbInsertedId();
    
    $sqlInsertHistory = "insert into stock_dia_tienda_historial (id_stock_dia_tienda, codigo_tienda, total, fecha, fecha_registro, numero_tienda, empresa) values ({$insertedId}, '{$store_code}', 0, '{$complete_date}', '{$resultCubos[0]["fecha_registro"]}', {$resultCubos[0]["numero_tienda"]}, '{$company}')";
    $dbStores->dbQuery($sqlInsertHistory);
}
else
{
    $sqlUpdate = "";
}

echo json_encode("ok");

$dbStores->dbClose();
$dbCubos->dbClose();