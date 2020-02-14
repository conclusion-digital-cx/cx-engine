<?php
require_once "lib/Medoo.php";
require_once "lib/AltoRouter.php";

$router = new AltoRouter();

// include "../lib/Cx.php";
$config = include("../config.php");

// Get Config
$uploaddir = $config->uploaddir;

// $cx = new Cx($config->dbpath);
// $router = $cx->router;
// $db = $cx->db;

// Initialize
// Using Medoo namespace
use Medoo\Medoo;

$db = new Medoo($config->db);

$router->setBasePath('/api');

// CORS
header("Access-Control-Allow-Origin: *");

// ******
// Image upload
// ******
$router->map('POST', '/upload', function () use ($db, $uploaddir) {
    // print_r($_FILES);
    $fileToUpload = $_FILES["fileToUpload"];

    $target_dir = $uploaddir; // "../uploads/";
    $target_file = $target_dir . basename($fileToUpload["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // =============
    // Validation
    // =============
    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($fileToUpload["tmp_name"]);
        if ($check !== false) {
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
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
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
            $message = "The file " . basename($fileToUpload["name"]) . " has been uploaded.";
        } else {
            $message = "Sorry, there was an error uploading your file.";
            http_response_code(404);
        }
    }

    header("content-type:application/json");
    $res = new StdClass;
    $res->message = $message;
    $res->url = "http://localhost:8666/uploads/" . basename($fileToUpload["name"]);
    $res->debug = $fileToUpload;
    echo json_encode($res);
});

// =========
// REST API
// =========
$router->map('GET', '', function () {
    echo "CxEngine REST API";
});

// ******
// Specials
// ******
$router->map('GET', '/blocks/[a:id]/render', function ($id) use ($db) {
    include "../blocks/$id.php";
});

// Layouts
// =======
$router->map('GET', '/layouts', function () use ($db) {
    $path = "../layouts";
    $files = glob("$path/*.*");
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

// Themes
// =======
$router->map('GET', '/themes', function () use ($db) {
    $path = "../themes";
    $files = glob("$path/*", GLOB_ONLYDIR);
    // print_r($files);
    foreach ($files as &$value) {
        $value = substr($value, strlen("$path/"));

        $sceenshotFile = "../themes/$value/screenshot.png";
        $sceenshotUrl = "/themes/$value/screenshot.png";
        $value = (object) [
            'name' => $value,
            'image' => file_exists($sceenshotFile) ? $sceenshotUrl : '',
        ];
    }

    header("content-type:application/json");
    echo json_encode($files);
});

// Blocks
// =======
$router->map('GET', '/blocks', function () use ($db) {
    $path = "../blocks/";
    $files = glob("$path*.*");
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

// Blocks
$router->map('GET', '/blocksjs', function () use ($db) {
    $path = "../blocksjs/";
    $files = glob("$path*.*");
    foreach ($files as &$value) {
        // Remove ../
        $value = substr($value, strlen('..'));
        // $value = substr($value, strlen($path));
        // $value = substr($value, 0, -3); // Extension
    }

    header("content-type:application/json");
    echo json_encode($files);
});


// GET /:entity/:id HACKY Special
$router->map('GET', '/blocks/[i:id]', function ($id) use ($db) {
    $entity = 'blocks';
    $fields = "*";
    $result = $db->get($entity, $fields, ['id' => $id]);

    if ($result['blocks']) {
        $result['blocks'] = json_decode($result['blocks']);
    }

    header("content-type:application/json");
    $json = json_encode($result);
    echo $json;
});


// PUT /:entity/:id HACKY Special
$router->map('PUT', '/blocks/[i:id]', function ($entity, $id) use ($db) {
    try {
        $inputJSON = file_get_contents('php://input');
        $body = json_decode($inputJSON, TRUE); //convert JSON into array

        // HACKY Special
        if ($body['blocks']) {
            $body['blocks'] = json_encode($body['blocks']);
        }

        $result = $db->update($entity, $body, ['id' =>  $id]);
        if (!$result) {
            throw new Exception();
        }

        header("content-type:application/json");
        $json = json_encode(array('message' => "saved"));
        echo $json;
    } catch (Throwable $t) {
        notFoundResponse($t);
    }
});


// ******************
// Collections : SPECIAL / DEBUG / TEMP
// ******************
// Special: create table
$router->map('GET', '/collections/[a:id]/create', function ($id) use ($db) {
    // Get schema from collections
    $entity = 'collections';
    $fields = "*";
    $result = $db->get($entity, $fields, ['id' => $id]);
    $res = $db->query($result['schema']);
    echo $res;
});


$router->map('GET', '/collections/[a:id]/createfromjson', function ($id) use ($db) {
    $entity = 'collections';
    $fields = "*";
    $result = $db->get($entity, $fields, ['id' => $id]);

    // TODO use schemaJson as source ?
    $schema = json_decode($result['schemaJson'], TRUE);
    // print_r($schema);

    // Convert to sqlite
    foreach ($schema as $key => $value) {
        $fields[] = "\n    '$key' TEXT";
    }

    $sql = "
CREATE TABLE '" . $result['name'] . "' (
    'id'	INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE,
    " . join(",", $fields) . "
);";
    echo $sql;

    $res = $db->query($sql);
    header("content-type:application/json");
    echo json_encode($res);
});

// $router->map('GET', '/collections/[a:id]/exists', function ($id) use ($db) {
//     $entity = 'collections';
//     $fields = "*";
//     $result = $db->get($entity, $fields, ['id' => $id]);
//     $res = $db->tableExist($result['name']);
//     header("content-type:application/json");
//     echo json_encode($res);
// });


// ==================
// Responses
// ==================
function jsonArrayResponse($data = [])
{
    if ($data) {
        foreach ($data as &$value) {
            // HACKY Special
            // TODO replace all *Json
            // print_r($value);
            if (isset($value['schemaJson'])) {
                $value['schemaJson'] = json_decode($value['schemaJson']);
            }
        }
    }

    header("content-type:application/json");
    echo json_encode($data);
}

function notFoundResponse($t)
{
    global $config;
    global $db;

    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    echo "Not found\n";
    if ($config->debug) {
        echo "\n";
        echo $t;
        echo $db->last();
    }
}

// ******************
// Collections
// ******************
// GET /:entity
$router->map('GET', '/[a:entity]', function ($entity) use ($db) {
    try {
        $fields = isset($_GET['fields']) ? $_GET['fields'] : '*';
        // Convert NULL
        $where = $_GET;
        // Process where
        foreach ($where as &$value) {
            $value = $value === 'NULL' ? null : $value;
        }
        // $where['state'] = null;
        
        $data = $db->select($entity, $fields, $where);
        jsonArrayResponse($data);
    } catch (Throwable $t) {
        notFoundResponse($t);
    }
});

// POST /:entity
$router->map('POST', '/[a:entity]', function ($entity) use ($db) {
    try {
        $inputJSON = file_get_contents('php://input');
        $body = json_decode($inputJSON, TRUE); //convert JSON into array

        $result = $db->insert($entity, (array) $body);
        if (!$result) {
            throw new Exception();
        }
        header("content-type:application/json");
        $json = json_encode(array('id' => $result));
        echo $json;
    } catch (Throwable $t) {
        notFoundResponse($t);
    }
});

// GET /:entity/:id
$router->map('GET', '/[a:entity]/[i:id]', function ($entity, $id) use ($db) {

    $fields = isset($_GET['fields']) ? $_GET['fields'] : '*';
    $result = $db->get($entity, $fields, ['id' => $id]);

    header("content-type:application/json");
    $json = json_encode($result);
    echo $json;
});

// PUT /:entity/:id ( replace all fields )
$router->map('PUT', '/[a:entity]/[i:id]', function ($entity, $id) use ($db) {
    try {
        $inputJSON = file_get_contents('php://input');
        $body = json_decode($inputJSON, TRUE); //convert JSON into array

        $result = $db->update($entity, $body, ['id' =>  $id]);
        if (!$result) {
            throw new Exception();
        }

        header("content-type:application/json");
        $json = json_encode(array('message' => "saved"));
        echo $json;
    } catch (Throwable $t) {
        notFoundResponse($t);
    }
});



// PATCH /:entity/:id ( update only fields provided )
$router->map('PATCH', '/[a:entity]/[i:id]', function ($entity, $id) use ($db) {
    try {
        $inputJSON = file_get_contents('php://input');
        $body = json_decode($inputJSON, TRUE); //convert JSON into array

        $result = $db->update($entity, $body, ['id' =>  $id]);
        if (!$result) {
            throw new Exception();
        }

        header("content-type:application/json");
        $json = json_encode(array('message' => "saved"));
        echo $json;
    } catch (Throwable $t) {
        notFoundResponse($t);
    }
});


// DELETE /:entity/:id
$router->map('DELETE', '/[a:entity]/[i:id]', function ($entity, $id) use ($db) {
    $result = $db->delete($entity, [
        'id' =>  $id
    ]);

    header("content-type:application/json");
    $json = json_encode($result);
    echo $json;
});

// =============
// Handle routing
// =============
// match current request url
$match = $router->match();

// call closure or throw 404 status
if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);

    // Handle logs
    if($config->db['logging']) {
        $queries = $db->log();
        $queries = "\n".join("\n",$queries);
        // Write the contents back to the file
        $file = 'queries.log';
        file_put_contents($file, $queries, FILE_APPEND | LOCK_EX);
    }
} else {
    // no route was matched
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
