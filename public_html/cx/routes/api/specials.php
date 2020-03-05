<?php

// Get Config
$uploaddir = $config->uploaddir;

// ******
// Image upload
// ******

$router->map('POST', '/upload', function () use ($config, $db, $uploaddir) {
    $fileToUpload = $_FILES["fileToUpload"];
    $targetDirectory = $uploaddir; // "../uploads/";
    // print_r($_FILES);

    try {
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($fileToUpload['error']) ||
            is_array($fileToUpload['error'])
        ) {
            print_r($fileToUpload);
            throw new RuntimeException('Invalid parameters.');
        }

        // Check $fileToUpload['error'] value.
        switch ($fileToUpload['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        // You should also check filesize here.
        if ($fileToUpload['size'] > 1000000) {
            throw new RuntimeException('Exceeded filesize limit.');
        }

        // DO NOT TRUST $fileToUpload['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($fileToUpload['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ),
            true
        )) {
            throw new RuntimeException('Invalid file format.');
        }

        // You should name it uniquely.
        // DO NOT USE $fileToUpload['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.

        $randomName = sha1_file($fileToUpload['tmp_name']);

        if (!move_uploaded_file(
            $fileToUpload['tmp_name'], "{$targetDirectory}/{$randomName}.{$ext}"
        )) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        $message = 'File is uploaded successfully.';
        // echo json_encode([
        //     'message' => $message,
        //     'file' => "{$randomName}.{$ext}"
        // ]);

        // Save also to database
        try {
            $name = "{$randomName}.{$ext}";
            $doc = [
                'filename' => $randomName,
                'extension' => $ext,
                'url'=> CONFIG['baseUrl'] . "/uploads/$name"
            ];
            $result = $db->insert('media', (array) $doc);
            echo json_encode($result);
        } catch (Throwable $t) {
            http_response_code(404);
            echo "Not found\n";
        }

    } catch (RuntimeException $e) {
        echo $e->getMessage();
    }
});


// $router->map('POST', '/uploadold', function () use ($config, $db, $uploaddir) {
//     $fileToUpload = $_FILES["fileToUpload"];

//     $target_dir = $uploaddir; // "../uploads/";
//     $target_file = $target_dir . basename($fileToUpload["name"]);
//     $uploadOk = 1;
//     $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

//     // =============
//     // Validation
//     // =============
//     // Check if image file is a actual image or fake image
//     if (isset($_POST["submit"])) {
//         $check = getimagesize($fileToUpload["tmp_name"]);
//         if ($check !== false) {
//             $message = "File is an image - " . $check["mime"] . ".";
//             $uploadOk = 1;
//         } else {
//             $message = "File is not an image.";
//             $uploadOk = 0;
//         }
//     }
//     // Check if file already exists
//     if (file_exists($target_file)) {
//         $message = "Sorry, file already exists.";
//         $uploadOk = 0;
//     }
//     // Check file size
//     if ($fileToUpload["size"] > 500000) {
//         $message = "Sorry, your file is too large.";
//         $uploadOk = 0;
//     }
//     // Allow certain file formats
//     if (
//         $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//         && $imageFileType != "gif"
//     ) {
//         $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//         $uploadOk = 0;
//     }

//     // Check if $uploadOk is set to 0 by an error
//     if ($uploadOk == 0) {
//         $message = "Sorry, your file was not uploaded.";
//         http_response_code(404);
//         // if everything is ok, try to upload file
//     } else {
//         if (move_uploaded_file($fileToUpload["tmp_name"], $target_file)) {
//             $message = "The file " . basename($fileToUpload["name"]) . " has been uploaded.";
//         } else {
//             $message = "Sorry, there was an error uploading your file.";
//             http_response_code(404);
//         }
//     }

//     header("content-type:application/json");
//     $res = new StdClass;
//     $res->message = $message;
//     $res->url = "$config->baseUrl/uploads/" . basename($fileToUpload["name"]);
//     $res->debug = $fileToUpload;
//     echo json_encode($res);
// });


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


// =======
// plugins
// =======
$router->map('GET', '/plugins', function () use ($db) {
    $path = APP . "/plugins";
    $files = glob("$path/*", GLOB_ONLYDIR);

    foreach ($files as &$value) {
        $value = substr($value, strlen("$path/"));

        $sceenshotFile = "../plugins/$value/screenshot.png";
        $sceenshotUrl = "/plugins/$value/screenshot.png";
        $value = (object) [
            'name' => $value,
            'image' => file_exists($sceenshotFile) ? $sceenshotUrl : '',
        ];
    }

    header("content-type:application/json");
    echo json_encode($files);
});



// =======
// uploads
// =======
$router->map('GET', '/uploads', function () use ($db) {
    $path = APP . "/uploads";
    $files = glob("$path/*.*");
    foreach ($files as &$value) {
        // Remove ../
        // $value = substr($value, strlen('../'));
        $value = substr($value, strlen($path));
        //    $value = substr($value, 0, -4); // Extension

        $value = (object) [
            'name' => $value,
            'image' => CONFIG['baseUrl'] . "/uploads/$value"
        ];
    }

    header("content-type:application/json");
    echo json_encode($files);
});



// =======
// Themes
// =======
$router->map('GET', '/themes', function () use ($db) {
    $path = APP . "/themes";
    $files = glob("$path/*", GLOB_ONLYDIR);

    foreach ($files as &$value) {
        $value = substr($value, strlen("$path/"));

        $sceenshotFile = APP . "/themes/$value/screenshot.png";
        $sceenshotUrl = CONFIG['baseUrl'] . "/themes/$value/screenshot.png";
        $value = (object) [
            'name' => $value,
            'image' => file_exists($sceenshotFile) ? $sceenshotUrl : '',
        ];
    }

    header("content-type:application/json");
    echo json_encode($files);
});

// =======
// Blocks
// =======
$router->map('GET', '/blocks', function () use ($db) {
    $path = APP . "/blocks";
    $files = glob("$path/*.*");
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
