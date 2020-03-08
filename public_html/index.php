<?php

$config = require("./config.php");

define("CX", __DIR__);
define("APP", __DIR__);
define("CONFIG", (array)$config);

// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL & ~E_NOTICE);

include __DIR__ . '/lib/Express.php';

// Create App
$app = new Express();
$router = new Router();

// Setup basePath
$app->set('basePath', '');

// ==========
// Custom View engine
// ==========
include __DIR__ . '/lib/CxTemplate.php';
$app->set('view_engine',new CxTemplate());

// ==========
// Setup Database connection 
// ==========
require_once "lib/Medoo.php";
use Medoo\Medoo;
// Validate config
$config = require("./config.php");
if (!$config->db) {
    throw new Error("config->db is required to be set");
}
// Initialize ORM
$db = new Medoo($config->db);

// Global closure ?
// $service = require_once(__DIR__ ."/lib/Service.php");

// =============
// Handle autoloaded plugins
// =============
$autoload = $config->autoload;
foreach($autoload as $key => $value) {
    $directory = is_array($value) ? $key : $value;
    include __DIR__."/plugins/$directory/index.php";
}

// If all fails
$router->use(function ($req, $res, $next) use ($app, $router) {
    // echo "$app->method $app->current\n";
    // $matches = $app->match($router);
    // print_r($matches);
    $res->status(400)->send("Not found: $req->path");
});

// ==========
// start app
// ==========
try {
    $app->listen($router);
} catch (Throwable $t) {
    // print_r($t);
    http_response_code(400);
    echo "ai\n";
    echo $t;
}

