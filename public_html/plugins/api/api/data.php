<?php


// ******************
// Collections
// ******************
$base = "/api";

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
    $model = APP . "/models/$name.php";
    if (file_exists($model)) {
        $model = include($model);
        return (object) array_merge($fakeModel, $model);
    } else {
        // Fake model
        return (object) $fakeModel;
    }
};

$router->get("{$base}/:entity", function ($req, $res) use ($db, $getModel) {
    $entity = $req->params->entity;

    // Move to https://github.com/typicode/json-server
    // $functionFields = [
    //     'page', 'itemsPerPage', 'sortBy', 'sortDesc',
    //     'groupBy', 'groupDesc', 'mustSort', 'multiSort'
    // ];
    $functionFields = [
        '_start', '_limit',
        '_embed', '_expand'
    ];

    $array_except = function($array, $keys)
    {
        foreach ($keys as $key) {
            unset($array[$key]);
        }
        return $array;
    };

    try {
        $fields = isset($_GET['fields']) ? $_GET['fields'] : '*';

        // Where = GET - functionFields
        $where = $_GET; // array_values($_GET);   // Clone
        $where = $array_except($where, $functionFields);

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

        // TODO order
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

        $res->json($rows);
    } catch (Throwable $t) {
        $res->status(400)->json([
            'message'    => 'Not found',
            'fault' => $db->error()
        ]);
    }
});

// POST /:entity
$router->post("{$base}/:entity", function ($req, $res) use ($db) {
    try {
        $entity = $req->params->entity;
        $body = $req->body;

        $result = $db->insert($entity, (array) $body);
        if (!$result) {
            throw new Exception();
        }
        $res->json(array('id' => $result));
    } catch (Throwable $t) {
        $res->status(400)->json([
            'message'    => 'Not found',
            'fault' => $t
        ]);
    }
});

// GET /:entity/:id
$router->get("{$base}/:entity/:id", function ($req, $res) use ($db) {
    $entity = $req->params->entity;
    $id = $req->params->id;

    $fields = isset($_GET['fields']) ? $_GET['fields'] : '*';
    $result = $db->get($entity, $fields, ['id' => $id]);

    $res->json($result);
});

// PUT /:entity/:id ( replace all fields )
// TODO
// $router->map('PUT', '/:entity/:id', function ($entity, $id) use ($db) {
//     
// });

// PATCH /:entity/:id ( update only fields provided )
$router->patch("{$base}/:entity/:id", function ($req, $res) use ($db, $getModel) {
    $entity = $req->params->entity;
    $id = $req->params->id;
    $body = (array)$req->body;

    try {
        // Process model
        $model = $getModel($entity);
        $fn = $model->save;
        $computed = $fn($body);
        $bodyParsedByModel = array_merge($body, $computed);

        $data = $db->update($entity, $bodyParsedByModel, ['id' =>  $id]);

        // Returns the number of rows affected by the last SQL statement
        $count = $data->rowCount();

        $res->json(array('count' => $count));
    } catch (Throwable $t) {
        $res->status(400)->json([
            'message'    => 'Not found',
            'fault' => $t
        ]);
    }
});

// DELETE /:entity/:id
$router->delete("{$base}/:entity/:id", function ($req, $res) use ($db, $config) {
    try {
        $id = $req->params->id;
        $entity = $req->params->entity;

        $data = $db->delete($entity, [
            'id' =>  $id
        ]);
        $rowCount = $data->rowCount();

        if (!$rowCount) {
            return $res->status(400)->json([
                'message'    => 'Not found',
            ]);
        }

        $res->json($rowCount);
    } catch (Throwable $t) {
        $res->status(400)->json([
            'message'    => 'Not found',
            'fault' => $t
        ]);
    }
});

// // Any: Handle db logs
// $router->use(function ($req, $res) use ($db, $config) {
//     if ($config->db['logging']) {
//         $queries = $db->log();
//         $queries = "\n" . join("\n", $queries);
//         // Write the contents back to the file
//         $file = 'queries.log';
//         file_put_contents($file, $queries, FILE_APPEND | LOCK_EX);
//     }
// });
