<?php

include "lib/Cx.php";

$cx = new Cx(__DIR__."/storage/test.db");
$cx->debug = false;

$page = $cx->match();

// Debug
// print_r($page);

// // ==========
// // Global helper 
// // ==========
// function get($id) {
// global $page;
//     print_r($page);
// }

if($page) {
    $page->body = $cx->render($page->blocks);

    // Add editor
    if(isset($_GET['editor'])) {
        $page->add('editor');
    }

    include "layouts/default.php";
} else {
    include "layouts/notfound.php";
}
