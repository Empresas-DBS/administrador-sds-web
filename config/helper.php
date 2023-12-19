<?php 
/*
 * All custom functions, reusable functions are placed in this file.
 */
namespace common;

class Helper
{
    public $db;
    public $host;
    public $port;
    public $username;
    public $password;

    public function __construct()
	{
        // Nothing
    }

    public function dbConnect()
    {
        $this->db->dbConnection();
    }

    public function dbDisconnect()
    {
        $this->db->dbClose();
    }

    public function get_config($key)
    {
        $sql = "SELECT valor from configuracion where llave = '{$key}'";
        $this->db->dbQuery($sql);
        $result = $this->db->dbGetResult();
        
        return $result[0]["valor"];
    }

    public function add_config($key, $val)
    {
        $sql = "INSERT into configuracion (llave, valor) values ('{$key}', '{$val}')";
        $this->db->dbQuery($sql);
    }

    public function update_config($key, $val)
    {
        $sql = "UPDATE configuracion set valor = '{$val}' where llave = '{$key}'";
        $this->db->dbQuery($sql);
    }

    public function formatDate($date)
    {
        $date = \DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        return $date;
    }

    public function validateDate($dateStart, $dateEnd)
    {
        if($dateStart > $dateEnd)
        {
            return false;
        }
        return true;
    }
}