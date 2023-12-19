<?php
include '../system/init.php';
$helper = new common\Helper;
$sds = new process_ilu\sds;
$sds->dbWeb1 = new database\Web1;
$sds->dbWeb1->dbConnection();
// Llamada a la función para obtener los datos
    $excelData = $sds->exportToExcel();

    // Encabezados de columna con BOM
    $csvContent = "\xEF\xBB\xBF"; // BOM para indicar codificación UTF-8
    $csvContent .= "SKU;ILUMINADO;SDS;DESDE;HASTA;CANAL\n";

    // Contenido de datos
    foreach ($excelData as $row) {
        $csvContent .= implode(';', $row) . "\n";
    }

    // Configurar las cabeceras HTTP para la descarga del archivo CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename=Consolidado_SDS.csv');
    header('Cache-Control: max-age=0');

    // Enviar el contenido al cliente
    echo $csvContent;
//excel
?>