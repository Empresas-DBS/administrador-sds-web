<?php 
namespace database;

class Web1
{
	public $link;
    public $host = "10.80.100.30"; // servidor
    public $dbName = "Rpro9";
    public $dbUser = "reportuser";
    public $dbPass = "report";
    public $protocol = "TCP";
    public $port = "1521";
    public $service = "rproods";
    public $result;
    public $stid;

    public function dbConnection()
    {
        //Dont justify this, or script show error in ora-connect
$tns="
(DESCRIPTION=

    (ADDRESS=

    (PROTOCOL=".$this->protocol.")

    (HOST=".$this->host.")

    (PORT=".$this->port.")

    )

    (CONNECT_DATA=

    (SERVICE_NAME=".$this->service.")

    )

)";
        // /Dont justify this, or script show error in ora-connect
            
        $this->link = oci_connect($this->dbUser, $this->dbPass, $tns);
        if (!$this->link) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        else {
            // echo 'conectado a Oracle - tablas de paso';
        }
    }

    public function dbQuery($query)
    {
        $this->stid = oci_parse($this->link, $query);
        oci_execute($this->stid);
    }

    // public function dbNumRows()
    // {
    //     return $this->result->num_rows;
    // }

    // public function dbGetResultByCompany()
    // {
    //     $i = 0;
    //     $array_rows = array();
    //     while ($row = oci_fetch_array($this->stid, OCI_ASSOC+OCI_RETURN_NULLS))
    //     {	
    //         $array_rows[0]["fecha"] = iconv('ISO-8859-1', 'UTF-8', ""); 
    //         $array_rows[0]["hora"] = iconv('ISO-8859-1', 'UTF-8', ""); 
    //         $array_rows[0]["total_peso"] = iconv('ISO-8859-1', 'UTF-8', $row["MONTO_ENCABEZADO"]); 
    //         $array_rows[0]["total_transacciones"] = iconv('ISO-8859-1', 'UTF-8', $row["CANTIDAD_TRX_ENCABEZADO"]); 
    //         $i++;
    //     }

    //     return $array_rows;
    // }

    public function dbClose()
    {
        oci_close($this->link);
    }
}