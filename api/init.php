<?php
include dirname(__FILE__) . "/vars.php";
include dirname(__FILE__) . "/config.class/rest.php";

$dr = new dbsresources\Communication(_APP_TOKEN_, _DEV_TOKEN_);

$buttonLogin = $dr->getLoginButton();