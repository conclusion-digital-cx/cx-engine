<?php
require_once "lib/Medoo.php";
require_once "lib/Router.php";  // Fork of AltoRouter

$router = new Router();

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

// =========
// CORS
// =========
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');

// =========
// REST API
// =========
$router->map('GET', '', function () {
    echo "CxEngine REST API";
    // print_r($_SERVER);
});


// ******
// Image upload
// ******
$router->map('POST', '/upload', function () use ($config, $db, $uploaddir) {
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
    $res->url = "$config->baseUrl/uploads/" . basename($fileToUpload["name"]);
    $res->debug = $fileToUpload;
    echo json_encode($res);
});


// ******
// Table Management
// ******
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



$router->map('GET', '/_tasks/test/[a:name]', function ($params) use ($db) {
    $name = $params->name;

    // Get schema from types table
    $entity = 'types';
    $fields = "*";
    $result = $db->get($entity, $fields, ['name' => $name]);

    // TODO use schemaJson as source ?
    $attributes = json_decode($result['attributes'], TRUE);

    $fields = createTableStructureFromAttributes($attributes);

    $res = $fields; // $db->create("account", $fields);

    // $res = $db->query($sql);
    header("content-type:application/json");
    echo json_encode($res);
});

$router->map('GET', '/_tasks/create/[a:name]', function ($params) use ($db) {
    $name = $params->name;

    // Get schema from types table
    $entity = 'types';
    $fields = "*";
    $result = $db->get($entity, $fields, ['name' => $name]);

    // TODO use schemaJson as source ?
    $attributes = json_decode($result['attributes'], TRUE);

    $fields = createTableStructureFromAttributes($attributes);

    $res = $db->create($name, $fields);

    // $res = $db->query($sql);
    header("content-type:application/json");
    echo json_encode($res);
});

$router->map('GET', '/_tasks/drop/[a:name]', function ($params) use ($db) {
    $name = $params->name;

    $db->drop($name);

    header("content-type:application/json");
    echo json_encode(array('message' => "table dropped"));
});

