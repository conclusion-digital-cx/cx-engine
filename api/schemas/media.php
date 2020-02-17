<?php

return [
    'get' => function ($row) {
        return [
            'imageUrl' => "http://localhost:8666$row[image]"
        ];
    }
];
