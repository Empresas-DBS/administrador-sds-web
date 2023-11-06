<?php
include '../system/init.php';

$helper = new common\Helper;
$helper->db = new dev\Database;

if(!isset($_POST))
    die("No se envió post.");

$prefix = $_POST["prefix"];

$helper->dbConnect();

$host_prefix_default = $helper->update_config("host_prefix_default", $prefix);

$helper->dbDisconnect();

echo json_encode("changed");