<?php
include '../system/init.php';
$helper = new common\Helper;
$sds = new process_ilu\sds;
$sds->dbWeb1 = new database\Web1;
$sds->dbWeb1->dbConnection();
try {


    $search_sku = strtoupper(trim($_POST["search_sku"]));
    $search_canal = $_POST["search_canal"] != "-1" ? trim($_POST["search_canal"]) : "";

    // Obtén la columna por la cual se está ordenando y la dirección del orden
    $orderColumnIndex = $_POST['order'][0]['column'];
    $orderDirection = $_POST['order'][0]['dir'];

    // Paginación
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 100;

    // Buscador datatable
    $searchDatatable = $_POST['search']['value'] ? $_POST['search']['value'] : "";

    if($searchDatatable != "")
    {
        $search_sku = strtoupper($searchDatatable);
    }

    $totalRecords = $sds->getTotalRecords($search_sku, $search_canal);

    // Consulta SQL con paginación
    $found = $sds->searchWithPagination($orderColumnIndex, $orderDirection, $start, $length, $search_sku, $search_canal);

    // Enviar datos en formato JSON para DataTables
    header('Content-Type: application/json');
    echo json_encode([
        "data" => $found,
        "draw" => intval($_POST['draw']),
        "recordsTotal" => $totalRecords[0]['cantidad'],
        "recordsFiltered" => $totalRecords[0]['cantidad'],
        "search" => $searchDatatable
    ]);
    exit;
} catch (Exception $e) {
    // Manejar excepciones y mostrar mensajes de error
    header('Content-Type: application/json');
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
?>
