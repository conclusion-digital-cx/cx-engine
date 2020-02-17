<?php
require_once "lib/Medoo.php";
require_once "lib/Router.php";  // Fork of AltoRouter

$router = new Router();

$config = include("../config.php");

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
    header('Access-Control-Allow-Headers: Authorization, token, Content-Type');
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


// ******************
// Specials: upload, _tasks, ..
// ******************
include "./specials.php";


// ===============
// Authorization
// ===============
include "./auth.php";

// ===============
// Middleware: Check token
// ===============
$checkToken = function ($params) use ($config) {

    // TODO apply policies by schemas
    // Check policies
    $apiKey = include("./policies/apiKey.php");
    $isUser = include("./policies/isUser.php");

    $resp[] = $apiKey();
    $resp[] = $isUser();

    $isAllowed = in_array(true, $resp);
    // echo $isAllowed;
    // print_r($resp);

    if(!$isAllowed) {
        http_response_code(401);
        exit("401 Not authorized");
    }

    return $isAllowed;
};


// Run on all routes
$router->map('GET|POST|PATCH', '*', $checkToken);

// ******************
// Collections
// ******************

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
// Handle route
// =============
// match current request url
$matches = $router->matches();
$match = $matches[0];
// print_r($matches);

if (!$match) {
    // no route was matched
    http_response_code(404);
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
