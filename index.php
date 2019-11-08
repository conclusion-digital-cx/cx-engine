<?php

include "lib/Cx.php";

$cx = new Cx(__DIR__."/storage/test.db");
$cx->debug = false;

$page = $cx->match();

// Add editor
if(isset($_GET['editor'])) {
    $page->add('editor');
}

if($page) {
    include "layouts/default.php";
} else {
    include "layouts/notfound.php";
}
