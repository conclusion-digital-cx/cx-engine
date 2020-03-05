<?php

/**
 *  Default Cx Routes
 * */

$config = require(CX . "/config.php");


return function ($router, $cx) use ($config) {

    // $router->map('GET', '/', function ($req, $res) {
    //     // echo "Welcome";
    //     $regions['main'] = ['file', APP . "/views/home/main.php"];
    //     $res->render(APP . "/views/home/layout.php", $regions);
    // });

    $router->map('GET', '/hello', function ($req, $res) {
        $res->send("Hello World");
    });


    // Map /api/* to Cx REST API
    $router->map('GET|OPTIONS|PATCH|POST|DELETE', '/api', function ($req, $res) use ($config) {
        $pack = file_get_contents(APP . "/package.json");
        $pack = json_decode($pack);

        echo <<<HTML
        <h1>CxEngine API</h1>
        <p>Version: $pack->version</p>
        HTML;
        exit();
    });

    $corsMiddleware = require __DIR__ . "/routes/cors/index.php";

    /** 
     * Plugin: restapi-tasks
     */
    $router->map('GET|OPTIONS|PATCH|POST|DELETE', '/api/_tasks/[*:trailing]', $corsMiddleware());
    $router->map('GET|OPTIONS|PATCH|POST|DELETE', '/api/_tasks[*:trailing]?', function ($req, $res) use ($cx, $config) {
        $req->trailing = isset($req->trailing) ? $req->trailing : "";
        // echo $req->trailing;

        $create = require __DIR__ . "/routes/restapi-tasks/lib.php";
        $router = $create($config, new Router);
        echo $cx->_listen($router, $req->trailing);
    });

    /** 
     * Restfull API + specials ( TO DEPRECATE ? )
     */
    $router->map('GET|OPTIONS|PATCH|POST|DELETE', '/api[*:trailing]', $corsMiddleware());
    $router->map('GET|OPTIONS|PATCH|POST|DELETE', '/api[*:trailing]', function ($req, $res) use ($config) {
        $create = require __DIR__ . "/routes/api/lib.php";
        $create($config, $req->trailing);
        exit();
    });
    // $router->map('GET|OPTIONS|PATCH|POST|DELETE', '/api[*:trailing]', $corsMiddleware());
    // $router->map('GET|OPTIONS|PATCH|POST|DELETE', '/api[*:trailing]', function ($req, $res) use ($config) {
    //     $create = require __DIR__ . "/routes/api/lib.php";
    //     // $routes = $create($config, $router);
    //     $router = $create($config, new Router);
    //     return $router;
    // });

    /** 
     * Restfull API ( WIP )
     */
    $router->map('GET|OPTIONS|PATCH|POST|DELETE', '/api/content/[*:trailing]', $corsMiddleware());
    $router->map('GET|OPTIONS|PATCH|POST|DELETE', '/api/content/[*:trailing]', function ($req, $res) use ($cx, $config) {
        $create = require __DIR__ . "/routes/restapi/lib.php";
        // $routes = $create($config, $router);
        $router = $create($config, new Router);
        // debugToConsole($router);
        echo $cx->_listen($router, $req->trailing);
    });

    // $router->map('GET', '/admin/', function ($req, $res) {
    //     require APP . "/admin/index.html";
    // });

    // Handle File based pages
    // Map /* to file at /views/*.php
    $router->map('GET', '[*:trailing]?', function ($req, $res) use ($cx) {
        $path =  $req->trailing;

        // Debug
        $cx->debug($path);

        $file = APP . "/views$path.php";
        $exists = file_exists($file);
        if($exists) {
            $regions = [
                'main' => ['file' => $file]
            ];
            $theme = CONFIG['theme'];
            $layout = $regions['layout'] ?: "./themes/{$theme}/index.php";
            $res->render($layout, $regions);
        } else {
            return true;    // proceed to next
        }
    });


    // Handle Database based pages
    // Map /* to file at /views/*.php
    $router->map('GET', '[*:trailing]?', function ($req, $res) use ($cx) {
        $path =  $req->trailing;

        // Get page from database
        // $page = file_get_contents("http://localhost:8666/api/pages?url=/");
        $page = $cx->service("pages")->getOne([
            'url'=>$path    // TODO
        ]);

        // Debug
        $cx->debug($page);

        $regions = [
            'main' => $page['body']
        ];

        $theme = CONFIG['theme'];
        $layout = $page['layout'] ?: APP."/themes/{$theme}/index.php";
        $res->render($layout, $regions);
    });



    // Handle File based pages
    // Map /* to file at /views/*.php
    $router->map('GET', '[*:trailing]?', function ($req, $res) use ($cx) {
        $path =  $req->trailing;

        // Debug
        $cx->debug($path);

        $loadRegionsFrom = function ($path) {
            $regions['layout'] = ['file' => APP . "/views$path/layout.php"];
            $regions['main'] = ['file' => APP . "/views$path/main.php"];
            $regions['footer'] = ['file' => APP . "/views$path/footer.php"];
            $setRegions = array_filter($regions);
            return $setRegions ? $regions : false;
        };

        $regions = $loadRegionsFrom($path);

        if ($regions['layout']) {
            $res->send($regions['layout']);
            exit;
        }
        if ($regions['main']) {
            $theme = CONFIG['theme'];
            $layout = $regions['layout'] ?: "./themes/{$theme}/index.php";
            $res->render($layout, $regions);
        } else {
            return true;    // proceed to next
        }
    });


    // Not Found
    $router->map('GET', '[*:trailing]', function ($req, $res) {
        // $regions['main'] = ["file" => APP . "/views/404.php"];
        // $res->render("./themes/bb/layout.php", $regions);
        exit("Not found");
    });
};
