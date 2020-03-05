<?php

return function ($request, $path = "") {
    $locations = [
        "{$path}{$request}.php",
        "{$path}{$request}.html",
        "{$path}{$request}/index.php"
    ];

    function searchFile($locations)
    {
        foreach ($locations as &$value) {
            if (file_exists($value)) {
                return $value;
            }
        }
    }

    $file = searchFile($locations);
    if ($file) {
        $blocks = [];
        // $main = $this->renderFile($file, $blocks); // TODO Make lazy 
        // $this->regions['main'][] = $main;
        return $file;
    } else {
        http_response_code(404);
        require "{$path}/404.php";
        exit();
    }
};
