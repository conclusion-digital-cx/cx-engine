<?php

return (object)[
    'theme' => 'inventum',
    'debug' => true,
    'uploaddir' => __DIR__."/uploads", 
    'db' => [
        // https://medoo.in/
        'database_type' => 'sqlite',
        'database_file' => __DIR__."/db.db",
        'server' => 'localhost',
        "logging" => true,
    ]
];