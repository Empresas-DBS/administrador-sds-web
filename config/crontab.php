<?php 
/**
 * *Este código es una clase PHP que se utiliza para administrar trabajos cron con SSH2. Esta clase contiene métodos 
 * *para conectarse a un servidor remoto a través de SSH2, ejecutar comandos y administrar trabajos cron en el 
 * *servidor remoto. Los métodos incluyen escribir en un archivo, agregar tareas cron, eliminar tareas cron y 
 * *eliminar la tabla cron completa.
 */

namespace crontab;
use Exception;

class Ssh2_crontab_manager
{
    private $connection;
    private $path;
    private $handle;
    private $cron_file;
    public $cronjobs_content = array();

    /**
     * Esta función __construct() es una función de constructor para una clase. Establece la conexión SSH2 entre dos 
     * servidores, estableciendo los parámetros de conexión como $host, $port, $username y $password. Si alguno de 
     * los parámetros está ausente, se lanzará una excepción indicando que faltan datos en la tabla configuración. 
     * Si la conexión SSH2 no se puede establecer o si no se puede autenticar el usuario, también se lanzará una 
     * excepción. La función también establece el archivo cron_file a utilizar para la conexión.
     * 
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * 
     * @return void
     */
    function __construct($host=NULL, $port=NULL, $username=NULL, $password=NULL) 
    {
        $path_length = strrpos(__FILE__, "/");		
		$this->path  = substr(__FILE__, 0, $path_length) . '/';
        $this->handle    = 'crontab.txt';		
		$this->cron_file = "{$this->path}{$this->handle}";

        try
		{
			if ((is_null($host)) || (is_null($port)) || (is_null($username)) || (is_null($password))) 
                throw new \Exception("Faltan datos en la tabla configuración para conectar via ssh.");

            if (extension_loaded('ssh2'))
            {
                $this->connection = @ssh2_connect($host, $port);
                if ( ! $this->connection) 
                    throw new \Exception("La conexión SSH2 no se puede establecer.");
            }
            else
                throw new \Exception("La extensión ssh2 no está instalada en el servidor, debe instalar esta extensión para administrar archivos crontab remotos.");

            $authentication = @ssh2_auth_password($this->connection, $username, $password);
			if ( ! $authentication) 
                throw new \Exception("No se puede autenticar '{$username}' usando el password: '{$password}'.");
		}
		catch(\Exception $e)
		{
            $this->error_message($e->getMessage());
		}
    }

    /**
     * Este código es una función que se utiliza para ejecutar comandos a través de SSH. Primero, cuenta el número 
     * de argumentos pasados a la función. Si no hay argumentos, lanza una excepción. Si hay argumentos, los obtiene 
     * con la función func_get_args(). Luego, forma una cadena de comandos si hay más de un argumento o usa el primer 
     * argumento si sólo hay uno. Finalmente, intenta ejecutar el comando a través de ssh2_exec() y lanza una 
     * excepción si no es posible ejecutarlo. La función devuelve el objeto al final.
     * 
     * @return $this
     */
    public function exec() 
    {
        $argument_count = func_num_args();

        try
		{
			if ( ! $argument_count)
                throw new \Exception("No hay nada que ejecutar.");

            $arguments = func_get_args();

			$command_string = ($argument_count > 1) ? implode(" && ", $arguments) : $arguments[0];

            $stream = @ssh2_exec($this->connection, $command_string);
			if ( ! $stream) 
                throw new \Exception("No es posible ejecutar el comando: <br />{$command_string}");
		}
		catch(\Exception $e)
		{
            $this->error_message($e->getMessage());
		}

        return $this;
    }

    /**
     * Este código crea un archivo de cronograma para el usuario actual. Primero, verifica si el archivo de 
     * cronograma ya existe. Si no es así, establece la ruta y el controlador del archivo de cronograma y luego 
     * ejecuta un comando para inicializar el archivo de cronograma. Finalmente, devuelve la instancia actual.
     * 
     * @param $path
     * @param $handle
     * 
     * @return $this
     */
    public function write_to_file($path=NULL, $handle=NULL) 
    {
        if ( ! $this->crontab_file_exists())
		{		
			$this->handle = (is_null($handle)) ? $this->handle : $handle;
			$this->path   = (is_null($path))   ? $this->path   : $path;

			$this->cron_file = "{$this->path}{$this->handle}";

            $init_cron = "crontab -l > {$this->cron_file} && [ -f {$this->cron_file} ] || > {$this->cron_file}";

            $this->exec($init_cron);
		}

        return $this;
    }

    /**
     * Este código lee un archivo de cronjobs. Comienza guardando el contenido del archivo en un archivo temporal 
     * y luego abre el archivo en modo de lectura. Lee línea por línea del archivo y agrega cada línea al contenido 
     * de la variable. Finalmente, cierra el archivo y elimina el archivo temporal.
     * 
     * @return void
     */
    public function read_cronjobs_file($host)
    {
        $temp_file_to_open = "http://" . $host . str_replace("var/www/html/", "", $this->path) . $this->handle;
        $this->cronjobs_content = array();
        $this->write_to_file();

        $fp = fopen($temp_file_to_open, "r");

        while(!feof($fp))
        {
            $line = fgets($fp);
            $this->cronjobs_content[] = $line;
        }

        fclose($fp);

        $this->remove_file();
    }