$router->map('GET', '/_tasks/exists/[a:name]', function ($params) use ($db) {
    $name = $params->name;

    $rows = $db->select($name, "*");
    $exist = is_array($rows);
    header("content-type:application/json");
    echo json_encode(array(
        'exists' => $exist,
        'table' => $name
    ));
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
    globPath($path);
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


// rglob folder: /blocksjs
$router->map('GET', '/blocksjs', function () use ($db) {
    $path = "../blocksjs/";
    $files = rglob("$path*.*");
    foreach ($files as &$value) {
        // Remove ../
        $value = substr($value, strlen('..'));
    }

    header("content-type:application/json");
    echo json_encode($files);
});

// ******************
// Collections
// ******************
// GET /:entity
use Ahc\Jwt\JWT;

require_once("./lib/jwt/ValidatesJWT.php");
require_once("./lib/jwt/JWT.php");
require_once("./utils.php");    // Responses

// Instantiate with key, algo, maxAge and leeway.
$jwt = new JWT('veryVerySecret');

$token = $jwt->encode([
    'uid'    => 1,
    'aud'    => 'http://site.com',
    'scopes' => ['user'],
    'iss'    => 'http://api.mysite.com',
]);

$jwt->decode($token);

// ===============
// Middleware: Check token
// ===============
$checkToken = function ($params) use ($config) {

    // $model = getModel($params->entity);
    // print_r($model);

    // if(!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    //     header($_SERVER["SERVER_PROTOCOL"] . ' 401');
    //     exit("401 Not authorized");
    // }

    // $auth = $_SERVER['HTTP_AUTHORIZATION'];
    // $validApiKeys = $config->apiKeys;
    // list($type, $token) = explode(" ", $auth);

    // if($type === "apiKey") {
    //     if (in_array($token, $validApiKeys)) {
    //         // Proceed
    //         return true;
    //     } else {
    //         header($_SERVER["SERVER_PROTOCOL"] . ' 401');
    //         exit("401 Not authorized");
    //     }
    // } else if($type === "Bearer") {
    //     header($_SERVER["SERVER_PROTOCOL"] . ' 401');
    //     exit("Bearer login is currently not supported");
    // }

    return true;
};

$router->map('GET', '/[a:entity]', $checkToken);

$router->map('GET', '/[a:entity]', function ($params) use ($db) {
    $entity = $params->entity;

    // Move to https://github.com/typicode/json-server
    $functionFields = [
        'page', 'itemsPerPage', 'sortBy', 'sortDesc',
        'groupBy', 'groupDesc', 'mustSort', 'multiSort'
    ];

    try {
        $fields = isset($_GET['fields']) ? $_GET['fields'] : '*';
        $where = $_GET;
        $where = array_diff_key($where, array_flip($functionFields));

        // Process where
        foreach ($where as &$value) {
            // Convert NULL
            $value = $value === 'NULL' ? null : $value;
        }

        // TODO
        // Set skips, limits, sorts
        // page=1&itemsPerPage=10&sortBy=&sortDesc=&groupBy=&groupDesc=&mustSort=false&multiSort=false
        // $where['LIMIT'] = [$_GET['page'] * $_GET['itemsPerPage'], $_GET['itemsPerPage']];

        $rows = $db->select($entity, $fields, $where);
        if (!is_array($rows)) {
            return true;    // Table not exist...exit this route handler
        }

        // Process model
        $model = getModel($entity);
        $fn = $model->get;
        // Call get on each row
        foreach ($rows as &$row) {
            $resp = $fn($row);
            // print_r($resp);
            // Merge
            $row = array_merge($row, $resp);
        }

        jsonArrayResponse($rows);
    } catch (Throwable $t) {
        notFoundResponse($t);
    }
});

$router->map('GET', '/[a:entity]', function ($params) use ($db) {
    notFoundResponse();
});


// POST /:entity
$router->map('POST', '/[a:entity]', $checkToken);

$router->map('POST', '/[a:entity]', function ($params) use ($db) {
    $entity = $params->entity;

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
$router->map('GET', '/[a:entity]/[i:id]', $checkToken);

$router->map('GET', '/[a:entity]/[i:id]', function ($params) use ($db) {
    //    print_r((array)$params);
    // list($entity, $id) = (array)$params;
    $entity = $params->entity;
    $id = $params->id;

    $fields = isset($_GET['fields']) ? $_GET['fields'] : '*';
    $result = $db->get($entity, $fields, ['id' => $id]);

    jsonResponse($result);
});

// PUT /:entity/:id ( replace all fields )
// TODO
// $router->map('PUT', '/[a:entity]/[i:id]', function ($entity, $id) use ($db) {
//     
// });

// PATCH /:entity/:id ( update only fields provided )
// $router->map('PATCH', '/[a:entity]/[i:id]', $checkToken );

$router->map('PATCH', '/[a:entity]/[i:id]', function ($params) use ($db) {
    $entity = $params->entity;
    $id = $params->id;

    try {
        $inputJSON = file_get_contents('php://input');
        $body = json_decode($inputJSON, TRUE); //convert JSON into array

        // Process model
        $model = getModel($entity);
        $fn = $model->save;
        $body = $fn($body);
        // print_r($body);

        $result = $db->update($entity, $body, ['id' =>  $id]);
        if (!$result) {
            throw new Exception("Not found");
        }

        header("content-type:application/json");
        $json = json_encode($body);
        // $json = json_encode(array('message' => "saved"));
        echo $json;
    } catch (Throwable $t) {
        notFoundResponse($t);
    }
});


// DELETE /:entity/:id
$router->map('DELETE', '/[a:entity]/[i:id]', $checkToken);

$router->map('DELETE', '/[a:entity]/[i:id]', function ($params) use ($db) {
    list($entity, $id) = (array) $params;

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
$matches = $router->matches();
$match = $matches[0];
// print_r($matches);

if (!$match) {
    // no route was matched
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    exit();
}

foreach ($matches as $match) {
    // Call closure
    $resp = $match['target']((object) $match['params']);

    // Stop loop on false returns
    if (!$resp) break;
}

// Handle db logs
if ($config->db['logging']) {
    $queries = $db->log();
    $queries = "\n" . join("\n", $queries);
    // Write the contents back to the file
    $file = 'queries.log';
    file_put_contents($file, $queries, FILE_APPEND | LOCK_EX);
}

// // call closure or throw 404 status
// if (is_array($match) && is_callable($match['target'])) {

//     // call_user_func_array($match['target'], $match['params']);
//     // Process matches

//     // $match['target']((object)$match['params']);

//     // Handle db logs
//     if ($config->db['logging']) {
//         $queries = $db->log();
//         $queries = "\n" . join("\n", $queries);
//         // Write the contents back to the file
//         $file = 'queries.log';
//         file_put_contents($file, $queries, FILE_APPEND | LOCK_EX);
//     }
// } else {


// }