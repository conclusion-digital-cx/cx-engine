<?php

include "lib/Cx.php";
$config = include("config.php");

$cx = new Cx();
$cx->debug = false;

$page = $cx->match();

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
