<?php

$config = require("./config.php");

define("CX", __DIR__);
define("APP", __DIR__);
define("CONFIG", (array)$config);

// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL & ~E_NOTICE);

include __DIR__ . '/lib/Express.php';

$app = new Express();
$router = new Router();

$app->set('basePath', '');

/**
 * Simple helper to debug to the console
 *
 * @param $data object, array, string $data
 * @param $context string  Optional a description.
 *
 * @return string
 */
function d($data, $context = 'Debug in Console')
{
	// Buffering to solve problems frameworks, like header() in this and not a solid return.
	ob_start();

	$output = "";
	// $output  = 'console.info(\'' . $context . ':\');';
	$output .= 'console.log(' . json_encode($data) . ');';
	$output  = sprintf('<script>%s</script>', $output);

	echo $output;
}

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

// ==========
// app routes...
// ==========
$corsMiddleware = include("./middleware/cors.php");
$router->use($corsMiddleware());

// $router->get("/",function ($req, $res, $next) {
//     $res->send("Cool");
// });

// Global closure
$service = require_once("./lib/Service.php");

// Web
require_once "routes/web.php";

// API , IMPORTANT currently no authentication, disable in production !!!!!!!!!!!
require_once "routes/api.php";

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

