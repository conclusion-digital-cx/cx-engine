<?php


/**
 * Webpages
 */
$router->get("/login", function ($req, $res, $next) {
    include __DIR__ . "/views/login.php";
});

$router->get("/signup", function ($req, $res, $next) {
    include __DIR__ . "/views/signup.php";
});


/** 
 * API
 */

use Ahc\Jwt\JWT;

require_once(__DIR__."/lib/jwt/ValidatesJWT.php");
require_once(__DIR__."/lib/jwt/JWTException.php");
require_once(__DIR__."/lib/jwt/JWT.php");

$router->post("/login", function ($req, $res, $next) use ($db, $config) {
    $entity = "users";
    
    // Find user
    $fields = '*';
    $where = [
        'email' => $req->body->username,
        // 'username' => $req->body->username,
    ];
    $result = $db->get($entity, $fields, $where);

    // Not found
    if (!$result) {
        return $res->status(400);
    }

    // Check hashed password
    // print_r($result);
    $hashed_password = $result['password'];
    $isValid = password_verify($req->body->password, $hashed_password);
    if ($isValid) {
        // Instantiate with key, algo, maxAge and leeway.
        $jwt = new JWT($config->tokenSecret);
        // Create JWT
        $token = $jwt->encode([
            'uid'    => $result['id'],
            // 'aud'    => 'http://site.com',
            'scopes' => ['user'],
            // 'iss'    => 'http://api.mysite.com',
        ]);

        return $res->status(200)->json([
            'message'=>'welcome',
            'token'=> $token
        ]);
    } else {
        return $res->status(400);
    }
});

$router->post("/signup", function ($req, $res, $next) use ($db) {
    $entity = "users";

    $fields = '*';
    $result = $db->get($entity, $fields, [
        'username' => $req->body->username,
        'password' => password_verify($req->body->password, PASSWORD_BCRYPT)
    ]);

    $res->json($result);
});
