<?php
error_reporting(0);
ini_set('max_execution_time', 15000);
set_time_limit(0);
ini_set('memory_limit', '-1');
include '../system/init.php';

// $company_code = 2; // DBS
// $company_code = 3; // MU
// $company_code = 4; // PM

$dbRpro = new rpro\CustomConnection;

$dbRpro->dbConnection();

$sql = "  select 
              p.codigo_producto as codigo_producto,
              p.nombre,
              p.marca as marca,
              p.categoria as categoria,
              te.codigo_empresa as codigo_empresa
          from producto p
          JOIN mapa m on p.codigo_producto = m.codigo_producto
          JOIN tiendas_equivalencias te on m.codigo_tienda = te.det_cod_rpro and te.codigo_empresa in (2, 3, 4)
          where p.estado != 'Inactive'
          group by m.codigo_producto
          order by m.codigo_producto asc";
$dbRpro->dbQuery($sql);
$productos = $dbRpro->dbGetResult();

$sql = "TRUNCATE TABLE producto_tiendas_disponibilidad";
if($dbRpro->dbQuery($sql) === false)
{
    $message .= "No se puede completar la operación: TRUNCATE TABLE producto_tiendas_disponibilidad.<br>";
}
else
{
    foreach($productos as $producto)
    {
        $sql = "select GROUP_CONCAT(codigo_tienda SEPARATOR ',') as listado_tiendas from mapa where codigo_producto = '{$producto["codigo_producto"]}'";
        $dbRpro->dbQuery($sql);
        $result = $dbRpro->dbGetResult();
        $listado_tiendas = $result[0]["listado_tiendas"];
    
        $sqlInsert = "insert producto_tiendas_disponibilidad (codigo_producto, nombre, marca, categoria, listado_tiendas, codigo_empresa) values ('{$producto["codigo_producto"]}', '{$producto["nombre"]}', '{$producto["marca"]}', '{$producto["categoria"]}', '{$listado_tiendas}', {$producto["codigo_empresa"]}) ";
        $dbRpro->dbQuery($sqlInsert);
    }
    echo "Listado de tiendas por producto actualizado.";
}

$dbRpro->dbClose();