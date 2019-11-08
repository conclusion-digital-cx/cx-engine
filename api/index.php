<?php

include "../lib/Cx.php";

$cx = new Cx(__DIR__.'/../storage/test.db');
$router = $cx->router;
$db = $cx->db;

$router->setBasePath('/api');

// =========
// REST API
// =========
$router->map( 'GET', '', function() {
    echo "CxEngine REST API";
});

// ******
// Specials
// ******

// PUT /pages/:id
// $router->map( 'PUT', '/pages/[i:id]', function($id) use($db) {
//     $inputJSON = file_get_contents('php://input');
//     $body = json_decode($inputJSON, TRUE); //convert JSON into array
    
//     $result = $db->updateById('pages', $id, $body);
    
//     header("content-type:application/json");
//     $json = json_encode($result);
//     echo $json;
// });


$router->map( 'GET', '/blocks/[a:id]/render', function($id) use($db) {
    include "../blocks/$id.php";
});

// Special: create table
/*
CREATE TABLE "news" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	"title"	TEXT NOT NULL,
	"body"	TEXT,
	"author"	TEXT
);
*/
$router->map( 'GET', '/collections/[a:id]/create', function($id) use($db) {
    // Get schema from collections
    $result = $db->findById('collections', $id);
    $res = $db->query($result['schema']);
    echo $res;
});


$router->map( 'GET', '/collections/[a:id]/createfromjson', function($id) use($db) {
    // Get schema from collections
    $result = $db->findById('collections', $id);
    // print_r($result);

    // TODO use schemaJson as source ?
    $schema = json_decode($result['schemaJson'], TRUE);
    // print_r($schema);

    // Convert to sqlite
    foreach ($schema as $key => $value) {
        $fields[] = "\n    '$key' TEXT";
       }

    $sql = "
CREATE TABLE '".$result['name']."' (
    'id'	INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE,
    ".join(",",$fields)."
);";
    echo $sql;

    $res = $db->query($sql);
    header("content-type:application/json");
    echo json_encode($res);
});


$router->map( 'GET', '/collections/[a:id]/exists', function($id) use($db) {
    // Get schema from collections
    $result = $db->findById('collections', $id);
    $res = $db->tableExist($result['name']);
    // print_r($res);
    header("content-type:application/json");
    echo json_encode($res);
});

// Layouts
$router->map( 'GET', '/layouts', function() use($db) {
    $path = "../layouts";
    $files = glob(  "$path/*.*" );
    foreach ($files as &$value) {
        // Remove path
        $value = substr($value, strlen($path));
        
        // Render
        // $render = file_get_contents("http://localhost:8666/$value");
        $render = "";

        $value = (object) [
            'name' => $value,
            'render' => $render,
          ];
      }  

    header("content-type:application/json");
    echo json_encode($files);
});

// Blocks
$router->map( 'GET', '/blocks', function() use($db) {
    $path = "../blocks/";
    $files = glob(  "$path*.*" );
    foreach ($files as &$value) {
        // Remove ../
        // $value = substr($value, strlen('../'));
        $value = substr($value, strlen($path));
        $value = substr($value, 0, -4); // Extension
        
        // Render
        // $render = file_get_contents("http://localhost:8666/$value");
        $render = "";

        $value = (object) [
            'name' => $value,
            'render' => $render,
          ];
      }  

    header("content-type:application/json");
    echo json_encode($files);
});

// ******************
// Collections
// ******************
// GET /:entity
$router->map( 'GET', '/[a:entity]', function($entity) use($db) {
    try {
        $result = $db->getAll($entity);
              
        header("content-type:application/json");
        $json = json_encode($result);
        echo $json;
    } catch (Throwable $t) {
        header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        echo "Not found\n";
        echo $t;
    }
});

// POST /:entity
$router->map( 'POST', '/[a:entity]', function($entity) use($db) {
    try {
        $inputJSON = file_get_contents('php://input');
        $body = json_decode($inputJSON, TRUE); //convert JSON into array
        
        $result = $db->insert($entity, $body);
        if(!$result) {
            throw new Exception();
        }
        header("content-type:application/json");
        $json = json_encode(array('id' => $result));
        echo $json;
    } catch (Throwable $t) {
        header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        echo "Not found\n";
        echo $t;
    }
});

// GET /:entity/:id
$router->map( 'GET', '/[a:entity]/[i:id]', function($entity, $id) use($db) {
    $result = $db->findById($entity, $id);
    header("content-type:application/json");
    $json = json_encode($result);
    echo $json;
});

// PUT /:entity/:id
$router->map( 'PUT', '/[a:entity]/[i:id]', function($entity, $id) use($db) {
    try {
        $inputJSON = file_get_contents('php://input');
        $body = json_decode($inputJSON, TRUE); //convert JSON into array
    
        $result = $db->updateById($entity, $id, $body);
        if(!$result) {
            throw new Exception();
        }
    //     echo "Cool";
    // print_r($result);

        header("content-type:application/json");
        // $json = json_encode($result);
        $json = json_encode(array('message' => "saved"));
        echo $json;
    } catch (Throwable $t) {
        header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        echo "Not found\n";
        echo $t;
    }
});


// PUT /:entity/:id
$router->map( 'DELETE', '/[a:entity]/[i:id]', function($entity, $id) use($db) {
    $result = $db->deleteById($entity, $id);

    header("content-type:application/json");
    $json = json_encode($result);
    echo $json;
});

try {
    $cx->match();
} catch (exception $e) {
    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
