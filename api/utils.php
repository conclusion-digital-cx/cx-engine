<?php


function globPath($path) {
    // $path = "../layouts";
    $files = glob("$path/*.*");
    foreach ($files as &$value) {
        // Remove path
        $value = substr($value, strlen($path));

        $value = (object) [
            'name' => $value,
        ];
    }
    header("content-type:application/json");
    echo json_encode($files);
};

function rglob($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir . '/' . basename($pattern), $flags));
    }
    return $files;
}

// ==================
// Responses
// ==================
function processDocument($doc)
{
    $model = getModel('all');
    $fn = $model->get;
    return $fn($doc);
}

function jsonArrayResponse($data = [])
{
    if ($data) {
        // print_r($data);

        foreach ($data as &$row) {
            $row = processDocument($row);
        }
    }

    header("content-type:application/json");
    echo json_encode($data);
}

function jsonResponse($data = [])
{
    if ($data) {
        $data = processDocument($data);
    }

    header("content-type:application/json");
    echo json_encode($data);
}

function notFoundResponse($t = "Not found")
{
    global $config;
    global $db;

    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    echo "Not found\n";
    // if ($config->debug) {
    //     echo "\n";
    //     echo $t;
    //     echo $db->last();
    // }
}

function getModel($name)
{
    $fakeModel = [
        'get' => function ($row) {
            return $row;
        },
        'save' => function ($row) {
            return $row;
        },
    ];

    // Process model
    $model = "./schemas/$name.php";
    if (file_exists($model)) {
        $model = include($model);
        return (object) array_merge($fakeModel, $model);
    } else {
        // Fake model
        return (object) $fakeModel;
    }
}