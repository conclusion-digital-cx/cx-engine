<?php

$base = "/api";

// ******
// Specials
// ******
$router->get("{$base}/blocks/:id/render", function ($req, $res) use ($db) {
    include "../blocks/{$req->params->id}.php";
});

// Layouts
// =======
$router->get("{$base}/layouts", function ($req, $res) use ($db) {
    $path = "../layouts";
    $files = glob("$path/*.*");
    foreach ($files as &$value) {
        // Remove path
        $value = substr($value, strlen($path));

        $value = (object) [
            'name' => $value,
        ];
    }
    $res->json($files);
});


// =======
// plugins
// =======
$router->get("{$base}/plugins", function ($req, $res) use ($db) {
    $path = APP . "/plugins";
    $files = glob("$path/*", GLOB_ONLYDIR);

    foreach ($files as &$value) {
        $value = substr($value, strlen("$path/"));

        $sceenshotFile = "../plugins/$value/screenshot.png";
        $sceenshotUrl = "/plugins/$value/screenshot.png";
        $value = (object) [
            'name' => $value,
            'image' => file_exists($sceenshotFile) ? $sceenshotUrl : '',
        ];
    }

    $res->json($files);
});



// =======
// uploads
// =======
$router->get("{$base}/uploads", function ($req, $res) use ($db) {
    $path = APP . "/uploads";
    $files = glob("$path/*.*");
    foreach ($files as &$value) {
        // Remove ../
        // $value = substr($value, strlen('../'));
        $value = substr($value, strlen($path));
        //    $value = substr($value, 0, -4); // Extension

        $value = (object) [
            'name' => $value,
            'image' => CONFIG['baseUrl'] . "/uploads/$value"
        ];
    }

    $res->json($files);
});



// =======
// Themes
// =======
$router->get("{$base}/themes", function ($req, $res) use ($db) {
    $path = APP . "/themes";
    $files = glob("$path/*", GLOB_ONLYDIR);

    foreach ($files as &$value) {
        $value = substr($value, strlen("$path/"));

        $sceenshotFile = APP . "/themes/$value/screenshot.png";
        $sceenshotUrl = CONFIG['baseUrl'] . "/themes/$value/screenshot.png";
        $value = (object) [
            'name' => $value,
            'image' => file_exists($sceenshotFile) ? $sceenshotUrl : '',
        ];
    }

    $res->json($files);
});

// =======
// Blocks
// =======
$router->get("{$base}/blocks", function ($req, $res) use ($db) {
    $path = APP . "/blocks";
    $files = glob("$path/*.*");
    foreach ($files as &$value) {
        // Remove ../
        // $value = substr($value, strlen('../'));
        $value = substr($value, strlen($path));
        $value = substr($value, 0, -4); // Extension

        // Render
        // $render = file_get_contents("http://localhost:8666/$value");
        $render = "";

        $value = (object) [
            'name' => $value,
            'render' => $render,
        ];
    }

    $res->json($files);
});


// rglob folder: /blocksjs
$router->get("{$base}/blocksjs", function ($req, $res) use ($db) {
    $path = "../blocksjs/";
    $files = rglob("$path*.*");
    foreach ($files as &$value) {
        // Remove ../
        $value = substr($value, strlen('..'));
    }

    $res->json($files);
});
