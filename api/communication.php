<?php
include dirname(__FILE__) . "/vars.php";
include dirname(__FILE__) . "/config.class/rest.php";

$dr = new dbsresources\Communication(_APP_TOKEN_, _DEV_TOKEN_);

// Si en la url vienen estas variables por get, es por que se redirecciono desde el Super Sign
// así que creo las cookies con el id de sesión y el correo del usuario,
// de lo contrario, leo las cookies creadas anteriormente
if((isset($_GET["sessid"]))&&(isset($_GET["sessmail"])))
{
    $inserted_id_session = base64_decode($_GET["sessid"]);
    $client_dbs_mail = base64_decode($_GET["sessmail"]);

    setcookie("sessid", $inserted_id_session, 0, "/");
    setcookie("sessmail", $client_dbs_mail, 0, "/");
}
else
{
    $inserted_id_session = $_COOKIE["sessid"];
    $client_dbs_mail = $_COOKIE["sessmail"];
}

$sess_data = $dr->getPermissionsFromSession($client_dbs_mail, $inserted_id_session);
if( ! is_array($sess_data))
{
    // header("location: http://10.30.70.70/administrador-sds-web/login.php");
    header("location: https://10.30.61.110/REPOSITORIO/administrador-sds-web/login.php");
    die();
}
else
{
    $user_data = $dr->getUserDataFromSession($inserted_id_session);
    $buttonLogout = $dr->destroySessionButton($client_dbs_mail, $inserted_id_session);

    $rol_name = $sess_data[0]->rol_nombre;
    $rol_value = $sess_data[0]->rol_valor;
    $rol_description = $sess_data[0]->rol_descripcion;
    $rol_eq = $sess_data[0]->equivalencia;
}