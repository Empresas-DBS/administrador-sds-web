<?php
include "api/communication.php";
include 'system/init.php';

$server = new server\server_manager;
$helper = new common\Helper;
 
$server->db = new dev\Database;
$helper->db = new dev\Database;

$server->dbConnect();
$helper->dbConnect();

$prefix = $_POST["hidden_prefix"];
$alias = $_POST["serverAlias"];
$host = $_POST["serverHost"];
$port = $_POST["serverPort"];
$username = $_POST["serverUser"];
$password = $_POST["serverPassword"];

$helper->update_config("{$prefix}alias", $alias);
$helper->update_config("{$prefix}host", $host);
$helper->update_config("{$prefix}port", $port);
$helper->update_config("{$prefix}username", $username);
$helper->update_config("{$prefix}password", $password);


$server->dbDisconnect();
$helper->dbDisconnect();

header("location: server-list.php");