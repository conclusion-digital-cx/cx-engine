<?php

// Get Config
$uploaddir = $config->uploaddir;

// ******
// Image upload
// ******

$router->post('/upload', function () use ($db, $uploaddir) {
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

