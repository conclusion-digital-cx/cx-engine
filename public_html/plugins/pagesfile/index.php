<?php

// Handle File based pages
// Map /* to file at /views/*.php
$router->use(function ($req, $res, $next) {
    $path = rtrim($req->path, "/"); // Remove trailing /

    $file = APP . "/pages$path.php";
    $exists = file_exists($file);
    if ($exists) {
        $slots = [
            'main' => ['file' => $file]
        ];
        $theme = CONFIG['theme'];
        $layout = $slots['layout'] ?: APP . "/themes/{$theme}/index.php";
        $res->render($layout, $slots);
    } else {
        $next();
    }
});

// Handle File based pages ( custom views )
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

