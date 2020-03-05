<?php

/**
 * Libary version
 */

use Medoo\Medoo;

/** Return function */
return function ($config = [], $router = '') {

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
    };


    // Validate config
    if (!$config->db) {
        throw new Error("config->db is required to be set");
    }

    // Initialize ORM
    $db = new Medoo($config->db);

    // ******
    // Table Management
    // ******
    $router->map('GET', '', function ($req, $res) use ($db) {
        // TODO 
        //Only for sqlite for now
        $tablesquery = $db->query("SELECT name FROM sqlite_master WHERE type='table';");
        $res = $tablesquery->fetchAll();

        // $res = $db->query($sql);
        header("content-type:application/json");
        echo json_encode($res);
    });

    $router->map('GET', '/schemas/[a:name]', function ($req, $res) use ($db) {
        $tablesquery = $db->query("PRAGMA table_info($req->name);");

        $res = $tablesquery->fetchAll();

        // $res = $db->query($sql);
        header("content-type:application/json");
        echo json_encode($res);
    });


    $router->map('GET', 'info', function ($req, $res) use ($db) {
        $res = $db->info();

        // $res = $db->query($sql);
        header("content-type:application/json");
        echo json_encode($res);
    });

    // Create table from the types table
    $router->map('GET', '/create/[a:name]', function ($req, $res) use ($db, $createTableStructureFromAttributes) {
        $name = $req->name;

        // Get schema from types table
        $entity = 'types';
        $fields = "*";
        $result = $db->get($entity, $fields, ['name' => $name]);

        $attributes = json_decode($result['attributes'], TRUE);

        $fields = $createTableStructureFromAttributes($attributes);

        $res = $db->create($name, $fields);

        // $res = $db->query($sql);
        header("content-type:application/json");
        echo json_encode($res);
    });

    // Create table from the types table
    $router->map('POST', '/create/[a:name]', function ($req, $res) use ($db, $createTableStructureFromAttributes) {
        $name = $req->name;

        // TODO use express style to read body
        $inputJSON = file_get_contents('php://input');
        $body = json_decode($inputJSON, TRUE); //convert JSON into array

        $attributes = $body['attributes'];
// print_r($attributes);
// exit("end");

        $fields = $createTableStructureFromAttributes($attributes);

        $res = $db->create($name, $fields);

        // $res = $db->query($sql);
        header("content-type:application/json");
        echo json_encode($res);
    });


    // Remove table
    $router->map('GET', '/drop/[a:name]', function ($req, $res) use ($db) {
        $name = $req->name;

        $db->drop($name);

        header("content-type:application/json");
        echo json_encode(array('message' => "table dropped"));
    });

    // $router->map('GET', '/exists/[a:name]', function ($req, $res)use ($db) {
    //     $name = $req->name;

    //     $rows = $db->select($name, "*");
    //     $exist = is_array($rows);
    //     header("content-type:application/json");
    //     echo json_encode(array(
    //         'exists' => $exist,
    //         'table' => $name
    //     ));
    // });

    return $router;
};
