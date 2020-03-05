<?php

/**
 * Libary version
 */

use Medoo\Medoo;

require_once "lib/Medoo.php";

/** Return function */
return function ($config = [], $router = '') {

    // Validate config
    if (!$config->db) {
        throw new Error("config->db is required to be set");
    }

    // Initialize ORM
    $db = new Medoo($config->db);

    // $router->setBasePath('/api');

    // Moved to cors plugin
    // // =========
    // // CORS
    // // =========
    // // Access-Control headers are received during OPTIONS requests
    // if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    //     header('Access-Control-Allow-Origin: *');
    //     header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    //     header('Access-Control-Allow-Headers: Authorization, token, Content-Type');
    //     header('Access-Control-Max-Age: 1728000');
    //     header('Content-Length: 0');
    //     header('Content-Type: text/plain');
    //     die();
    // }

    // header('Access-Control-Allow-Origin: *');
    // header('Content-Type: application/json');

    /** 
     * Utils 
     * */
    $getJsonBody = function () {
        $inputJSON = file_get_contents('php://input');
        return json_decode($inputJSON);
    };

    $rglob = function ($pattern, $flags = 0) {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, rglob($dir . '/' . basename($pattern), $flags));
        }
        return $files;
    };

    $responses = [
        'jsonArray' => function ($data = []) {
            header("content-type:application/json");
            echo json_encode($data);
        },
    
        'json' => function ($data = []) {
            header("content-type:application/json");
            echo json_encode($data);
        },
    
        'notFound' => function ($t = "Not found") {
            http_response_code(404);
            echo "Not found\n";
        }
    ];
   
    $getModel = function ($name) {
        $fakeModel = [
            'get' => function ($row) {
                return $row;
            },
            'save' => function ($row) {
                return $row;
            },
        ];

        // Process model
        $model = __DIR__ . "/schemas/$name.php";
        if (file_exists($model)) {
            $model = include($model);
            return (object) array_merge($fakeModel, $model);
        } else {
            // Fake model
            return (object) $fakeModel;
        }
    };

    // ******************
    // Routes
    // ******************
    $router->map('GET', '', function () {
        echo "CxEngine REST API";
    });

    $router->map('GET', '/[a:entity]', function ($params) use ($db, $getModel, $responses) {
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

            $rows = $db->select($entity, $fields, $where);
            if (!is_array($rows)) {
                // return true;    // Table not exist...exit this route handler
                throw new Exception("No rows");
            }

            // Process model
            $model = $getModel($entity);
            $fn = $model->get;

            // Call get on each row
            foreach ($rows as &$row) {
                $resp = $fn($row);
                // Merge
                $row = array_merge($row, $resp);
            }

            $responses['jsonArray']($rows);
        } catch (Throwable $t) {
            $responses['notFound']($t);
            var_dump( $db->error() );
        }
    });

    $router->map('GET', '/[a:entity]', function ($params) use ($db, $responses) {
        $responses['notFound']();
    });

    // POST /:entity
    $router->map('POST', '/[a:entity]', function ($params) use ($db, $responses) {
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
            $responses['notFound']($t);
        }
    });

    // GET /:entity/:id
    $router->map('GET', '/[a:entity]/[i:id]', function ($params) use ($db, $responses) {
        // list($entity, $id) = (array)$params;
        $entity = $params->entity;
        $id = $params->id;

        $fields = isset($_GET['fields']) ? $_GET['fields'] : '*';
        $result = $db->get($entity, $fields, ['id' => $id]);

        $responses['json']($result);
    });

    // PUT /:entity/:id ( replace all fields )
    // TODO
    // $router->map('PUT', '/[a:entity]/[i:id]', function ($entity, $id) use ($db) {
    //     
    // });

    // PATCH /:entity/:id ( update only fields provided )
    $router->map('PATCH', '/[a:entity]/[i:id]', function ($params) use ($db, $getModel, $responses) {
        $entity = $params->entity;
        $id = $params->id;

        try {
            $inputJSON = file_get_contents('php://input');
            $body = json_decode($inputJSON, TRUE); //convert JSON into array

            // Process model
            $model = $getModel($entity);
            $fn = $model->save;
            $computed = $fn($body);
            $bodyParsedByModel = array_merge($body, $computed);

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
            $responses['notFound']($t);
            exit();
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

    // Any: Handle db logs
    $router->map('GET|OPTIONS|PATCH|PUT|POST|DELETE', '[*:trailing]', function ($params) use ($db, $config) {
        if ($config->db['logging']) {
            $queries = $db->log();
            $queries = "\n" . join("\n", $queries);
            // Write the contents back to the file
            $file = 'queries.log';
            file_put_contents($file, $queries, FILE_APPEND | LOCK_EX);
        }
    });

    return $router;
};
