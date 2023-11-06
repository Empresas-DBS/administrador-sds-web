<?php
/*
 * Define constants
 */
define("API_URL", "");
define("API_MAIL", "");
define("API_TOKEN", "");
define("DB_HOST", "");
define("DB_NAME", "");
define("DB_USER", "");
define("DB_PASS", "");
define("SHOW_DEBUG_INFO", true);
define("SHIPPING_METHOD", "Despacho");

/* 
 * Get all classes in config folder, and includes in app
 */
$directory = dirname(__FILE__) . '/../config/';
$files  = array_diff(scandir($directory), array('..', '.'));

foreach($files as $file)
{
    $ext = explode(".", $file)[1];
    if(($ext == "php") || ($ext == "PHP"))
    {
        if($file == "_debug.php")
        {
            if(SHOW_DEBUG_INFO)
            {
                include $directory . $file;
            }
        }
        else
        {
            include $directory . $file;
        }
    }
}

/* 
 * Get all classes in extra folder, and includes in app
 */
$extraDirectory = dirname(__FILE__) . '/../extras/';
$files  = array_diff(scandir($extraDirectory), array('..', '.'));

foreach($files as $file)
{
    $ext = explode(".", $file)[1];
    if(($ext == "php") || ($ext == "PHP"))
    {
        include $extraDirectory . $file;
    }
}