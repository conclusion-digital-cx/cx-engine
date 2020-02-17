<?php


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


// $router->map('GET', '/collections/[a:id]/exists', function ($id) use ($db) {
//     $entity = 'collections';
//     $fields = "*";
//     $result = $db->get($entity, $fields, ['id' => $id]);
//     $res = $db->tableExist($result['name']);
//     header("content-type:application/json");
//     echo json_encode($res);
// });

// PUT /:entity/:id HACKY Special
// $router->map('PUT', '/pages/[i:id]', function ($id) use ($db) {
//     $entity = 'pages';

//     try {
//         $inputJSON = file_get_contents('php://input');
//         $body = json_decode($inputJSON, TRUE); //convert JSON into array

//         // HACKY Special
//         if ($body['blocks']) {
//             $body['blocks'] = json_encode($body['blocks']);
//         }

//         $result = $db->update($entity, $body, ['id' =>  $id]);
//         if (!$result) {
//             throw new Exception();
//         }

//         header("content-type:application/json");
//         $json = json_encode(
//             $body
//             // array('message' => "saved")
//         );
//         echo $json;
//     } catch (Throwable $t) {
//         notFoundResponse($t);
//     }
// });