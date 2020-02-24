<?php

use Medoo\Medoo;

require_once "lib/Medoo.php";
require_once __DIR__ . "/../lib/Router.php";  // Fork of AltoRouter


return function ($config = [], $requestUrl = '') {

    // Defaults
    $config = (object)array_merge([
        'debug' => false
    ], (array)$config);

    // Destructure config
    $debug = $config->debug;

    $router = new Router();

    // $config = include(__DIR__ . "/../config.php");

    // Initialize
    // Using Medoo namespace
    $db = new Medoo($config->db);

    // $router->setBasePath('/api');

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
    include __DIR__ . "/specials.php";


    // ===============
    // Authorization
    // ===============
    include __DIR__ . "/auth.php";

    // ===============
    // Middleware: Check token
    // ===============
    $checkToken = function ($params) use ($config) {

        // TODO apply policies by schemas
        // Check policies
        $apiKey = include(__DIR__ . "/policies/apiKey.php");
        $isUser = include(__DIR__ . "/policies/isUser.php");

        $resp[] = $apiKey($config);
        $resp[] = $isUser($config);

        $isAllowed = in_array(true, $resp);
        // echo $isAllowed;
        // print_r($resp);

        if (!$isAllowed) {
            http_response_code(401);
            exit("401 Not authorized");
        }

        return $isAllowed;
    };


    // Run on all routes
    // $router->map('GET|POST|PATCH', '*', $checkToken);

    // ******************
    // Collections
    // ******************

    $router->map('GET', '/[a:entity]', function ($params) use ($db) {
        $entity = $params->entity;

        // Move to https://github.com/typicode/json-server
        // $functionFields = [
        //     'page', 'itemsPerPage', 'sortBy', 'sortDesc',
        //     'groupBy', 'groupDesc', 'mustSort', 'multiSort'
        // ];
        $functionFields = [
            '_start', '_limit'
        ];

        function array_except($array, $keys)
        {
            foreach ($keys as $key) {
                unset($array[$key]);
            }
            return $array;
        }

        try {
            $fields = isset($_GET['fields']) ? $_GET['fields'] : '*';

            // Where = GET - functionFields
            $where = $_GET; // array_values($_GET);   // Clone
            $where = array_except($where, $functionFields);

            // Process where
            foreach ($where as &$value) {
                // Convert NULL
                $value = $value === 'NULL' ? null : $value;
            }

            // Process special modifiers
            $specials = array_merge([
                '_start' => 0,
                '_limit' => 1000
            ], $_GET);
            $where['LIMIT'] = [$specials['_start'], $specials['_limit']];

            // TODO
            // Set skips, limits, sorts
            // page=1&itemsPerPage=10&sortBy=&sortDesc=&groupBy=&groupDesc=&mustSort=false&multiSort=false
            // $where['LIMIT'] = [$_GET['page'] * $_GET['itemsPerPage'], $_GET['itemsPerPage']];

            // print_r($where);
            // print_r($specials);
            $rows = $db->select($entity, $fields, $where);
            if (!is_array($rows)) {
                // return true;    // Table not exist...exit this route handler
                throw new Exception("No rows");
            }

            // Process model
            $model = getModel($entity);
            $fn = $model->get;

            // print_r($rows);
            // Call get on each row
            foreach ($rows as &$row) {
                $resp = $fn($row);
                // Merge
                $row = array_merge($row, $resp);
            }
            // print_r($rows);

            jsonArrayResponse($rows);
        } catch (Throwable $t) {
            notFoundResponse($t);
            // var_dump( $db->error() );
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
    $router->map('PATCH', '/[a:entity]/[i:id]', function ($params) use ($db, $debug) {
        $entity = $params->entity;
        $id = $params->id;

        try {
            $inputJSON = file_get_contents('php://input');
            $body = json_decode($inputJSON, TRUE); //convert JSON into array

            // Process model
            $model = getModel($entity);
            $fn = $model->save;
            $computed = $fn($body);
            $bodyParsedByModel = array_merge($body, $computed);

            // print_r($bodyParsedByModel);
            // echo $entity;
            // echo $id;
            // exit();

            $data = $db->update($entity, $bodyParsedByModel, ['id' =>  $id]);

            // if (!$result) {
            //     exit("Not found");
            //     // throw new Exception("Not found");
            // }

            // Returns the number of rows affected by the last SQL statement
            $count = $data->rowCount();

            header("content-type:application/json");
            $json = json_encode(array('count' => $count));
            // $json = json_encode(array('message' => "saved"));
            echo $json;
        } catch (Throwable $t) {
            notFoundResponse($t);
            exit();
            // if ($debug) {
            //     var_dump($db->error());
            // }
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
    $matches = $router->matches($requestUrl);
    $match = $matches[0];
    // debugToConsole($requestUrl);
    // debugToConsole($matches);

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
};
