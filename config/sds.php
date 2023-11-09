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
        $this->tablename = "REPORTUSER.STOCK_ILUMINADO_DEV";
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
            DESDE, 
            HASTA, 
            CANAL 
        FROM 
            {$this->tablename} 
        WHERE 
            UPPER(ALU) LIKE '%{$search_sku}%'";

        if($canal != "")
        {
            $sql .= "AND CANAL = '{$canal}'";
        }

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
                $valores = "'" . implode("', '", $fvalues) . "'";

                $sql = "INSERT INTO {$this->tablename} ($columnas) VALUES ($valores)";
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