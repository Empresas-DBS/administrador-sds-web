<?php 
/**
 * *Este código es una clase PHP que se utiliza para administrar trabajos cron con SSH2. Esta clase contiene métodos 
 * *para conectarse a un servidor remoto a través de SSH2, ejecutar comandos y administrar trabajos cron en el 
 * *servidor remoto. Los métodos incluyen escribir en un archivo, agregar tareas cron, eliminar tareas cron y 
 * *eliminar la tabla cron completa.
 */

namespace server;

class server_manager
{
    public $db;
    private $server_count;
    public $new_count;
    public $all;

    /**
     * 
     * 
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * 
     * @return void
     */
    function __construct() 
    {
        // parent::__construct();
    }

    public function dbConnect()
    {
        $this->db->dbConnection();
    }

    public function dbDisconnect()
    {
        $this->db->dbClose();
    }

    public function get_server_count()
    {
        $sql = "SELECT valor from configuracion where llave = 'host_count'";
        $this->db->dbQuery($sql);
        $result = $this->db->dbGetResult();
        
        if(isset($result[0]["valor"]))
            $this->server_count = $result[0]["valor"];
        else
            die("No se ha definido ninguna llave host_count con un vaor válido (0-9)");
    }

    public function next_count_prefix()
    {
        $this->new_count = $this->server_count + 1;
    }

    public function get_list()
    {
        $sql = "SELECT * from configuracion where llave like '%alias' order by llave ASC";
        $this->db->dbQuery($sql);
        $result = $this->db->dbGetResult();

        if(count($result) > 0)
            $this->all = $result;
        else
            $this->all = 0;
    }

    public function get_data($prefix)
    {
        $this->all = array();
        $sql = "SELECT * from configuracion where llave like '{$prefix}%'";
        $this->db->dbQuery($sql);
        $result = $this->db->dbGetResult();

        if(count($result) > 0)
        {
            foreach($result as $key => $server)
            {
                $this->all[$server["llave"]] = $server["valor"];
            }
        }
            
        else
            $this->all = 0;
    }
}