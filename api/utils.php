<?php

function getJsonBody() {
    $inputJSON = file_get_contents('php://input');
    return json_decode($inputJSON); 
}

function createTableStructureFromAttributes($attributes)
{
    // Convert to sqlite
    $fields = [
        // "id" => [
        //     "INT",
        //     "NOT NULL",
        //     "AUTO_INCREMENT",
        //     "PRIMARY KEY"
        // ],

        // For Sqlite
        "id" => [
            "INTEGER",
            "NOT NULL",
            "PRIMARY KEY",
            "AUTOINCREMENT",
            "UNIQUE",
        ]
    ];
    foreach ($attributes as $key => $field) {
        $key = $field['name'];
        $type = $field['type']; // string, number, enum, relation, ...

        /*
sqlite: INTEGER, TEXT, BLOB, REAL, NUMERIC
        */
        $mapTypes = [
            "string" => "TEXT",
            "number" => "TEXT",
            "enum" => "TEXT",
            "date" => "REAL",
            "relation" => "INTEGER",
        ];

        // TODO better typing
        $fields[$key] = [
            $mapTypes[$type] ?: "TEXT"
            // "VARCHAR(30)",
            // "NOT NULL"
        ];
    }
    return $fields;
}


function rglob($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir . '/' . basename($pattern), $flags));
    }
    return $files;
}

// ==================
// Responses
// ==================
function jsonArrayResponse($data = [])
{
    header("content-type:application/json");
    echo json_encode($data);
}

function jsonResponse($data = [])
{
    header("content-type:application/json");
    echo json_encode($data);
}

function notFoundResponse($t = "Not found")
{
    global $config;
    global $db;

    http_response_code(404);
    echo "Not found\n";
}

function getModel($name)
{
    $fakeModel = [
        'get' => function ($row) {
            return $row;
        },
        'save' => function ($row) {
            return $row;
        },
    ];

    // Process model
    $model = "./schemas/$name.php";
    if (file_exists($model)) {
        $model = include($model);
        return (object) array_merge($fakeModel, $model);
    } else {
        // Fake model
        return (object) $fakeModel;
    }
}