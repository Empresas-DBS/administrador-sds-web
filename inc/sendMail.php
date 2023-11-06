<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('max_execution_time', 15000);
set_time_limit(0);
ini_set('memory_limit', '-1');

require("PHPMailer/class.phpmailer.php");
$mail = new PHPMailer();

$type = $_POST["type"];
$date = $_POST["date"];
$message = $_POST["message"];

$custom_message = "";
if($message != "")
{
    $custom_message = "Mensaje adicional: {$message}";
}
$body = "
<html>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <head>
        <title>Notificacion dashboard transacciones</title>
        <style>
            table, th, td {
            border: 1px solid black;
            text-align:center;
            }
        </style>
    </head>
    <body>
        <center>
            <br><br>
            <b>
                Revisar ventas dashboard transacciones (" . $date . ")
            </b>
            <br><br>

            <img src='https://consultasweb.dbs.cl/form/op1/images/LOGO_DBS_90.jpg' ><br><br>

            <p style='color:red;font-size:20px;'>
                <b>
                    Se solicita, por favor, revisar las ventas para la fecha {$date}, y responder este correo, para dar aviso a Josue Sanhueza.
                </b>
                <br>&nbsp;<br>
                <b>
                    {$custom_message}
                </b>
            <p>
            <br><br>

            Desarrollado por la Gerencia de Tecnología DBS

        </center>
    </body>
</html>
";

require("PHPMailer/config.php");

$mail->SMTPDebug  = 1;
$mail->CharSet = "UTF-8";
$mail->AddReplyTo("josue.sanhueza@empresasdbs.com", "Josue Sanhueza");
$mail->From = "notificaciones@dbschile.cl";
$mail->FromName = "Notificaciones DBS - Solicitud de revisión ";
$mail->AddAddress("ignacio.inchausti@empresasdbs.com");
$mail->AddAddress("saul.palma@empresasdbs.com");
$mail->AddAddress("cristian.ahumada@empresasdbs.com");
$mail->AddAddress("oswaldo.benitez@empresasdbs.com");
$mail->AddAddress("luis.castro@empresasdbs.com");
$mail->AddAddress("josue.sanhueza@empresasdbs.com");
$mail->AddAddress("constanza.vives@empresasdbs.com");
$mail->AddAddress("lorena.abud@empresasdbs.com");
$mail->AddAddress("barbara.sepulveda@empresasdbs.com");
$mail->AddAddress("hugo.ayal@empresasdbs.com");
$mail->IsHTML(true);
$mail->Subject = "Revisar fecha en dashboard trx.";
$mail->Body = $body;
$exito = $mail->Send();
unset($mail); 

if($exito)
    echo json_encode("SENT");
else
    echo json_encode("DONT_SENT");
