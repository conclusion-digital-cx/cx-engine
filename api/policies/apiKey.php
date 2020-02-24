<?php

/*
 This policy will allow request with a valid ApiKey, 
 which is defined in your config.
*/

// $config = include("../config.php");

return function($config) {
    if(!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return false;
    }

    $auth = $_SERVER['HTTP_AUTHORIZATION'];
    $validApiKeys = $config->apiKeys;
    $resp = explode(" ", $auth);

    // Only allow valid formatted Authorization headers, e.g. Bearer <token>
    if(count($resp) !== 2) {
        return false;
    }

    list($type, $token) = $resp;
    if($type === "apiKey") {
        if (in_array($token, $validApiKeys)) {
            // Proceed
            return true;
        } else {
            return false;
        }
    }
};