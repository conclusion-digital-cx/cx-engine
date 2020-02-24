<?php

use Ahc\Jwt\JWT;

$jwt = new JWT($config->tokenSecret);

return function ($config) use ($jwt) {
    // if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    //     return false;
    // }

    $fromHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;
    // $fromQuery = $_GET['token'] ?: null;
    // echo $fromQuery;
    // // echo $_GET['token'];
    // echo $auth =  $fromHeader ?: $fromQuery;
    $auth = $fromHeader;

    // Only allow provided token
    if (!$auth) {
        return false;
    }
    $resp = explode(" ", $auth);

    // Only allow valid formatted Authorization headers, e.g. Bearer <token>
    if (count($resp) !== 2) {
        return false;
    }

    // Spoof time() for testing token expiry:
    // $jwt->setTestTimestamp(time() + 10000);


    list($type, $token) = $resp;
    if ($type === "Bearer") {
        try {
            $resp = $jwt->decode($token);
            // print_r($resp);
            return true;
        } catch (Throwable $t) {
            // Token expired ?
            return false;
        }
    }
};
