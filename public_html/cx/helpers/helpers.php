<?php

/**
 *  Helpers
 * */



return [
    $render = require "./renderFile.php",

    $renderView = function ($path, $context = "") use ($render) {
        return $render(__DIR__ . "/views$path.php", $context);
    },
    
    $loadRegionsFrom = function ($path) use ($render) {
        // include "./views/experiences/index.php";
        $regions['layout'] = $render(__DIR__ . "/views$path/layout.php");
        $regions['main'] = $render(__DIR__ . "/views$path/main.php");
        $regions['footer'] = $render(__DIR__ . "/views$path/footer.php");
        // $regions['nav'] = $render("./themes/bb/blocks/nav.php");
        $setRegions = array_filter($regions);
        return $setRegions ? $regions : false;
    },
    
    $fetchRaw = function ($url, $body = array(), $headers = array()) {
        $token = "apiKey veryVerySecretInSomeWay";
        $headers = array_merge(array(
            'Accept: application/json',
            'Content-Type: application/json',
            "Authorization: $token"
            // 'Authorization: Basic '. base64_encode("app_key:app_secret")
        ), $headers);
    
        $options = array(
            'http' => array(
                'method' => "GET",
                'header' => $headers
            )
        );
        debugToConsole($url);
        $context = stream_context_create($options);
        return file_get_contents($url, false, $context);
    },

    $fetch = function ($url, $body = array(), $headers = array()) use ($fetchRaw) {
        $resp = $fetchRaw($url, $body = array(), $headers = array());
        return json_decode($resp);
    },

    $service = function ($endpoint, $body = array(), $headers = array()) use ($fetchRaw, $config) {
        $api = $config->api; // "http://localhost:3030/api/v1";
        $resp = $fetchRaw("$api$endpoint", $body = array(), $headers = array());
        return json_decode($resp);
    }
];
