<?php

/**
 * Standalone
 */

$config = include("config.php");
$createCx = require_once("./lib.php");
$cx = $createCx((object)$config);

// Map to Cx PHP REST API
$cx->router->map('GET|OPTIONS|PATCH|PUT|POST|DELETE', '/api[*:trailing]', function($request) use ($config) {
    $cxRestApi = include "./node_modules/cx-engine/api/lib.php";
    echo $cxRestApi($config, $request->trailing);
    exit();
});
