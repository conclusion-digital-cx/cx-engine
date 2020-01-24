<?php

include "../lib/Cx.php";

$cx = new Cx(__DIR__.'/../storage/test.db');
$router = $cx->router;
$db = $cx->db;

$router->setBasePath('/api');



// ******
// Image upload
// ******
$router->map( 'POST', '/upload', function() use($db) {
    // print_r($_FILES);
    $fileToUpload = $_FILES["fileToUpload"];

    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($fileToUpload["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // =============
    // Validation
    // =============
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($fileToUpload["tmp_name"]);
        if($check !== false) {
            $message = "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $message = "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        $message = "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($fileToUpload["size"] > 500000) {
        $message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
        http_response_code(404);
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($fileToUpload["tmp_name"], $target_file)) {
            $message = "The file ". basename( $fileToUpload["name"]). " has been uploaded.";
        } else {
            $message = "Sorry, there was an error uploading your file.";
            http_response_code(404);
        }
    }   

    header("content-type:application/json");
    $res = new StdClass;
    $res->message = $message;
    $res->url = "http://localhost:8666/uploads/".basename( $fileToUpload["name"]);
    $res->debug = $fileToUpload;
    echo json_encode($res);
});

// =========
// REST API
// =========
$router->map( 'GET', '', function() {
    echo "CxEngine REST API";
});

// ******
// Specials
// ******
$router->map( 'GET', '/blocks/[a:id]/render', function($id) use($db) {
    include "../blocks/$id.php";
});

// Special: create table
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

    // HACKY Special
    if($result['blocks']) {
        $result['blocks'] = json_decode($result['blocks']);
    }

    header("content-type:application/json");
    $json = json_encode($result);
    echo $json;
});

// PUT /:entity/:id
$router->map( 'PUT', '/[a:entity]/[i:id]', function($entity, $id) use($db) {


    try {
        $inputJSON = file_get_contents('php://input');
        $body = json_decode($inputJSON, TRUE); //convert JSON into array
    
        // HACKY Special
        if($body['blocks']) {
            $body['blocks'] = json_encode($body['blocks']);
        }

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
