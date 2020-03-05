<?php

return function ($url, $body = array(), $headers = array()) {
    $token = "apiKey veryVerySecretInSomeWay";
    $headers = array_merge(array(
        'Accept: application/json',
        'Content-Type: application/json',
        "Authorization: $token"
        // 'Authorization: Basic '. base64_encode("app_key:app_secret")
    ), $headers);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
};