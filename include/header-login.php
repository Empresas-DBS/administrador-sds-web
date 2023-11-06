<?php
$url = $_SERVER["REQUEST_URI"];
$explodeUrl = explode("/", $url);
$file = $explodeUrl[count($explodeUrl) - 1];
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>DBS Chile - Dashboard para administrar crontab - Inicie sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->
    
    <link href="main.css?v=<?php echo date("YmdHms"); ?>" rel="stylesheet">
    <link id="bsdp-css" href="assets/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link id="bsdp-css" href="assets/css/jquery.dataTables.min.css" rel="stylesheet">
    <link id="bsdp-css" href="assets/css/dropzone.min.css" rel="stylesheet">
    <link  rel="icon" type="image/x-icon" href="https://dbs.cl/media/favicon/stores/1/_01_13-32-29.png" />
    <link  rel="shortcut icon" type="image/x-icon" href="https://dbs.cl/media/favicon/stores/1/_01_13-32-29.png" />

</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow bg-premium-dark header-text-light">
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>   
        </div>       
        <div class="app-main">
                