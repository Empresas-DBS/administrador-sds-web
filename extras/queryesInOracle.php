<?php 
namespace ora;

class OracleConnection
{
	public $link;
    public $host = "172.20.1.77"; // servidor
    public $dbName = "rpro";
    public $dbUser = "rpro";
    public $dbPass = "pdrpro";
    public $protocol = "TCP";
    public $port = "1526";
    public $service = "PRDSB12";
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

    public function dbGetResultByCompany()
    {
        $i = 0;
        $array_rows = array();
        while ($row = oci_fetch_array($this->stid, OCI_ASSOC+OCI_RETURN_NULLS))
        {	
            $array_rows[0]["fecha"] = iconv('ISO-8859-1', 'UTF-8', ""); 
            $array_rows[0]["hora"] = iconv('ISO-8859-1', 'UTF-8', ""); 
            $array_rows[0]["total_peso"] = iconv('ISO-8859-1', 'UTF-8', $row["MONTO_ENCABEZADO"]); 
            $array_rows[0]["total_transacciones"] = iconv('ISO-8859-1', 'UTF-8', $row["CANTIDAD_TRX_ENCABEZADO"]); 
            $i++;
        }

        return $array_rows;
    }

    public function dbGetResultByStores()
    {
        $i = 0;
        $array_rows = array();
        while ($row = oci_fetch_array($this->stid, OCI_ASSOC+OCI_RETURN_NULLS))
        {	
            $array_rows[$i]["fecha"] = iconv('ISO-8859-1', 'UTF-8', ""); 
            $array_rows[$i]["hora"] = iconv('ISO-8859-1', 'UTF-8', ""); 
            $array_rows[$i]["tienda"] = iconv('ISO-8859-1', 'UTF-8', $row["TIENDA"]); 
            $array_rows[$i]["total_peso"] = iconv('ISO-8859-1', 'UTF-8', $row["MONTO_ENCABEZADO"]); 
            $array_rows[$i]["total_transacciones"] = iconv('ISO-8859-1', 'UTF-8', $row["CANTIDAD_TRX_ENCABEZADO"]); 
            $array_rows[$i]["tipo_documento"] = iconv('ISO-8859-1', 'UTF-8', $row["TIPO_DOCUMENTO"]); 
            $i++;
        }

        return $array_rows;
    }

    public function dbGetResultEquivalences()
    {
        $i = 0;
        $array_rows = array();
        while ($row = oci_fetch_array($this->stid, OCI_ASSOC+OCI_RETURN_NULLS))
        {	
            $array_rows[$i]["det_cod_rpro"] = iconv('ISO-8859-1', 'UTF-8', $row["DET_COD_RPRO"]); 
            $array_rows[$i]["cod_oracle"] = iconv('ISO-8859-1', 'UTF-8', $row["COD_ORACLE"]); 
            $i++;
        }

        return $array_rows;
    }

    public function dbGetResultByTicket()
    {
        $i = 0;
        $array_rows = array();
        while ($row = oci_fetch_array($this->stid, OCI_ASSOC+OCI_RETURN_NULLS))
        {	
            $array_rows[$i]["fecha"] = iconv('ISO-8859-1', 'UTF-8', ""); 
            $array_rows[$i]["hora"] = iconv('ISO-8859-1', 'UTF-8', ""); 
            $array_rows[$i]["tienda"] = iconv('ISO-8859-1', 'UTF-8', $row["TIENDA"]); 
            $array_rows[$i]["boleta"] = iconv('ISO-8859-1', 'UTF-8', $row["FOLIO_DOCUMENTO"]); 
            $array_rows[$i]["total_peso"] = iconv('ISO-8859-1', 'UTF-8', $row["MONTO_ENCABEZADO"]); 
            $array_rows[$i]["tipo_documento"] = iconv('ISO-8859-1', 'UTF-8', $row["TIPO_DOCUMENTO"]); 
            $array_rows[$i]["codigo_autorizacion"] = iconv('ISO-8859-1', 'UTF-8', $row["CODAUT"]); 
            $i++;
        }

        return $array_rows; 
    }

    public function dbGetResultPayMethodOld()
    {
        $i = 0;
        $array_rows = array();
        while ($row = oci_fetch_array($this->stid, OCI_ASSOC+OCI_RETURN_NULLS))
        {	
            $array_rows[$i]["boleta"] = iconv('ISO-8859-1', 'UTF-8', $row["FOLIO_DOCUMENTO"]); 
            $array_rows[$i]["id_rpro"] = iconv('ISO-8859-1', 'UTF-8', $row["ID_RPRO"]); 
            $array_rows[$i]["fecha"] = iconv('ISO-8859-1', 'UTF-8', $row["FECHA_VENTA"]); 
            $array_rows[$i]["tipo_tarjeta"] = iconv('ISO-8859-1', 'UTF-8', $row["TIPO_TARJETA"]); 
            $i++;
        }

        return $array_rows; 
    }

    public function dbGetResultPayMethod()
    {
        $i = 0;
        $array_rows = array();
        while ($row = oci_fetch_array($this->stid, OCI_ASSOC+OCI_RETURN_NULLS))
        {	
            $array_rows[$i]["boleta"] = iconv('ISO-8859-1', 'UTF-8', $row["FOLIO_DOCUMENTO"]); 
            $array_rows[$i]["tipo_tarjeta"] = iconv('ISO-8859-1', 'UTF-8', $row["TIPO_TARJETA"]); 
            $i++;
        }

        return $array_rows; 
    }

    public function dbGetCountTrx()
    {
        $i = 0;
        $array_rows = array();
        while ($row = oci_fetch_array($this->stid, OCI_ASSOC+OCI_RETURN_NULLS))
        {	
            $array_rows[$i]["total_transacciones"] = iconv('ISO-8859-1', 'UTF-8', $row["TOTAL_TRANSACCIONES"]);
            $i++;
        }

        return $array_rows; 
    }

    // public function dbClose()
    // {
    //     mysqli_close($this->link);
    // }
}