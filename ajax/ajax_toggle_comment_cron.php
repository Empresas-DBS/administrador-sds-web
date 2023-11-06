<?php
include '../system/init.php';

$helper = new common\Helper;
$helper->db = new dev\Database;

$cron_line = $_POST["task"]; 

// *Este código usa la función addcslashes() para agregar barras diagonales inversas antes de cualquier barra diagonal en la variable $cron_line. 
// *Las barras diagonales inversas se utilizan para escapar de las barras diagonales para que no se interpreten como caracteres especiales.
$addcslashes_cron_line = addcslashes($cron_line, "/");

// *Este código toma una cadena, $addcslashes_cron_line, y la divide en una matriz de cadenas mediante la función explotar(). 
// *La función explotar() usa " " (un espacio) como delimitador para separar las partes individuales de la cadena. La función implode() 
// *luego toma esas piezas y las combina nuevamente en una sola cadena, pero solo usa los elementos del índice 5 en adelante. 
// *Esto corta efectivamente cualquier elemento antes del índice 5.
$pieces = explode(" ", $addcslashes_cron_line);
$orig_cron_line = implode(" ", array_slice($pieces, 5));

// *Quito saltos de linea
$orig_cron_line = str_replace(array("\r", "\n"), "", $orig_cron_line);
$orig_cron_line = "/" . $orig_cron_line . "/";

$helper->dbConnect();
$host_prefix_default = $helper->get_config("host_prefix_default");
$helper->host = $helper->get_config("{$host_prefix_default}host");
$helper->port = $helper->get_config("{$host_prefix_default}port");
$helper->username = $helper->get_config("{$host_prefix_default}username");
$helper->password = $helper->get_config("{$host_prefix_default}password");

$crontab = new crontab\Ssh2_crontab_manager($helper->host, $helper->port, $helper->username, $helper->password);

/**
 * *Eliminar un trabajo cron
 */
$resp = $crontab->toggle_comment_cronjob($cron_line, $orig_cron_line);

$helper->dbDisconnect();

echo json_encode("{$resp}");