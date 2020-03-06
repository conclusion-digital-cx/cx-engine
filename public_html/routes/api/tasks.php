<?php


// ******
// Table Management
// ******
$base = "/api/_tasks";

// Helpers
$createTableStructureFromAttributes = function ($attributes = []) {
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
    foreach ((array)$attributes as $key => $field) {
        $field = (array)$field;
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
};

$router->get("{$base}/", function ($req, $res) use ($db) {
    // TODO 
    //Only for sqlite for now
    $tablesquery = $db->query("SELECT name FROM sqlite_master WHERE type='table';");
    $resp = $tablesquery->fetchAll();

    $res->json($resp);
});

$router->get("{$base}/schemas/:name", function ($req, $res) use ($db) {
    $tablesquery = $db->query("PRAGMA table_info($req->params->name);");

    $resp = $tablesquery->fetchAll();

    $res->json($resp);
});


$router->get("{$base}info", function ($req, $res) use ($db) {
    $resp = $db->info();

    $res->json($resp);
});

// Create table from the types table
$router->get("{$base}/create/:name", function ($req, $res) use ($db, $createTableStructureFromAttributes) {
    $name = $req->params->name;

    // Get schema from types table
    $entity = 'types';
    $fields = "*";
    $result = $db->get($entity, $fields, ['name' => $name]);

    $attributes = json_decode($result['attributes'], TRUE);

    $fields = $createTableStructureFromAttributes($attributes);

    $resp = $db->create($name, $fields);

    $res->json($resp);
});

// Create table from the types table ( POST )
$router->post("{$base}/create/:name", function ($req, $res) use ($db, $createTableStructureFromAttributes) {
    $name = $req->params->name;
    $attributes = $req->body->attributes;
    // print_r($attributes);
    // exit("end");

    $fields = $createTableStructureFromAttributes($attributes);

    $resp = $db->create($name, $fields);

    $res->json($resp);
});


// Remove table
$router->get("{$base}/drop/:name", function ($req, $res) use ($db) {
    $name = $req->params->name;

    $db->drop($name);

    $res->json(array('message' => "table dropped"));
});