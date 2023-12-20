<?php 
/**
 * *Este código es una clase PHP que se utiliza para administrar los registros de SKU's iluminados y su
 * *stock de seguridad (SDS).
 */

namespace process_ilu;
use Exception;

class sds
{
    private $cron_file;
    public $cronjobs_content = array();
    public $dbWeb1;
    public $tablename;

    /**
     * Esta función __construct() es una función de constructor para una clase.  
     * Define la tabla que se va a utilizar. 
     * 
     * @param void
     * 
     * @return void
     */
    function __construct() 
    {
        $this->tablename = "REPORTUSER.STOCK_ILUMINADO";
    }

    /**
     * Busca en la tabla REPORTUSER.STOCK_ILUMINADO el SKU entregado por parametro.
     * 
     * @return query result
     */
    public function search($search_sku, $canal = "") 
    {
        $sql = "SELECT 
            ID, 
            SBS_NO, 
            STORE_NO, 
            ALU,
            QTY, 
            SEGURIDAD, 
            TO_CHAR(DESDE, 'DD-MM-YYYY') as DESDE,
            TO_CHAR(HASTA, 'DD-MM-YYYY') as HASTA,
            CANAL 
        FROM 
            {$this->tablename} 
        WHERE 
            UPPER(ALU) LIKE '%{$search_sku}%'";

        if($canal != "")
        {
            $sql .= "AND CANAL = '{$canal}'";
        }

        // $sql .= "AND ROWNUM < 14";

        $this->dbWeb1->dbQuery($sql);
        return $this->dbWeb1->dbGetSearch();
    }

    /**
     * Elimina el registro por SKU y canal.
     * 
     * @return query result
     */
    public function delete($fvalues)
    {
        $alu = $fvalues["alu"];
        $canal = $fvalues["canal"];

        $sql = "DELETE FROM {$this->tablename} WHERE ALU = '{$alu}' AND CANAL = '{$canal}'";
        $res = $this->dbWeb1->dbQuery($sql);

        return $res;
    }

    public function truncate(){
        // $sql = "TRUNCATE TABLE {$this->tablename}";
        $sql = "DELETE FROM REPORTUSER.STOCK_ILUMINADO_DEV";
        $res = $this->dbWeb1->dbQuery($sql);

        return $res;
    }

    /**
     * Comprueba si el registro existe mediante un count a la tabla, por SKU y canal.
     * 
     * @return query result
     */
    public function checkIfexist($fvalues)
    {
        $alu = $fvalues["alu"];
        $canal = $fvalues["canal"];

        $sql = "SELECT COUNT(ID) as CANTIDAD FROM {$this->tablename} WHERE ALU = '{$alu}' AND CANAL = '{$canal}'";
        $this->dbWeb1->dbQuery($sql);
        return $this->dbWeb1->dbGetCount();
    }

    /**
     * Inserta un nuevo registro.
     * 
     * @return query result
     */
    public function insert($fvalues)
    {
        $count = $this->checkIfexist($fvalues);
        
        if(OVERWRITE)
        {
            $res = $this->delete($fvalues);

            if($res != 1)
                return "NOK_DELETE";
            else
            {
                $columnas = implode(', ', array_keys($fvalues));
                $valores = implode(', ', array_map(function($value) {
                    // Si es una cadena y no es una función TO_DATE, agregar comillas
                    return (is_string($value) && strpos($value, 'TO_DATE') === false) ? "'$value'" : $value;
                }, $fvalues));

                $sql = "INSERT INTO {$this->tablename} ($columnas) VALUES ($valores)";
                // var_dump($sql);die();
                $res = $this->dbWeb1->dbQuery($sql);
                
                if($res == 1)
                    return "OK";
                else
                    return "NOK_ERROR_INSERT";   
            }
        }
        elseif($count[0]["cantidad"] == 0)
        {
            $columnas = implode(', ', array_keys($fvalues));
            $valores = "'" . implode("', '", $fvalues) . "'";

            $sql = "INSERT INTO {$this->tablename} ($columnas) VALUES ($valores)";
            $res = $this->dbWeb1->dbQuery($sql);
            
            if($res == 1)
                return "OK";
            else
                return "NOK_ERROR_INSERT";      
        }
        else
            return "NOK_ALREADY_EXIST";

    }


