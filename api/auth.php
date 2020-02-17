<?php

$config = include("../config.php");

// GET /:entity
use Ahc\Jwt\JWT;

require_once("./lib/jwt/ValidatesJWT.php");
require_once("./lib/jwt/JWTException.php");
require_once("./lib/jwt/JWT.php");
require_once("./utils.php");    // Responses

// Instantiate with key, algo, maxAge and leeway.
$jwt = new JWT($config->tokenSecret);

// $token = $jwt->encode([
//     'uid'    => 1,
//     'aud'    => 'http://site.com',
//     'scopes' => ['user'],
//     'iss'    => 'http://api.mysite.com',
// ]);

// $jwt->decode($token);




$router->map('POST', '/login', function ($params)
use ($db, $jwt) {
    $entity = "users";
    $body = getJsonBody();
    // print_r($body);

    $fields = isset($_GET['fields']) ? $_GET['fields'] : '*';
    $result = $db->get($entity, $fields, [
        'email' => $body->email,
        'password' => $body->password
    ]);

    if(!$result) {
        return notFoundResponse("Not found");
    }

    // Create JWT
    $token = $jwt->encode([
        'uid'    => $result['id'],
        // 'aud'    => 'http://site.com',
        'scopes' => ['user'],
        // 'iss'    => 'http://api.mysite.com',
    ]);
    
    $result['token'] = $token;
    jsonResponse($result);
});