    /**
     * Este código es una función de una clase que se encarga de eliminar un archivo crontab. La función comprueba 
     * primero si el archivo crontab existe utilizando la función "crontab_file_exists()". Si el archivo existe, 
     * entonces ejecuta el comando "rm {$this->cron_file}" para eliminarlo. Finalmente, devuelve la instancia de la 
     * clase.
     * 
     * @return $this
     */
    public function remove_file() 
    {
        if ($this->crontab_file_exists()) $this->exec("rm {$this->cron_file}");

		return $this;
    }

    /**
     * Este código es una función que se utiliza para agregar tareas cron a un archivo de cron. La función toma un 
     * parámetro opcional, $cron_jobs, que puede ser una tarea cron o un array de tareas cron. Si el parámetro es nulo, 
     * la función generará un mensaje de error. Luego, la función agregará la tarea o el array de tareas al archivo de 
     * cron. Finalmente, la función ejecutará el archivo de cron y lo eliminará.
     * 
     * @return $this
     */
    public function append_cronjob($cron_jobs = NULL) 
    {
        if (is_null($cron_jobs)) 
            $this->error_message("Nada para agregar!  por favor especifique una tarea cron o un array de tareas cron.");

        $append_cronfile = "echo '";	
		
		$append_cronfile .= (is_array($cron_jobs)) ? implode("\n", $cron_jobs) : $cron_jobs;
		
		$append_cronfile .= "'  >> {$this->cron_file}";

        $install_cron = "crontab {$this->cron_file}";

		$this->write_to_file()->exec($append_cronfile, $install_cron)->remove_file();
		
		return $this;
    }

    /**
     * Este código es una función que se utiliza para eliminar un trabajo programado (cronjob) desde un archivo cronTab. 
     * La función toma como parámetro un trabajo programado o un arreglo de trabajos programados. Luego, el código 
     * escribe en el archivo cronTab y luego lee el contenido del archivo. Si el contenido está vacío, se muestra un 
     * mensaje de error. Después de eso, se compara la cantidad original de trabajos programados con la cantidad 
     * después de eliminar los trabajos programados especificados. Si son iguales, se elimina el archivo, de lo 
     * contrario, se elimina el contenido del cronTab y luego se agregan los trabajos programados restantes al 
     * archivo.
     * 
     * @param $cron_jobs
     * 
     * @return eval
     */
    public function remove_cronjob($cron_jobs = NULL) 
    {
        if (is_null($cron_jobs)) $this->error_message("Nada para borrar!  Por favor especifica la tarea cron, o un array de tareas cron.");
		
		$this->write_to_file();

		$cron_array = file($this->cron_file, FILE_IGNORE_NEW_LINES);
		
		if (empty($cron_array)) $this->error_message("Nada para borrar!  El archivo crontab esta vacío.");
		
		$original_count = count($cron_array);

        if (is_array($cron_jobs))
		{
			foreach ($cron_jobs as $cron_regex) $cron_array = preg_grep($cron_regex, $cron_array, PREG_GREP_INVERT);
		}
		else
		{
            $cron_array = preg_grep($cron_jobs, $cron_array, PREG_GREP_INVERT);
		}	

        return ($original_count === count($cron_array)) ? $this->remove_file() : $this->remove_crontab()->append_cronjob($cron_array);
    }

    public function toggle_comment_cronjob($cron_jobs = NULL, $orig_cron_line = NULL) 
    {
        if (is_null($cron_jobs)) $this->error_message("Nada para editar!  Por favor especifica la tarea cron, o un array de tareas cron.");
		
		$this->write_to_file();

		$cron_array = file($this->cron_file, FILE_IGNORE_NEW_LINES);
		
		if (empty($cron_array)) $this->error_message("Nada para editar!  El archivo crontab esta vacío.");
		
		$original_count = count($cron_array);

        if (is_array($cron_jobs))
		{
			// foreach ($cron_jobs as $cron_regex) 
            //     $cron_array = preg_grep($cron_regex, $cron_array, PREG_GREP_INVERT);
		}
		else
		{
            $cron_jobs = trim($cron_jobs);
            $key = array_search($cron_jobs, $cron_array);
            $line = $cron_array[$key];

            if (strpos($cron_array[$key], "#") !== 0)
            {
                $cron_array[$key] = "#" . $line;
                $resp = "add_comment";
            }
            else
            {
                
                $line = str_replace("#", "", $line);
                $line = str_replace("# ", "", $line);
                $cron_array[$key] = $line;
                $resp = "del_comment";
            }
		}	
        
        $this->remove_crontab()->append_cronjob($cron_array);
        return $resp;
    }

    /**
     * !Atención
     * Este código es una función que se encarga de eliminar la crontab. La función utiliza el comando "crontab -r" 
     * para eliminar la configuración de la crontab. Luego llama a la función remove_file() para eliminar el 
     * archivo de configuración de la crontab. Finalmente, devuelve el objeto actual para permitir encadenar métodos.
     * 
     * @return $this
     */
    public function remove_crontab() 
    {
        $this->exec("crontab -r")->remove_file();
		
		return $this;
    }

    /**
     * Esta función comprueba si un archivo especificado por la variable $this->cron_file existe. Devuelve un valor 
     * booleano (true o false) dependiendo de si el archivo existe o no.
     * 
     * @return eval(file_exists)
     */
    private function crontab_file_exists() 
    {
        return file_exists($this->cron_file);
    }

    /**
     * Este código es una función privada que muestra un mensaje de error en la pantalla. La función toma un parámetro 
     * de entrada llamado $error, que es el mensaje de error a mostrar. La función usa la función die() para mostrar 
     * el mensaje de error con un estilo específico (en este caso, un color rojo).
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