<?php

// ==========
// app routes...
// ==========
$corsMiddleware = include(__DIR__ ."/middleware/cors.php");
$router->use($corsMiddleware());

// Database tasks
include __DIR__."/api/upload.php";
include __DIR__."/api/files.php";
include __DIR__."/api/tasks.php";
include __DIR__."/api/data.php";
