<?php

return (object)[
    'tokenSecret' => 'veryVerySecret',
    'root' => __DIR__,
    'baseUrl' => 'http://localhost:8666',   // used for uploads, etc.
    'apiKeys' => [
        'yourSecretApiKey'
    ],
    'autoload' => [
        // 'editor',
        // 'contenttools'
    ],
    'theme' => '2020',
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