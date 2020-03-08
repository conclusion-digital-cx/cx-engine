<?php

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

    if (!$page) {
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

