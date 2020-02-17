<?php
// ============
// App entry
// ============
include("lib/Cx.php");
$config = include("config.php");
$cx = new Cx($config);

// Get Page object
$page = $cx->match();
$regions = [
    'title' => [],
    'head' => [],
    'afterbody' => [],
    'content' => [],
    'menu' => [],
    'main' => [],
    'footer' => []
];

// Process config->autoload
foreach ($config->autoload as &$value) {
    // $resp = include("plugins/$value/register.php");
    $resp = include("plugins/$value/register.php");
    debug($resp);
    $regions = array_merge($regions, $resp);
}

// ============
// Register blocks
// ============
$blocks = [];

addBlocksFromPath("./blocks");
addBlocksFromPath("./themes/$config->theme/blocks");
// debug($blocks, "Blocks");

// ============
// Fill regions
// ============
// $regions['main'][] = $blocks['menu'];
// $regions['afterbody'][] = $blocks['editor']['toolbar'];    // TODO only admins
// debug($regions, "regions");

// ============
// Render Page
// ============
$blocks['news'] = function() {
    return render ("./blocks/news.php");
};


if ($page) {
    // $regions['main'][] = $page->body;
    $regions['main'][] = renderTemplate($page->body, $blocks);

    // TEST template system
    // $renderTemplate = function () {
    //     include "templates/home.php";
    // };
    // $regions['main'][] = $renderTemplate;
} else {
    $url = strtok($_SERVER["REQUEST_URI"], '?');
    $regions['main'][] = "<h1>Page doesn't exist.</h1>";
}

include "themes/$config->theme/index.php";
