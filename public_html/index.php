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

// ==========
// Custom View engine
// ==========
class CxTemplate {
    function render($view, $slots = []) {
        global $db, $service;
        /**
		 * Template Helpers
		 */
		$asset = function ($dir = '') {
			return str_replace(APP, "", $dir);
		};
		// $service = function ($entity = '') {
		// 	// $cx = getCx();
		// 	// return new Service($entity, $cx->db);
		// };
		// $db = getCx()->db;

		$slot = function ($name) use ($slots) {
			$scope = isset($slots[$name]) ? $slots[$name] : null;
			// echo $name;
			// getCx()->debug($scope);

			if(is_string($scope)) {
				echo $scope;
			} else {
				$type = $scope[0];
				// getCx()->debug($type);

				// ['file' => 'view.php']
				if($scope['file']) {
					include $scope['file'];
				}

				// ['file', 'view.php']
				if($type === 'file') {
					include $scope[1];
				}
				echo isset($scope[$name]) ? $scope[$name] : '';
			}
        };
        
        $region = $slot;

        include $view;
    }
}

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

require_once "routes/web.php";
require_once "routes/api.php";

// If all fails
$router->use(function ($req, $res, $next) use ($app, $router) {
    // echo "$app->method $app->current\n";
    // $matches = $app->match($router);
    // print_r($matches);

    $res->status(400)->send("Not found");
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

