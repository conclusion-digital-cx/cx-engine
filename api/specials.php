<?php

// Get Config
$uploaddir = $config->uploaddir;

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
});

// Themes
// =======
$router->map('GET', '/themes', function () use ($db) {
    $path = __DIR__ . "/../themes";
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
    $path = __DIR__ . "/../blocks/";
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
