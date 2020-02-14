<?php
// ============
// App entry
// ============
include "lib/Cx.php";
$config = include("config.php");
$cx = new Cx($config);

// Get Page object
$page = $cx->match();

// ============
// Register blocks
// ============
$blocks = [];

// Register blocks
// $blocks['editor'] = (object) [
//     'file' => "plugins/editor/blocks/editor.php",
// ];
// $blocks['widgets'] = (object) [
//     'file' => "plugins/editor/blocks/widgets.php",
// ];
$blocks['editor'] = getBlocksFromPath("./plugins/editor/blocks");

// addBlocksFromPath("./blocks");
addBlocksFromPath("./themes/$config->theme/blocks");
debug($blocks, "Blocks");

// ============
// Define Zones
// ============
$zones = [
    'head' => [
    ],
    'afterbody' => [
        $blocks['editor']['toolbar']    // TODO only admins
    ],
    'content' => [
        // 'header'
    ],
    'menu' => [
        $blocks['menu']
    ],
    'main' => [
        // $blocks['main'],
    ],
    'footer' => [
    ]
];
debug($zones, "Zones");

// Dynamicly Add editor
if (isset($_GET['editor'])) {
    $editorZones = [
        'head' => [
            $blocks['editor']['pageid'],
            $blocks['editor']['head'],
        ],
        'afterbody' => [
            $blocks['editor']['toolbar']
        ],
        'main' => [
            $blocks['editor']['widgets']
        ],
        'footer' => [
            $blocks['editor']['footer']
        ]
    ];
    $zones = array_merge($zones, $editorZones);
}
    

// ============
// Render Page
// ============


debug('Page');
debug($page);
if($page) {
    $zones['main'][] = $page->body;
} else {
    $url = strtok($_SERVER["REQUEST_URI"],'?');
    $zones['main'][] = "<h1>Page doesn't exist.</h1>";
}

include "themes/$config->theme/index.php";