    //buscador server side datatable con paginacion, buscador y ordenamiento
    public function searchWithPagination($orderColumnIndex, $orderDirection, $start, $length, $search_sku, $canal = ""){
        // Calcular el número de la página actual
        $page = $start / $length + 1;

        // Define las columnas permitidas para el ordenamiento datatable
        $allowedColumns = array('ID', 'SBS_NO', 'STORE_NO', 'ALU', 'QTY', 'SEGURIDAD', 'DESDE', 'HASTA', 'CANAL');

        // Verifica si la columna especificada está permitida para el ordenamiento
        $orderColumnName = in_array($orderColumnIndex, array_keys($allowedColumns)) ? $allowedColumns[$orderColumnIndex] : 'ID';

        // Construir la consulta SQL con paginación
        $sql = "SELECT
            ID,
            SBS_NO,
            STORE_NO,
            ALU,
            QTY,
            SEGURIDAD,
            TO_CHAR(DESDE, 'DD-MM-YYYY') as DESDE,
            TO_CHAR(HASTA, 'DD-MM-YYYY') as HASTA,
            CANAL
        FROM
            {$this->tablename}
        WHERE
            UPPER(ALU) LIKE '%{$search_sku}%'";

        if ($canal != "") {
            $sql .= " AND CANAL = '{$canal}'";
        }

        // Aplicar ordenamiento
        $sql .= " ORDER BY $orderColumnName $orderDirection";

        // Aplicar paginación
        $sql .= " OFFSET " . (($page - 1) * $length) . " ROWS FETCH NEXT {$length} ROWS ONLY";

        $this->dbWeb1->dbQuery($sql);
        return $this->dbWeb1->dbGetSearch();
    }

    public function exportToExcel() {

        $sql = "SELECT
            ID,
            SBS_NO,
            STORE_NO,
            ALU,";
        if(SUMAR){
            $sql .= "
                QTY - 1 as QTY,
                SEGURIDAD - 1 as SEGURIDAD,";
        }else{
            $sql .= "
                QTY,
                SEGURIDAD";
        }

        $sql .= "
            TO_CHAR(DESDE, 'DD-MM-YYYY') as DESDE,
            TO_CHAR(HASTA, 'DD-MM-YYYY') as HASTA,
            CANAL
        FROM
            {$this->tablename}
        ORDER BY ALU ASC";

        $this->dbWeb1->dbQuery($sql);
        $data = $this->dbWeb1->dbGetSearchCompact();
        return $data;
    }

    public function getTotalRecords($search_sku, $search_canal = ""){
        $sql = "SELECT COUNT(ID) as CANTIDAD FROM {$this->tablename}
        WHERE UPPER(ALU) LIKE '%{$search_sku}%'";

        if ($search_canal != "") {
            $sql .= " AND CANAL = '{$search_canal}'";
        }

        $this->dbWeb1->dbQuery($sql);
        return $this->dbWeb1->dbGetCount();
    }

    /**
     * Este código es una función privada que muestra un mensaje de error en la pantalla. La función toma un parámetro 
     * de entrada llamado $error, que es el mensaje de error a mostrar. La función usa la función die() para mostrar 
     * el mensaje de error con un estilo específico (en este caso, un color rojo).
     * 
     * USAGE: if (empty($cron_array)) $this->error_message("Nada para editar!  El archivo crontab esta vacío.");
     * 
     * @return void
     */
    private function error_message($error) 
    {
        echo "<pre style='color:#EE2711'>ERROR: {$error}</pre>";
        include "include/footer.php";
        die();
    }
}