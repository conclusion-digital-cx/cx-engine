<?php

define("CX", __DIR__);
define("APP", __DIR__);

// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL & ~E_NOTICE);

// Global helper
// function asset($dir)
// {
//     return str_replace(__DIR__, "", $dir);
// }

$config = require(CX."/config.php");
$createCx = require(CX."/cx/Cx.php");
$cx = $createCx((object) $config);

$createRoutes = require(CX."/cx/routes.php");
$rootRouter = $cx->router;
$createRoutes($rootRouter, $cx);

// $cx->debug($cx->router->getRoutes());

// Match route
$cx->listen();
