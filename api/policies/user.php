<?php

$config = include("../../config.php");

return function() use ($config) {
    if(!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        header($_SERVER["SERVER_PROTOCOL"] . ' 401');
        exit("401 Not authorized");
    }

    $auth = $_SERVER['HTTP_AUTHORIZATION'];
    $validApiKeys = $config->apiKeys;
    list($type, $token) = explode(" ", $auth);

    if($type === "apiKey") {
        if (in_array($token, $validApiKeys)) {
            // Proceed
            return true;
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . ' 401');
            exit("401 Not authorized");
        }
    } else if($type === "Bearer") {
        header($_SERVER["SERVER_PROTOCOL"] . ' 401');
        exit("Bearer login is currently not supported");
    }
}