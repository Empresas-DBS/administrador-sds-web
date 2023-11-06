<?php
include "api/communication.php";
include 'system/init.php';

$server = new server\server_manager;
$helper = new common\Helper;
 
$server->db = new dev\Database;
$helper->db = new dev\Database;

$server->dbConnect();
$helper->dbConnect();

$alias = $_POST["serverAlias"];
$host = $_POST["serverHost"];
$port = $_POST["serverPort"];
$username = $_POST["serverUser"];
$password = $_POST["serverPassword"];

$server->get_server_count();
$server->next_count_prefix();
$helper->add_config("h{$server->new_count}_alias", $alias);
$helper->add_config("h{$server->new_count}_host", $host);
$helper->add_config("h{$server->new_count}_port", $port);
$helper->add_config("h{$server->new_count}_username", $username);
$helper->add_config("h{$server->new_count}_password", $password);
$helper->update_config("host_count", $server->new_count);


$server->dbDisconnect();
$helper->dbDisconnect();

header("location: server-list.php");