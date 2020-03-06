<?php

// Handle File based pages
// Map /* to file at /views/*.php
$router->use(function ($req, $res, $next) {
    $path = $req->path;

    $file = APP . "/views$path.php";
    $exists = file_exists($file);
    if ($exists) {
        $slots = [
            'main' => ['file' => $file]
        ];
        $theme = CONFIG['theme'];
        $layout = $slots['layout'] ?: "./themes/{$theme}/index.php";
        $res->render($layout, $slots);
    } else {
        $next();
    }
});

// Handle Database based pages
$router->use(function ($req, $res, $next) use ($db) {
    $path = $req->path;

    // Get page from database
    // $page = file_get_contents("http://localhost:8666/api/pages?url=/");
    $page = $db->get("pages", "*", [
        'url' => rtrim($path, "/")    // TODO
    ]);
    // print_r($page);
    // var_dump($db->log());

    if(!$page) {
        $next();
    }

    $slots = [
        'title' => $page['title'],
        'main' => $page['body']
    ];

    $theme = CONFIG['theme'];
    $layout = $page['layout'] ?: APP . "/themes/{$theme}/index.php";
    $res->render($layout, $slots);
});



// Handle File based pages
// Map /* to file at /views/*.php
// $router->use(function ($req, $res, $next) {
//     $path =  $req->path;

//     $loadRegionsFrom = function ($path) {
//         $slots['layout'] = ['file' => APP . "/views$path/layout.php"];
//         $slots['main'] = ['file' => APP . "/views$path/main.php"];
//         $slots['footer'] = ['file' => APP . "/views$path/footer.php"];
//         $setRegions = array_filter($slots);
//         return $setRegions ? $slots : false;
//     };

//     $slots = $loadRegionsFrom($path);

//     if ($slots['layout']) {
//         $res->send($slots['layout']);
//         exit;
//     }
//     if ($slots['main']) {
//         $theme = CONFIG['theme'];
//         $layout = $slots['layout'] ?: "./themes/{$theme}/index.php";
//         $res->render($layout, $slots);
//     } else {
//         $next();
//     }
// });
