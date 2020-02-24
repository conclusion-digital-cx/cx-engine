<?php

$config = include("config.php");
$createCx = require_once("./lib.php");
$cx = $createCx((object)$config);

// Map to Cx PHP REST API
$cx->router->map('GET|OPTIONS|PATCH|POST', '/api[*:trailing]', function($request) use ($config) {
    // print_r($request);
    $cxRestApi = include "./node_modules/cx-engine/api/lib.php";
    echo $cxRestApi($config, $request->trailing);
    exit();
});
