<?php

// ============
// App entry
// ============
include("lib/Cx.php");
$defaultConfig = include("config.php");
// $cx = new Cx($config);

return function ($config) {
    $cx = new Cx($config);
    return $cx;
};
